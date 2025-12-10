<?php
// Récupérer les vraies données de la base de données
$eventsCount = $eventModel->countEvents();
$stats = $reservation->getStats();
$reservationsCount = $stats['total'] ?? 0;
$confirmedCount = $stats['confirmées'] ?? 0;
$totalRevenue = $stats['revenu_total'] ?? 0.00;
$upcomingEvents = $eventModel->getUpcomingEvents(5);

// Calculer le taux de remplissage
$totalPlaces = $eventsCount * 100; // 100 places par événement
$placesReservees = $stats['total'] ?? 0;
$tauxRemplissage = $totalPlaces > 0 ? round(($placesReservees / $totalPlaces) * 100) : 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Gestion Événements</title>
    <style>
        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background: #F5F1ED;
            min-height: 100vh;
        }
        
        /* Conteneur principal */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #4A3F35 0%, #3E3731 100%);
            color: white;
            padding: 30px 0;
            box-shadow: 3px 0 15px rgba(75, 63, 53, 0.15);
        }
        
        .logo {
            text-align: center;
            padding: 0 20px 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }
        
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .logo p {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        .nav-menu {
            padding: 0 20px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            color: #D4C5B9;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .nav-item:hover {
            background: #5C4F43;
            color: white;
            transform: translateX(5px);
        }
        
        .nav-item.active {
            background: #8B7355;
            color: white;
        }
        
        .nav-item .icon {
            font-size: 1.2rem;
        }
        
        /* Contenu principal */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            font-size: 2.2rem;
            color: #3E3731;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #6B5F55;
            font-size: 1.1rem;
        }
        
        /* Grid de statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(75, 63, 53, 0.12);
            position: relative;
            overflow: hidden;
            border-left: 5px solid #8B7355;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        
        .stat-card:nth-child(2) {
            border-left-color: #8B9556;
        }
        
        .stat-card:nth-child(3) {
            border-left-color: #C07855;
        }
        
        .stat-card:nth-child(4) {
            border-left-color: #D4A574;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.05));
        }
        
        .stat-label {
            font-size: 0.95rem;
            color: #8B7F76;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #3E3731;
            line-height: 1;
        }
        
        .stat-value.currency {
            color: #8B9556;
        }
        
        /* Section événements */
        .events-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(75, 63, 53, 0.12);
            margin-bottom: 40px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 1.5rem;
            color: #3E3731;
            font-weight: 600;
        }
        
        .btn {
            padding: 10px 25px;
            background: #8B7355;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn:hover {
            background: #6B5744;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #8B9556;
        }
        
        .btn-secondary:hover {
            background: #7A8449;
        }
        
        /* Tableau */
        .events-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .events-table thead {
            background: #F5F1ED;
        }
        
        .events-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #3E3731;
            border-bottom: 2px solid #D4C5B9;
        }
        
        .events-table td {
            padding: 15px;
            border-bottom: 1px solid #F5F1ED;
        }
        
        .events-table tr:hover {
            background: #F5F1ED;
        }
        
        .price-tag {
            background: #8B9556;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Carte revenu */
        .revenue-card {
            background: linear-gradient(135deg, #8B7355 0%, #6B5744 100%);
            border-radius: 15px;
            padding: 40px;
            color: white;
            text-align: center;
        }
        
        .revenue-label {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        
        .revenue-value {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 20px;
            }
            
            .nav-menu {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .nav-item {
                flex: 1;
                min-width: 150px;
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-menu {
                flex-direction: column;
            }
            
            .nav-item {
                width: 100%;
            }
            
            .revenue-value {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1>🎯 Événements & Réservations</h1>
                <p>Gestion simplifiée</p>
            </div>
            
            <div class="nav-menu">
                <a href="?action=dashboard" class="nav-item active">
                    <span class="icon">📊</span>
                    <span>Tableau de bord</span>
                </a>
                <a href="?action=events" class="nav-item">
                    <span class="icon">📅</span>
                    <span>Événements</span>
                </a>
                <a href="?action=reservations" class="nav-item">
                    <span class="icon">🎫</span>
                    <span>Réservations</span>
                </a>
                <a href="?action=create_reservation" class="nav-item">
                    <span class="icon">➕</span>
                    <span>Nouvelle Réservation</span>
                </a>
                <a href="?action=create_event" class="nav-item">
                    <span class="icon">✨</span>
                    <span>Nouvel Événement</span>
                </a>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="main-content">
            <!-- En-tête -->
            <div class="page-header">
                <h1>Tableau de bord</h1>
                <p>Vue d'ensemble de votre activité</p>
            </div>
            
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Événements</div>
                    <div class="stat-value"><?php echo $eventsCount; ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">Réservations</div>
                    <div class="stat-value"><?php echo $reservationsCount; ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">Confirmées</div>
                    <div class="stat-value"><?php echo $confirmedCount; ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">Revenu Total</div>
                    <div class="stat-value currency"><?php echo number_format($totalRevenue, 2, ',', ' '); ?> €</div>
                </div>
            </div>
            
            <!-- Événements à venir -->
            <div class="events-section">
                <div class="section-header">
                    <h2 class="section-title">📅 Événements à venir</h2>
                    <div class="action-buttons">
                        <button class="btn" onclick="window.location.href='?action=create_event'">
                            <span>✨</span> Nouvel Événement
                        </button>
                        <button class="btn btn-secondary" onclick="window.location.href='?action=create_reservation'">
                            <span>🎫</span> Nouvelle Réservation
                        </button>
                    </div>
                </div>
                
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($upcomingEvents)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #7f8c8d;">
                                    <div style="font-size: 3em; margin-bottom: 15px;">📭</div>
                                    <strong>Aucun événement à venir</strong>
                                    <p style="margin-top: 10px;">Créez votre premier événement pour commencer</p>
                                    <button class="btn" style="margin-top: 15px;" onclick="window.location.href='?action=create_event'">
                                        ✨ Créer un événement
                                    </button>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($upcomingEvents as $event): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($event['titre']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($event['date_event'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['lieu']); ?></td>
                                    <td><span class="price-tag"><?php echo number_format($event['prix'], 2, ',', ' '); ?> €</span></td>
                                    <td>
                                        <button class="btn" style="padding: 5px 15px; font-size: 0.9rem;" 
                                                onclick="window.location.href='?action=edit_event&id=<?php echo $event['id']; ?>'">
                                            Voir
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Revenu total -->
            <div class="revenue-card">
                <div class="revenue-label">💰 Revenu Total Généré</div>
                <div class="revenue-value"><?php echo number_format($totalRevenue, 2, ',', ' '); ?> €</div>
                <p>Sur l'ensemble des réservations confirmées</p>
            </div>
        </div>
    </div>
    
    <script>
        // Navigation active
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            const currentUrl = window.location.search;
            
            navItems.forEach(item => {
                // Retirer active de tous
                item.classList.remove('active');
                
                // Vérifier si c'est la page active
                const href = item.getAttribute('href');
                if (href && currentUrl.includes(href.replace('?action=', ''))) {
                    item.classList.add('active');
                } else if (!currentUrl && href === '?action=dashboard') {
                    // Page d'accueil par défaut
                    item.classList.add('active');
                }
            });
            
            // Animation des cartes
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>