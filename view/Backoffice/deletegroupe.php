<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Groupe - Dashboard Admin</title>
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
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .logo {
            text-align: center;
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 2rem;
        }

        .logo h2 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .nav-menu {
            flex: 1;
            padding: 0 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 1.2rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .nav-item:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background-color: rgba(255,255,255,0.25);
            color: white;
            font-weight: 600;
        }

        .nav-item .icon {
            font-size: 1.3rem;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: auto;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            width: calc(100% - 260px);
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            padding: 2rem;
            border-radius: 20px;
            color: white;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .header-left h1 {
            font-size: 2rem;
            margin-bottom: 0.3rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header-left p {
            opacity: 0.95;
            font-size: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .confirmation-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .warning-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            color: #dc3545;
        }

        .confirmation-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .confirmation-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .groupe-details {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: left;
        }

        .detail-row {
            display: flex;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e1e5e9;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            width: 150px;
        }

        .detail-value {
            color: #666;
            flex: 1;
        }

        .confirmation-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 1.5rem 0;
            }

            .logo h2 {
                font-size: 1.5rem;
            }

            .nav-item span:not(.icon) {
                display: none;
            }

            .nav-item {
                justify-content: center;
                padding: 0.9rem;
            }

            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
                padding: 1rem;
            }

            .top-header {
                flex-direction: column;
                gap: 1.5rem;
            }

            .confirmation-container {
                padding: 1.5rem;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .confirmation-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
   <!-- SIDEBAR NAVIGATION - Add this to your backoffice files -->
<div class="sidebar">
    <!-- Logo -->
    <div class="logo">
        <h2>🤝 Aide Solidaire</h2>
        <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">Administration</p>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <!-- Dashboard -->
        <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php' || (!isset($_GET['action']) && isset($_GET['action']) == 'dashboard')) ? 'active' : ''; ?>">
            <span class="icon">📊</span>
            <span>Tableau de bord</span>
        </a>
        
        <!-- Donations Section -->
        <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600;">
            GESTION DES DONS
        </div>
        
        <a href="/aide_solitaire/controller/donC.php?action=dons" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'dons') ? 'active' : ''; ?>">
            <span class="icon">🎁</span>
            <span>Tous les dons</span>
        </a>
        
        <a href="/aide_solitaire/controller/donC.php?action=create_don" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'create_don') ? 'active' : ''; ?>">
            <span class="icon">➕</span>
            <span>Ajouter un don</span>
        </a>
        
        <a href="/aide_solitaire/controller/donC.php?action=statistics" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'statistics') ? 'active' : ''; ?>">
            <span class="icon">📈</span>
            <span>Statistiques dons</span>
        </a>
        
        <!-- Groups Section -->
        <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; margin-top: 1rem;">
            GESTION DES GROUPES
        </div>
        
        <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'groupes') ? 'active' : ''; ?>">
            <span class="icon">👥</span>
            <span>Tous les groupes</span>
        </a>
        
        <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'create_groupe') ? 'active' : ''; ?>">
            <span class="icon">➕</span>
            <span>Ajouter un groupe</span>
        </a>
        
        <!-- Settings Section -->
        <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; margin-top: 1rem;">
            ADMINISTRATION
        </div>
        
        <a href="#" class="nav-item">
            <span class="icon">👤</span>
            <span>Utilisateurs</span>
        </a>
        
        <a href="#" class="nav-item">
            <span class="icon">⚙️</span>
            <span>Paramètres</span>
        </a>
        
        <a href="#" class="nav-item">
            <span class="icon">📢</span>
            <span>Newsletter</span>
        </a>
        
        <a href="#" class="nav-item">
            <span class="icon">📋</span>
            <span>Rapports</span>
        </a>
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <!-- User Profile -->
        <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.5rem;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 1.2rem;">👤</span>
            </div>
            <div>
                <div style="font-weight: 600; font-size: 0.9rem;">Administrateur</div>
                <div style="font-size: 0.8rem; opacity: 0.8;">Admin</div>
            </div>
        </div>
        
        <!-- Logout Button -->
        <a href="#" style="display: block; text-align: center; margin-top: 1rem; padding: 0.7rem; background: rgba(255,255,255,0.1); border-radius: 10px; text-decoration: none; color: white; font-size: 0.9rem; transition: all 0.3s ease;">
            <span>🚪</span>
            <span>Déconnexion</span>
        </a>
        
        <!-- FrontOffice Link -->
        <a href="/aide_solitaire/view/frontoffice/index.php" style="display: block; text-align: center; margin-top: 0.5rem; padding: 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; text-decoration: none; color: white; font-size: 0.9rem; transition: all 0.3s ease;">
            <span>🌐</span>
            <span>Voir le site public</span>
        </a>
    </div>
