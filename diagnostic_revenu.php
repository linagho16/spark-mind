<?php
// Script de diagnostic pour le revenu total
require_once __DIR__ . '/config/config.php';
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
    
    $reservation = new Reservation($pdo);
    
    echo "<h2>🔍 Diagnostic du Revenu Total</h2>";
    echo "<hr>";
    
    // Vérifier si la table existe
    echo "<h3>1. Vérification de la table reservations</h3>";
    $checkTable = $pdo->query("SHOW TABLES LIKE 'reservations'")->fetch();
    if ($checkTable) {
        echo "✅ La table 'reservations' existe<br><br>";
    } else {
        echo "❌ La table 'reservations' n'existe pas !<br>";
        echo "<p style='color: red;'>Veuillez importer le fichier database.sql</p>";
        exit;
    }
    
    // Compter les réservations
    echo "<h3>2. Nombre de réservations</h3>";
    $count = $pdo->query("SELECT COUNT(*) as total FROM reservations")->fetch();
    echo "Total de réservations dans la base : <strong>{$count['total']}</strong><br><br>";
    
    if ($count['total'] == 0) {
        echo "<p style='color: orange;'>⚠️ Aucune réservation dans la base de données</p>";
        echo "<p>Pour tester, vous devez d'abord créer des réservations.</p>";
        echo "<p><a href='index.php?action=create_reservation' style='padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px;'>Créer une réservation</a></p>";
        echo "<hr>";
    }
    
    // Afficher toutes les réservations
    echo "<h3>3. Liste des réservations</h3>";
    $reservations = $pdo->query("SELECT * FROM reservations")->fetchAll();
    
    if (!empty($reservations)) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>
                <th>ID</th>
                <th>Client</th>
                <th>Places</th>
                <th>Montant</th>
                <th>Statut</th>
              </tr>";
        
        foreach ($reservations as $res) {
            $color = $res['statut'] == 'confirmée' ? '#d4edda' : 
                    ($res['statut'] == 'en attente' ? '#fff3cd' : '#f8d7da');
            echo "<tr style='background: {$color};'>
                    <td>{$res['id']}</td>
                    <td>{$res['nom_client']}</td>
                    <td>{$res['nombre_places']}</td>
                    <td>{$res['montant_total']} €</td>
                    <td><strong>{$res['statut']}</strong></td>
                  </tr>";
        }
        echo "</table><br>";
    }
    
    // Statistiques détaillées
    echo "<h3>4. Calcul manuel des statistiques</h3>";
    $manualStats = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN statut = 'confirmée' THEN 1 ELSE 0 END) as confirmées,
            SUM(CASE WHEN statut = 'en attente' THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut = 'annulée' THEN 1 ELSE 0 END) as annulées,
            SUM(montant_total) as total_brut,
            COALESCE(SUM(CASE WHEN statut = 'confirmée' THEN montant_total ELSE 0 END), 0) as revenu_confirmees
        FROM reservations
    ")->fetch();
    
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><td><strong>Total réservations</strong></td><td>{$manualStats['total']}</td></tr>";
    echo "<tr><td><strong>Confirmées</strong></td><td style='color: green;'>{$manualStats['confirmées']}</td></tr>";
    echo "<tr><td><strong>En attente</strong></td><td style='color: orange;'>{$manualStats['en_attente']}</td></tr>";
    echo "<tr><td><strong>Annulées</strong></td><td style='color: red;'>{$manualStats['annulées']}</td></tr>";
    echo "<tr><td><strong>Montant total (toutes)</strong></td><td>{$manualStats['total_brut']} €</td></tr>";
    echo "<tr style='background: #d4edda;'><td><strong>Revenu (confirmées uniquement)</strong></td><td style='font-size: 1.3em;'><strong>{$manualStats['revenu_confirmees']} €</strong></td></tr>";
    echo "</table><br>";
    
    // Appeler la méthode getStats()
    echo "<h3>5. Résultat de la méthode getStats()</h3>";
    $stats = $reservation->getStats();
    echo "<pre>";
    print_r($stats);
    echo "</pre>";
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>✅ Diagnostic terminé</strong></p>";
    
    if ($stats['revenu_total'] == 0 && $count['total'] == 0) {
        echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
        echo "<h3>💡 Solution</h3>";
        echo "<p>Vous n'avez aucune réservation dans la base de données.</p>";
        echo "<p><strong>Pour voir un revenu :</strong></p>";
        echo "<ol>";
        echo "<li>Créez un événement (si ce n'est pas déjà fait)</li>";
        echo "<li>Créez une réservation pour cet événement</li>";
        echo "<li>Confirmez la réservation (statut = confirmée)</li>";
        echo "<li>Le revenu s'affichera automatiquement</li>";
        echo "</ol>";
        echo "<p><a href='index.php?action=create_reservation' style='padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>➕ Créer une réservation</a></p>";
        echo "</div>";
    } else if ($stats['revenu_total'] == 0 && $manualStats['confirmées'] == 0) {
        echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
        echo "<h3>💡 Solution</h3>";
        echo "<p>Vous avez des réservations, mais aucune n'est <strong>confirmée</strong>.</p>";
        echo "<p>Le revenu total compte uniquement les réservations confirmées.</p>";
        echo "<p><a href='index.php?action=reservations' style='padding: 10px 20px; background: #2ecc71; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>✅ Confirmer des réservations</a></p>";
        echo "</div>";
    }
    
    echo "<p><a href='index.php'>← Retour au dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Erreur : </strong>" . $e->getMessage() . "</p>";
    echo "<p>Vérifiez que la base de données 'evenement' existe et que les tables sont créées.</p>";
}
?>
