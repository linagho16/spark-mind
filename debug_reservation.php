<?php
// DEBUG - Afficher toutes les requêtes POST
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>🔍 Données POST reçues :</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>Action et ID :</h3>";
    echo "Action: " . ($_GET['action'] ?? 'NON DÉFINI') . "<br>";
    echo "ID: " . ($_GET['id'] ?? 'NON DÉFINI') . "<br>";
    
    echo "<hr>";
    echo "<h3>Vérification des champs requis :</h3>";
    
    $required = ['event_id', 'nom_client', 'email', 'telephone', 'nombre_places', 'statut'];
    foreach ($required as $field) {
        $status = isset($_POST[$field]) && !empty($_POST[$field]) ? '✅' : '❌';
        $value = $_POST[$field] ?? 'NON DÉFINI';
        echo "$status $field: $value<br>";
    }
    
    echo "<hr>";
    echo "<p><a href='index.php?action=reservations'>← Retour aux réservations</a></p>";
    exit;
}

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
    $reservations = $pdo->query("SELECT * FROM reservations LIMIT 1")->fetch();
    
    if (!$reservations) {
        echo "<p>Aucune réservation à tester. <a href='index.php?action=create_reservation'>Créez une réservation</a></p>";
        exit;
    }
    
    echo "<h2>📝 Formulaire de test de modification</h2>";
    echo "<p>ID de test: {$reservations['id']}</p>";
    ?>
    
    <form method="POST" action="debug_reservation.php?action=update&id=<?= $reservations['id'] ?>">
        <p>Nom: <input type="text" name="nom_client" value="<?= $reservations['nom_client'] ?> TEST" required></p>
        <p>Email: <input type="email" name="email" value="<?= $reservations['email'] ?>" required></p>
        <p>Téléphone: <input type="text" name="telephone" value="<?= $reservations['telephone'] ?>" required></p>
        <p>Event ID: <input type="number" name="event_id" value="<?= $reservations['event_id'] ?>" required></p>
        <p>Places: <input type="number" name="nombre_places" value="<?= $reservations['nombre_places'] ?>" required></p>
        <p>Montant: <input type="number" step="0.01" name="montant_total" value="<?= $reservations['montant_total'] ?>" required></p>
        <p>Statut: 
            <select name="statut" required>
                <option value="en attente">En attente</option>
                <option value="confirmée">Confirmée</option>
                <option value="annulée">Annulée</option>
            </select>
        </p>
        <p>Méthode: 
            <select name="methode_paiement">
                <option value="carte">Carte</option>
                <option value="especes">Espèces</option>
            </select>
        </p>
        <p>Notes: <textarea name="notes"><?= $reservations['notes'] ?></textarea></p>
        <button type="submit">🧪 Tester l'envoi</button>
    </form>
    
    <hr>
    <p><a href="index.php?action=edit_reservation&id=<?= $reservations['id'] ?>">Modifier avec le vrai formulaire</a></p>
    
    <?php
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<style>
    body { font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto; }
    input, select, textarea { padding: 8px; margin: 5px 0; width: 100%; max-width: 400px; }
    button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
    pre { background: #f0f0f0; padding: 15px; border-radius: 5px; overflow-x: auto; }
</style>
```