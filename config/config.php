<?php
// config.php - Configuration de la base de données et paramètres de l'application

class Config {
    // Configuration de la base de données
    const DB_HOST = 'localhost';
    const DB_NAME = 'projet_groupe3';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_CHARSET = 'utf8mb4';

    // Paramètres de l'application
    const SITE_NAME = 'Gestion Événements';
    const SITE_URL = 'http://localhost/evenement';
    const DEFAULT_TIMEZONE = 'Europe/Paris';
    
    // Paramètres de sécurité
    const SESSION_NAME = 'event_manager';
    const SESSION_LIFETIME = 3600; // 1 heure en secondes
    
    // Paramètres d'upload
    const UPLOAD_DIR = 'uploads/';
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Paramètres d'email (optionnel)
    const SMTP_HOST = '';
    const SMTP_USER = '';
    const SMTP_PASS = '';
    const SMTP_PORT = 587;
    
    // Pagination
    const EVENTS_PER_PAGE = 12;
    const PARTICIPANTS_PER_PAGE = 25;
}

// Connexion à la base de données
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=" . Config::DB_CHARSET;
        $pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Initialisation de la session
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(Config::SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => Config::SESSION_LIFETIME,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}

// Configuration du fuseau horaire
date_default_timezone_set(Config::DEFAULT_TIMEZONE);

// Gestion des erreurs (en développement)
if (defined('DEVELOPMENT') && DEVELOPMENT) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Fonctions utilitaires
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function formatDate($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '';
    $datetime = new DateTime($date);
    return $datetime->format($format);
}

// Initialisation automatique
initSession();
?>