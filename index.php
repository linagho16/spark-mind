<?php
// Point d'entrée unique MVC pour tout le site SPARKMIND

$page = $_GET['page'] ?? 'front';

/* ==== CONTROLLERS (SparkMind) ==== */
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/ForumAdminController.php';

/* ==== INSTANCES ==== */
$homeController    = new HomeController();
$authController    = new AuthController();
$profileController = new ProfileController();
$adminController   = new AdminController();

/* ==== ROUTEUR ==== */
switch ($page) {

    /* =======================
       FRONT OFFICE
    ======================= */
    case 'front':
        $homeController->front();
        break;

    case 'front_step':
        $homeController->step();
        break;

    case 'main':
        $homeController->main();
        break;

    /* =======================
       AUTHENTIFICATION
    ======================= */
    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'forgot_password':
        $authController->forgotPassword();
        break;

    case 'reset_password':
        $authController->resetPassword();
        break;

    /* =======================
       PROFIL UTILISATEUR
    ======================= */
    case 'profile':
        $profileController->show();
        break;

    case 'profile_edit':
        $profileController->edit();
        break;

    case 'upload_photo':
        $profileController->uploadPhoto();
        break;

    case 'delete_account':
        $profileController->delete();
        break;

    /* =======================
       ADMINISTRATION (BACKOFFICE)
    ======================= */
    case 'admin_home':
        $adminController->home();
        break;

    case 'admin_users':
        $adminController->users();
        break;

    case 'admin_help_requests':
        $adminController->helpRequests();
        break;

    case 'admin_help_request_action':
        $adminController->helpRequestAction();
        break;

    case 'admin_block_user':
        (new AdminController())->blockUser();
        break;

    case 'admin_unblock_user':
        (new AdminController())->unblockUser();
        break;

    case 'admin_user_profile':
        $adminController->userProfile();
        break;

    case 'admin_delete_user':
        $adminController->deleteUser();
        break;

    /* =======================
       FORUM ADMIN
    ======================= */
    case 'admin_forum':
        (new ForumAdminController())->dashboard();
        break;

    case 'admin_forum_posts':
        (new ForumAdminController())->listPosts();
        break;

    case 'admin_forum_comments':
        (new ForumAdminController())->listComments();
        break;

    case 'admin_forum_types':
        (new ForumAdminController())->listDonationTypes();
        break;

    case 'admin_forum_ai':
        (new ForumAdminController())->aiDashboard();
        break;

    /* =======================
       VUES EXISTANTES
    ======================= */
    case 'offer_support':
        include __DIR__ . '/views/front/offer_support.php';
        break;

    case 'demande':
        require_once __DIR__ . '/views/frontoffice/formulaire.html';
        break;

    case 'backoffice':
        require_once __DIR__ . '/views/backoffice/back.html';
        break;

    case 'reponse':
        require_once __DIR__ . '/views/reponse/reponse.html';
        break;

    /* =======================
       ANCIEN FRONT (DON/GROUPE) - si tu l'utilises encore
    ======================= */
    case 'frontoffice':
        require_once __DIR__ . '/view/Frontoffice/index.php';
        break;

    case 'browse_dons':
        require_once __DIR__ . '/view/Frontoffice/browse_dons.php';
        break;

    case 'browse_groupes':
        require_once __DIR__ . '/view/Frontoffice/browse_groupes.php';
        break;

    case 'create_don':
        require_once __DIR__ . '/view/Frontoffice/create_don.php';
        break;

    case 'create_groupe':
        require_once __DIR__ . '/view/Frontoffice/create_groupe.php';
        break;

    case 'view_don':
        require_once __DIR__ . '/view/Frontoffice/view_don.php';
        break;

    case 'view_groupe':
        require_once __DIR__ . '/view/Frontoffice/view_groupe.php';
        break;

    case 'backoffice_aide':
        require_once __DIR__ . '/view/Backoffice/dashboard.php';
        break;

    /* =======================
       BACKOFFICE AIDE (DONS/GROUPES) via controllers procéduraux
    ======================= */
    case 'aide_dons':
        require_once __DIR__ . '/controller/donC.php';
        $_GET['action'] = 'dons';
        (new DonController())->handleRequest();
        break;

    case 'aide_don_create':
        require_once __DIR__ . '/controller/donC.php';
        $_GET['action'] = 'create_don';
        (new DonController())->handleRequest();
        break;

    case 'aide_don_stats':
        require_once __DIR__ . '/controller/donC.php';
        $_GET['action'] = 'statistics';
        (new DonController())->handleRequest();
        break;

    case 'aide_groupes':
        require_once __DIR__ . '/controller/groupeC.php';
        $_GET['action'] = 'groupes';
        (new GroupeController())->handleRequest();
        break;

    case 'aide_create_groupe':
        require_once __DIR__ . '/controller/groupeC.php';
        $_GET['action'] = 'create_groupe';
        (new GroupeController())->handleRequest();
        break;

    /* =======================
       ✅ PRODUITS (OMAR)
    ======================= */
    case 'produits':
        require_once __DIR__ . '/view/omar/index.php';
        break;

    case 'liste_produits':
        require_once __DIR__ . '/view/omar/liste_produits.php';
        break;

    case 'ajouter_produit':
        require_once __DIR__ . '/view/omar/ajouterProduit.php';
        break;

    case 'details_produit':
        require_once __DIR__ . '/view/omar/detailsfront.php';
        break;

    case 'ajouter_categorie':
        require_once __DIR__ . '/view/omar/ajouterCategorie.php';
        break;

    /* =======================
       ✅ EVENTS
    ======================= */
    case 'events_dashboard':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        require_once __DIR__ . '/models/Reservation.php';

        $eventModel  = new EventModel($pdo);
        $reservation = new Reservation($pdo);

        require_once __DIR__ . '/views/dashboard.php';
        break;

    case 'event_create':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        $eventModel = new EventModel($pdo);
        require_once __DIR__ . '/views/events/create.php';
        break;

    case 'reservations_list':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';
        $reservation = new Reservation($pdo);
        require_once __DIR__ . '/views/reservations/index.php';
        break;

    case 'reservation_create':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';
        require_once __DIR__ . '/models/EventModel.php';

        $reservation = new Reservation($pdo);
        $eventModel  = new EventModel($pdo);

        if (method_exists($eventModel, 'getAllEvents')) {
            $events = $eventModel->getAllEvents();
        } elseif (method_exists($eventModel, 'listEvents')) {
            $events = $eventModel->listEvents();
        } else {
            $events = [];
        }

        require_once __DIR__ . '/views/reservations/create.php';
        break;

    case 'events_list':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        $eventModel = new EventModel($pdo);
        $events = $eventModel->getAllEvents();
        require_once __DIR__ . '/views/events/index.php';
        break;

    case 'events_scan':
        require_once __DIR__ . '/views/tickets/scan.php';
        break;

    case 'event_edit':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';

        $eventModel = new EventModel($pdo);
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $event = $eventModel->getEventById($id);

        if (!$event) {
            header('Location: index.php?page=events_list');
            exit;
        }

        require_once __DIR__ . '/views/events/edit.php';
        break;

    case 'event_show':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        $eventModel = new EventModel($pdo);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $event = $id ? $eventModel->getEventById($id) : null;

        require_once __DIR__ . '/views/events/show.php';
        break;

    case 'event_delete':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';

        $eventModel = new EventModel($pdo);
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id) $eventModel->deleteEvent($id);

        header('Location: index.php?page=events_list');
        exit;

    case 'event_update':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';

        $eventModel = new EventModel($pdo);
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($id) {
            $eventModel->updateEvent(
                $id,
                $_POST['titre'] ?? '',
                $_POST['description'] ?? '',
                $_POST['lieu'] ?? '',
                $_POST['prix'] ?? 0,
                $_POST['date_event'] ?? ''
            );
        }

        header('Location: /sparkmind_mvc_100percent/index.php?page=event_show&id=' . $id);
        exit;

    case 'events_home':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        require_once __DIR__ . '/models/Reservation.php';

        $eventModel  = new EventModel($pdo);
        $reservation = new Reservation($pdo);

        $upcomingEvents = $eventModel->getUpcomingEvents(6);
        $stats          = $reservation->getStats();

        require_once __DIR__ . '/views/public/home.php';
        break;

    case 'booking_form':
        session_start();

        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        require_once __DIR__ . '/models/Reservation.php';

        $eventModel  = new EventModel($pdo);
        $reservation = new Reservation($pdo);

        $eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once __DIR__ . '/views/public/booking_form.php';
        break;

    case 'event_detail':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        require_once __DIR__ . '/models/Reservation.php';

        $eventModel  = new EventModel($pdo);
        $reservation = new Reservation($pdo);

        $eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$eventId) {
            header('Location: index.php?page=events_home');
            exit;
        }

        require_once __DIR__ . '/views/public/event_detail.php';
        break;

    case 'events_list_public':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';

        $eventModel = new EventModel($pdo);
        $events     = $eventModel->getAllEvents();

        require_once __DIR__ . '/views/public/events_list.php';
        break;

    case 'my_reservations':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';

        $reservation = new Reservation($pdo);
        $reservations = $reservation->getAll();

        require_once __DIR__ . '/views/public/my_reservations.php';
        break;

    case 'reservation_detail_public':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';
        require_once __DIR__ . '/models/EventModel.php';

        $reservation = new Reservation($pdo);
        $eventModel  = new EventModel($pdo);

        $reservationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $email         = $_GET['email'] ?? '';

        if (!$reservationId || !$email) {
            header('Location: index.php?page=my_reservations');
            exit;
        }

        $reservationData = $reservation->getByIdAndEmail($reservationId, $email);
        if (!$reservationData) {
            header('Location: index.php?page=my_reservations');
            exit;
        }

        $event = $eventModel->getEventById($reservationData['event_id']);
        require_once __DIR__ . '/views/public/reservation_detail.php';
        break;

    /* =======================
       ✅ POSTS (FORUM)
    ======================= */
    case 'post_list':
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once __DIR__ . '/models/Post.php';
        require_once __DIR__ . '/models/DonationType.php';

        $postModel = new Post();
        $donationTypeModel = new DonationType();
        $donation_types = $donationTypeModel->getAll();

        $typeId = isset($_GET['type']) ? (int)$_GET['type'] : null;
        $posts = $postModel->getAll($typeId);

        $errors = [];
        $success = '';

        require_once __DIR__ . '/views/front/post_list.php';
        break;

    case 'post_detail':
        session_start();
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Post.php';

        $postModel = new Post($pdo);
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $post = $postModel->getById($id);

        require_once __DIR__ . '/views/front/post_detail.php';
        break;

    case 'post_edit':
        session_start();
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Post.php';

        $postModel = new Post($pdo);
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $post = $postModel->getById($id);

        require_once __DIR__ . '/views/front/post_edit.php';
        break;

    case 'comment_edit':
        require_once __DIR__ . '/views/front/comment_edit.php';
        break;

    case 'post_delete':
        session_start();
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Post.php';

        $postModel = new Post($pdo);
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id) $postModel->delete($id);

        header('Location: index.php?page=post_list');
        exit;

    case 'notifications':
        session_start();
        require_once __DIR__ . '/views/notifications/index.php';
        break;

    /* =======================
       ✅ POST UPDATE
    ======================= */
    case 'post_update': {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/models/Post.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=post_list');
            exit;
        }

        $id      = (int)($_POST['id'] ?? 0);
        $titre   = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        if ($id <= 0) {
            $_SESSION['flash_error'] = "ID du post invalide.";
            header("Location: index.php?page=post_list");
            exit;
        }

        if ($contenu === '') {
            $_SESSION['flash_error'] = "Le contenu est obligatoire.";
            header("Location: index.php?page=post_edit&id=" . $id);
            exit;
        }

        // upload image (facultatif)
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['image']['tmp_name'];
            $name = basename($_FILES['image']['name']);

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];

            if (!in_array($ext, $allowed, true)) {
                $_SESSION['flash_error'] = "Format image non autorisé.";
                header("Location: index.php?page=post_edit&id=" . $id);
                exit;
            }

            $uploadDir = __DIR__ . '/public/uploads/posts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $newName = 'post_' . $id . '_' . time() . '.' . $ext;
            $dest    = $uploadDir . $newName;

            if (!move_uploaded_file($tmp, $dest)) {
                $_SESSION['flash_error'] = "Upload image échoué.";
                header("Location: index.php?page=post_edit&id=" . $id);
                exit;
            }

            $imagePath = 'public/uploads/posts/' . $newName;
        }

        // ✅ Si tu supprimes le champ donation_type_id du form, on met une valeur par défaut (1)
        $donation_type_id = (int)($_POST['donation_type_id'] ?? 1);

        $postModel = new Post();
        $ok = $postModel->update($id, $titre, $contenu, $imagePath, $donation_type_id);

        if ($ok) {
            $_SESSION['flash_success'] = "Post mis à jour ✅";
            header("Location: index.php?page=post_detail&id=" . $id);
            exit;
        }

        $_SESSION['flash_error'] = "Échec mise à jour.";
        header("Location: index.php?page=post_edit&id=" . $id);
        exit;
    }

    /* =======================
       ✅ POST STORE (PUBLISH)
    ======================= */
    case 'post_store': {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/models/Post.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=post_list');
            exit;
        }

        $titre   = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $donation_type_id = (int)($_POST['donation_type_id'] ?? 0);

        $errors = [];
        if ($donation_type_id <= 0) $errors[] = "Veuillez choisir un type.";
        if ($contenu === '')        $errors[] = "Le message est obligatoire.";

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode(" ", $errors);
            header('Location: index.php?page=post_list');
            exit;
        }

        // upload image (facultatif)
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['image']['tmp_name'];
            $name = basename($_FILES['image']['name']);

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];

            if (!in_array($ext, $allowed, true)) {
                $_SESSION['flash_error'] = "Format image non autorisé.";
                header('Location: index.php?page=post_list');
                exit;
            }

            $uploadDir = __DIR__ . '/public/uploads/posts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $newName = 'post_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $dest    = $uploadDir . $newName;

            if (!move_uploaded_file($tmp, $dest)) {
                $_SESSION['flash_error'] = "Upload image échoué.";
                header('Location: index.php?page=post_list');
                exit;
            }

            $imagePath = 'public/uploads/posts/' . $newName;
        }

        $postModel = new Post();
        $ok = $postModel->create($titre, $contenu, $imagePath, $donation_type_id);

        if ($ok) $_SESSION['flash_success'] = "Post publié ✅";
        else     $_SESSION['flash_error']   = "Erreur: post non enregistré.";

        header('Location: index.php?page=post_list');
        exit;
    }
    case 'comment_add': {
    if (session_status() === PHP_SESSION_NONE) session_start();

    require_once __DIR__ . '/config/config.php';   // doit fournir $pdo
    require_once __DIR__ . '/models/Comment.php';  // à créer si pas existant

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=post_list');
        exit;
    }

    $post_id = (int)($_POST['post_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');

    if ($post_id <= 0 || $content === '') {
        $_SESSION['flash_error'] = "Commentaire invalide.";
        header('Location: index.php?page=post_detail&id=' . $post_id);
        exit;
    }

    $commentModel = new Comment($pdo);

    $ok = $commentModel->create($post_id, $content, $_SESSION['user_id'] ?? 1);

    if ($ok) {
        $_SESSION['flash_success'] = "Commentaire ajouté ✅";
    } else {
        $_SESSION['flash_error'] = "Erreur: commentaire non enregistré.";
    }

    header('Location: index.php?page=post_detail&id=' . $post_id);
    exit;
}





    /* =======================
       ROUTE PAR DÉFAUT
    ======================= */
    default:
        header('Location: index.php?page=front');
        exit;
}
