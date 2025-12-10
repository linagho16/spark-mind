<?php
// Test de modification de réservation
session_start();
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
    
    echo "<h2>🔍 Test de modification de réservation</h2>";
    echo "<hr>";
    
    // Afficher toutes les réservations
    echo "<h3>Réservations existantes</h3>";
    $reservations = $pdo->query("SELECT * FROM reservations")->fetchAll();
    
    if (empty($reservations)) {
        echo "<p style='color: orange;'>⚠️ Aucune réservation à tester. Créez d'abord une réservation.</p>";
        echo "<p><a href='index.php?action=create_reservation' class='btn'>Créer une réservation</a></p>";
    } else {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>
                <th>ID</th>
                <th>Client</th>
                <th>Email</th>
                <th>Places</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Action</th>
              </tr>";
        
        foreach ($reservations as $res) {
            echo "<tr>
                    <td>{$res['id']}</td>
                    <td>{$res['nom_client']}</td>
                    <td>{$res['email']}</td>
                    <td>{$res['nombre_places']}</td>
                    <td>{$res['montant_total']} €</td>
                    <td>{$res['statut']}</td>
                    <td><a href='index.php?action=edit_reservation&id={$res['id']}'>✏️ Modifier</a></td>
                  </tr>";
        }
        echo "</table><br>";
        
        // Test de mise à jour
        echo "<h3>Test de mise à jour</h3>";
        echo "<p>Pour tester, cliquez sur 'Modifier' ci-dessus, changez quelque chose et soumettez.</p>";
        
        // Vérifier si on reçoit des données POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_id'])) {
            echo "<div style='background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h4>📋 Données reçues du formulaire :</h4>";
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            
            // Tester la mise à jour
            $test_id = $_POST['test_id'];
            $data = [
                'event_id' => $_POST['event_id'],
                'nom_client' => $_POST['nom_client'],
                'email' => $_POST['email'],
                'telephone' => $_POST['telephone'],
                'nombre_places' => $_POST['nombre_places'],
                'montant_total' => $_POST['montant_total'],
                'statut' => $_POST['statut'],
                'methode_paiement' => $_POST['methode_paiement'],
                'notes' => $_POST['notes']
            ];
            
            try {
                $result = $reservation->update($test_id, $data);
                if ($result) {
                    echo "<p style='color: green;'><strong>✅ Mise à jour réussie !</strong></p>";
                    echo "<p><a href='test_modification.php'>Rafraîchir la page</a></p>";
                } else {
                    echo "<p style='color: red;'><strong>❌ Échec de la mise à jour</strong></p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: red;'><strong>❌ Erreur : " . $e->getMessage() . "</strong></p>";
            }
            echo "</div>";
        }
        
        // Formulaire de test
        if (!empty($reservations)) {
            $first = $reservations[0];
            echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
            echo "<h4>🧪 Formulaire de test rapide</h4>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='test_id' value='{$first['id']}'>";
            echo "<input type='hidden' name='event_id' value='{$first['event_id']}'>";
            echo "<input type='hidden' name='methode_paiement' value='{$first['methode_paiement']}'>";
            echo "<input type='hidden' name='notes' value='{$first['notes']}'>";
            echo "<input type='hidden' name='nombre_places' value='{$first['nombre_places']}'>";
            echo "<input type='hidden' name='montant_total' value='{$first['montant_total']}'>";
            echo "<p>Modification de la réservation ID: <strong>{$first['id']}</strong></p>";
            echo "<p>Nouveau nom du client: <input type='text' name='nom_client' value='{$first['nom_client']} - MODIFIÉ' style='padding: 5px; width: 300px;'></p>";
            echo "<p>Nouvel email: <input type='email' name='email' value='{$first['email']}' style='padding: 5px; width: 300px;'></p>";
            echo "<p>Nouveau téléphone: <input type='text' name='telephone' value='{$first['telephone']}' style='padding: 5px; width: 300px;'></p>";
            echo "<p>Nouveau statut: 
                    <select name='statut' style='padding: 5px;'>
                        <option value='en attente'>En attente</option>
                        <option value='confirmée'>Confirmée</option>
                        <option value='annulée'>Annulée</option>
                    </select>
                  </p>";
            echo "<button type='submit' style='padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>🧪 Tester la modification</button>";
            echo "</form>";
            echo "</div>";
        }
    }
    
    echo "<hr>";
    echo "<p><a href='index.php'>← Retour au dashboard</a> | <a href='index.php?action=reservations'>Voir les réservations</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Erreur : </strong>" . $e->getMessage() . "</p>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    table {
        margin: 20px 0;
    }
    .btn {
        padding: 10px 20px;
        background: #2196F3;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }
</style>
```