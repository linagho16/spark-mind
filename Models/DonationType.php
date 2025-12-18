<?php
require_once __DIR__ . '/../config/database.php';

class DonationType {
    private $db;

    public function __construct() {
        $this->db = (new Database())->pdo;
    }
    public function getAll() {
        $sql = "SELECT * FROM donation_types ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $sql = "SELECT * FROM donation_types WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}