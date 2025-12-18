<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $db;

    public function __construct() {
        $this->db = (new Database())->pdo;
    }

    public function create($titre, $contenu, $imagePath, $donation_type_id) {
        $sql = "INSERT INTO posts (titre, contenu, image, donation_type_id, user_id) VALUES (:titre, :contenu, :image, :donation_type_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'   => $titre ?: null,
            ':contenu' => $contenu,
            ':image'   => $imagePath,
            ':donation_type_id' => $donation_type_id,
            ':user_id' => $_SESSION['user_id'] ?? 1
        ]);
    }

    public function getAll($donation_type_id = null) {
        if ($donation_type_id){
            $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color
            FROM posts p
            LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
            WHERE p.donation_type_id = :type_id
            ORDER BY p.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':type_id' => $donation_type_id]);
        } else{
            $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color
            FROM posts p
            LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
            ORDER BY p.created_at DESC";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color
            FROM posts p
            LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
            WHERE p.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id, $titre, $contenu, $imagePath, $donation_type_id) {
        if ($imagePath) {
            $sql = "UPDATE posts 
            SET titre = :titre, contenu = :contenu, image= :image,
            donation_type_id = :donation_type_id
            WHERE id = :id";
            $params = [
                ':titre' => $titre ?: null,
                ':contenu' => $contenu,
                ':image' => $imagePath,
                ':donation_type_id' => $donation_type_id,
                ':id' => $id
            ];
            
        } else {
            $sql = "UPDATE posts 
            SET titre = :titre, contenu = :contenu, 
            donation_type_id = :donation_type_id
            WHERE id = :id";
            $params = [
                ':titre' => $titre ?: null,
                ':contenu' => $contenu,
                ':donation_type_id' => $donation_type_id,
                ':id' => $id
            ];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    public function delete($id) {
        $post = $this->getById($id);
        if ($post && !empty($post['image'])) {
            $imagePath = __DIR__ . '/../public/' . $post['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function getCommentsCount($post_id) {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE post_id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
