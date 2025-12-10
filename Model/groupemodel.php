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
public function getAllGroupesWithDonStats() {
        $sql = "SELECT g.*, 
                       COUNT(d.id) as total_dons,
                       SUM(CASE WHEN d.statut = 'livre' THEN 1 ELSE 0 END) as dons_livres,
                       SUM(CASE WHEN d.statut = 'en_attente' THEN 1 ELSE 0 END) as dons_en_attente
                FROM groupes g 
                LEFT JOIN dons d ON g.id = d.groupe_id 
                GROUP BY g.id 
                ORDER BY g.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
     public function getGroupeWithDons($groupeId) {
        $sql = "SELECT g.*, 
                       d.id as don_id, 
                       d.type_don, 
                       d.quantite, 
                       d.etat_object,
                       d.photos,
                       d.region as don_region, 
                       d.description as don_description,
                       d.date_don, 
                       d.statut as don_statut
                FROM groupes g 
                LEFT JOIN dons d ON g.id = d.groupe_id 
                WHERE g.id = ? 
                ORDER BY d.date_don DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$groupeId]);
        return $stmt->fetchAll();
    }
    public function getActiveGroupesByRegion($region) {
        $sql = "SELECT * FROM groupes 
                WHERE statut = 'actif' 
                AND (region = ? OR region = 'National')
                ORDER BY nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$region]);
        return $stmt->fetchAll();
    }
     public function getGroupesByDonType($donType) {
        // Map donation types to group types if needed
        $typeMapping = [
            'vetements' => 'humanitaire',
            'nourriture' => 'humanitaire',
            'medicaments' => 'medical',
            'argent' => 'tous'
        ];
        
        $groupType = $typeMapping[$donType] ?? 'tous';
        
        $sql = "SELECT * FROM groupes 
                WHERE statut = 'actif' 
                AND (type = ? OR type = 'tous' OR ? = 'tous')
                ORDER BY nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$groupType, $groupType]);
        return $stmt->fetchAll();
    }
     public function getEnhancedGroupesStats() {
        $stats = $this->getGroupesStats(); // Keep existing stats
        
        // Add jointure-based stats
        $stmt = $this->pdo->query("
            SELECT g.type, 
                   COUNT(DISTINCT g.id) as groupe_count,
                   COUNT(d.id) as total_dons,
                   AVG(CASE WHEN d.id IS NOT NULL THEN 1 ELSE 0 END) * 100 as avg_dons_per_groupe
            FROM groupes g 
            LEFT JOIN dons d ON g.id = d.groupe_id 
            GROUP BY g.type
        ");
        $stats['type_with_dons'] = $stmt->fetchAll();
         $stmt = $this->pdo->query("
            SELECT g.region, 
                   COUNT(DISTINCT g.id) as groupe_count,
                   COUNT(d.id) as dons_count
            FROM groupes g 
            LEFT JOIN dons d ON g.id = d.groupe_id 
            GROUP BY g.region
            ORDER BY dons_count DESC
        ");
        $stats['region_performance'] = $stmt->fetchAll();
        
        return $stats;
    }
     public function getGroupesWithLatestDons($limit = 5) {
        $sql = "SELECT g.id, g.nom, g.type, g.region,
                       d.id as dernier_don_id,
                       d.type_don as dernier_don_type,
                       d.quantite as dernier_don_quantite,
                       d.date_don as dernier_don_date
                FROM groupes g 
                LEFT JOIN dons d ON g.id = d.groupe_id 
                WHERE d.id = (
                    SELECT MAX(id) 
                    FROM dons 
                    WHERE groupe_id = g.id
                ) OR d.id IS NULL
                ORDER BY g.nom
                LIMIT ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
     public function updateGroupeDonCount($groupeId) {
        $sql = "UPDATE groupes g 
                SET membres_count = (
                    SELECT COUNT(*) 
                    FROM dons 
                    WHERE groupe_id = g.id
                ) 
                WHERE g.id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$groupeId]);
    }   

}
?>