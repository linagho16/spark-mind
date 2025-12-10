<?php
/**
 * Page d'affichage du ticket avec QR code
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Reservation.php';
require_once __DIR__ . '/utils/QrGenerator.php';

use Utils\QrGenerator;

// Connexion PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$reservationId = $_GET['id'] ?? null;

if (!$reservationId) {
    die("ID de réservation manquant");
}

// Récupérer la réservation
$stmt = $pdo->prepare("
    SELECT r.*, e.titre as event_titre, e.date_event, e.lieu, e.duree
    FROM reservations r
    LEFT JOIN events e ON r.event_id = e.id
    WHERE r.id = :id
");
$stmt->execute(['id' => $reservationId]);
$reservation = $stmt->fetch();

if (!$reservation) {
    die("Réservation introuvable");
}

// Générer le QR code
$qrGenerator = new QrGenerator();
$qrCode = '';

if (!empty($reservation['ticket_code'])) {
    $qrCode = $qrGenerator->generateTicketQr($reservation['ticket_code'], [
        'reservation_id' => $reservation['id'],
        'event_titre' => $reservation['event_titre'],
        'nom_client' => $reservation['nom_client'],
        'nombre_places' => $reservation['nombre_places']
    ]);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - <?= htmlspecialchars($reservation['reference']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .ticket-container {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #8B7355 0%, #C19A6B 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: white;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }
        
        .ticket-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .ticket-header .reference {
            font-size: 16px;
            opacity: 0.9;
            letter-spacing: 2px;
        }
        
        .ticket-body {
            padding: 40px 30px;
        }
        
        .ticket-main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .ticket-info {
            border-left: 3px solid #8B7355;
            padding-left: 20px;
        }
        
        .ticket-qr {
            text-align: center;
            padding: 20px;
            background: #F5F1ED;
            border-radius: 10px;
        }
        
        .ticket-qr img {
            max-width: 250px;
            width: 100%;
            height: auto;
            border: 3px solid #8B7355;
            border-radius: 10px;
        }
        
        .info-group {
            margin-bottom: 20px;
        }
        
        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }
        
        .event-details {
            background: #F5F1ED;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .event-details h2 {
            color: #8B7355;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-issued {
            background: #d4edda;
            color: #155724;
        }
        
        .status-used {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .ticket-footer {
            background: #F5F1ED;
            padding: 20px 30px;
            text-align: center;
            border-top: 2px dashed #D4C5B9;
        }
        
        .ticket-footer p {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #8B7355;
            color: white;
        }
        
        .btn-primary:hover {
            background: #6F5A42;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: white;
            color: #8B7355;
            border: 2px solid #8B7355;
        }
        
        .btn-secondary:hover {
            background: #8B7355;
            color: white;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .actions {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .ticket-main {
                grid-template-columns: 1fr;
            }
            
            .ticket-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- En-tête -->
        <div class="ticket-header">
            <h1>🎭 TICKET ÉVÉNEMENT</h1>
            <p class="reference">Référence: <?= htmlspecialchars($reservation['reference']) ?></p>
        </div>
        
        <!-- Corps -->
        <div class="ticket-body">
            <!-- Section principale -->
            <div class="ticket-main">
                <!-- Informations client -->
                <div class="ticket-info">
                    <div class="info-group">
                        <div class="info-label">👤 Titulaire</div>
                        <div class="info-value"><?= htmlspecialchars($reservation['nom_client']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">📧 Email</div>
                        <div class="info-value"><?= htmlspecialchars($reservation['email']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">📞 Téléphone</div>
                        <div class="info-value"><?= htmlspecialchars($reservation['telephone']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">🎫 Nombre de places</div>
                        <div class="info-value"><?= $reservation['nombre_places'] ?> place(s)</div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">💰 Montant</div>
                        <div class="info-value"><?= number_format($reservation['montant_total'], 2) ?> €</div>
                    </div>
                    
                    <?php if (!empty($reservation['ticket_status'])): ?>
                    <div class="info-group">
                        <div class="info-label">📌 Statut du ticket</div>
                        <div class="info-value">
                            <span class="status-badge status-<?= $reservation['ticket_status'] ?>">
                                <?= strtoupper($reservation['ticket_status']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- QR Code -->
                <?php if ($qrCode): ?>
                <div class="ticket-qr">
                    <img src="<?= $qrCode ?>" alt="QR Code">
                    <p style="margin-top: 15px; color: #666; font-size: 13px;">
                        Scannez ce code à l'entrée
                    </p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Détails événement -->
            <div class="event-details">
                <h2>📅 Détails de l'événement</h2>
                <div class="event-grid">
                    <div class="info-group">
                        <div class="info-label">Événement</div>
                        <div class="info-value"><?= htmlspecialchars($reservation['event_titre']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">📍 Lieu</div>
                        <div class="info-value"><?= htmlspecialchars($reservation['lieu']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">🗓️ Date</div>
                        <div class="info-value"><?= date('d/m/Y à H:i', strtotime($reservation['date_event'])) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">⏱️ Durée</div>
                        <div class="info-value"><?= $reservation['duree'] ?> minutes</div>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($reservation['ticket_code'])): ?>
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <p style="margin: 0; color: #856404; font-size: 13px;">
                    <strong>Code du ticket:</strong><br>
                    <code style="font-family: monospace; font-size: 11px; word-break: break-all;">
                        <?= htmlspecialchars($reservation['ticket_code']) ?>
                    </code>
                </p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Pied de page -->
        <div class="ticket-footer">
            <p>
                ⚠️ Conservez précieusement ce ticket. Il sera nécessaire pour accéder à l'événement.<br>
                En cas de perte, contactez-nous à contact@eventpro.com
            </p>
            
            <div class="actions">
                <button onclick="window.print()" class="btn btn-primary">
                    🖨️ Imprimer le ticket
                </button>
                <a href="index.php?action=reservations" class="btn btn-secondary">
                    ← Retour aux réservations
                </a>
                <a href="public_index.php" class="btn btn-secondary">
                    🏠 Accueil
                </a>
            </div>
        </div>
    </div>
</body>
</html>
