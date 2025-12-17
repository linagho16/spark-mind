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
       ✅ PRODUITS (OMAR) - INTÉGRATION PROPRE
       (adapte les chemins si tes fichiers sont ailleurs)
    ======================= */

    // Page d'accueil "produits" (la page que tu montres en capture avec la grille)
    case 'produits':
        require_once __DIR__ . '/view/omar/index.php';
        break;

    // Liste complète des produits
    case 'liste_produits':
        require_once __DIR__ . '/view/omar/liste_produits.php';
        break;

    // Ajouter un produit
    case 'ajouter_produit':
        require_once __DIR__ . '/view/omar/ajouterProduit.php';
        break;

    // Détails produit
    case 'details_produit':
        require_once __DIR__ . '/view/omar/detailsfront.php';
        break;


    case 'ajouter_categorie':
        require_once __DIR__ . '/view/omar/ajouterCategorie.php';
        break;
    
    case 'events_dashboard':
        require_once __DIR__ . '/config/config.php';              // fournit $pdo
        require_once __DIR__ . '/models/EventModel.php';
        require_once __DIR__ . '/models/Reservation.php';         // <-- IMPORTANT

        $eventModel  = new EventModel($pdo);
        $reservation = new Reservation($pdo);                     // <-- IMPORTANT

        require_once __DIR__ . '/views/dashboard.php';
        break;






    case 'event_create':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        $eventModel = new EventModel($pdo);
        require_once __DIR__ . '/views/events/create.php'; // <-- adapte le nom exact
        break;

    case 'reservations_list':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';
        $reservation = new Reservation($pdo);
        require_once __DIR__ . '/views/reservations/index.php'; // <-- adapte le nom exact
        break;

    case 'reservation_create':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';
        require_once __DIR__ . '/models/EventModel.php';

        $reservation = new Reservation($pdo);
        $eventModel  = new EventModel($pdo);

        // ✅ IMPORTANT : alimenter $events pour create.php
        // adapte le nom selon ton modèle (getAllEvents / listEvents)
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
    $events = $eventModel->getAllEvents(); // ou listEvents() selon ton modèle
    require_once __DIR__ . '/views/events/index.php'; // ✅ page LISTE
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
        $event = $id ? $eventModel->getEventById($id) : null; // adapte si besoin

        require_once __DIR__ . '/views/events/show.php';
        break;
    case 'event_delete':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';

        $eventModel = new EventModel($pdo);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id) {
            $eventModel->deleteEvent($id); // ⚠️ méthode à vérifier
        }

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

        // Préparer les données AVANT d'inclure la vue
        $upcomingEvents = $eventModel->getUpcomingEvents(6);
        $stats          = $reservation->getStats();

        require_once __DIR__ . '/views/public/home.php';
        break;

    case 'booking_form':
        session_start(); // si pas déjà fait en haut du projet

        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';
        require_once __DIR__ . '/models/Reservation.php';

        $eventModel  = new EventModel($pdo);
        $reservation = new Reservation($pdo);

        // booking_form.php utilise $eventId
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

        $event = $eventModel->getEventById($eventId);

        if (!$event) {
            header('Location: index.php?page=events_home');
            exit;
        }


    case 'events_list_public':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/EventModel.php';

        $eventModel = new EventModel($pdo);
        $events     = $eventModel->getAllEvents(); // ou listEvents()

        require_once __DIR__ . '/views/public/events_list.php';
        break;


    require_once __DIR__ . '/views/public/event_detail.php';
    break;

    case 'my_reservations':
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/models/Reservation.php';

        $reservation = new Reservation($pdo);

        // selon ton modèle : par email / user_id / session
        $reservations = $reservation->getAll(); 
        // ou getByUser($_SESSION['user_id'])

        require_once __DIR__ . '/views/public/my_reservations.php';
        break;





    

    

        







    /* =======================
       ROUTE PAR DÉFAUT
    ======================= */
    default:
        header('Location: index.php?page=front');
        exit;
}
