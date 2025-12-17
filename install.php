<?php
// install.php - version propre (sans conflits Git)

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Install SparkMind</h2>";

$pathsToTry = [
    __DIR__ . '/config/config.php',
    __DIR__ . '/config/database.php',
    __DIR__ . '/db.php'
];

$loaded = false;
foreach ($pathsToTry as $p) {
    if (file_exists($p)) {
        require_once $p;
        $loaded = true;
        echo "<p>✅ Fichier chargé : <code>" . htmlspecialchars($p) . "</code></p>";
        break;
    }
}

if (!$loaded) {
    echo "<p style='color:red'>❌ Aucun fichier de configuration DB trouvé (config/config.php, config/database.php, db.php).</p>";
    exit;
}

// Si $pdo existe, on teste la connexion
if (isset($pdo) && $pdo instanceof PDO) {
    try {
        $pdo->query("SELECT 1");
        echo "<p>✅ Connexion PDO OK.</p>";
    } catch (Exception $e) {
        echo "<p style='color:red'>❌ Connexion PDO échouée : " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
} else {
    echo "<p style='color:orange'>⚠️ \$pdo n'est pas disponible. Vérifie ton fichier config.</p>";
}

echo "<hr>";
echo "<p>✅ Install prêt. (Si tu veux créer les tables ici, colle-moi ton ancien install.php et je l’intègre proprement.)</p>";
