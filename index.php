

<?php
// Point d'entrée unique MVC pour tout le site SPARKMIND

$page = $_GET['page'] ?? 'front';

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/NotificationController.php';


$homeController    = new HomeController();
$authController    = new AuthController();
$profileController = new ProfileController();
$notificationController = new NotificationController();
$adminController   = new AdminController();


switch ($page) {
    case 'front':
        $homeController->front();
        break;

    case 'front_step':
        $homeController->step();
        break;

    case 'main':
        $homeController->main();
        break;

    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

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

    case 'logout':
        $authController->logout();
        break;


    case 'admin_home':
        $adminController->home();
        break;

    case 'admin_users':
        $adminController->users();
        break;

    case 'forgot_password':
        $authController->forgotPassword();
        break;

    case 'reset_password':
        $authController->resetPassword();
        break;
    case 'notifications':
        $notificationController->front();
        break;

    case 'notif_action':
        $notificationController->action();
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




    default:
        // page par défaut : front office
        header('Location: index.php?page=front');
        exit;
}
