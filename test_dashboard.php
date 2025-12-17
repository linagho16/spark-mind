<?php
// Test rapide pour vérifier les statistiques du dashboard
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/EventModel.php';
require_once __DIR__ . '/models/Reservation.php';

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
    
    $eventModel = new EventModel($pdo);
    $reservation = new Reservation($pdo);
    
    echo "<h2>Test des statistiques du Dashboard</h2>";
    echo "<hr>";
    
    echo "<h3>Événements</h3>";
    $eventsCount = $eventModel->countEvents();
    echo "Nombre total d'événements : <strong>$eventsCount</strong><br>";
    
    echo "<h3>Événements à venir</h3>";
    $upcomingEvents = $eventModel->getUpcomingEvents(5);
    echo "Nombre d'événements à venir : <strong>" . count($upcomingEvents) . "</strong><br>";
    if (!empty($upcomingEvents)) {
        echo "<ul>";
        foreach ($upcomingEvents as $event) {
            echo "<li>{$event['titre']} - " . date('d/m/Y', strtotime($event['date_event'])) . "</li>";
        }
        echo "</ul>";
    }
    
    echo "<h3>Réservations</h3>";
    $stats = $reservation->getStats();
    echo "<pre>";
    print_r($stats);
    echo "</pre>";
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>✅ Test terminé avec succès !</strong></p>";
    echo "<p><a href='index.php'>Retour au dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}
?>
