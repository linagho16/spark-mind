<?php
class Database {
    private $host = "localhost";
    private $dbname = "SparkMind"; 
    private $user = "root";
    private $pass = "";
    public $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->user,
                $this->pass
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur connexion DB : " . $e->getMessage());
        }
    }
}
