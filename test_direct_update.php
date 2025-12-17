<?php
// Test direct de la requête UPDATE
session_start();
require_once __DIR__ . '/config/config.php';

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
    
    echo "<h2>🔍 Diagnostic approfondi de la modification</h2>";
    echo "<hr>";
    
    // 1. Vérifier la structure de la table
    echo "<h3>1. Structure de la table 'reservations'</h3>";
    $columns = $pdo->query("DESCRIBE reservations")->fetchAll();
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>Colonne</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
    }
    echo "</table><br>";
    
    // 2. Récupérer une réservation pour test
    echo "<h3>2. Données d'une réservation (AVANT modification)</h3>";
    $res = $pdo->query("SELECT * FROM reservations LIMIT 1")->fetch();
    
    if (!$res) {
        echo "<p style='color: red;'>Aucune réservation trouvée. Créez-en une d'abord.</p>";
        echo "<p><a href='index.php?action=create_reservation'>Créer une réservation</a></p>";
        exit;
    }
    
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    foreach ($res as $key => $value) {
        echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
    }
    echo "</table><br>";
    
    $test_id = $res['id'];
    $old_nom = $res['nom_client'];
    $new_nom = $old_nom . " - MODIFIÉ " . date('H:i:s');
    
    // 3. Test de la requête UPDATE directe
    echo "<h3>3. Test de la requête UPDATE</h3>";
    echo "<p>ID à modifier: <strong>$test_id</strong></p>";
    echo "<p>Ancien nom: <strong>$old_nom</strong></p>";
    echo "<p>Nouveau nom: <strong>$new_nom</strong></p>";
    
    $sql = "UPDATE reservations SET nom_client = :nom_client WHERE id = :id";
    echo "<p>Requête SQL: <code>$sql</code></p>";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':nom_client' => $new_nom,
        ':id' => $test_id
    ]);
    
    $rowCount = $stmt->rowCount();
    
    echo "<div style='background: " . ($result ? '#d4edda' : '#f8d7da') . "; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p>Résultat execute(): " . ($result ? '✅ TRUE' : '❌ FALSE') . "</p>";
    echo "<p>Nombre de lignes affectées (rowCount): <strong>$rowCount</strong></p>";
    echo "</div>";
    
    // 4. Vérifier si la modification a réussi
    echo "<h3>4. Vérification (APRÈS modification)</h3>";
    $after = $pdo->query("SELECT * FROM reservations WHERE id = $test_id")->fetch();
    
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>Champ</th><th>Avant</th><th>Après</th><th>Changé?</th></tr>";
    foreach ($res as $key => $value) {
        $changed = ($value != $after[$key]) ? '✅ OUI' : '❌ NON';
        $color = ($value != $after[$key]) ? '#d4edda' : '#fff';
        echo "<tr style='background: $color;'>";
        echo "<td><strong>$key</strong></td>";
        echo "<td>$value</td>";
        echo "<td>{$after[$key]}</td>";
        echo "<td>$changed</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // 5. Test avec la méthode du modèle
    echo "<h3>5. Test avec la méthode Reservation->update()</h3>";
    require_once __DIR__ . '/models/Reservation.php';
    $reservation = new Reservation($pdo);
    
    $test_data = [
        'event_id' => $res['event_id'],
        'nom_client' => $old_nom . " - MODELE " . date('H:i:s'),
        'email' => $res['email'],
        'telephone' => $res['telephone'],
        'nombre_places' => $res['nombre_places'],
        'montant_total' => $res['montant_total'],
        'statut' => $res['statut'],
        'methode_paiement' => $res['methode_paiement'] ?? 'carte',
        'notes' => $res['notes'] ?? ''
    ];
    
    echo "<p>Nouveau nom à tester: <strong>{$test_data['nom_client']}</strong></p>";
    
    try {
        $model_result = $reservation->update($test_id, $test_data);
        echo "<div style='background: " . ($model_result ? '#d4edda' : '#f8d7da') . "; padding: 15px; border-radius: 5px;'>";
        echo "<p>Résultat: " . ($model_result ? '✅ TRUE' : '❌ FALSE') . "</p>";
        echo "</div>";
        
        // Vérifier après update du modèle
        $final = $pdo->query("SELECT nom_client FROM reservations WHERE id = $test_id")->fetch();
        echo "<p>Nom dans la BD après update(): <strong>{$final['nom_client']}</strong></p>";
        
        if ($final['nom_client'] == $test_data['nom_client']) {
            echo "<p style='color: green; font-weight: bold;'>✅ LA MODIFICATION FONCTIONNE !</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ LA MODIFICATION NE FONCTIONNE PAS</p>";
            echo "<p>Attendu: {$test_data['nom_client']}</p>";
            echo "<p>Reçu: {$final['nom_client']}</p>";
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "<p style='color: red;'><strong>❌ ERREUR : </strong>" . $e->getMessage() . "</p>";
        echo "</div>";
    }
    
    // 6. Diagnostic des valeurs NULL
    echo "<h3>6. Vérification des valeurs NULL/vides</h3>";
    echo "<p>Vérifier si des champs obligatoires sont NULL...</p>";
    
    $nullCheck = $pdo->query("
        SELECT 
            id,
            event_id IS NULL as event_null,
            nom_client IS NULL as nom_null,
            email IS NULL as email_null,
            telephone IS NULL as tel_null,
            nombre_places IS NULL as places_null,
            montant_total IS NULL as montant_null,
            statut IS NULL as statut_null
        FROM reservations 
        LIMIT 5
    ")->fetchAll();
    
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>ID</th><th>event_id NULL?</th><th>nom NULL?</th><th>email NULL?</th><th>tel NULL?</th><th>places NULL?</th><th>montant NULL?</th><th>statut NULL?</th></tr>";
    foreach ($nullCheck as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>" . ($row['event_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "<td>" . ($row['nom_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "<td>" . ($row['email_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "<td>" . ($row['tel_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "<td>" . ($row['places_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "<td>" . ($row['montant_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "<td>" . ($row['statut_null'] ? '❌ OUI' : '✅ NON') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    echo "<hr>";
    echo "<h3>📊 Résumé</h3>";
    echo "<ul>";
    echo "<li>✅ Table 'reservations' existe</li>";
    echo "<li>✅ Colonnes présentes: " . count($columns) . "</li>";
    echo "<li>✅ Requête UPDATE simple fonctionne (rowCount: $rowCount)</li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<p><a href='index.php'>← Retour au dashboard</a> | ";
    echo "<a href='index.php?action=reservations'>Voir les réservations</a> | ";
    echo "<a href='test_direct_update.php'>🔄 Rafraîchir ce test</a></p>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ ERREUR CRITIQUE</h3>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Ligne:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
        background: #f5f5f5;
    }
    h2, h3 {
        color: #333;
    }
    table {
        background: white;
        margin: 15px 0;
        width: 100%;
        max-width: 800px;
    }
    code {
        background: #f0f0f0;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
    }
    a {
        color: #2196F3;
        text-decoration: none;
        padding: 8px 15px;
        background: white;
        border-radius: 5px;
        display: inline-block;
        margin: 5px;
    }
    a:hover {
        background: #2196F3;
        color: white;
    }
</style>
```