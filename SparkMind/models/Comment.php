<?php
require_once __DIR__ . '/../config/database.php';

class Comment {
    private $db;
    public function __construct() {
        $this->db = (new Database())->pdo;
    }
    public function create($post_id, $content) {
        $sql= "INSERT INTO comments (post_id, user_id, content)
        VALUES (:post_id, :user_id, :content)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':post_id' => $post_id,
            ':user_id' => $_SESSION['user_id'] ?? 1,
            ':content' => $content
        ]);
    }
    public function getByPostId($post_id) {
        $sql = "SELECT c.*, 'Anonyme' as username
        FROM comments c
        WHERE c.post_id = :post_id
        ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id){
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id, $content) {
        $sql ="UPDATE comments SET content = :content WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':content' => $content
        ]);
    }
    public function delete($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]); 
    }
    public function getCount($post_id) {
        $sql = "SELECT COUNT(*) as count FROM comments Where post_id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    public function getAll() {
        $sql = "SELECT c.*, p.titre as post_titre, 'Anonyme' as username
        FROM comments c
        LEFT JOIN posts p ON c.post_id = p.id
        ORDER BY c.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}