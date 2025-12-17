<?php

class EventModel {
    private $conn;
    private $table_name = "events";

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Lire tous les événements
    public function getAllEvents($limit = null, $offset = 0, $sortBy = 'date_desc') {
        $query = "SELECT * FROM " . $this->table_name . " ";
        
        // Gérer le tri
        switch ($sortBy) {
            case 'date_asc':
                $query .= "ORDER BY date_event ASC";
                break;
            case 'titre_asc':
                $query .= "ORDER BY titre ASC";
                break;
            case 'titre_desc':
                $query .= "ORDER BY titre DESC";
                break;
            case 'prix_asc':
                $query .= "ORDER BY prix ASC";
                break;
            case 'prix_desc':
                $query .= "ORDER BY prix DESC";
                break;
            case 'date_desc':
            default:
                $query .= "ORDER BY date_event DESC";
                break;
        }
        
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Compter le nombre total d'événements
    public function countAllEvents($search = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        if ($search) {
            $query .= " WHERE titre LIKE :search 
                        OR description LIKE :search 
                        OR lieu LIKE :search";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':search' => '%' . $search . '%']);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $result['total'] ?? 0;
    }
    
    // Rechercher des événements
    public function search($searchTerm, $limit = null, $offset = 0, $sortBy = 'date_desc') {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE titre LIKE :search 
                     OR description LIKE :search 
                     OR lieu LIKE :search ";
        
        // Gérer le tri
        switch ($sortBy) {
            case 'date_asc':
                $query .= "ORDER BY date_event ASC";
                break;
            case 'titre_asc':
                $query .= "ORDER BY titre ASC";
                break;
            case 'titre_desc':
                $query .= "ORDER BY titre DESC";
                break;
            case 'prix_asc':
                $query .= "ORDER BY prix ASC";
                break;
            case 'prix_desc':
                $query .= "ORDER BY prix DESC";
                break;
            case 'date_desc':
            default:
                $query .= "ORDER BY date_event DESC";
                break;
        }
        
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':search' => '%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    // Lire un événement par ID - CORRIGÉ pour retourner un tableau associatif
    public function getEventById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Ajout de FETCH_ASSOC
    }

    // Créer un événement
    public function createEvent($data) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (titre, description, lieu, prix, date_event) 
                      VALUES (:titre, :description, :lieu, :prix, :date_event)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":titre", $data['titre']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":lieu", $data['lieu']);
            $stmt->bindParam(":prix", $data['prix']);
            $stmt->bindParam(":date_event", $data['date_event']);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur création événement: " . $e->getMessage());
            return false;
        }
    }

    // Mettre à jour un événement
    public function updateEvent($id, $titre, $description, $lieu, $prix, $date_event) {
        $query = "UPDATE " . $this->table_name . " 
                  SET titre = :titre, 
                      description = :description, 
                      lieu = :lieu, 
                      prix = :prix, 
                      date_event = :date_event 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":titre", $titre);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":lieu", $lieu);
        $stmt->bindParam(":prix", $prix);
        $stmt->bindParam(":date_event", $date_event);
        
        return $stmt->execute();
    }

    // Supprimer un événement
    public function deleteEvent($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // AJOUTER CETTE MÉTHODE POUR LE DASHBOARD
    public function countEvents() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    // AJOUTER CETTE MÉTHODE POUR LES ÉVÉNEMENTS À VENIR
    public function getUpcomingEvents($limit = 5) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE date_event >= CURDATE() 
                  ORDER BY date_event ASC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>