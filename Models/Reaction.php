<?php
require_once __DIR__ . '/../config/database.php';

class Reaction {
    private $db;
    
    // Types de réactions disponibles
    const REACTIONS = [
        'love' => '❤️',
        'haha' => '😂',
        'wow' => '😮',
        'sad' => '😢',
        'angry' => '😡',
        'like' => '👍',
        'fire' => '🔥',
        'clap' => '👏'
    ];
    
    public function __construct() {
        $this->db = (new Database())->pdo;
    }
    
    /**
     * Ajouter une réaction (remplace l'ancienne si existe)
     */
    public function addReaction($user_id, $reaction_type, $post_id = null, $comment_id = null) {
        try {
            // Supprimer l'ancienne réaction si existe
            $this->removeReaction($user_id, $post_id, $comment_id);
            
            $sql = "INSERT INTO reactions (user_id, post_id, comment_id, reaction_type) 
                    VALUES (:user_id, :post_id, :comment_id, :reaction_type)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $user_id,
                ':post_id' => $post_id,
                ':comment_id' => $comment_id,
                ':reaction_type' => $reaction_type
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Supprimer une réaction
     */
    public function removeReaction($user_id, $post_id = null, $comment_id = null) {
        $sql = "DELETE FROM reactions 
                WHERE user_id = :user_id 
                AND (post_id = :post_id OR post_id IS NULL) 
                AND (comment_id = :comment_id OR comment_id IS NULL)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':post_id' => $post_id,
            ':comment_id' => $comment_id
        ]);
    }
    
    /**
     * Obtenir la réaction d'un utilisateur
     */
    public function getUserReaction($user_id, $post_id = null, $comment_id = null) {
        $sql = "SELECT reaction_type FROM reactions 
                WHERE user_id = :user_id";
        
        if ($post_id) {
            $sql .= " AND post_id = :post_id AND comment_id IS NULL";
        } elseif ($comment_id) {
            $sql .= " AND comment_id = :comment_id AND post_id IS NULL";
        }
        
        $stmt = $this->db->prepare($sql);
        $params = [':user_id' => $user_id];
        if ($post_id) $params[':post_id'] = $post_id;
        if ($comment_id) $params[':comment_id'] = $comment_id;
        
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['reaction_type'] : null;
    }
    
    /**
     * Obtenir le compte de chaque type de réaction
     */
    public function getReactionCounts($post_id = null, $comment_id = null) {
        $sql = "SELECT reaction_type, COUNT(*) as count 
                FROM reactions 
                WHERE ";
        
        if ($post_id) {
            $sql .= "post_id = :id AND comment_id IS NULL";
            $param = ':id';
            $value = $post_id;
        } else {
            $sql .= "comment_id = :id AND post_id IS NULL";
            $param = ':id';
            $value = $comment_id;
        }
        
        $sql .= " GROUP BY reaction_type";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$param => $value]);
        
        $counts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $counts[$row['reaction_type']] = $row['count'];
        }
        
        return $counts;
    }
    
    /**
     * Obtenir le nombre total de réactions
     */
    public function getTotalReactions($post_id = null, $comment_id = null) {
        $sql = "SELECT COUNT(*) as total FROM reactions WHERE ";
        
        if ($post_id) {
            $sql .= "post_id = :id AND comment_id IS NULL";
        } else {
            $sql .= "comment_id = :id AND post_id IS NULL";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $post_id ?? $comment_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'];
    }
}

























































































































































































