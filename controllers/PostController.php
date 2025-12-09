<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/DonationType.php';


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
        $errors = [];
        $success = "";

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

        // Validation côté PHP
        if ($contenu === "") {
            $errors[] = "Le contenu du post ne peut pas être vide.";
        } elseif (strlen($contenu) < 5) {
            $errors[] = "Le message doit contenir au moins 5 caractères.";
        }
        if (!$donation_type_id) {
            $errors[] = "Veuillez sélectionner un type de donnation.";
        }

        if (empty($errors)) {
            $postModel->create($titre, $contenu, $imagePath, $donation_type_id);
            $success = "Post publié avec succès ✅";
        }
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
                $commentModel = new comment();
                $commentModel->create($post_id, $content);
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
            $commentModel = new Comment();
            $commentModel->update($id, $content);
        }
        
        header('Location: index.php?action=show&id=' . $post_id);
        exit;
    }
    }
}



