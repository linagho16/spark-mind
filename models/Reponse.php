<?php
/**
 * Modèle Reponse - Gestion des réponses aux demandes
 * SparkMind - Plateforme de solidarité
 * VERSION CORRIGÉE - Problèmes de comptage et affichage résolus
 */

class Reponse {
    private $conn;
    private $table_name = "reponses";
    
    // Propriétés de la réponse
    public $id;
    public $demande_id;
    public $administrateur;
    public $message;
    public $date_reponse;
    
    /**
     * Constructeur - Initialisation de la connexion
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Créer une nouvelle réponse
     * @return bool
     */
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                     SET demande_id = :demande_id,
                         administrateur = :administrateur,
                         message = :message,
                         date_reponse = NOW()";
            
            $stmt = $this->conn->prepare($query);
            
            // Nettoyage des données
            $this->demande_id = htmlspecialchars(strip_tags($this->demande_id));
            $this->administrateur = htmlspecialchars(strip_tags($this->administrateur));
            $this->message = htmlspecialchars(strip_tags($this->message));
            
            // Binding des paramètres
            $stmt->bindParam(':demande_id', $this->demande_id);
            $stmt->bindParam(':administrateur', $this->administrateur);
            $stmt->bindParam(':message', $this->message);
            
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Erreur create Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lire toutes les réponses
     * @return PDOStatement|false
     */
    public function readAll() {
        try {
            $query = "SELECT r.*, d.nom as demandeur_nom, d.statut as demande_statut
                     FROM " . $this->table_name . " r
                     LEFT JOIN demandes d ON r.demande_id = d.id
                     ORDER BY r.date_reponse DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt;
            
        } catch (PDOException $e) {
            error_log("Erreur readAll Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lire une réponse spécifique
     * @return array|false
     */
    public function readOne() {
        try {
            $query = "SELECT r.*, d.nom as demandeur_nom, d.email as demandeur_email
                     FROM " . $this->table_name . " r
                     LEFT JOIN demandes d ON r.demande_id = d.id
                     WHERE r.id = :id
                     LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur readOne Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer toutes les réponses d'une demande spécifique
     * @param int $demande_id
     * @return array
     */
    public function getByDemande($demande_id) {
        try {
            $query = "SELECT r.* FROM " . $this->table_name . " r
                     WHERE r.demande_id = :demande_id
                     ORDER BY r.date_reponse DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':demande_id', $demande_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Log pour debug
            error_log("getByDemande - Demande ID: " . $demande_id . ", Résultats: " . count($results));
            
            return $results;
            
        } catch (PDOException $e) {
            error_log("Erreur getByDemande Reponse: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Mettre à jour une réponse
     * @return bool
     */
    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . "
                     SET administrateur = :administrateur,
                         message = :message
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Nettoyage des données
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->administrateur = htmlspecialchars(strip_tags($this->administrateur));
            $this->message = htmlspecialchars(strip_tags($this->message));
            
            // Binding des paramètres
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':administrateur', $this->administrateur);
            $stmt->bindParam(':message', $this->message);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Erreur update Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprimer une réponse
     * @return bool
     */
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Erreur delete Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprimer toutes les réponses d'une demande
     * @param int $demande_id
     * @return bool
     */
    public function deleteByDemande($demande_id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE demande_id = :demande_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':demande_id', $demande_id);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Erreur deleteByDemande Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Compter le nombre de réponses pour une demande
     * @param int $demande_id
     * @return int
     */
    public function countByDemande($demande_id) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                     WHERE demande_id = :demande_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':demande_id', $demande_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['total'];
            
        } catch (PDOException $e) {
            error_log("Erreur countByDemande Reponse: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtenir les statistiques des réponses - VERSION CORRIGÉE
     * @return array
     */
    public function getStatistics() {
        try {
            $stats = [];
            
            // Total des réponses
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['totalReponses'] = (int)$row['total'];
            
            // Demandes sans réponse - REQUÊTE CORRIGÉE
            $query = "SELECT COUNT(*) as total 
                     FROM demandes d
                     WHERE d.id NOT IN (SELECT DISTINCT demande_id FROM " . $this->table_name . ")";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['demandesSansReponse'] = (int)$row['total'];
            
            // Demandes avec au moins une réponse - REQUÊTE CORRIGÉE
            $query = "SELECT COUNT(DISTINCT demande_id) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['demandesAvecReponse'] = (int)$row['total'];
            
            // Réponses du jour
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                     WHERE DATE(date_reponse) = CURDATE()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['reponsesDuJour'] = (int)$row['total'];
            
            // Réponses de la semaine
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                     WHERE YEARWEEK(date_reponse, 1) = YEARWEEK(NOW(), 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['reponsesDeLaSemaine'] = (int)$row['total'];
            
            // Réponses du mois
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                     WHERE MONTH(date_reponse) = MONTH(NOW()) 
                     AND YEAR(date_reponse) = YEAR(NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['reponsesDuMois'] = (int)$row['total'];
            
            // Top 5 des administrateurs les plus actifs
            $query = "SELECT administrateur, COUNT(*) as nb_reponses
                     FROM " . $this->table_name . "
                     GROUP BY administrateur
                     ORDER BY nb_reponses DESC
                     LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['topAdmins'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Log pour debug
            error_log("Statistiques: " . json_encode($stats));
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Erreur getStatistics Reponse: " . $e->getMessage());
            return [
                'totalReponses' => 0,
                'demandesSansReponse' => 0,
                'demandesAvecReponse' => 0,
                'reponsesDuJour' => 0,
                'reponsesDeLaSemaine' => 0,
                'reponsesDuMois' => 0,
                'topAdmins' => []
            ];
        }
    }
    
    /**
     * Rechercher des réponses
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        try {
            $query = "SELECT r.*, d.nom as demandeur_nom
                     FROM " . $this->table_name . " r
                     LEFT JOIN demandes d ON r.demande_id = d.id
                     WHERE r.administrateur LIKE :keyword 
                     OR r.message LIKE :keyword
                     OR d.nom LIKE :keyword
                     ORDER BY r.date_reponse DESC";
            
            $stmt = $this->conn->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur search Reponse: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtenir les réponses récentes
     * @param int $limit
     * @return array
     */
    public function getRecent($limit = 10) {
        try {
            $query = "SELECT r.*, d.nom as demandeur_nom, d.gouvernorat
                     FROM " . $this->table_name . " r
                     LEFT JOIN demandes d ON r.demande_id = d.id
                     ORDER BY r.date_reponse DESC
                     LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur getRecent Reponse: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Vérifier si une demande a des réponses
     * @param int $demande_id
     * @return bool
     */
    public function hasReponses($demande_id) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                     WHERE demande_id = :demande_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':demande_id', $demande_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['total'] > 0;
            
        } catch (PDOException $e) {
            error_log("Erreur hasReponses Reponse: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtenir le nombre de réponses pour chaque demande - NOUVELLE MÉTHODE
     * @return array [demande_id => nb_reponses]
     */
    public function getCountByAllDemandes() {
        try {
            $query = "SELECT demande_id, COUNT(*) as nb_reponses 
                     FROM " . $this->table_name . "
                     GROUP BY demande_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['demande_id']] = (int)$row['nb_reponses'];
            }
            
            return $results;
            
        } catch (PDOException $e) {
            error_log("Erreur getCountByAllDemandes: " . $e->getMessage());
            return [];
        }
    }
}
?>