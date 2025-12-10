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
    try {
        $sql = "INSERT INTO dons (type_don, quantite, etat_object, photos, region, description, statut, date_don, payment_intent_id, contact_name, contact_email, groupe_id) 
                VALUES (:type_don, :quantite, :etat_object, :photos, :region, :description, :statut, NOW(), :payment_intent_id, :contact_name, :contact_email, :groupe_id)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // DEBUG
        error_log("=== DonModel::createDon ===");
        error_log("Données: " . print_r($data, true));
        
        $result = $stmt->execute([
            ':type_don' => $data['type_don'] ?? 'Argent',
            ':quantite' => $data['quantite'] ?? 1,
            ':etat_object' => $data['etat_object'] ?? '',
            ':photos' => $data['photos'] ?? '',
            ':region' => $data['region'] ?? '',
            ':description' => $data['description'] ?? 'Don financier',
            ':statut' => $data['statut'] ?? 'payé',
            ':payment_intent_id' => $data['payment_intent_id'] ?? '',
            ':contact_name' => $data['contact_name'] ?? '',
            ':contact_email' => $data['contact_email'] ?? '',
            ':groupe_id' => isset($data['groupe_id']) ? $data['groupe_id'] : null
        ]);
        
        error_log("Résultat: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
        return $result;
        
    } catch (PDOException $e) {
        error_log("Erreur création don: " . $e->getMessage());
        return false;
    }
}

