<?php
require_once __DIR__ . '/../config/database.php';

class Like {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->pdo;
    }
    
    /**
     * Add a like to a post
     */
    public function addLike($post_id, $user_id) {
        try {
            $sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':post_id' => $post_id,
                ':user_id' => $user_id
            ]);
        } catch (PDOException $e) {
            // Duplicate like attempt (already liked)
            return false;
        }
    }
    
    /**
     * Remove a like from a post
     */
    public function removeLike($post_id, $user_id) {
        $sql = "DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':post_id' => $post_id,
            ':user_id' => $user_id
        ]);
    }
    
    /**
     * Check if user has liked a post
     */
    public function hasUserLiked($post_id, $user_id) {
        $sql = "SELECT COUNT(*) as count FROM likes 
                WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':post_id' => $post_id,
            ':user_id' => $user_id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    /**
     * Get total likes for a post
     */
    public function getLikesCount($post_id) {
        $sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Get all users who liked a post
     */
    public function getLikers($post_id) {
        $sql = "SELECT l.*, 'Anonyme' as username 
                FROM likes l 
                WHERE l.post_id = :post_id 
                ORDER BY l.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get post owner's user_id (for notifications)
     */
    public function getPostOwner($post_id) {
        $sql = "SELECT user_id FROM posts WHERE id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['user_id'] : null;
    }
    /**
 * Add like to a comment
 */
public function addCommentLike($comment_id, $user_id) {
    try {
        $sql = "INSERT INTO comment_likes (comment_id, user_id) VALUES (:comment_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':comment_id' => $comment_id,
            ':user_id' => $user_id
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Remove like from a comment
 */
public function removeCommentLike($comment_id, $user_id) {
    $sql = "DELETE FROM comment_likes WHERE comment_id = :comment_id AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':comment_id' => $comment_id,
        ':user_id' => $user_id
    ]);
}

/**
 * Check if user has liked a comment
 */
public function hasUserLikedComment($comment_id, $user_id) {
    $sql = "SELECT COUNT(*) as count FROM comment_likes 
            WHERE comment_id = :comment_id AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':comment_id' => $comment_id,
        ':user_id' => $user_id
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

/**
 * Get likes count for a comment
 */
public function getCommentLikesCount($comment_id) {
    $sql = "SELECT COUNT(*) as count FROM comment_likes WHERE comment_id = :comment_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':comment_id' => $comment_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

/**
 * Get comment owner
 */
public function getCommentOwner($comment_id) {
    $sql = "SELECT user_id FROM comments WHERE id = :comment_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':comment_id' => $comment_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['user_id'] : null;
}
}




























































