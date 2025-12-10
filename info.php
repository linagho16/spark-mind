<?php
echo "<h1>Test de connexion</h1>";
echo "<p>PHP fonctionne !</p>";
echo "<p>Répertoire actuel : " . __DIR__ . "</p>";
echo "<p>Fichier actuel : " . __FILE__ . "</p>";

// Test des chemins
$basePath = dirname(__DIR__, 1);
echo "<p>Base Path calculé : $basePath</p>";

// Vérifier les fichiers
$files = [
    'config/db.php',
    'Controllers/Eventcontroller.php',
    'Models/Event.php',
    'app/public/index.php'
];

echo "<h2>Vérification des fichiers :</h2>";
foreach ($files as $file) {
    $fullPath = $basePath . '/' . $file;
    $exists = file_exists($fullPath);
    echo "<p>" . ($exists ? "✅" : "❌") . " $file</p>";
    if (!$exists) {
        echo "<small style='color:red;'>Chemin testé : $fullPath</small><br>";
    }
}
?>

