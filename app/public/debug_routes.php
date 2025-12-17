<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug Routes</h1>";

// Test des inclusions
require_once 'C:/xampp/htdocs/evenement/config/db.php';
require_once 'C:/xampp/htdocs/evenement/Controllers/EventController.php';

echo " Fichiers inclus<br>";

// Test route
$route = $_GET['route'] ?? 'events.index';
echo "Route demandée: " . $route . "<br>";
echo "Méthode HTTP: " . $_SERVER['REQUEST_METHOD'] . "<br>";

// Test controller
$controller = new EventController($pdo);
echo " Controller créé<br>";

// Test méthode create
echo "<h2>Test méthode create()</h2>";
$controller->create();