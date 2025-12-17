<?php
/**
 * API REST pour les opérations de tickets
 * 
 * Endpoints:
 * - POST /api/ticket_operations.php?action=issue&reservation_id=X
 * - POST /api/ticket_operations.php?action=validate&ticket_code=XXX
 * - POST /api/ticket_operations.php?action=cancel&reservation_id=X
 * - GET  /api/ticket_operations.php?action=stats
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/TicketService.php';
require_once __DIR__ . '/../utils/QrGenerator.php';

use Services\TicketService;
use Utils\QrGenerator;

try {
    // Connexion PDO
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    $ticketService = new TicketService($pdo);
    $qrGenerator = new QrGenerator();
    
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        
        // Émettre un ticket
        case 'issue':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Méthode non autorisée']);
                exit;
            }
            
            $reservationId = $_POST['reservation_id'] ?? $_GET['reservation_id'] ?? null;
            
            if (!$reservationId) {
                http_response_code(400);
                echo json_encode(['error' => 'reservation_id requis']);
                exit;
            }
            
            $reservation = $ticketService->issueTicket((int)$reservationId);
            
            // Générer le QR code
            $qrCode = $qrGenerator->generateTicketQr($reservation['ticket_code'], [
                'reservation_id' => $reservation['id'],
                'event_titre' => $reservation['event_titre'],
                'nom_client' => $reservation['nom_client'],
                'nombre_places' => $reservation['nombre_places']
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Ticket émis avec succès',
                'data' => [
                    'reservation_id' => $reservation['id'],
                    'ticket_code' => $reservation['ticket_code'],
                    'ticket_status' => $reservation['ticket_status'],
                    'issued_at' => $reservation['issued_at'],
                    'qr_code' => $qrCode
                ]
            ]);
            break;
        
        // Valider un ticket
        case 'validate':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Méthode non autorisée']);
                exit;
            }
            
            $ticketCode = $_POST['ticket_code'] ?? $_GET['ticket_code'] ?? null;
            
            if (!$ticketCode) {
                http_response_code(400);
                echo json_encode(['error' => 'ticket_code requis']);
                exit;
            }
            
            $result = $ticketService->validateTicket($ticketCode);
            
            if ($result->isValid) {
                echo json_encode([
                    'success' => true,
                    'status' => $result->status,
                    'message' => $result->message,
                    'data' => [
                        'reservation' => [
                            'id' => $result->reservation['id'],
                            'reference' => $result->reservation['reference'],
                            'nom_client' => $result->reservation['nom_client'],
                            'event_titre' => $result->reservation['event_titre'],
                            'nombre_places' => $result->reservation['nombre_places'],
                            'used_at' => $result->reservation['used_at']
                        ]
                    ]
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'status' => $result->status,
                    'message' => $result->message
                ]);
            }
            break;
        
        // Annuler un ticket
        case 'cancel':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Méthode non autorisée']);
                exit;
            }
            
            $reservationId = $_POST['reservation_id'] ?? $_GET['reservation_id'] ?? null;
            
            if (!$reservationId) {
                http_response_code(400);
                echo json_encode(['error' => 'reservation_id requis']);
                exit;
            }
            
            $success = $ticketService->cancelTicket((int)$reservationId);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Ticket annulé avec succès'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation du ticket'
                ]);
            }
            break;
        
        // Statistiques des tickets
        case 'stats':
            $stats = $ticketService->getTicketStats();
            
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            break;
        
        // Tickets d'un événement
        case 'event_tickets':
            $eventId = $_GET['event_id'] ?? null;
            
            if (!$eventId) {
                http_response_code(400);
                echo json_encode(['error' => 'event_id requis']);
                exit;
            }
            
            $tickets = $ticketService->getTicketsByEvent((int)$eventId);
            
            echo json_encode([
                'success' => true,
                'count' => count($tickets),
                'data' => $tickets
            ]);
            break;
        
        default:
            http_response_code(400);
            echo json_encode([
                'error' => 'Action invalide',
                'available_actions' => ['issue', 'validate', 'cancel', 'stats', 'event_tickets']
            ]);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
