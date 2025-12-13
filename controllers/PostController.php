<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/DonationType.php';
require_once __DIR__ . '/../models/AIHelper.php';


class PostController {

    // liste des posts
    public function indexFront() {
        $postModel = new Post();
        $donationTypeModel = new DonationType();

        $type_filter = $_GET['type'] ?? null;
        $posts = $postModel->getAll($type_filter);
        $donation_types = $donationTypeModel->getAll();

        $errors = [];
        $success = "";
        include __DIR__ . '/../views/front/post_list.php';
    }

    // crée un post
    public function storeFront() {
        file_put_contents('debug.txt', print_r($_POST, true));
        $postModel = new Post();
        $aiHelper = new AIHelper();
        $errors = [];
        $success = "";
        $warnings = [];

        $titre   = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $donation_type_id = $_POST['donation_type_id'] ?? null;
        $imagePath= NULL;
        //upload image
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] ===0){
            //crée dossier si pas encore
            $uploadDir =__DIR__. "/../public/assets/img/posts/";
            if (!file_exists($uploadDir)){
                mkdir($uploadDir, 0777, true);

            } 
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $fullPath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $fullPath)){
            $imagePath = "assets/img/posts/" . $imageName; } 
         }
         //analyse IA
         $aiAnalysis = $aiHelper->analyze($contenu);
         // 1. VÉRIFIER PROPOS HAINEUX
    if ($aiAnalysis['hate_speech']['is_hate_speech']) {
        $errors[] = "⚠️ Votre message contient des propos inappropriés : " 
                       . $aiAnalysis['hate_speech']['reason'];
    }
    
    // 2. SUGGÉRER CATÉGORIE si non sélectionnée
            if (!$donation_type_id && $aiAnalysis['suggested_category']['suggested_category_id']) {
            $confidence = $aiAnalysis['suggested_category']['confidence'];
            
            if ($confidence > 60) {
                // Auto-sélection si confiance élevée
                $donation_type_id = $aiAnalysis['suggested_category']['suggested_category_id'];
                $warnings[] = "✅ Catégorie suggérée automatiquement : " 
                             . $aiAnalysis['suggested_category']['category_name'] 
                             . " (confiance: {$confidence}%)";
            } elseif ($confidence > 30) {
                // Simple suggestion
                $warnings[] = "💡 Suggestion IA : Catégorie " 
                             . $aiAnalysis['suggested_category']['category_name'] 
                             . " (confiance: {$confidence}%)";
            }
        }
        
        // 3. ALERTE SI MESSAGE URGENT
        if ($aiAnalysis['sentiment']['type'] === 'urgent') {
            $warnings[] = "⚡ Message urgent détecté - Il sera visible en priorité";
        }

        // Validation côté PHP
        if ($contenu === "") {
            $errors[] = "Le contenu du post ne peut pas être vide.";
        } elseif (strlen($contenu) < 5) {
            $errors[] = "Le message doit contenir au moins 5 caractères.";
        }
        if (!$donation_type_id) {
            $errors[] = "Veuillez sélectionner un type de donnation.";
        }

        // ✅ POST-REDIRECT-GET PATTERN - FIX POPUP
        if (empty($errors)) {
            $postModel->create($titre, $contenu, $imagePath, $donation_type_id);
            
            // Sauvegarder les messages en session
            $_SESSION['success'] = "Post publié avec succès ✅";
            if (!empty($warnings)) {
                $_SESSION['warnings'] = $warnings;
            }
            
            // Rediriger vers la liste (GET)
            header('Location: index.php');
            exit;
        }
        
        // Si erreurs, afficher le formulaire avec les erreurs
        $donationTypeModel = new DonationType();
        $posts = $postModel->getAll();
        $donation_types = $donationTypeModel->getAll();
        include __DIR__ . '/../views/front/post_list.php';

    }
    //afficher un post avec un commentaire
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('location: index.php');
            exit;
        }
        $postModel = new Post();
        $commentModel= new Comment();

        $post = $postModel->getById($id);
        $comments = $commentModel->getByPostId($id);
        include __DIR__ . '/../views/front/post_detail.php';
    }
    //ajoutet un commentaire
    public function addComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_id = $_POST['post_id'] ?? null;
            $content = trim($_POST['content'] ?? '');

            if ($post_id && !empty($content)) {
                // ===== ANALYSE IA DU COMMENTAIRE =====
                $aiHelper = new AIHelper();
                $aiAnalysis = $aiHelper->analyze($content);
                
                // BLOQUER si propos haineux
                if ($aiAnalysis['hate_speech']['is_hate_speech']) {
                    $_SESSION['comment_error'] = "⚠️ Votre commentaire contient des propos inappropriés : " 
                                                 . $aiAnalysis['hate_speech']['reason'];
                    header('location: index.php?action=show&id=' . $post_id);
                    exit;
                }
                
                // ALERTER si besoin de modération (mais publier quand même)
                if ($aiAnalysis['needs_moderation']) {
                    error_log("Commentaire nécessite modération (post_id: $post_id) : " . substr($content, 0, 50));
                    // Vous pouvez marquer le commentaire pour revue admin
                }
                
                // Créer le commentaire si OK
                $commentModel = new Comment();
                $commentModel->create($post_id, $content);
                require_once __DIR__ . '/../models/Like.php';
                require_once __DIR__ . '/../models/Notification.php';

                $likeModel = new Like();
                $notificationModel = new Notification();
                $currentUserId = $_SESSION['user_id'] ?? 1;

                // Get the post owner
                $postOwner = $likeModel->getPostOwner($post_id);

                // Notify post owner about new comment
                if ($postOwner && $postOwner != $currentUserId) {
                    $notificationModel->notifyNewComment($postOwner, $currentUserId, $post_id, null);
                }
                
                $_SESSION['comment_success'] = "✅ Commentaire publié avec succès !";
            }
            
            header('location: index.php?action=show&id=' . $post_id);
            exit;
        }
    }
    public function deleteComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_id = $_POST['comment_id'] ?? null;
            $post_id = $_POST['post_id'] ?? null;
            if ($comment_id) {
                $commentModel = new Comment();
                $commentModel->delete($comment_id);
            }
            header('Location: index.php?action=show&id=' . $post_id);
            exit;
        }
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id){
            header('location: index.php');
            exit;
        }
        $postModel = new Post();
        $donationTypeModel = new DonationType();
        $post = $postModel->getById($id);
        $donation_types = $donationTypeModel->getAll();
        include __DIR__ . '/../views/front/post_edit.php';
    }
    //mettre a jour un post
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $titre = trim($_POST['titre'] ?? '');
            $contenu = trim($_POST['contenu'] ?? '');
            $donation_type_id = $_POST['donation_type_id'] ?? null;
            $imagePath = null;

            // Upload nouvelle image si fournie
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
                $uploadDir = __DIR__ . "/../public/assets/img/posts/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $fullPath = $uploadDir . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $fullPath)) {
                    $imagePath = "assets/img/posts/" . $imageName;
                }
            }

            $postModel = new Post();
            $postModel->update($id, $titre, $contenu, $imagePath, $donation_type_id);
            
            header('Location: index.php?action=show&id=' . $id);
            exit;
        }

    } 

