<?php
require_once __DIR__ . '/../config/database.php';

class Reaction
{
    private $db;

    // Types de réactions disponibles
    const REACTIONS = [
        'love'  => '❤️',
        'haha'  => '😂',
        'wow'   => '😮',
        'sad'   => '😢',
        'angry' => '😡',
        'like'  => '👍',
        'fire'  => '🔥',
        'clap'  => '👏'
    ];

    public function __construct()
    {
        $this->db = (new Database())->pdo;
    }

    /**
     * Vérifier si le type de réaction est valide
     */
    public function isValidReactionType(string $reaction_type): bool
    {
        return array_key_exists($reaction_type, self::REACTIONS);
    }

    /**
     * Ajouter une réaction (remplace l'ancienne si existe)
     * - soit pour un post (post_id != null, comment_id = null)
     * - soit pour un commentaire (comment_id != null, post_id = null)
     */
    public function addReaction(int $user_id, string $reaction_type, ?int $post_id = null, ?int $comment_id = null): bool
    {
        try {
            // Validation simple
            if ($user_id <= 0) return false;
            if (!$this->isValidReactionType($reaction_type)) return false;

            // Il faut EXACTEMENT un target : post OU commentaire
            if (($post_id === null && $comment_id === null) || ($post_id !== null && $comment_id !== null)) {
                return false;
            }

            // Supprimer l'ancienne réaction sur CE post ou CE commentaire
            $this->removeReaction($user_id, $post_id, $comment_id);

            $sql = "INSERT INTO reactions (user_id, post_id, comment_id, reaction_type)
                    VALUES (:user_id, :post_id, :comment_id, :reaction_type)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':user_id'       => $user_id,
                ':post_id'       => $post_id,
                ':comment_id'    => $comment_id,
                ':reaction_type' => $reaction_type
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprimer une réaction (corrigé ✅)
     * Supprime UNIQUEMENT la réaction du user sur ce post OU ce commentaire.
     */
    public function removeReaction(int $user_id, ?int $post_id = null, ?int $comment_id = null): bool
    {
        try {
            if ($user_id <= 0) return false;

            // Réaction sur un POST
            if ($post_id !== null) {
                $sql = "DELETE FROM reactions
                        WHERE user_id = :user_id
                          AND post_id = :post_id
                          AND comment_id IS NULL";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    ':user_id' => $user_id,
                    ':post_id' => $post_id
                ]);
            }

            // Réaction sur un COMMENTAIRE
            if ($comment_id !== null) {
                $sql = "DELETE FROM reactions
                        WHERE user_id = :user_id
                          AND comment_id = :comment_id
                          AND post_id IS NULL";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    ':user_id'    => $user_id,
                    ':comment_id' => $comment_id
                ]);
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtenir la réaction d'un utilisateur (sur post OU commentaire)
     */
    public function getUserReaction(int $user_id, ?int $post_id = null, ?int $comment_id = null): ?string
    {
        $sql = "SELECT reaction_type
                FROM reactions
                WHERE user_id = :user_id";

        $params = [':user_id' => $user_id];

        if ($post_id !== null) {
            $sql .= " AND post_id = :post_id AND comment_id IS NULL";
            $params[':post_id'] = $post_id;
        } elseif ($comment_id !== null) {
            $sql .= " AND comment_id = :comment_id AND post_id IS NULL";
            $params[':comment_id'] = $comment_id;
        } else {
            return null;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['reaction_type'] : null;
    }

    /**
     * Obtenir le compte de chaque type de réaction (sur post OU commentaire)
     */
    public function getReactionCounts(?int $post_id = null, ?int $comment_id = null): array
    {
        if (($post_id === null && $comment_id === null) || ($post_id !== null && $comment_id !== null)) {
            return [];
        }

        if ($post_id !== null) {
            $sql = "SELECT reaction_type, COUNT(*) as count
                    FROM reactions
                    WHERE post_id = :id AND comment_id IS NULL
                    GROUP BY reaction_type";
            $value = $post_id;
        } else {
            $sql = "SELECT reaction_type, COUNT(*) as count
                    FROM reactions
                    WHERE comment_id = :id AND post_id IS NULL
                    GROUP BY reaction_type";
            $value = $comment_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $value]);

        $counts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $counts[$row['reaction_type']] = (int)$row['count'];
        }

        return $counts;
    }

    /**
     * Obtenir le nombre total de réactions (sur post OU commentaire)
     */
    public function getTotalReactions(?int $post_id = null, ?int $comment_id = null): int
    {
        if (($post_id === null && $comment_id === null) || ($post_id !== null && $comment_id !== null)) {
            return 0;
        }

        if ($post_id !== null) {
            $sql = "SELECT COUNT(*) as total
                    FROM reactions
                    WHERE post_id = :id AND comment_id IS NULL";
            $value = $post_id;
        } else {
            $sql = "SELECT COUNT(*) as total
                    FROM reactions
                    WHERE comment_id = :id AND post_id IS NULL";
            $value = $comment_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $value]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }
}