</div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1>Supprimer un Groupe</h1>
                <p>Confirmez la suppression du groupe #<?php echo htmlspecialchars($groupe['id'] ?? ''); ?></p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-secondary">← Retour à la liste</a>
            </div>
        </header>

        <!-- Confirmation Box -->
        <div class="confirmation-container">
            <div class="warning-icon">⚠️</div>
            
            <h2 class="confirmation-title">Êtes-vous sûr de vouloir supprimer ce groupe ?</h2>
            
            <p class="confirmation-message">
                Cette action est irréversible. Toutes les informations relatives à ce groupe seront définitivement supprimées.
            </p>

            <!-- Group Details -->
            <div class="groupe-details">
                <h3 style="color: #333; margin-bottom: 1.5rem;">Détails du groupe à supprimer</h3>
                
                <div class="detail-row">
                    <div class="detail-label">ID :</div>
                    <div class="detail-value"><strong>#<?php echo htmlspecialchars($groupe['id'] ?? ''); ?></strong></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Nom :</div>
                    <div class="detail-value"><?php echo htmlspecialchars($groupe['nom'] ?? ''); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Type :</div>
                    <div class="detail-value">
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
                        echo ($icons[$groupe['type']] ?? '👥') . ' ' . htmlspecialchars($groupe['type'] ?? '');
                        ?>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Région :</div>
                    <div class="detail-value"><?php echo htmlspecialchars($groupe['region'] ?? ''); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Responsable :</div>
                    <div class="detail-value"><?php echo htmlspecialchars($groupe['responsable'] ?? ''); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Email :</div>
                    <div class="detail-value"><?php echo htmlspecialchars($groupe['email'] ?? ''); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Téléphone :</div>
                    <div class="detail-value"><?php echo htmlspecialchars($groupe['telephone'] ?? ''); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Statut :</div>
                    <div class="detail-value">
                        <?php if (($groupe['statut'] ?? '') == 'actif'): ?>
                            <span style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">Actif</span>
                        <?php elseif (($groupe['statut'] ?? '') == 'inactif'): ?>
                            <span style="background: linear-gradient(135deg, #6c757d, #495057); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">Inactif</span>
                        <?php else: ?>
                            <span style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">En attente</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($groupe['description'])): ?>
                <div class="detail-row">
                    <div class="detail-label">Description :</div>
                    <div class="detail-value"><?php echo nl2br(htmlspecialchars($groupe['description'] ?? '')); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="detail-row" style="border-bottom: none; padding-bottom: 0;">
                    <div class="detail-label">Créé le :</div>
                    <div class="detail-value"><?php echo isset($groupe['created_at']) ? date('d/m/Y à H:i', strtotime($groupe['created_at'])) : 'Date non disponible'; ?></div>
                </div>
            </div>

            <!-- Confirmation Actions -->
            <form method="POST" action="/aide_solitaire/controller/groupeC.php?action=delete_groupe&id=<?php echo $groupe['id'] ?? ''; ?>">
                <div class="confirmation-actions">
                    <button type="submit" class="btn-danger">🗑️ Oui, supprimer définitivement</button>
                    <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-secondary">Non, annuler</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>