public function createDonAndReturnId($data) {
    try {
        $sql = "INSERT INTO dons (type_don, quantite, etat_object, photos, region, description, statut, date_don, payment_intent_id, contact_name, contact_email, groupe_id) 
                VALUES (:type_don, :quantite, :etat_object, :photos, :region, :description, :statut, NOW(), :payment_intent_id, :contact_name, :contact_email, :groupe_id)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':type_don' => $data['type_don'] ?? 'Argent',
            ':quantite' => $data['quantite'] ?? 1,
            ':etat_object' => $data['etat_object'] ?? '',
            ':photos' => $data['photos'] ?? '',
            ':region' => $data['region'] ?? '',
            ':description' => $data['description'] ?? 'Don financier',
            ':statut' => $data['statut'] ?? 'payé',
            ':payment_intent_id' => $data['payment_intent_id'] ?? '',
            ':contact_name' => $data['contact_name'] ?? '',
            ':contact_email' => $data['contact_email'] ?? '',
            ':groupe_id' => $data['groupe_id'] ?? null
        ]);
        
        return $this->pdo->lastInsertId();
        
    } catch (PDOException $e) {
        error_log("Erreur création don: " . $e->getMessage());
        return false;
    }
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
public function getAllDonsWithGroupes() {
        $sql = "SELECT d.*, 
                       g.nom as groupe_nom, 
                       g.type as groupe_type,
                       g.region as groupe_region,
                       g.responsable,
                       g.email,
                       g.telephone,
                       g.statut as groupe_statut
                FROM dons d 
                LEFT JOIN groupes g ON d.groupe_id = g.id 
                ORDER BY d.date_don DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
 public function getDonWithGroupe($id) {
        $sql = "SELECT d.*, 
                       g.id as groupe_id,
                       g.nom as groupe_nom, 
                       g.description as groupe_description,
                       g.type as groupe_type,
                       g.region as groupe_region,
                       g.responsable,
                       g.email,
                       g.telephone,
                       g.statut as groupe_statut,
                       g.membres_count,
                       g.created_at as groupe_created_at
                FROM dons d 
                LEFT JOIN groupes g ON d.groupe_id = g.id 
                WHERE d.id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
 public function getDonsByGroupeId($groupeId) {
        $sql = "SELECT d.* FROM dons d 
                WHERE d.groupe_id = ? 
                ORDER BY d.date_don DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$groupeId]);
        return $stmt->fetchAll();
    }
     public function assignToGroupe($donId, $groupeId) {
        $sql = "UPDATE dons SET groupe_id = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$groupeId, $donId]);
    }
    public function removeFromGroupe($donId) {
        $sql = "UPDATE dons SET groupe_id = NULL WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$donId]);
    }
     public function getAvailableGroupesForDon($donId) {
        // First get the donation's region
        $don = $this->getDonById($donId);
        if (!$don) {
            return [];
        }

        $sql = "SELECT * FROM groupes 
                WHERE (region = ? OR region = 'National') 
                AND statut = 'actif'
                ORDER BY nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$don['region']]);
        return $stmt->fetchAll();
    }
    
 public function getEnhancedDashboardStats() {
        $stats = $this->getDashboardStats(); // Keep existing stats
        
        // Add jointure-based stats
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as dons_with_group 
            FROM dons WHERE groupe_id IS NOT NULL
        ");
        $stats['dons_with_group'] = $stmt->fetch()['dons_with_group'];
        
        $stmt = $this->pdo->query("
            SELECT g.nom, COUNT(d.id) as donation_count
            FROM groupes g 
            LEFT JOIN dons d ON g.id = d.groupe_id 
            GROUP BY g.id 
            ORDER BY donation_count DESC 
            LIMIT 5
        ");
        $stats['top_groupes'] = $stmt->fetchAll();
        
        return $stats;
    }
    // In donmodel.php - Add these methods to the DonModel class

public function getMonthlyStats($months = 12) {
    // Convert months to integer
    $months = (int)$months;
    
    $sql = "SELECT 
                DATE_FORMAT(date_don, '%Y-%m') as month,
                COUNT(*) as count,
                MONTHNAME(date_don) as month_name,
                YEAR(date_don) as year
            FROM dons 
            WHERE date_don >= DATE_SUB(NOW(), INTERVAL :months MONTH)
            GROUP BY DATE_FORMAT(date_don, '%Y-%m'), MONTHNAME(date_don), YEAR(date_don)
            ORDER BY month DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':months', $months, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getDonationsByStatus() {
    $sql = "SELECT 
                statut,
                COUNT(*) as count
            FROM dons 
            GROUP BY statut
            ORDER BY count DESC";
    
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll();
}


public function getTopRegions($limit = 5) {
    // Convert limit to integer
    $limit = (int)$limit;
    
    $sql = "SELECT 
                region,
                COUNT(*) as count
            FROM dons 
            GROUP BY region 
            ORDER BY count DESC 
            LIMIT :limit";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
public function getTopDonationTypes($limit = 5) {
    // Convert limit to integer
    $limit = (int)$limit;
    
    $sql = "SELECT 
                type_don,
                COUNT(*) as count
            FROM dons 
            GROUP BY type_don 
            ORDER BY count DESC 
            LIMIT :limit";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getDonationsGrowth() {
    $sql = "SELECT 
                DATE_FORMAT(date_don, '%Y-%m') as period,
                COUNT(*) as count,
                LAG(COUNT(*), 1) OVER (ORDER BY DATE_FORMAT(date_don, '%Y-%m')) as previous_count
            FROM dons 
            WHERE date_don >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(date_don, '%Y-%m')
            ORDER BY period DESC";
    
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll();
}


public function getDetailedStatistics() {
    $stats = $this->getDashboardStats(); // Get existing stats
    
    // Add enhanced statistics
    $stats['monthly_stats'] = $this->getMonthlyStats();
    $stats['by_status'] = $this->getDonationsByStatus();
    $stats['top_regions'] = $this->getTopRegions();
    $stats['top_types'] = $this->getTopDonationTypes();
    $stats['growth_stats'] = $this->getDonationsGrowth();
    
    // Calculate additional metrics
    $stats['total_dons'] = $stats['total_dons'] ?? 0;
    $stats['recent_dons'] = $stats['recent_dons'] ?? 0;
    
    $stats['avg_daily'] = $stats['total_dons'] > 0 
        ? round($stats['total_dons'] / 30, 2) 
        : 0;
    
    $stats['completion_rate'] = $stats['total_dons'] > 0
        ? round(($stats['recent_dons'] / $stats['total_dons']) * 100, 2)
        : 0;
    
    return $stats;
}
    // Ajoutez à la fin de votre classe DonModel existante

    // Mettre à jour le statut d'un don
    public function updateDonStatut($id, $statut, $paymentIntentId = '') {
        try {
            $sql = "UPDATE dons SET statut = :statut";
            if (!empty($paymentIntentId)) {
                $sql .= ", payment_intent_id = :payment_intent_id";
            }
            $sql .= " WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':statut' => $statut,
                ':id' => $id
            ];
            
            if (!empty($paymentIntentId)) {
                $params[':payment_intent_id'] = $paymentIntentId;
            }
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("Erreur mise à jour statut: " . $e->getMessage());
            return false;
        }
    }

    // Obtenir le dernier ID inséré
    public function getLastInsertId() {
        $stmt = $this->pdo->query("SELECT LAST_INSERT_ID() as id");
        $result = $stmt->fetch();
        return $result['id'] ?? null;
    }

    // Obtenir un don avec son paiement
    public function getDonWithPaiement($id) {
        $sql = "SELECT d.*, p.montant, p.statut as statut_paiement, p.stripe_session_id
                FROM dons d 
                LEFT JOIN paiements p ON d.paiement_id = p.id 
                WHERE d.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
     // Dans la méthode getDonsWithFiltersAndGroupes() :
public function getDonsWithFiltersAndGroupes($filters = []) {
    $sql = "SELECT d.*, g.nom as groupe_nom, g.type as groupe_type
            FROM dons d 
            LEFT JOIN groupes g ON d.groupe_id = g.id 
            WHERE 1=1";
    
    $params = [];

    if (!empty($filters['type_don'])) {
        $sql .= " AND d.type_don = ?";
        $params[] = $filters['type_don'];
    }
    
    if (!empty($filters['region'])) {
        $sql .= " AND d.region = ?";
        $params[] = $filters['region'];
    }
    
    if (!empty($filters['groupe_id'])) {
        $sql .= " AND d.groupe_id = ?";
        $params[] = $filters['groupe_id'];
    }
    
    // CORRECTION IMPORTANTE ICI :
    if (!empty($filters['statut'])) {
        if ($filters['statut'] === 'frontoffice') {
            // Pour frontoffice, on affiche 'actif', 'en_attente' et 'payé'
            $sql .= " AND (d.statut = 'actif' OR d.statut = 'en_attente' OR d.statut = 'payé')";
        } else {
            $sql .= " AND d.statut = ?";
            $params[] = $filters['statut'];
        }
    } else {
        // Par défaut, ne pas afficher les dons annulés ou supprimés
        $sql .= " AND d.statut != 'annulé' AND d.statut != 'supprimé'";
    }

    $sql .= " ORDER BY d.date_don DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
}        


?>