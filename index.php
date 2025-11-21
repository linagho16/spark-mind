<?php
// Point d'entrée principal de l'application
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Définir le chemin de base du projet
define('BASE_PATH', __DIR__);

// Inclusions
require_once BASE_PATH . '/config/db.php';
require_once BASE_PATH . '/Controllers/Eventcontroller.php';

$controller = new EventController($pdo);
$route = $_GET['route'] ?? 'events.index';

switch($route) {
    case 'events.index': 
        $controller->index(); 
        break;
    case 'events.show': 
        $controller->show(); 
        break;
    case 'events.create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;
    case 'events.edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } else {
            $controller->edit();
        }
        break;
    case 'events.delete': 
        $controller->destroy(); 
        break;
    default: 
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        break;
}
?>
