<?php
require_once __DIR__ . '/../view/config.php';

class GroupeModel {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // CRUD Méthodes
    public function getAllGroupes() {
        $stmt = $this->pdo->query("SELECT * FROM groupes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getGroupeById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM groupes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createGroupe($data) {
        $stmt = $this->pdo->prepare("INSERT INTO groupes (nom, description, type, region, responsable, email, telephone, statut) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['description'], 
            $data['type'],
            $data['region'],
            $data['responsable'],
            $data['email'],
            $data['telephone'],
            'actif'
        ]);
    }

    public function updateGroupe($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE groupes SET nom = ?, description = ?, type = ?, region = ?, 
                                    responsable = ?, email = ?, telephone = ?, statut = ? WHERE id = ?");
        return $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['type'],
            $data['region'], 
            $data['responsable'],
            $data['email'],
            $data['telephone'],
            $data['statut'],
            $id
        ]);
    }

    public function deleteGroupe($id) {
        $stmt = $this->pdo->prepare("DELETE FROM groupes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Statistiques
    public function getGroupesStats() {
        $stats = [];

        // Total groupes
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM groupes");
        $stats['total_groupes'] = $stmt->fetch()['total'];

        // Groupes par type
        $stmt = $this->pdo->query("SELECT type, COUNT(*) as count FROM groupes GROUP BY type");
        $stats['groupes_by_type'] = $stmt->fetchAll();

        // Groupes par région
        $stmt = $this->pdo->query("SELECT region, COUNT(*) as count FROM groupes GROUP BY region");
        $stats['groupes_by_region'] = $stmt->fetchAll();

        return $stats;
    }

    // Filtres
   // In your getGroupesWithFilters method, update the FrontOffice logic:
public function getGroupesWithFilters($filters = []) {
    $sql = "SELECT * FROM groupes WHERE 1=1";
    $params = [];

    if (!empty($filters['type'])) {
        $sql .= " AND type = ?";
        $params[] = $filters['type'];
    }

    if (!empty($filters['region'])) {
        $sql .= " AND region = ?";
        $params[] = $filters['region'];
    }

    // IMPORTANT CHANGE: For FrontOffice, show 'actif' AND 'en_attente' groups
    if (!empty($filters['statut'])) {
        if ($filters['statut'] === 'frontoffice') {
            // For frontoffice, show both active and pending groups
            $sql .= " AND (statut = 'actif' OR statut = 'en_attente')";
        } else {
            // For backoffice filtering by specific status
            $sql .= " AND statut = ?";
            $params[] = $filters['statut'];
        }
    } else {
        // Default: for backoffice, show all statuses
    }

    $sql .= " ORDER BY created_at DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
}
?>