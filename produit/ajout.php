<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once('C:\xampp\htdocs\produit\controller\produitC.php');
    require_once('C:\xampp\htdocs\produit\model\produit.php');

    $produitC = new ProduitC();

    // Champs texte
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $category = isset($_POST["category"]) ? trim($_POST["category"]) : "";
    $condition = isset($_POST["condition"]) ? trim($_POST["condition"]) : "";
    $statut = isset($_POST["statut"]) ? trim($_POST["statut"]) : "";

    // Gestion upload image
    $photo = "";
    if (!empty($_FILES["photo"]["name"])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir);

        $photo = $uploadDir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    // Vérifier les champs obligatoires
    if (
        !empty($title) &&
        !empty($description) &&
        !empty($category) &&
        !empty($condition) &&
        !empty($statut) &&
        !empty($photo)
    ) {

        $produit = new produit(
            null,
            $title,
            $description,
            $category,
            $condition,
            $statut,
            $photo
        );

        $result = $produitC->addProduit($produit);

        if ($result === "Produit ajouté avec succès !") {
            ?>
            <script>
                alert("Produit ajouté avec succès !");
                window.location.href = 'liste.php';
            </script>
            <?php
        } else {
            echo "<br><strong>$result</strong><br>";
        }

    } else {
        echo "<br><strong>Erreur : Un ou plusieurs champs sont vides.</strong><br>";
        if (empty($title)) echo "Champ title est vide.<br>";
        if (empty($description)) echo "Champ description est vide.<br>";
        if (empty($category)) echo "Champ catégorie est vide.<br>";
        if (empty($condition)) echo "Champ condition est vide.<br>";
        if (empty($statut)) echo "Champ statut est vide.<br>";
        if (empty($photo)) echo "Champ photo est vide.<br>";

        ?>
        <script>
            setTimeout(function() {
                window.location.href = 'ajout.php';
            }, 3000);
        </script>
        <?php
    }

} else {
    ?>
    <script>
        alert("Accès non autorisé !");
    </script>
    <?php
}
?>
