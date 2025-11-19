<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start session
session_start();
// Define base path for includes
define('BASE_PATH', __DIR__);
// Autoload classes 
spl_autoload_register(function ($class_name) {
    $paths = [
        'controller/' . $class_name . '.php',
        'Model/' . $class_name . '.php', 
        'view/' . $class_name . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
try {
    // Include the controller
    require_once 'controller/donC.php';
    
    // Check if the controller file exists
    if (!file_exists('controller/donC.php')) {
        throw new Exception("Controller file not found. Please check the file path.");
    }
    
    // Initialize and handle the request
    $controller = new DonController();
    $controller->handleRequest();
    
} catch (Exception $e) {
    // Display error message
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Erreur - Aide Solidaire</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                background: #f8f9fa; 
                margin: 0; 
                padding: 2rem; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                min-height: 100vh; 
            }
            .error-container { 
                background: white; 
                padding: 2rem; 
                border-radius: 10px; 
                box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
                max-width: 600px; 
                text-align: center; 
            }
            .error-icon { 
                font-size: 3rem; 
                margin-bottom: 1rem; 
            }
            h1 { 
                color: #dc3545; 
                margin-bottom: 1rem; 
            }
            .error-details { 
                background: #f8f9fa; 
                padding: 1rem; 
                border-radius: 5px; 
                margin: 1rem 0; 
                text-align: left; 
                font-family: monospace; 
                font-size: 0.9rem; 
            }
            .btn { 
                display: inline-block; 
                padding: 0.75rem 1.5rem; 
                background: #007bff; 
                color: white; 
                text-decoration: none; 
                border-radius: 5px; 
                margin: 0.5rem; 
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <div class='error-icon'>⚠️</div>
            <h1>Erreur d'Application</h1>
            <p>Une erreur s'est produite lors du chargement de l'application.</p>
            
            <div class='error-details'>
                <strong>Message d'erreur:</strong><br>
                " . htmlspecialchars($e->getMessage()) . "
            </div>
            
            <p>Veuillez vérifier que :</p>
            <ul style='text-align: left;'>
                <li>Le fichier controller/donC.php existe</li>
                <li>Le fichier Model/donmodel.php existe</li>
                <li>La base de données est configurée</li>
            </ul>
            
            <div>
                <a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' class='btn'>🔄 Réessayer</a>
                <a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?action=dashboard' class='btn'>📊 Tableau de bord</a>
            </div>
        </div>
    </body>
    </html>";
    
    error_log("MVC Application Error: " . $e->getMessage());
}
?>