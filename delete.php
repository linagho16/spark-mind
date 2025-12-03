<?php
require_once('C:\xampp\htdocs\produit\controller\produitC.php');

$produitC = new ProduitC();

if (isset($_GET['id'])) {
    $produitC->deleteProduit($_GET['id']);
    header('Location: liste.php');
    exit();
} else {
    echo "Erreur : ID du produit non spécifié.";
}
?>
