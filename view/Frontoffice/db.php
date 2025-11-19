<?php
$servername = "localhost";  // généralement localhost
$username = "root";         // ton utilisateur MySQL
$password = "";             // ton mot de passe MySQL (vide par défaut sur XAMPP)
$dbname = "aide_solidaire"; // le nom de ta base de données

// Créer connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
