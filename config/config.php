<?php

// ===============================
// Configuration base de données
// ===============================

$host = 'localhost';
$db   = 'sparkmind';   // ✅ base principale SparkMind
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Erreur de connexion à la base : ' . $e->getMessage());
}

// ===============================
// Constantes globales
// ===============================

if (!defined('BASE_URL')) {
    define('BASE_URL', '/sparkmind_mvc_100percent/');
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'SparkMind');
}


// Fonction utilitaire de redirection
if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}