//supprimer un post
    public function deleteFront(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if ($id) {
                    $postModel = new Post();
                    $postModel->delete($id);
                }
            }
            header('Location: index.php');
            exit;
        }
        // Afficher le formulaire de modification d'un commentaire
public function editComment() {
    $id = $_GET['id'] ?? null;
    $post_id = $_GET['post_id'] ?? null;
    
    if (!$id || !$post_id) {
        header('Location: index.php');
        exit;
    }
    
    $commentModel = new Comment();
    $comment = $commentModel->getById($id);
    
    if (!$comment) {
        header('Location: index.php?action=show&id=' . $post_id);
        exit;
    }
    
    include __DIR__ . '/../views/front/comment_edit.php';
}

// Mettre à jour un commentaire
public function updateComment() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['comment_id'] ?? null;
        $post_id = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        
     if ($id && $post_id && !empty($content)) {
                // ===== ANALYSE IA DU COMMENTAIRE MODIFIÉ =====
                $aiHelper = new AIHelper();
                $aiAnalysis = $aiHelper->analyze($content);
                
                // BLOQUER si propos haineux
                if ($aiAnalysis['hate_speech']['is_hate_speech']) {
                    $_SESSION['comment_error'] = "⚠️ Modification refusée : " 
                                                 . $aiAnalysis['hate_speech']['reason'];
                    header('Location: index.php?action=show&id=' . $post_id);
                    exit;
                }
                
                // Mettre à jour le commentaire
                $commentModel = new Comment();
                $commentModel->update($id, $content);
                
                $_SESSION['comment_success'] = "✅ Commentaire modifié avec succès !";
            }
            
            header('Location: index.php?action=show&id=' . $post_id);
            exit;
        }
    }
    
}




























































































































































































