<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test Simple</h1>";

// Test 1: Inclusion db.php
echo "<h2>Test db.php</h2>";
$db_path = 'C:/xampp/htdocs/evenement/config/db.php';
if (file_exists($db_path)) {
    require_once $db_path;
    echo "
    
    
    
    
    db.php inclus<br>";
} else {
    die(" db.php non trouvé");
}

// Test 2: Inclusion EventController.php
echo "<h2>Test EventController.php</h2>";
$controller_path = 'C:/xampp/htdocs/evenement/Controllers/EventController.php';
if (file_exists($controller_path)) {
    require_once $controller_path;
    echo " EventController.php inclus<br>";
    
    // Test 3: Création du controller
    try {
        $controller = new EventController($pdo);
        echo " EventController créé avec succès<br>";
    } catch (Exception $e) {
        echo " Erreur création controller: " . $e->getMessage() . "<br>";
    }
} else {
    echo " EventController.php non trouvé à: " . $controller_path . "<br>";
}

echo "<h2> Test terminé</h2>";