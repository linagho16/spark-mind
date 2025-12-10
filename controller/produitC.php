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

    // Filtrer et trier les produits (avec pagination)
    public function filtrerProduits($recherche = null, $categorie = null, $etat = null, $condition = null, $tri = null, $page = null, $perPage = null)
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
                WHERE 1=1";
        
        $params = [];

        if (!empty($recherche)) {
            $sql .= " AND p.title LIKE :recherche";
            $params['recherche'] = "%$recherche%";
        }

        if (!empty($categorie)) {
            $sql .= " AND p.category = :categorie";
            $params['categorie'] = $categorie;
        }

        if (!empty($etat)) {
            $sql .= " AND p.statut = :statut";
            $params['statut'] = $etat;
        }

        if (!empty($condition)) {
            $sql .= " AND p.condition = :condition";
            $params['condition'] = $condition;
        }

        if (!empty($tri)) {
            switch ($tri) {
                case 'titre_asc':
                    $sql .= " ORDER BY p.title ASC";
                    break;
                case 'titre_desc':
                    $sql .= " ORDER BY p.title DESC";
                    break;
                case 'recents':
                    $sql .= " ORDER BY p.id DESC";
                    break;
                case 'anciens':
                    $sql .= " ORDER BY p.id ASC";
                    break;
                default:
                    $sql .= " ORDER BY p.id DESC";
            }
        } else {
            $sql .= " ORDER BY p.id DESC";
        }

        // Pagination
        if ($page !== null && $perPage !== null) {
            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT :offset, :limit";
            // Bind value directly later because execute array treats everything as string often, 
            // but LIMIT needs integers in emulated prepares or depending on driver.
            // PDO wrapper in execute generally handles strings but for LIMIT it's safer to bind as INT.
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

    // Compter les produits filtrés (pour la pagination)
    public function countFiltrerProduits($recherche = null, $categorie = null, $etat = null, $condition = null)
    {
        $sql = "SELECT COUNT(*) as total
                FROM produit p
                INNER JOIN categorie c ON p.category = c.idc
                WHERE 1=1";
        
        $params = [];

        if (!empty($recherche)) {
            $sql .= " AND p.title LIKE :recherche";
            $params['recherche'] = "%$recherche%";
        }

        if (!empty($categorie)) {
            $sql .= " AND p.category = :categorie";
            $params['categorie'] = $categorie;
        }

        if (!empty($etat)) {
            $sql .= " AND p.statut = :statut";
            $params['statut'] = $etat;
        }

        if (!empty($condition)) {
            $sql .= " AND p.condition = :condition";
            $params['condition'] = $condition;
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
