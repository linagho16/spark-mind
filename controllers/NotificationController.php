<?php
require_once __DIR__ . '/../models/Notification.php';

class NotificationController {
    
    /**
     * Display notifications page
     */
    public function index() {
        $user_id = $_SESSION['user_id'] ?? 1;
        
        $notificationModel = new Notification();
        $notifications = $notificationModel->getUserNotifications($user_id, 50);
        
        include __DIR__ . '/../views/notifications/index.php';
    }
    
    /**
     * Get unread count (for AJAX badge updates)
     */
    public function getUnreadCount() {
        header('Content-Type: application/json');
        
        $user_id = $_SESSION['user_id'] ?? 1;
        $notificationModel = new Notification();
        $count = $notificationModel->getUnreadCount($user_id);
        
        echo json_encode(['count' => $count]);
        exit;
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notification_id = $_POST['notification_id'] ?? null;
            
            if ($notification_id) {
                $notificationModel = new Notification();
                $notificationModel->markAsRead($notification_id);
            }
            
            // Redirect to the post if specified
            $redirect = $_POST['redirect'] ?? 'index.php?action=notifications';
            header('Location: ' . $redirect);
            exit;
        }
    }
    
    /**
     * Mark all as read
     */
    public function markAllAsRead() {
        $user_id = $_SESSION['user_id'] ?? 1;
        
        $notificationModel = new Notification();
        $notificationModel->markAllAsRead($user_id);
        
        header('Location: index.php?action=notifications');
        exit;
    }
}




























































