<?php
// Configuration générale de l'application

// Chemin de base de l'application
define('BASE_URL', '/evennement/');

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'evenement');  // Votre base de données
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration de l'application
define('APP_NAME', 'Gestion des Événements & Réservations');

// Connexion PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction de redirection
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit();
}
?>