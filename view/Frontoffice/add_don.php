<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_don = $_POST['type_don'];
    $quantite = $_POST['quantite'];
    $etat_objet = $_POST['etat_objet'];
    $region = $_POST['region'];
    $description = $_POST['description'];

    $sql = "INSERT INTO dons (type_don, quantite, etat_objet, region, description)
            VALUES ('$type_don', '$quantite', '$etat_objet', '$region', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Don ajouté avec succès !</p>";
    } else {
        echo "<p style='color:red;'>Erreur : " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un don</title>
    <link rel="stylesheet" href="add_don.css">
</head>
<body>
<form action="" method="POST">
    <input type="text" name="type_don" placeholder="Type de don" required>
    <input type="text" name="quantite" placeholder="Quantité">
    <input type="text" name="etat_objet" placeholder="État">
    <input type="text" name="region" placeholder="Région">
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Envoyer</button>
</form>
</body>
</html>
