<?php
// ==========================================
// SPARKMIND - CONFIG.PHP
// Configuration de la base de données
// ==========================================

class Database {
    
    // Paramètres de connexion
    private $host = "localhost";
    private $db_name = "sparkmind_db";
    private $username = "root";
    private $password = "";  // Mot de passe VIDE (par défaut XAMPP)
    private $charset = "utf8mb4";
    
    public $conn;
    
    /**
     * Établir la connexion à la base de données
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Construction du DSN (Data Source Name)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            
            // Options PDO pour améliorer la sécurité et les performances
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            // Création de la connexion PDO
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
            error_log("Erreur DB: " . $exception->getMessage());
        }
        
        return $this->conn;
    }
    
    /**
     * Fermer la connexion
     */
    public function closeConnection() {
        $this->conn = null;
    }
}
?>