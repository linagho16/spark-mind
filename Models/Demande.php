<?php
// ==========================================
// SPARKMIND - DEMANDE.PHP (MODEL)
// Classe entité pour les demandes d'aide
// ==========================================

class Demande {
    
    // Connexion à la base de données
    private $conn;
    private $table_name = "demandes";
    
    // Propriétés de la classe (correspondant aux colonnes de la table)
    public $id;
    public $nom;
    public $age;
    public $gouvernorat;
    public $ville;
    public $situation;
    public $categories_aide;
    public $urgence;
    public $description_situation;
    public $demande_exacte;
    public $telephone;
    public $email;
    public $preference_contact;
    public $horaires_disponibles;
    public $visibilite;
    public $anonyme;
    public $statut;
    public $date_soumission;
    public $date_modification;
    
    /**
     * Constructeur
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // ==========================================
    // CREATE - Créer une nouvelle demande
    // ==========================================
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET nom = :nom,
                    age = :age,
                    gouvernorat = :gouvernorat,
                    ville = :ville,
                    situation = :situation,
                    categories_aide = :categories_aide,
                    urgence = :urgence,
                    description_situation = :description_situation,
                    demande_exacte = :demande_exacte,
                    telephone = :telephone,
                    email = :email,
                    preference_contact = :preference_contact,
                    horaires_disponibles = :horaires_disponibles,
                    visibilite = :visibilite,
                    anonyme = :anonyme,
                    statut = :statut";
        
        $stmt = $this->conn->prepare($query);
        
        // Nettoyage des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->gouvernorat = htmlspecialchars(strip_tags($this->gouvernorat));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->situation = htmlspecialchars(strip_tags($this->situation));
        $this->urgence = htmlspecialchars(strip_tags($this->urgence));
        $this->description_situation = htmlspecialchars(strip_tags($this->description_situation));
        $this->demande_exacte = htmlspecialchars(strip_tags($this->demande_exacte));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->preference_contact = htmlspecialchars(strip_tags($this->preference_contact));
        $this->visibilite = htmlspecialchars(strip_tags($this->visibilite));
        
        // Bind des paramètres
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":gouvernorat", $this->gouvernorat);
        $stmt->bindParam(":ville", $this->ville);
        $stmt->bindParam(":situation", $this->situation);
        $stmt->bindParam(":categories_aide", $this->categories_aide);
        $stmt->bindParam(":urgence", $this->urgence);
        $stmt->bindParam(":description_situation", $this->description_situation);
        $stmt->bindParam(":demande_exacte", $this->demande_exacte);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":preference_contact", $this->preference_contact);
        $stmt->bindParam(":horaires_disponibles", $this->horaires_disponibles);
        $stmt->bindParam(":visibilite", $this->visibilite);
        $stmt->bindParam(":anonyme", $this->anonyme);
        $stmt->bindParam(":statut", $this->statut);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    // ==========================================
    // READ - Lire toutes les demandes
    // ==========================================
    
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  ORDER BY 
                    CASE urgence
                        WHEN 'tres-urgent' THEN 1
                        WHEN 'urgent' THEN 2
                        WHEN 'important' THEN 3
                        WHEN 'peut-attendre' THEN 4
                    END,
                    date_soumission DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // ==========================================
    // READ ONE - Lire une demande spécifique
    // ==========================================
    
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nom = $row['nom'];
            $this->age = $row['age'];
            $this->gouvernorat = $row['gouvernorat'];
            $this->ville = $row['ville'];
            $this->situation = $row['situation'];
            $this->categories_aide = $row['categories_aide'];
            $this->urgence = $row['urgence'];
            $this->description_situation = $row['description_situation'];
            $this->demande_exacte = $row['demande_exacte'];
            $this->telephone = $row['telephone'];
            $this->email = $row['email'];
            $this->preference_contact = $row['preference_contact'];
            $this->horaires_disponibles = $row['horaires_disponibles'];
            $this->visibilite = $row['visibilite'];
            $this->anonyme = $row['anonyme'];
            $this->statut = $row['statut'];
            $this->date_soumission = $row['date_soumission'];
            $this->date_modification = $row['date_modification'];
            
            return true;
        }
        
        return false;
    }
    
    // ==========================================
    // UPDATE - Mettre à jour une demande
    // ==========================================
    
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nom = :nom,
                    age = :age,
                    gouvernorat = :gouvernorat,
                    ville = :ville,
                    situation = :situation,
                    categories_aide = :categories_aide,
                    urgence = :urgence,
                    description_situation = :description_situation,
                    demande_exacte = :demande_exacte,
                    telephone = :telephone,
                    email = :email,
                    preference_contact = :preference_contact,
                    horaires_disponibles = :horaires_disponibles,
                    visibilite = :visibilite,
                    anonyme = :anonyme,
                    statut = :statut
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Nettoyage
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->gouvernorat = htmlspecialchars(strip_tags($this->gouvernorat));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->situation = htmlspecialchars(strip_tags($this->situation));
        $this->urgence = htmlspecialchars(strip_tags($this->urgence));
        $this->description_situation = htmlspecialchars(strip_tags($this->description_situation));
        $this->demande_exacte = htmlspecialchars(strip_tags($this->demande_exacte));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->preference_contact = htmlspecialchars(strip_tags($this->preference_contact));
        $this->visibilite = htmlspecialchars(strip_tags($this->visibilite));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":gouvernorat", $this->gouvernorat);
        $stmt->bindParam(":ville", $this->ville);
        $stmt->bindParam(":situation", $this->situation);
        $stmt->bindParam(":categories_aide", $this->categories_aide);
        $stmt->bindParam(":urgence", $this->urgence);
        $stmt->bindParam(":description_situation", $this->description_situation);
        $stmt->bindParam(":demande_exacte", $this->demande_exacte);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":preference_contact", $this->preference_contact);
        $stmt->bindParam(":horaires_disponibles", $this->horaires_disponibles);
        $stmt->bindParam(":visibilite", $this->visibilite);
        $stmt->bindParam(":anonyme", $this->anonyme);
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // ==========================================
    // UPDATE STATUS - Mettre à jour le statut uniquement
    // ==========================================
    
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . "
                SET statut = :statut
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->statut = htmlspecialchars(strip_tags($this->statut));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":statut", $this->statut);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // ==========================================
    // DELETE - Supprimer une demande
    // ==========================================
    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // ==========================================
    // SEARCH - Rechercher des demandes
    // ==========================================
    
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE nom LIKE :keywords
                    OR gouvernorat LIKE :keywords
                    OR ville LIKE :keywords
                    OR description_situation LIKE :keywords
                ORDER BY date_soumission DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(":keywords", $keywords);
        $stmt->execute();
        
        return $stmt;
    }
    
    // ==========================================
    // FILTER - Filtrer par critères
    // ==========================================
    
    public function filter($statut = null, $urgence = null, $gouvernorat = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        
        if($statut) {
            $query .= " AND statut = :statut";
        }
        
        if($urgence) {
            $query .= " AND urgence = :urgence";
        }
        
        if($gouvernorat) {
            $query .= " AND gouvernorat = :gouvernorat";
        }
        
        $query .= " ORDER BY date_soumission DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if($statut) {
            $statut = htmlspecialchars(strip_tags($statut));
            $stmt->bindParam(":statut", $statut);
        }
        
        if($urgence) {
            $urgence = htmlspecialchars(strip_tags($urgence));
            $stmt->bindParam(":urgence", $urgence);
        }
        
        if($gouvernorat) {
            $gouvernorat = htmlspecialchars(strip_tags($gouvernorat));
            $stmt->bindParam(":gouvernorat", $gouvernorat);
        }
        
        $stmt->execute();
        
        return $stmt;
    }
    
    // ==========================================
    // STATISTIQUES
    // ==========================================
    
    public function getStatistics() {
        $stats = [];
        
        // Total
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total'] = $row['total'];
        
        // Par statut
        $query = "SELECT statut, COUNT(*) as count FROM " . $this->table_name . " GROUP BY statut";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['statut'][$row['statut']] = $row['count'];
        }
        
        // Par urgence
        $query = "SELECT urgence, COUNT(*) as count FROM " . $this->table_name . " GROUP BY urgence";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['urgence'][$row['urgence']] = $row['count'];
        }
        
        return $stats;
    }
}
?>