<?php
require_once('config.php');

class CategorieC
{
    // Ajouter une catégorie
    public function addCategorie($categorie)
    {
        $sql = "INSERT INTO categorie (nomC, descriptionC, dateC, nom_Createur)
                VALUES (:nomC, :descriptionC, :dateC, :nom_Createur)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nomC' => $categorie->getNomC(),
                'descriptionC' => $categorie->getDescriptionC(),
                'dateC' => $categorie->getDateC(),
                'nom_Createur' => $categorie->getNom_Createur()
            ]);
            return "Catégorie ajoutée avec succès !";
        } catch (PDOException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
            return "Erreur lors de l'ajout de la catégorie.";
        }
    }

    // Liste des catégories
    public function listCategories()
    {
        $sql = "SELECT idc, nomC, descriptionC, dateC, nom_Createur FROM categorie";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Supprimer une catégorie (avec suppression en cascade des produits)
    public function deleteCategorie($idc)
    {
        $db = config::getConnexion();
        
        try {
            // Compter le nombre de produits associés pour information
            $checkSql = "SELECT COUNT(*) as count FROM produit WHERE category = :idc";
            $checkQuery = $db->prepare($checkSql);
            $checkQuery->bindValue(':idc', $idc);
            $checkQuery->execute();
            $result = $checkQuery->fetch(PDO::FETCH_ASSOC);
            $nbProduits = $result['count'];
            
            // Supprimer d'abord tous les produits associés à cette catégorie
            if ($nbProduits > 0) {
                $deleteProduits = "DELETE FROM produit WHERE category = :idc";
                $queryProduits = $db->prepare($deleteProduits);
                $queryProduits->bindValue(':idc', $idc);
                $queryProduits->execute();
            }
            
            // Ensuite, supprimer la catégorie
            $sql = "DELETE FROM categorie WHERE idc = :idc";
            $query = $db->prepare($sql);
            $query->bindValue(':idc', $idc);
            $query->execute();
            
            // Message de succès avec information sur les produits supprimés
            if ($nbProduits > 0) {
                $_SESSION['info_suppression'] = $nbProduits; // Pour afficher le nombre de produits supprimés
            }
            
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // Afficher une catégorie
    public function showCategorie($idc)
    {
        $sql = "SELECT * FROM categorie WHERE idc = :idc";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':idc', $idc);
            $query->execute();
            $categorie = $query->fetch();
            return $categorie;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'affichage de la catégorie : ' . $e->getMessage());
        }
    }

    // Mettre à jour une catégorie
    public function updateCategorie($categorie, $idc)
    {
        $sql = "UPDATE categorie SET
                    nomC = :nomC,
                    descriptionC = :descriptionC,
                    dateC = :dateC,
                    nom_Createur = :nom_Createur
                WHERE idc = :idc";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);

            $query->bindValue(':nomC', $categorie->getNomC());
            $query->bindValue(':descriptionC', $categorie->getDescriptionC());
            $query->bindValue(':dateC', $categorie->getDateC());
            $query->bindValue(':nom_Createur', $categorie->getNom_Createur());
            $query->bindValue(':idc', $idc);

            return $query->execute();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
    
    // Compter le nombre de produits dans une catégorie
    public function countProductsInCategory($idc)
    {
        $sql = "SELECT COUNT(*) as count FROM produit WHERE category = :idc";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':idc', $idc);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>
