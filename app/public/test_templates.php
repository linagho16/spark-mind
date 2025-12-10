<?php
echo "<h1>Test des Templates</h1>";

$template_dir = 'C:/xampp/htdocs/evenement/templates/';
$header_path = $template_dir . 'front_header.php';
$footer_path = $template_dir . 'front_footer.php';

echo "Dossier templates: " . $template_dir . "<br>";
echo "Existe: " . (is_dir($template_dir) ? 'OUI' : 'NON') . "<br><br>";

echo "Header: " . $header_path . "<br>";
echo "Existe: " . (file_exists($header_path) ? 'OUI' : 'NON') . "<br><br>";

echo "Footer: " . $footer_path . "<br>";
echo "Existe: " . (file_exists($footer_path) ? 'OUI' : 'NON') . "<br><br>";

// Test inclusion
if (file_exists($header_path)) {
    echo "<h2>Test inclusion header:</h2>";
    include $header_path;
    echo " Header inclus avec succès<br>";
    include $footer_path;
    echo " Footer inclus avec succès<br>";
} else {
    echo " Les templates n'existent pas au chemin attendu";
}