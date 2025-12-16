<?php
session_start();
require_once 'controller/CategorieC.php';

if (isset($_GET['id'])) {
    $categorieC = new CategorieC();
    
    try {
        $categorieC->deleteCategorie($_GET['id']);
        
        // Vérifier si des produits ont été supprimés
        if (isset($_SESSION['info_suppression']) && $_SESSION['info_suppression'] > 0) {
            $nbProduits = $_SESSION['info_suppression'];
            $_SESSION['success'] = "Catégorie supprimée avec succès ! ($nbProduits produit(s) supprimé(s) également)";
            unset($_SESSION['info_suppression']);
        } else {
            $_SESSION['success'] = "Catégorie supprimée avec succès !";
        }
        
        header('Location: listeCategories.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: listeCategories.php');
        exit();
    }
} else {
    $_SESSION['error'] = "ID de catégorie manquant.";
    header('Location: listeCategories.php');
    exit();
}
?>
