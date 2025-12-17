<?php

namespace Services;

use Models\Reservation;
use PDO;
use DateTime;
use Exception;

/**
 * Service de gestion des tickets
 * 
 * Génère, émet et valide les tickets de réservation avec signature HMAC
 */
class TicketService
{
    private PDO $pdo;
    private const SECRET_KEY = 'SECRET_KEY_CHANGE_IN_PRODUCTION';
    private const HASH_ALGO = 'sha256';
    
    // Statuts des tickets
    public const STATUS_PENDING = 'pending';
    public const STATUS_ISSUED = 'issued';
    public const STATUS_USED = 'used';
    public const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Constructeur
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Génère un code de ticket sécurisé avec signature HMAC-SHA256
     * 
     * @param int $reservationId ID de la réservation
     * @return string Code du ticket au format: uuid.signature
     * @throws Exception Si la génération échoue
     */
    public function generateTicketCode(int $reservationId): string
    {
        try {
            // Génération d'un UUID v4
            $uuid = $this->generateUUID();
            
            // Données à signer: uuid + reservationId + timestamp
            $timestamp = time();
            $dataToSign = $uuid . '|' . $reservationId . '|' . $timestamp;
            
            // Calcul de la signature HMAC-SHA256
            $signature = hash_hmac(self::HASH_ALGO, $dataToSign, self::SECRET_KEY);
            
            // Format final: uuid.signature
            $ticketCode = $uuid . '.' . $signature;
            
            return $ticketCode;
            
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la génération du ticket: " . $e->getMessage());
        }
    }
    
