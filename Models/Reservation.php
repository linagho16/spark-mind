<?php

class Reservation {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    // Créer une réservation
    public function create($data) {
        // Générer une référence unique
        $reference = 'RES-' . date('Ymd-His') . '-' . rand(100, 999);
        
        $sql = "INSERT INTO reservations 
                (event_id, nom_client, email, telephone, nombre_places, 
                 montant_total, reference, methode_paiement, notes) 
                VALUES (:event_id, :nom_client, :email, :telephone, :nombre_places, 
                        :montant_total, :reference, :methode_paiement, :notes)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':event_id' => $data['event_id'],
            ':nom_client' => $data['nom_client'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':nombre_places' => $data['nombre_places'],
            ':montant_total' => $data['montant_total'],
            ':reference' => $reference,
            ':methode_paiement' => $data['methode_paiement'] ?? 'carte',
            ':notes' => $data['notes'] ?? ''
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }
    
    // Récupérer toutes les réservations avec infos événement
    public function getAll($limit = null, $offset = 0, $sortBy = 'date_desc') {
        $sql = "SELECT r.*, e.titre as event_titre, e.date_event, e.lieu 
                FROM reservations r 
                LEFT JOIN events e ON r.event_id = e.id ";
        
        // Gérer le tri
        switch ($sortBy) {
            case 'date_asc':
                $sql .= "ORDER BY r.date_reservation ASC";
                break;
            case 'event_date_asc':
                $sql .= "ORDER BY e.date_event ASC";
                break;
            case 'event_date_desc':
                $sql .= "ORDER BY e.date_event DESC";
                break;
            case 'client_asc':
                $sql .= "ORDER BY r.nom_client ASC";
                break;
            case 'montant_desc':
                $sql .= "ORDER BY r.montant_total DESC";
                break;
            case 'date_desc':
            default:
                $sql .= "ORDER BY r.date_reservation DESC";
                break;
        }
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        return $this->db->query($sql)->fetchAll();
    }
    
    // Compter le nombre total de réservations
    public function count($search = null) {
        $sql = "SELECT COUNT(*) as total FROM reservations r 
                LEFT JOIN events e ON r.event_id = e.id";
        
        if ($search) {
            $sql .= " WHERE r.nom_client LIKE :search 
                      OR r.email LIKE :search 
                      OR r.telephone LIKE :search 
                      OR r.reference LIKE :search 
                      OR e.titre LIKE :search";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':search' => '%' . $search . '%']);
            $result = $stmt->fetch();
        } else {
            $result = $this->db->query($sql)->fetch();
        }
        
        return $result['total'] ?? 0;
    }
    
    // Rechercher des réservations
    public function search($searchTerm, $limit = null, $offset = 0, $sortBy = 'date_desc') {
        $sql = "SELECT r.*, e.titre as event_titre, e.date_event, e.lieu 
                FROM reservations r 
                LEFT JOIN events e ON r.event_id = e.id 
                WHERE r.nom_client LIKE :search 
                   OR r.email LIKE :search 
                   OR r.telephone LIKE :search 
                   OR r.reference LIKE :search 
                   OR e.titre LIKE :search ";
        
        // Gérer le tri
        switch ($sortBy) {
            case 'date_asc':
                $sql .= "ORDER BY r.date_reservation ASC";
                break;
            case 'event_date_asc':
                $sql .= "ORDER BY e.date_event ASC";
                break;
            case 'event_date_desc':
                $sql .= "ORDER BY e.date_event DESC";
                break;
            case 'client_asc':
                $sql .= "ORDER BY r.nom_client ASC";
                break;
            case 'montant_desc':
                $sql .= "ORDER BY r.montant_total DESC";
                break;
            case 'date_desc':
            default:
                $sql .= "ORDER BY r.date_reservation DESC";
                break;
        }
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':search' => '%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }
    
    // Récupérer une réservation par ID
    public function getById($id) {
        $sql = "SELECT r.id, r.event_id, r.nom_client, r.email, r.telephone, 
                       r.nombre_places, r.montant_total, r.reference, r.statut, 
                       r.methode_paiement, r.notes, r.date_reservation,
                       e.titre as event_titre, e.description as event_description, 
                       e.lieu as event_lieu, e.prix as event_prix, e.date_event
                FROM reservations r 
                JOIN events e ON r.event_id = e.id 
                WHERE r.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    // Récupérer les réservations d'un événement
    public function getByEvent($event_id) {
        $sql = "SELECT * FROM reservations WHERE event_id = :event_id ORDER BY date_reservation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':event_id' => $event_id]);
        return $stmt->fetchAll();
    }
    
    // Mettre à jour une réservation
    public function update($id, $data) {
        $sql = "UPDATE reservations SET 
                event_id = :event_id,
                nom_client = :nom_client,
                email = :email,
                telephone = :telephone,
                nombre_places = :nombre_places,
                montant_total = :montant_total,
                statut = :statut,
                methode_paiement = :methode_paiement,
                notes = :notes
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':event_id' => $data['event_id'],
            ':nom_client' => $data['nom_client'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':nombre_places' => $data['nombre_places'],
            ':montant_total' => $data['montant_total'],
            ':statut' => $data['statut'],
            ':methode_paiement' => $data['methode_paiement'],
            ':notes' => $data['notes'],
            ':id' => $id
        ]);
    }
    
    // Mettre à jour le statut
    public function updateStatus($id, $status) {
        $sql = "UPDATE reservations SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':statut' => $status, ':id' => $id]);
    }
    
    // Supprimer une réservation
    public function delete($id) {
        $sql = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Statistiques
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'confirmée' THEN 1 ELSE 0 END) as confirmées,
                SUM(CASE WHEN statut = 'en attente' THEN 1 ELSE 0 END) as en_attente,
                SUM(CASE WHEN statut = 'annulée' THEN 1 ELSE 0 END) as annulées,
                COALESCE(SUM(CASE WHEN statut = 'confirmée' THEN montant_total ELSE 0 END), 0) as revenu_total
                FROM reservations";
        
        return $this->db->query($sql)->fetch();
    }
    
    // Vérifier places disponibles pour un événement
    public function getAvailablePlaces($event_id) {
        // Supposons que chaque événement a 100 places maximum
        $sql = "SELECT 
                100 - IFNULL(SUM(nombre_places), 0) as places_disponibles
                FROM reservations 
                WHERE event_id = :event_id AND statut = 'confirmée'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':event_id' => $event_id]);
        $result = $stmt->fetch();
        return $result['places_disponibles'] ?? 100;
    }
    // Récupérer une réservation par ID + email (sécurité côté public)
    public function getByIdAndEmail($id, $email) {
        $sql = "SELECT r.id, r.event_id, r.nom_client, r.email, r.telephone, 
                    r.nombre_places, r.montant_total, r.reference, r.statut, 
                    r.methode_paiement, r.notes, r.date_reservation,
                    e.titre as event_titre, e.description as event_description, 
                    e.lieu as event_lieu, e.prix as event_prix, e.date_event
                FROM reservations r 
                JOIN events e ON r.event_id = e.id 
                WHERE r.id = :id AND r.email = :email
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => (int)$id,
            ':email' => trim($email)
        ]);

        return $stmt->fetch();
    }

}
?>