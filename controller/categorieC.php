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

    // Filtrer et trier les catégories (avec pagination)
    public function filtrerCategories($recherche = null, $createur = null, $tri = null, $page = null, $perPage = null)
    {
        $sql = "SELECT * FROM categorie WHERE 1=1";
        $params = [];

        if (!empty($recherche)) {
            $sql .= " AND (nomC LIKE :recherche OR descriptionC LIKE :recherche)";
            $params['recherche'] = "%$recherche%";
        }

        if (!empty($createur)) {
            $sql .= " AND nom_Createur LIKE :createur";
            $params['createur'] = "%$createur%";
        }

        if (!empty($tri)) {
            switch ($tri) {
                case 'nom_asc':
                    $sql .= " ORDER BY nomC ASC";
                    break;
                case 'nom_desc':
                    $sql .= " ORDER BY nomC DESC";
                    break;
                case 'date_asc':
                    $sql .= " ORDER BY dateC ASC";
                    break;
                case 'date_desc':
                    $sql .= " ORDER BY dateC DESC";
                    break;
                default:
                    $sql .= " ORDER BY idc DESC";
            }
        } else {
            $sql .= " ORDER BY idc DESC";
        }

        // Pagination
        if ($page !== null && $perPage !== null) {
            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT :offset, :limit";
        }

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            
            // Bind existing params
            foreach ($params as $key => $value) {
                $query->bindValue(':' . $key, $value);
            }

            if ($page !== null && $perPage !== null) {
                $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
                $query->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            }

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Compter les catégories filtrées (pour la pagination)
    public function countFiltrerCategories($recherche = null, $createur = null)
    {
        $sql = "SELECT COUNT(*) as total FROM categorie WHERE 1=1";
        $params = [];

        if (!empty($recherche)) {
            $sql .= " AND (nomC LIKE :recherche OR descriptionC LIKE :recherche)";
            $params['recherche'] = "%$recherche%";
        }

        if (!empty($createur)) {
            $sql .= " AND nom_Createur LIKE :createur";
            $params['createur'] = "%$createur%";
        }

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}

?>
