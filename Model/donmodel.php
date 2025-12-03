<?php
require_once __DIR__ . '/../view/config.php';
class DonModel {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // Don methods - matching your table structure
    public function getAllDons() {
        $stmt = $this->pdo->query("SELECT * FROM dons ORDER BY date_don DESC");
        return $stmt->fetchAll();
    }

    public function getDonById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM dons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createDon($data) {
    // Add default status if not provided
    $statut = $data['statut'] ?? 'en_attente';
    
    $stmt = $this->pdo->prepare("INSERT INTO dons (type_don, quantite, etat_object, photos, region, description, statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['type_don'],
        $data['quantite'],
        $data['etat_object'],
        $data['photos'],
        $data['region'],
        $data['description'],
        $statut
    ]);
}

    public function updateDon($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE dons SET type_don = ?, quantite = ?, etat_object = ?, photos = ?, region = ?, description = ? WHERE id = ?");
        return $stmt->execute([
            $data['type_don'],
            $data['quantite'],
            $data['etat_object'],
            $data['photos'],
            $data['region'],
            $data['description'],
            $id
        ]);
    }

    public function deleteDon($id) {
        $stmt = $this->pdo->prepare("DELETE FROM dons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Statistics methods for dashboard
    public function getDashboardStats() {
        $stats = [];

        // Total donations count
        $stmt = $this->pdo->query("SELECT COUNT(*) as total_dons FROM dons");
        $stats['total_dons'] = $stmt->fetch()['total_dons'];

        // Donations by type
        $stmt = $this->pdo->query("SELECT type_don, COUNT(*) as count FROM dons GROUP BY type_don");
        $stats['dons_by_type'] = $stmt->fetchAll();

        // Recent donations count (last 7 days)
        $stmt = $this->pdo->query("SELECT COUNT(*) as recent_dons FROM dons WHERE date_don >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stats['recent_dons'] = $stmt->fetch()['recent_dons'];

        // Donations by region
        $stmt = $this->pdo->query("SELECT region, COUNT(*) as count FROM dons GROUP BY region");
        $stats['dons_by_region'] = $stmt->fetchAll();

        return $stats;
    }

    // Get donations with filters
   // In your getDonsWithFilters method, update the FrontOffice logic:
public function getDonsWithFilters($filters = []) {
    $sql = "SELECT * FROM dons WHERE 1=1";
    $params = [];

    if (!empty($filters['type_don'])) {
        $sql .= " AND type_don = ?";
        $params[] = $filters['type_don'];
    }
    
    if (!empty($filters['region'])) {
        $sql .= " AND region = ?";
        $params[] = $filters['region'];
    }
    
    // IMPORTANT: For FrontOffice, show both 'actif' and 'en_attente' donations
    if (!empty($filters['statut'])) {
        if ($filters['statut'] === 'frontoffice') {
            // For frontoffice, show both active and pending donations
            $sql .= " AND (statut = 'actif' OR statut = 'en_attente')";
        } else {
            // For backoffice filtering by specific status
            $sql .= " AND statut = ?";
            $params[] = $filters['statut'];
        }
    }

    $sql .= " ORDER BY date_don DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
}
?>