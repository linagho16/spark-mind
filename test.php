<?php
// Test simple pour vérifier que PHP fonctionne
echo "PHP fonctionne !<br>";
echo "Répertoire actuel : " . __DIR__ . "<br>";
echo "Fichier actuel : " . __FILE__ . "<br>";

// Test de connexion à la base de données
echo "<br>Test de connexion à la base de données :<br>";
try {
    require_once __DIR__ . '/../../config/db.php';
    echo "✅ Connexion à la base de données réussie !<br>";
    echo "Base de données : projet_groupe3<br>";
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

// Test des fichiers
echo "<br>Vérification des fichiers :<br>";
$files = [
    'config/db.php' => __DIR__ . '/../../config/db.php',
    'Controllers/Eventcontroller.php' => __DIR__ . '/../../Controllers/Eventcontroller.php',
    'Models/Event.php' => __DIR__ . '/../../Models/Event.php',
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name existe<br>";
    } else {
        echo "❌ $name n'existe pas (chemin: $path)<br>";
    }
}
?>

