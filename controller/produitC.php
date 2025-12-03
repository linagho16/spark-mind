<?php
require_once('config.php');

class ProduitC
{
    // Ajouter un produit
    public function addProduit($produit)
    {
        $sql = "INSERT INTO produit (title, description, category, `condition`, statut, photo)
                VALUES (:title, :description, :category, :condition, :statut, :photo)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'title' => $produit->getTitle(),
                'description' => $produit->getDescription(),
                'category' => $produit->getCategory(),
                'condition' => $produit->getCondition(),
                'statut' => $produit->getStatut(),
                'photo' => $produit->getPhoto()
            ]);
            return "Produit ajouté avec succès !";
        } catch (PDOException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
            return "Erreur lors de l'ajout du produit.";
        }
    }

    // Liste des produits
    public function listProduits()
    {
        $sql = "SELECT id, title, description, category, `condition`, statut, photo FROM produit";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Liste des produits avec informations de catégorie (JOIN)
    public function listProduitsWithCategories()
    {
        $sql = "SELECT 
                    p.id, 
                    p.title, 
                    p.description, 
                    p.category,
                    p.condition, 
                    p.statut, 
                    p.photo,
                    c.idc,
                    c.nomC,
                    c.descriptionC,
                    c.dateC,
                    c.nom_Createur
                FROM produit p
                INNER JOIN categorie c ON p.category = c.idc
                ORDER BY p.id DESC";
        
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Supprimer un produit
    public function deleteProduit($id)
    {
        $sql = "DELETE FROM produit WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Afficher un produit
    public function showProduit($id)
    {
        $sql = "SELECT * FROM produit WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            $produit = $query->fetch();
            return $produit;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'affichage du produit : ' . $e->getMessage());
        }
    }

    // Mettre à jour un produit
    public function updateProduit($produit, $id)
    {
        $sql = "UPDATE produit SET
                    title = :title,
                    description = :description,
                    category = :category,
                    `condition` = :condition,
                    statut = :statut,
                    photo = :photo
                WHERE id = :id";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);

            $query->bindValue(':title', $produit->getTitle());
            $query->bindValue(':description', $produit->getDescription());
            $query->bindValue(':category', $produit->getCategory());
            $query->bindValue(':condition', $produit->getCondition());
            $query->bindValue(':statut', $produit->getStatut());
            $query->bindValue(':photo', $produit->getPhoto());
            $query->bindValue(':id', $id);

            return $query->execute();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
}
?>
