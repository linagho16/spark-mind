<?php
// Front Office - Page publique
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/EventModel.php';
require_once __DIR__ . '/models/Reservation.php';

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

$eventModel = new EventModel($pdo);
$reservation = new Reservation($pdo);

// Router
$action = $_GET['action'] ?? 'home';
$eventId = $_GET['id'] ?? null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - Réservez votre place</title>
    <link rel="stylesheet" href="assets/css/public-style.css">
</head>
<body>
    <!-- Header Public -->
    <div class="public-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🎭 Sparkmind</h1>
                    <p>
                </div>
                <nav class="public-nav">
                    <a href="?action=home" class="<?= $action == 'home' ? 'active' : '' ?>">Accueil</a>
                    <a href="?action=events" class="<?= $action == 'events' ? 'active' : '' ?>">Événements</a>
                    <a href="?action=my_reservations" class="<?= $action == 'my_reservations' ? 'active' : '' ?>">Mes Réservations</a>
                    <a href="index.php" class="btn-admin">🔐 Administration</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="public-main">
        <?php
        switch ($action) {
            case 'home':
                include __DIR__ . '/views/public/home.php';
                break;
            
            case 'events':
                include __DIR__ . '/views/public/events_list.php';
                break;
            
            case 'event_detail':
                include __DIR__ . '/views/public/event_detail.php';
                break;
            
            case 'book':
                include __DIR__ . '/views/public/booking_form.php';
                break;
            
            case 'my_reservations':
                include __DIR__ . '/views/public/my_reservations.php';
                break;
            
            case 'reservation_detail':
                include __DIR__ . '/views/public/reservation_detail.php';
                break;
            
            default:
                include __DIR__ . '/views/public/home.php';
                break;
        }
        ?>
    </div>

    <!-- Footer -->
    <div class="public-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🎭 Sparkmind</h3>
                    <p>Plateforme de gestion et réservation d'événements</p>
                </div>
                <div class="footer-section">
                    <h4>Liens rapides</h4>
                    <ul>
                        <li><a href="?action=home">Accueil</a></li>
                        <li><a href="?action=events">Événements</a></li>
                        <li><a href="?action=my_reservations">Mes Réservations</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>📧 contact@Sparkmind.com</p>
                    <p>📞 +33 1 23 45 67 89</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> EventPro - Tous droits réservés</p>
            </div>
        </div>
    </div>

    <script>
        // Messages flash
        <?php if (isset($_SESSION['message'])): ?>
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flash-message <?= $_SESSION['message_type'] ?? 'info' ?>';
        messageDiv.innerHTML = '<?= addslashes($_SESSION['message']) ?>';
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            messageDiv.classList.remove('show');
            setTimeout(() => messageDiv.remove(), 300);
        }, 4000);
        
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <?php endif; ?>
    </script>
</body>
</html>
