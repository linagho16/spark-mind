<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug Création</h1>";

// Test db.php
require_once 'C:/xampp/htdocs/evenement/config/db.php';
echo " db.php chargé<br>";

// Test si la table existe
try {
    $stmt = $pdo->query("SELECT 1 FROM evenement LIMIT 1");
    echo " Table 'evenement' existe<br>";
} catch (Exception $e) {
    echo " Table 'evenement' n'existe pas: " . $e->getMessage() . "<br>";
}

// Test POST
echo "<h2>Données POST:</h2>";
var_dump($_POST);

echo "<h2>Test Model</h2>";
require_once 'C:/xampp/htdocs/evenement/Models/Event.php';
$model = new Event($pdo);

// Test création
$test_data = [
    'titre' => 'Test Event',
    'description' => 'Description test',
    'date_event' => '2024-12-25',
    'lieu' => 'Paris',
    'prix' => '25.00'
];

try {
    $id = $model->create($test_data);
    echo " Création test réussie - ID: $id<br>";
} catch (Exception $e) {
    echo " Erreur création: " . $e->getMessage() . "<br>";
}