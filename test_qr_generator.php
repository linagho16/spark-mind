<?php
/**
 * Script de test pour le générateur de QR codes
 */

require_once __DIR__ . '/utils/QrGenerator.php';
require_once __DIR__ . '/utils/QrGeneratorAdvanced.php';

use Utils\QrGenerator;
use Utils\QrGeneratorAdvanced;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Générateur QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #8B7355;
            text-align: center;
        }
        .qr-section {
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #D4C5B9;
            border-radius: 8px;
        }
        .qr-section h2 {
            color: #4A3F35;
            margin-top: 0;
        }
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .qr-item {
            text-align: center;
            padding: 15px;
            background: #F5F1ED;
            border-radius: 8px;
        }
        .qr-item img {
            max-width: 100%;
            height: auto;
            border: 2px solid #8B7355;
            border-radius: 5px;
        }
        .qr-item h3 {
            color: #8B7355;
            margin-bottom: 10px;
        }
        .qr-item p {
            color: #666;
            font-size: 14px;
            word-break: break-all;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎫 Test Générateur QR Code</h1>
        
        <?php
        // Test 1: QR Code simple avec API
        echo '<div class="qr-section">';
        echo '<h2>1. QR Code via API (Recommandé)</h2>';
        try {
            $generator = new QrGenerator();
            
            echo '<div class="qr-grid">';
            
            // Ticket simple
            $ticketCode = 'TICKET-2025-ABC123XYZ';
            $qr1 = $generator->generateQrBase64($ticketCode);
            echo '<div class="qr-item">';
            echo '<h3>Code Ticket Simple</h3>';
            echo '<img src="' . $qr1 . '" alt="QR Code Ticket">';
            echo '<p>' . $ticketCode . '</p>';
            echo '</div>';
            
            // Ticket avec UUID
            $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
            $signature = hash_hmac('sha256', $uuid, 'SECRET_KEY');
            $fullTicket = $uuid . '.' . $signature;
            
            $qr2 = $generator->generateTicketQr($fullTicket, [
                'event_id' => 123,
                'reservation_id' => 456,
                'client' => 'Jean Dupont'
            ]);
            echo '<div class="qr-item">';
            echo '<h3>Ticket Complet (UUID + Signature)</h3>';
            echo '<img src="' . $qr2 . '" alt="QR Code Ticket Complet">';
            echo '<p>' . substr($fullTicket, 0, 50) . '...</p>';
            echo '</div>';
            
            // URL
            $url = 'http://localhost:8081/evennement/evennement/public_index.php?action=reservation_detail&ref=REF12345';
            $qr3 = $generator->generateUrlQr($url);
            echo '<div class="qr-item">';
            echo '<h3>URL de Réservation</h3>';
            echo '<img src="' . $qr3 . '" alt="QR Code URL">';
            echo '<p>' . $url . '</p>';
            echo '</div>';
            
            echo '</div>';
            echo '<div class="success">✅ Génération via API réussie</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">❌ Erreur: ' . $e->getMessage() . '</div>';
        }
        echo '</div>';
        
        // Test 2: QR Code avancé (algorithme natif)
        echo '<div class="qr-section">';
        echo '<h2>2. QR Code Natif (Algorithme PHP)</h2>';
        try {
            $generatorAdvanced = new QrGeneratorAdvanced();
            
            echo '<div class="qr-grid">';
            
            // Texte court
            $qr4 = $generatorAdvanced->generateQrBase64('HELLO WORLD 2025');
            echo '<div class="qr-item">';
            echo '<h3>Texte Simple</h3>';
            echo '<img src="' . $qr4 . '" alt="QR Code Texte">';
            echo '<p>HELLO WORLD 2025</p>';
            echo '</div>';
            
            // Code ticket
            $qr5 = $generatorAdvanced->generateQrBase64('TKT-' . date('Ymd') . '-' . rand(1000, 9999));
            echo '<div class="qr-item">';
            echo '<h3>Code Ticket</h3>';
            echo '<img src="' . $qr5 . '" alt="QR Code Ticket">';
            echo '<p>TKT-' . date('Ymd') . '-' . rand(1000, 9999) . '</p>';
            echo '</div>';
            
            echo '</div>';
            echo '<div class="success">✅ Génération native réussie (implémentation simplifiée)</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">❌ Erreur: ' . $e->getMessage() . '</div>';
        }
        echo '</div>';
        
        // Informations système
        echo '<div class="qr-section">';
        echo '<h2>ℹ️ Informations Système</h2>';
        echo '<p><strong>Extension GD:</strong> ' . (extension_loaded('gd') ? '✅ Installée' : '❌ Non installée') . '</p>';
        echo '<p><strong>PHP Version:</strong> ' . phpversion() . '</p>';
        echo '<p><strong>Méthode recommandée:</strong> QrGenerator (utilise API externe)</p>';
        echo '<p><strong>Méthode alternative:</strong> QrGeneratorAdvanced (algorithme PHP natif)</p>';
        echo '</div>';
        ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" style="display: inline-block; padding: 12px 24px; background: #8B7355; color: white; text-decoration: none; border-radius: 5px;">
                ← Retour à l'administration
            </a>
        </div>
    </div>
</body>
</html>
