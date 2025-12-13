<?php
require_once __DIR__ . '/../config/database.php';

class Notification {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->pdo;
    }
    
    /**
     * Create a notification
     */
    public function create($user_id, $from_user_id, $post_id, $type, $message, $comment_id = null) {
        // Don't notify yourself
        if ($user_id == $from_user_id) {
            return false;
        }
        
        $sql = "INSERT INTO notifications (user_id, from_user_id, post_id, comment_id, type, message) 
                VALUES (:user_id, :from_user_id, :post_id, :comment_id, :type, :message)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':from_user_id' => $from_user_id,
            ':post_id' => $post_id,
            ':comment_id' => $comment_id,
            ':type' => $type,
            ':message' => $message
        ]);
    }
    
    /**
     * Get all notifications for a user
     */
    public function getUserNotifications($user_id, $limit = 20) {
        $sql = "SELECT n.*, p.titre as post_titre, p.contenu as post_contenu
                FROM notifications n
                LEFT JOIN posts p ON n.post_id = p.id
                WHERE n.user_id = :user_id
                ORDER BY n.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get unread notifications count
     */
    public function getUnreadCount($user_id) {
        $sql = "SELECT COUNT(*) as count FROM notifications 
                WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notification_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $notification_id]);
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($user_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }
    
    /**
     * Delete old notifications (older than 30 days)
     */
    public function deleteOldNotifications() {
        $sql = "DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        return $this->db->exec($sql);
    }
    
    /**
     * Helper: Create notification for new comment
     */
    public function notifyNewComment($post_owner_id, $commenter_id, $post_id, $comment_id) {
        $message = "Quelqu'un a commenté votre post";
        return $this->create($post_owner_id, $commenter_id, $post_id, 'comment', $message, $comment_id);
    }
    
    /**
     * Helper: Create notification for new like
     */
    public function notifyNewLike($post_owner_id, $liker_id, $post_id) {
        $message = "Quelqu'un a aimé votre post";
        return $this->create($post_owner_id, $liker_id, $post_id, 'like', $message);
    }
}




























































