<?php
session_start();
if (!isset($_SESSION['user_id'])){
    $_SESSION['user_id'] = 1;
}
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/AIController.php';
require_once __DIR__ . '/../controllers/ChatbotController.php';
require_once __DIR__ . '/../controllers/LikeController.php';
require_once __DIR__ . '/../controllers/NotificationController.php';



$action = $_GET['action'] ?? 'front';
$controller = new PostController();

switch ($action) {
    //liste des posts (page d'accueil)
    case 'front':
        $controller = new PostController();
        $controller->indexFront();
        break;
        //crée nouveau post
        case 'store_front':
            $controller = new PostController();
            if ($_SERVER['REQUEST_METHOD']=='POST'){
                $controller->storeFront();
            }else{
                $controller->indexFront();
            }
            break;
            //afficher un post avec commentaires 
            case 'show':
                $controller = new PostController();
                $controller->show();
                break;
            
                //ajouter un commentaire
                case 'add_comment':
                    $controller = new PostController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->addComment();
                    }else{
                        header('Location: index.php');
                        exit;
                    }
                    break;
                    case 'edit':
                        $controller = new PostController();
                        $controller->edit();
                        break;
                        //mettre a jour un post
                        case 'update':
                            $controller = new PostController();
                            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $controller->update();
                            }else{
                                header('location: index.php');
                                exit;
                            }
                            break;
                            //supprimer un post
                            case 'delete_front':
                                $controller = new PostController();
                                if ($_SERVER['REQUEST_METHOD'] ==='POST') {
                                    $controller->deletefront();
                                } else{
                                    header('Location : index.php');
                                    exit;
                                }
                                break;
                                case 'delete_comment':
                                    $controller = new PostController();
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                                        $controller->deleteComment();
                                    } else{
                                        header('Location: index.php');
                                        exit;
                                    }
                                    break;
                                    case 'edit_comment':
                                        $controller = new PostController();
                                        $controller->editComment();
                                        break;

                                    case 'update_comment':
                                        $controller = new PostController();
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            $controller->updateComment();
                                        } else {
                                            header('Location: index.php');
                                            exit;
                                        }
                                        break;
                                    case 'admin':
                                        $adminController = new AdminController();
                                        $adminController->dashboard();
                                        break;
                                        case 'admin_posts':
                                            $adminController = new AdminController();
                                            $adminController->listPosts();
                                            break;
                                            case 'admin_delete_post':
                                                $adminController = new AdminController();
                                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                    $adminController->deletePost();
                                                } else {
                                                    header('Location: index.php?action=admin_posts');
                                                    exit;
                                                }
                                                break;
                                                case 'admin_comments':
                                                    $adminController = new AdminController();
                                                    $adminController->listComments();
                                                    break;
                                                case 'admin_delete_comment':
                                                $adminController = new AdminController();
                                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                    $adminController->deleteCommentAdmin();
                                                } else {
                                                    header('Location: index.php?action=admin_comments');
                                                    exit;
                                                }
                                                break;

                                                case 'admin_types':
                                                    $adminController = new AdminController();
                                                    $adminController->listDonationTypes();
                                                    break;
                                                    case 'chatbot':
                                                        $chatbot = new ChatbotController();
                                                        $chatbot->index();
                                                        break;

                                                    // API : chatbot floating widget reply
                                                    case 'chatbot_reply':
                                                        $chatbot = new ChatbotController();
                                                        $chatbot->reply();   // <-- this returns JSON
                                                        exit;
                   

                                                            // API - Obtenir les tendances
                                                            case 'chatbot_trends':
                                                                $chatbotController = new ChatbotController();
                                                                $chatbotController->getTrends();
                                                                break;

                                                            // Dashboard admin IA
                                                            case 'admin_ai':
                                                                $chatbotController = new ChatbotController();
                                                                $chatbotController->adminDashboard();
                                                                break;

                                                            // Analyser un post spécifique
                                                            case 'analyze_post':
                                                                $chatbotController = new ChatbotController();
                                                                $chatbotController->analyzePost();
                                                                break;
                                                                case 'ai_dashboard':
                                                                    $adminController = new AdminController();
                                                                    $adminController->aiDashboard();
                                                                    break;

                                                                case 'ai_test':
                                                                    $aiController = new AIController();
                                                                    $aiController->test();
                                                                    break;
                                                                    // Toggle like
                                                                case 'toggle_like':
                                                                    $likeController = new LikeController();
                                                                    $likeController->toggleLike();
                                                                    break;

                                                                // View notifications
                                                                case 'notifications':
                                                                    $notificationController = new NotificationController();
                                                                    $notificationController->index();
                                                                    break;

                                                                // Mark notification as read
                                                                case 'mark_notification_read':
                                                                    $notificationController = new NotificationController();
                                                                    $notificationController->markAsRead();
                                                                    break;

                                                                // Mark all as read
                                                                case 'mark_all_read':
                                                                    $notificationController = new NotificationController();
                                                                    $notificationController->markAllAsRead();
                                                                    break;
                                                                    // Toggle comment like
                                                                case 'toggle_comment_like':
                                                                    $likeController = new LikeController();
                                                                    $likeController->toggleCommentLike();
                                                                    break;
                                                                    // Toggle like AJAX
                                                                                                    case 'toggle_like_ajax':
                                                                                                        $likeController = new LikeController();
                                                                                                        $likeController->toggleLikeAjax();
                                                                                                        break;

                                                                                                    // Toggle comment like AJAX
                                                                                                    case 'toggle_comment_like_ajax':
                                                                                                        $likeController = new LikeController();
                                                                                                        $likeController->toggleCommentLikeAjax();
                                                                                                        break;
                                                                                                        // Toggle reaction
                                                                                                        case 'toggle_reaction':
                                                                                                            require_once __DIR__ . '/../controllers/ReactionController.php';
                                                                                                            $reactionController = new ReactionController();
                                                                                                            $reactionController->toggleReaction();
                                                                                                            break;

                                                                                        

                                                                default:
                                                                    $controller->indexFront();
                                                                    break;
            }