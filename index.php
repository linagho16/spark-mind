<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Charger la configuration
require_once __DIR__ . '/config/config.php';

// Charger les modèles
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

// Initialiser les modèles
$eventModel = new EventModel($pdo);
$reservation = new Reservation($pdo);

// Action par défaut
$action = $_GET['action'] ?? 'dashboard';
$id = $_GET['id'] ?? null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/modern-sidebar.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="?action=dashboard" class="sidebar-logo">
                    <div class="logo-icon">🎭</div>
                    <div class="logo-text">
                        <h2>sparkmind</h2>
                        <p>Gestion d'événements</p>
                    </div>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Menu Principal</div>
                    <a href="?action=dashboard" class="nav-item <?= ($action == 'dashboard') ? 'active' : '' ?>">
                        <span class="icon">📊</span>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="?action=events" class="nav-item <?= ($action == 'events') ? 'active' : '' ?>">
                        <span class="icon">📅</span>
                        <span>Événements</span>
                    </a>
                    <a href="?action=reservations" class="nav-item <?= ($action == 'reservations') ? 'active' : '' ?>">
                        <span class="icon">🎫</span>
                        <span>Réservations</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Actions Rapides</div>
                    <a href="?action=create_event" class="nav-item">
                        <span class="icon">➕</span>
                        <span>Nouvel Événement</span>
                    </a>
                    <a href="?action=create_reservation" class="nav-item">
                        <span class="icon">🎟️</span>
                        <span>Nouvelle Réservation</span>
                    </a>
                    <a href="?action=ticket_scanner" class="nav-item">
                        <span class="icon">🎫</span>
                        <span>Scanner Tickets</span>
                    </a>
                </div>
            </div>
            
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">A</div>
                    <div class="user-info">
                        <h4>Administrateur</h4>
                        <p>En ligne</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main-wrapper">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <div class="breadcrumb">
                        <a href="?action=dashboard">Accueil</a>
                        <span>›</span>
                        <span><?= ucfirst(str_replace('_', ' ', $action)) ?></span>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <span class="search-icon">🔍</span>
                        <input type="text" 
                               id="globalSearch" 
                               placeholder="Rechercher événements ou réservations..."
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="main-content">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?? 'success' ?>">
                    <?= $_SESSION['message'] ?>
                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                </div>
            <?php endif; ?>
            
            <?php
            switch ($action) {
                case 'dashboard':
                    include 'views/dashboard.php';
                    break;
                    
                case 'events':
                    include 'views/events/index.php';
                    break;
                    
                case 'create_event':
                    include 'views/events/create.php';
                    break;
                    
                case 'edit_event':
                    $event = $eventModel->getEventById($id);
                    include 'views/events/edit.php';
                    break;
                    
                case 'reservations':
                    include 'views/reservations/index.php';
                    break;
                    
                case 'create_reservation':
                    $events = $eventModel->getAllEvents();
                    include 'views/reservations/create.php';
                    break;
                    
                case 'view_reservation':
                    include 'views/reservations/show.php';
                    break;
                    
                case 'edit_reservation':
                    if ($id) {
                        include 'views/reservations/edit.php';
                    } else {
                        header('Location: index.php?action=reservations');
                    }
                    break;
                    
                case 'ticket_scanner':
                    include 'views/tickets/scan.php';
                    break;
                    
                default:
                    include 'views/dashboard.php';
                    break;
            }
            ?>
            </main>
        </div>
    </div>
    
    <script>
        // Scripts JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Calcul automatique du montant pour les réservations
            const eventSelect = document.getElementById('event_id');
            const placesInput = document.getElementById('nombre_places');
            
            if (eventSelect && placesInput) {
                function calculateTotal() {
                    const selectedOption = eventSelect.options[eventSelect.selectedIndex];
                    const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
                    const places = parseInt(placesInput.value) || 0;
                    const total = price * places;
                    
                    const montantTotal = document.getElementById('montant_total');
                    const montantTotalInput = document.getElementById('montant_total_input');
                    
                    if (montantTotal) montantTotal.textContent = total.toFixed(2) + ' €';
                    if (montantTotalInput) montantTotalInput.value = total.toFixed(2);
                }
                
                eventSelect.addEventListener('change', calculateTotal);
                placesInput.addEventListener('input', calculateTotal);
                calculateTotal();
            }
            
            // Confirmation avant suppression
            const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer ? Cette action est irréversible.')) {
                        e.preventDefault();
                    }
                });
            });
            
            // Recherche globale
            const searchInput = document.getElementById('globalSearch');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const searchTerm = this.value.trim();
                        if (searchTerm) {
                            // Déterminer la page actuelle
                            const currentUrl = new URL(window.location.href);
                            const action = currentUrl.searchParams.get('action') || 'dashboard';
                            
                            // Rediriger avec le terme de recherche
                            if (action === 'events' || action === 'create_event' || action === 'edit_event') {
                                window.location.href = '?action=events&search=' + encodeURIComponent(searchTerm);
                            } else if (action === 'reservations' || action === 'create_reservation' || action === 'edit_reservation') {
                                window.location.href = '?action=reservations&search=' + encodeURIComponent(searchTerm);
                            } else {
                                // Par défaut, rechercher dans les événements
                                window.location.href = '?action=events&search=' + encodeURIComponent(searchTerm);
                            }
                        }
                    }
                });
                
                // Bouton pour effacer la recherche
                if (searchInput.value) {
                    searchInput.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            const currentUrl = new URL(window.location.href);
                            const action = currentUrl.searchParams.get('action') || 'dashboard';
                            window.location.href = '?action=' + action;
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>