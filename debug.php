<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de Diagnostic</h1>";

echo "<h2>1. Test PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Répertoire actuel: " . __DIR__ . "<br>";

echo "<h2>2. Test des fichiers</h2>";
$basePath = dirname(__DIR__, 1);
echo "Base Path: $basePath<br>";

$files = [
    'config/db.php' => $basePath . '/config/db.php',
    'Controllers/Eventcontroller.php' => $basePath . '/Controllers/Eventcontroller.php',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "$name: " . ($exists ? "✅ Existe" : "❌ N'existe pas") . " ($path)<br>";
}

echo "<h2>3. Test de connexion DB</h2>";
try {
    require_once $basePath . '/config/db.php';
    echo "✅ Connexion DB OK<br>";
    echo "PDO disponible: " . (isset($pdo) ? "Oui" : "Non") . "<br>";
} catch (Exception $e) {
    echo "❌ Erreur DB: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Test du contrôleur</h2>";
try {
    require_once $basePath . '/Controllers/Eventcontroller.php';
    if (class_exists('EventController')) {
        echo "✅ Classe EventController trouvée<br>";
    } else {
        echo "❌ Classe EventController non trouvée<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
}
?>

