<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $db;

    public function __construct() {
        $this->db = (new Database())->pdo;
    }

    public function create($titre, $contenu) {
        $sql = "INSERT INTO posts (titre, contenu) VALUES (:titre, :contenu)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'   => $titre ?: null,
            ':contenu' => $contenu
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
