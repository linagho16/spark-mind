<?php
// Point d'entrée unique MVC pour tout le site SPARKMIND

$page = $_GET['page'] ?? 'front';

/* ==== CONTROLLERS ==== */
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/AdminController.php';

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

    case 'admin_notifications':
        $adminController->notifications();
        break;

    case 'admin_help_requests':
        $adminController->helpRequests();
        break;

    case 'admin_help_request_action':
        $adminController->helpRequestAction();
        break;

    case 'admin_block_user':
        $adminController->blockUser();
        break;

    case 'admin_activate_user':
        $adminController->activateUser();
        break;

    case 'admin_user_profile':
        $adminController->userProfile();
        break;

    case 'admin_delete_user':
        $adminController->deleteUser();
        break;

    /* =======================
       ROUTE PAR DÉFAUT
    ======================= */
    default:
        header('Location: index.php?page=front');
        exit;
}
