<?php
// frontoffice/index.php - FrontOffice Homepage
session_start();
require_once __DIR__ . '/../../model/donmodel.php';
require_once __DIR__ . '/../../model/groupemodel.php';

try {
    $donModel = new DonModel();
    $groupeModel = new GroupeModel();
    
    // CHANGED: Get donations with 'frontoffice' status filter
    // At the top of index.php:
    $activeDons = $donModel->getDonsWithFilters(['statut' => 'frontoffice']);
    $activeGroupes = $groupeModel->getGroupesWithFilters(['statut' => 'frontoffice']);
    
    // Limit to 6 each for homepage
    $recentDons = array_slice($activeDons, 0, 6);
    $recentGroupes = array_slice($activeGroupes, 0, 6);
    
} catch (Exception $e) {
    $error = "Erreur de connexion: " . $e->getMessage();
    $recentDons = [];
    $recentGroupes = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- ... rest of your index.php file ... -->
<?php
// frontoffice/index.php - Main homepage
session_start();
require_once __DIR__ . '/../../Model/donmodel.php';
require_once __DIR__ . '/../../Model/groupemodel.php';

try {
    $donModel = new DonModel();
    $groupeModel = new GroupeModel();
    
    // Get active donations (for frontoffice)
    $activeDons = $donModel->getDonsWithFilters(['statut' => 'actif']);
    $activeGroupes = $groupeModel->getGroupesWithFilters(['statut' => 'actif']);
    
    // Limit to 6 each for homepage
    $recentDons = array_slice($activeDons, 0, 6);
    $recentGroupes = array_slice($activeGroupes, 0, 6);
    
} catch (Exception $e) {
    $error = "Erreur de connexion: " . $e->getMessage();
    $recentDons = [];
    $recentGroupes = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide Solidaire - Plateforme de Don et Solidarité</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #FBEDD7;
            color: #333;
            line-height: 1.6;
        }

        /* Header - Dashboard Style */
        .header {
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            color: white;
            padding: 3rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            border-radius: 0 0 20px 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .pigeon-bg {
            position: absolute;
            bottom: 20px;
            right: 5%;
            font-size: 8rem;
            opacity: 0.15;
            z-index: 1;
        }

        /* Stats Bar - Dashboard Style */
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: -3rem auto 3rem;
            max-width: 1000px;
            padding: 0 2rem;
            z-index: 2;
            position: relative;
        }

        .stat-card {
            background: white;
            padding: 1.8rem 2rem;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            text-align: center;
            min-width: 180px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }

        .stat-card:nth-child(1)::before {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
        }

        .stat-card:nth-child(2)::before {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
        }

        .stat-card:nth-child(3)::before {
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            display: block;
        }

        .stat-label {
            color: #666;
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

        /* Sections */
        .section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin: 2.5rem 0;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.2rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .section-title h2 {
            color: #333;
            font-size: 1.6rem;
            font-weight: 600;
        }

        .view-all {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 0.7rem 1.8rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .view-all:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(31,140,135,0.3);
        }

        /* Grid Layouts */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.8rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f3f5;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .card-header {
            padding: 1.8rem 1.8rem 0;
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1.2rem;
        }

        .card-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem 1.8rem;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            color: #666;
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .card-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .card-actions {
            display: flex;
            gap: 0.8rem;
        }

        .btn {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* Quick Actions - Dashboard Style */
        .quick-actions {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin: 2.5rem 0;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .quick-actions h3 {
            color: #333;
            margin-bottom: 1.8rem;
            font-size: 1.6rem;
            font-weight: 600;
            text-align: center;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            padding: 1.2rem 1.5rem;
            border: none;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn:nth-child(1) {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
        }

        .action-btn:nth-child(2) {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
        }

        .action-btn:nth-child(3) {
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
        }

        .action-btn:nth-child(4) {
            background: linear-gradient(135deg, #ec7546, #f4a261);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 3rem;
        }

        .footer p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            opacity: 0.9;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px dashed #e1e5e9;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 2rem 1rem 3rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .stats-bar {
                flex-direction: column;
                gap: 1rem;
                margin: -1.5rem auto 2rem;
                padding: 0 1rem;
            }
            
            .stat-card {
                min-width: auto;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .card-actions {
                flex-direction: column;
            }
            
            .section {
                padding: 1.5rem;
            }
            
            .section-title {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem 2rem;
            }
            
            .btn {
                padding: 0.8rem;
                font-size: 0.85rem;
            }
            
            .action-btn {
                padding: 1rem;
                font-size: 0.9rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>🤝 Aide Solidaire</h1>
        <p>Plateforme de don et de solidarité. Partagez, donnez, et rejoignez des initiatives qui changent des vies.</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <!-- Stats -->
    <div class="stats-bar">
        <div class="stat-card">
            <span class="stat-number"><?php echo count($activeDons ?? []); ?></span>
            <span class="stat-label">Dons actifs</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo count($activeGroupes ?? []); ?></span>
            <span class="stat-label">Groupes actifs</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo (count($activeDons ?? []) + count($activeGroupes ?? [])); ?></span>
            <span class="stat-label">Opportunités</span>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <!-- Quick Actions -->
        <section class="quick-actions">
            <h3>⚡ Que souhaitez-vous faire ?</h3>
            <div class="action-buttons">
                <a href="create_don.php" class="action-btn">🎁 Faire un don</a>
                <a href="create_groupe.php" class="action-btn">👥 Créer un groupe</a>
                <a href="browse_dons.php" class="action-btn">🔍 Voir les dons</a>
                <a href="browse_groupes.php" class="action-btn">🤝 Voir les groupes</a>
            </div>
        </section>

        <!-- Recent Donations -->
        <section class="section">
            <div class="section-title">
                <h2>🎁 Dons récents</h2>
                <a href="browse_dons.php" class="view-all">Voir tous →</a>
            </div>
            
            <?php if (!empty($recentDons)): ?>
                <div class="grid">
                    <?php foreach ($recentDons as $don): ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <?php 
                                $icons = [
                                    'Vêtements' => '👕',
                                    'Nourriture' => '🍞',
                                    'Médicaments' => '💊',
                                    'Équipement' => '🔧',
                                    'Argent' => '💰',
                                    'Services' => '🤝',
                                    'Autre' => '🎁'
                                ];
                                echo $icons[$don['type_don']] ?? '🎁';
                                ?>
                            </div>
                            <h3 class="card-title"><?php echo htmlspecialchars($don['type_don']); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span>📦 <?php echo htmlspecialchars($don['quantite']); ?> unités</span>
                                <span>📍 <?php echo htmlspecialchars($don['region']); ?></span>
                            </div>
                            <p class="card-description"><?php echo substr(htmlspecialchars($don['description'] ?? 'Pas de description'), 0, 100); ?>...</p>
                            <div class="card-actions">
                                <a href="view_don.php?id=<?php echo $don['id']; ?>" class="btn btn-primary">Voir détails</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Aucun don disponible pour le moment.</p>
                    <a href="create_don.php" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Soyez le premier à donner</a>
                </div>
            <?php endif; ?>
        </section>

        <!-- Recent Groups -->
        <section class="section">
            <div class="section-title">
                <h2>👥 Groupes récents</h2>
                <a href="browse_groupes.php" class="view-all">Voir tous →</a>
            </div>
            
            <?php if (!empty($recentGroupes)): ?>
                <div class="grid">
                    <?php foreach ($recentGroupes as $groupe): ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <?php 
                                $icons = [
                                    'Santé' => '🏥',
                                    'Éducation' => '📚',
                                    'Seniors' => '👵',
                                    'Jeunesse' => '👦',
                                    'Culture' => '🎨',
                                    'Urgence' => '🚨',
                                    'Animaux' => '🐾',
                                    'Environnement' => '🌿'
                                ];
                                echo $icons[$groupe['type']] ?? '👥';
                                ?>
                            </div>
                            <h3 class="card-title"><?php echo htmlspecialchars($groupe['nom']); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span>📍 <?php echo htmlspecialchars($groupe['region']); ?></span>
                                <span>👤 <?php echo htmlspecialchars($groupe['responsable']); ?></span>
                            </div>
                            <p class="card-description"><?php echo substr(htmlspecialchars($groupe['description'] ?? 'Pas de description'), 0, 100); ?>...</p>
                            <div class="card-actions">
                                <a href="view_groupe.php?id=<?php echo $groupe['id']; ?>" class="btn btn-primary">Voir groupe</a>
                                <a href="mailto:<?php echo htmlspecialchars($groupe['email']); ?>" class="btn btn-secondary">Contacter</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>👥 Aucun groupe disponible pour le moment.</p>
                    <a href="create_groupe.php" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Créer le premier groupe</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Aide Solidaire - Ensemble, faisons la différence ❤️</p>
        <div class="footer-links">
            <a href="index.php">🏠 Accueil</a>
            <a href="../Backoffice/dashboard.php">🔒 Espace Admin</a>
        </div>
    </footer>

    <?php if (isset($error)): ?>
    <script>
        alert('Erreur: <?php echo $error; ?>');
    </script>
    <?php endif; ?>
</body>
</html>