<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de diagnostic</h1>";
echo "<p>PHP fonctionne : OUI ✅</p>";

// Test connexion base de données
try {
    require_once __DIR__ . '/config/config.php';
    echo "<p>Config chargée : OUI ✅</p>";
    
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    echo "<p>Connexion BD : OUI ✅</p>";
    
    require_once __DIR__ . '/models/EventModel.php';
    echo "<p>EventModel chargé : OUI ✅</p>";
    
    require_once __DIR__ . '/models/Reservation.php';
    echo "<p>Reservation chargé : OUI ✅</p>";
    
    $eventModel = new EventModel($pdo);
    $events = $eventModel->countEvents();
    echo "<p>Nombre d'événements : $events ✅</p>";
    
    echo "<h2>✅ Tout fonctionne correctement !</h2>";
    echo "<p><a href='public_index.php'>Essayer public_index.php</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ ERREUR : " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
