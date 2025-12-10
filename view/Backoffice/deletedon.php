<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer le Don - Dashboard Admin</title>
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

        /* SIDEBAR */
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

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            width: calc(100% - 260px);
        }

        /* TOP HEADER */
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

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ec7546;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 50%;
            font-weight: 600;
            z-index: 10;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: 2px solid rgba(255,255,255,0.6);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.1);
            background-color: rgba(255,255,255,0.4);
        }

        /* DELETE CONTAINER STYLES */
        .delete-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin: 2rem auto;
            max-width: 600px;
            text-align: center;
        }

        .warning-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dc3545;
        }

        .don-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: left;
            border-left: 4px solid #ec7546;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .detail-value {
            color: #6c757d;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 1rem;
            font-weight: 600;
        }

        .btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(220,53,69,0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: scale(1.05);
        }

        .btn-cancel {
            background: transparent;
            color: white;
            padding: 0.5rem 1.5rem;
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-bottom: 2rem;
            text-align: left;
        }

        /* RESPONSIVE */
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
                text-align: center;
            }

            .delete-container {
                padding: 2rem;
                margin: 1rem;
            }

            .detail-row {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-danger, .btn-secondary {
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <!-- Logo -->
        <div class="logo">
            <h2>🤝 Aide Solidaire</h2>
            <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">Administration</p>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="nav-menu">
            <!-- Dashboard -->
            <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="nav-item">
                <span class="icon">📊</span>
                <span>Tableau de bord</span>
            </a>
            
            <!-- Donations Section -->
            <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600;">
                GESTION DES DONS
            </div>
            
            <a href="/aide_solitaire/controller/donC.php?action=dons" class="nav-item">
                <span class="icon">🎁</span>
                <span>Tous les dons</span>
            </a>
            
            <a href="/aide_solitaire/controller/donC.php?action=create_don" class="nav-item">
                <span class="icon">➕</span>
                <span>Ajouter un don</span>
            </a>
            
            <a href="/aide_solitaire/controller/donC.php?action=statistics" class="nav-item">
                <span class="icon">📈</span>
                <span>Statistiques dons</span>
            </a>
            
            <!-- Groups Section -->
            <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; margin-top: 1rem;">
                GESTION DES GROUPES
            </div>
            
            <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="nav-item">
                <span class="icon">👥</span>
                <span>Tous les groupes</span>
            </a>
            
            <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="nav-item">
                <span class="icon">➕</span>
                <span>Ajouter un groupe</span>
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
                <h1>Supprimer le Don</h1>
                <p>Confirmez la suppression de ce don</p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/controller/donC.php?action=dons" class="btn-cancel">
                    ← Retour à la liste
                </a>
                <div class="user-profile">
                    <div class="avatar">👤</div>
                </div>
            </div>
        </header>

        <!-- Delete Confirmation -->
        <div class="delete-container">
            <div class="warning-icon">⚠️</div>
            <h2>Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir supprimer ce don ? Cette action est irréversible.</p>

            <div class="alert-warning">
                <strong>Attention :</strong> Toutes les données relatives à ce don seront définitivement perdues.
            </div>

            <!-- Don Details -->
            <div class="don-details">
                <h3>Détails du don à supprimer :</h3>
                <div class="detail-row">
                    <span class="detail-label">ID :</span>
                    <span class="detail-value">#<?php echo $don['id']; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type de don :</span>
                    <span class="detail-value"><?php echo htmlspecialchars($don['type_don']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Quantité :</span>
                    <span class="detail-value"><?php echo htmlspecialchars($don['quantite']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Région :</span>
                    <span class="detail-value"><?php echo htmlspecialchars($don['region']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date :</span>
                    <span class="detail-value"><?php echo date('d/m/Y H:i', strtotime($don['date_don'])); ?></span>
                </div>
                <?php if (!empty($don['etat_object'])): ?>
                <div class="detail-row">
                    <span class="detail-label">État :</span>
                    <span class="detail-value"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">Description :</span>
                    <span class="detail-value"><?php echo nl2br(htmlspecialchars($don['description'] ?? '')); ?></span>
                </div>
            </div>

            <!-- Confirmation Form -->
            <form method="POST" action="/aide_solitaire/controller/donC.php?action=delete_don&id=<?php echo $don['id']; ?>">
                <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem;">
                    <a href="/aide_solitaire/controller/donC.php?action=dons" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-danger" onclick="return confirm('Êtes-vous ABSOLUMENT sûr ? Cette action ne peut pas être annulée.')">
                        🗑️ Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Double confirmation for delete
        document.querySelector('form').addEventListener('submit', function(e) {
            const confirmed = confirm('⚠️ ACTION IRREVERSIBLE ⚠️\n\nÊtes-vous ABSOLUMENT certain de vouloir supprimer ce don ?\n\nCette action ne peut pas être annulée.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>