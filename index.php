<?php
// Point d'entrée unique MVC pour tout le site SPARKMIND

$page = $_GET['page'] ?? 'front';

/* ==== CONTROLLERS ==== */
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

    case 'admin_forum':
        require_once __DIR__ . '/controllers/ForumAdminController.php';
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


    


  


    /* =======================
       ROUTE PAR DÉFAUT
    ======================= */
    default:
        header('Location: index.php?page=front');
        exit;
}