    /**
     * Émet un ticket pour une réservation
     * 
     * @Transactional
     * @param int $reservationId ID de la réservation
     * @return array Données de la réservation mise à jour
     * @throws Exception Si la réservation n'existe pas ou si l'émission échoue
     */
    public function issueTicket(int $reservationId): array
    {
        try {
            // Début de transaction
            $this->pdo->beginTransaction();
            
            // 1. Charger la réservation
            $reservation = $this->getReservationById($reservationId);
            
            if (!$reservation) {
                throw new Exception("Réservation #$reservationId introuvable");
            }
            
            // Vérifier que la réservation est confirmée
            if ($reservation['statut'] !== 'confirmée') {
                throw new Exception("La réservation doit être confirmée pour émettre un ticket");
            }
            
            // Vérifier qu'un ticket n'a pas déjà été émis
            if (!empty($reservation['ticket_code']) && $reservation['ticket_status'] === self::STATUS_ISSUED) {
                throw new Exception("Un ticket a déjà été émis pour cette réservation");
            }
            
            // 2. Générer le code du ticket
            $ticketCode = $this->generateTicketCode($reservationId);
            
            // 3. Définir les données du ticket
            $issuedAt = new DateTime();
            $ticketStatus = self::STATUS_ISSUED;
            
            // 4. Sauvegarder en base de données
            $stmt = $this->pdo->prepare("
                UPDATE reservations 
                SET 
                    ticket_code = :ticket_code,
                    ticket_status = :ticket_status,
                    issued_at = :issued_at
                WHERE id = :id
            ");
            
            $stmt->execute([
                'ticket_code' => $ticketCode,
                'ticket_status' => $ticketStatus,
                'issued_at' => $issuedAt->format('Y-m-d H:i:s'),
                'id' => $reservationId
            ]);
            
            // Commit de la transaction
            $this->pdo->commit();
            
            // 5. Recharger et retourner la réservation mise à jour
            $updatedReservation = $this->getReservationById($reservationId);
            
            return $updatedReservation;
            
        } catch (Exception $e) {
            // Rollback en cas d'erreur
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Erreur lors de l'émission du ticket: " . $e->getMessage());
        }
    }
    
    /**
     * Valide un ticket et le marque comme utilisé
     * 
     * @Transactional
     * @param string $ticketCode Code du ticket à valider
     * @return TicketValidationResult Résultat de la validation
     * @throws Exception Si la validation échoue
     */
    public function validateTicket(string $ticketCode): TicketValidationResult
    {
        try {
            // Début de transaction
            $this->pdo->beginTransaction();
            
            // 1. Vérifier la signature HMAC
            if (!$this->verifyTicketSignature($ticketCode)) {
                $this->pdo->rollBack();
                return new TicketValidationResult(
                    false,
                    'INVALID',
                    'Signature du ticket invalide',
                    null
                );
            }
            
            // 2. Extraire l'UUID du ticket
            $parts = explode('.', $ticketCode);
            $uuid = $parts[0] ?? '';
            
            // 3. Trouver la réservation associée
            $reservation = $this->getReservationByTicketCode($ticketCode);
            
            if (!$reservation) {
                $this->pdo->rollBack();
                return new TicketValidationResult(
                    false,
                    'NOT_FOUND',
                    'Aucune réservation trouvée pour ce ticket',
                    null
                );
            }
            
            // 4. Vérifier le statut du ticket
            $ticketStatus = $reservation['ticket_status'];
            
            if ($ticketStatus === self::STATUS_USED) {
                $this->pdo->rollBack();
                return new TicketValidationResult(
                    false,
                    'ALREADY_USED',
                    'Ce ticket a déjà été utilisé le ' . $reservation['used_at'],
                    $reservation
                );
            }
            
            if ($ticketStatus === self::STATUS_CANCELLED || $reservation['statut'] === 'annulée') {
                $this->pdo->rollBack();
                return new TicketValidationResult(
                    false,
                    'CANCELLED',
                    'Cette réservation a été annulée',
                    $reservation
                );
            }
            
            if ($ticketStatus !== self::STATUS_ISSUED) {
                $this->pdo->rollBack();
                return new TicketValidationResult(
                    false,
                    'INVALID',
                    'Statut du ticket invalide: ' . $ticketStatus,
                    $reservation
                );
            }
            
            // 5. Marquer le ticket comme utilisé
            $usedAt = new DateTime();
            
            $stmt = $this->pdo->prepare("
                UPDATE reservations 
                SET 
                    ticket_status = :ticket_status,
                    used_at = :used_at
                WHERE id = :id
            ");
            
            $stmt->execute([
                'ticket_status' => self::STATUS_USED,
                'used_at' => $usedAt->format('Y-m-d H:i:s'),
                'id' => $reservation['id']
            ]);
            
            // Commit de la transaction
            $this->pdo->commit();
            
            // 6. Recharger la réservation mise à jour
            $updatedReservation = $this->getReservationById($reservation['id']);
            
            // 7. Retourner un résultat VALID
            return new TicketValidationResult(
                true,
                'VALID',
                'Ticket validé avec succès',
                $updatedReservation
            );
            
        } catch (Exception $e) {
            // Rollback en cas d'erreur
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Erreur lors de la validation du ticket: " . $e->getMessage());
        }
    }
    
    /**
     * Annule un ticket
     * 
     * @Transactional
     * @param int $reservationId ID de la réservation
     * @return bool Succès de l'annulation
     * @throws Exception Si l'annulation échoue
     */
    public function cancelTicket(int $reservationId): bool
    {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("
                UPDATE reservations 
                SET 
                    ticket_status = :ticket_status,
                    statut = 'annulée'
                WHERE id = :id
            ");
            
            $result = $stmt->execute([
                'ticket_status' => self::STATUS_CANCELLED,
                'id' => $reservationId
            ]);
            
            $this->pdo->commit();
            
            return $result;
            
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Erreur lors de l'annulation du ticket: " . $e->getMessage());
        }
    }
    
    /**
     * Vérifie la signature HMAC d'un ticket
     * 
     * @param string $ticketCode Code complet du ticket
     * @return bool True si la signature est valide
     */
    private function verifyTicketSignature(string $ticketCode): bool
    {
        // Extraire l'UUID et la signature
        $parts = explode('.', $ticketCode);
        
        if (count($parts) !== 2) {
            return false;
        }
        
        $uuid = $parts[0];
        $providedSignature = $parts[1];
        
        // Récupérer la réservation pour obtenir l'ID et le timestamp
        $stmt = $this->pdo->prepare("
            SELECT id, issued_at 
            FROM reservations 
            WHERE ticket_code = :ticket_code
        ");
        $stmt->execute(['ticket_code' => $ticketCode]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return false;
        }
        
        // Recalculer la signature avec les mêmes données
        $timestamp = strtotime($result['issued_at']);
        $dataToSign = $uuid . '|' . $result['id'] . '|' . $timestamp;
        $expectedSignature = hash_hmac(self::HASH_ALGO, $dataToSign, self::SECRET_KEY);
        
        // Comparaison sécurisée contre les attaques timing
        return hash_equals($expectedSignature, $providedSignature);
    }
    
    /**
     * Récupère une réservation par son ID
     */
    private function getReservationById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, e.titre as event_titre, e.date_event, e.lieu 
            FROM reservations r
            LEFT JOIN events e ON r.event_id = e.id
            WHERE r.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }
    
    /**
     * Récupère une réservation par son code de ticket
     */
    private function getReservationByTicketCode(string $ticketCode): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, e.titre as event_titre, e.date_event, e.lieu 
            FROM reservations r
            LEFT JOIN events e ON r.event_id = e.id
            WHERE r.ticket_code = :ticket_code
        ");
        $stmt->execute(['ticket_code' => $ticketCode]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }
    
    /**
     * Génère un UUID v4
     */
    private function generateUUID(): string
    {
        $data = random_bytes(16);
        
        // Version 4 (random)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Variant RFC 4122
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    /**
     * Obtient les statistiques des tickets
     */
    public function getTicketStats(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN ticket_status = 'issued' THEN 1 ELSE 0 END) as issued,
                SUM(CASE WHEN ticket_status = 'used' THEN 1 ELSE 0 END) as used,
                SUM(CASE WHEN ticket_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            FROM reservations
            WHERE ticket_code IS NOT NULL
        ");
        
        return $stmt->fetch();
    }
    
    /**
     * Récupère tous les tickets d'un événement
     */
    public function getTicketsByEvent(int $eventId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, e.titre as event_titre
            FROM reservations r
            LEFT JOIN events e ON r.event_id = e.id
            WHERE r.event_id = :event_id 
            AND r.ticket_code IS NOT NULL
            ORDER BY r.issued_at DESC
        ");
        $stmt->execute(['event_id' => $eventId]);
        
        return $stmt->fetchAll();
    }
}

/**
 * Classe représentant le résultat de la validation d'un ticket
 */
class TicketValidationResult
{
    public bool $isValid;
    public string $status;
    public string $message;
    public ?array $reservation;
    
    public function __construct(bool $isValid, string $status, string $message, ?array $reservation)
    {
        $this->isValid = $isValid;
        $this->status = $status;
        $this->message = $message;
        $this->reservation = $reservation;
    }
    
    /**
     * Convertit le résultat en tableau
     */
    public function toArray(): array
    {
        return [
            'isValid' => $this->isValid,
            'status' => $this->status,
            'message' => $this->message,
            'reservation' => $this->reservation
        ];
    }
    
    /**
     * Convertit en JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
