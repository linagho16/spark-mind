<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Dons - Dashboard Admin</title>
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

        /* SIDEBAR - Style cohérent avec le Frontoffice */
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

        /* TOP HEADER - Style cohérent */
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

        /* BOUTONS - Style cohérent */
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

        /* FILTRES - Style amélioré */
        .filters {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            align-items: end;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .filter-select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #1f8c87;
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.1);
        }

        .btn-apply {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-reset {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3);
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
        }

        /* MESSAGES - Style cohérent */
        .message-alert {
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 6px solid;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .message-success {
            background: #d5f4e6;
            color: #166534;
            border-left-color: #10b981;
        }

        .message-error {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        /* STATS BAR */
        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .total-count {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }

        .export-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-export {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(125, 90, 166, 0.3);
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(125, 90, 166, 0.4);
        }

        /* TABLEAU - Style amélioré */
        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }

        th {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 1.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 1.1rem;
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid #e1e5e9;
            vertical-align: middle;
            color: #333;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .table-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-icon {
            padding: 0.75rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 45px;
            height: 45px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .btn-view {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .btn-icon:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* BADGES - Style cohérent */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
            background: white;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #666;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .filters {
                grid-template-columns: 1fr 1fr;
            }
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

            .filters {
                grid-template-columns: 1fr;
            }

            .stats-bar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .table-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-icon {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
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
        <a href="/aide_solitaire/view/frontoffice/index.php" style="display: block; text-align: center; margin-top: 0.5rem; padding: 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; text-decoration: none; color: white; font-size: 0.9rem; transition: all 0.3s ease;">
        <span>🌐</span>
        <span>Voir le site public</span>
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
        <!-- Top Header - Style cohérent -->
        <header class="top-header">
            <div class="header-left">
                <h1>Gestion des Dons</h1>
                <p>Gérez tous les dons du système</p>
            </div>
            <div class="header-right">
                <!-- FIXED: Changed from index.php to controller/donC.php -->
                <a href="/aide_solitaire/controller/donC.php?action=create_don" class="btn-primary">+ Nouveau Don</a>
                <div class="user-profile">
                    <span class="notification-badge">3</span>
                    <div class="avatar">👤</div>
                </div>
            </div>
        </header>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['message'])): ?>
            <?php
            $messages = [
                'created' => ['type' => 'success', 'text' => 'Don créé avec succès!'],
                'updated' => ['type' => 'success', 'text' => 'Don modifié avec succès!'],
                'deleted' => ['type' => 'success', 'text' => 'Don supprimé avec succès!'],
                'error' => ['type' => 'error', 'text' => 'Une erreur est survenue!'],
                'not_found' => ['type' => 'error', 'text' => 'Don non trouvé!']
            ];
            $message = $messages[$_GET['message']] ?? null;
            ?>
            <?php if ($message): ?>
                <div class="message-alert message-<?php echo $message['type']; ?>">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Filters -->
        <!-- FIXED: Changed action to controller/donC.php -->
        <form method="GET" action="/aide_solitaire/controller/donC.php">
            <input type="hidden" name="action" value="dons">
            <div class="filters">
                <div class="filter-group">
                    <label class="filter-label">Type de don</label>
                    <select class="filter-select" name="type_don">
                        <option value="">Tous les types</option>
                        <option value="Vêtements" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>Vêtements</option>
                        <option value="Nourriture" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>Nourriture</option>
                        <option value="Médicaments" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>Médicaments</option>
                        <option value="Équipement" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Équipement' ? 'selected' : ''; ?>>Équipement</option>
                        <option value="Argent" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Argent' ? 'selected' : ''; ?>>Argent</option>
                        <option value="Services" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Services' ? 'selected' : ''; ?>>Services</option>
                        <option value="Autre" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Région</label>
                    <select class="filter-select" name="region">
                        <option value="">Toutes les régions</option>
                        <option value="Tunis" <?php echo isset($_GET['region']) && $_GET['region'] == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                        <option value="Sfax" <?php echo isset($_GET['region']) && $_GET['region'] == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                        <option value="Sousse" <?php echo isset($_GET['region']) && $_GET['region'] == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                        <option value="Kairouan" <?php echo isset($_GET['region']) && $_GET['region'] == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                        <option value="Bizerte" <?php echo isset($_GET['region']) && $_GET['region'] == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                        <option value="Gabès" <?php echo isset($_GET['region']) && $_GET['region'] == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                        <option value="Ariana" <?php echo isset($_GET['region']) && $_GET['region'] == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                        <option value="Gafsa" <?php echo isset($_GET['region']) && $_GET['region'] == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                        <option value="Monastir" <?php echo isset($_GET['region']) && $_GET['region'] == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                        <option value="Autre" <?php echo isset($_GET['region']) && $_GET['region'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                    </select>
                </div>

                <button type="submit" class="btn-apply">🔍 Appliquer</button>
                <!-- FIXED: Changed from index.php to controller/donC.php -->
                <a href="/aide_solitaire/controller/donC.php?action=dons" class="btn-reset">🔄 Réinitialiser</a>
            </div>
        </form>

        <!-- Stats and Export -->
        <div class="stats-bar">
            <div class="total-count">
                📊 Total: <?php echo isset($dons) ? count($dons) : 0; ?> don(s)
            </div>
            <div class="export-buttons">
                <button class="btn-export" onclick="printTable()">🖨️ Imprimer</button>
                <button class="btn-export" onclick="exportToCSV()">📥 CSV</button>
            </div>
        </div>

        <!-- Dons Table -->
        <div class="table-container">
            <?php if (!isset($dons) || empty($dons)): ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <h3>Aucun don trouvé</h3>
                    <p>Aucun don ne correspond à vos critères de recherche.</p>
                    <!-- FIXED: Changed from index.php to controller/donC.php -->
                    <a href="/aide_solitaire/controller/donC.php?action=create_don" class="btn-primary" style="margin-top: 1rem;">➕ Ajouter le premier don</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>État</th>
                            <th>Région</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dons as $don): ?>
                        <tr>
                            <td><strong>#<?php echo $don['id']; ?></strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.2rem;">
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
                                    </span>
                                    <?php echo htmlspecialchars($don['type_don']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($don['quantite']); ?></td>
                            <td>
                                <?php if (!empty($don['etat_object'])): ?>
                                    <span class="badge badge-active"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-pending">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-active"><?php echo htmlspecialchars($don['region']); ?></span>
                            </td>
                            <td>
                                <small><?php echo date('d/m/Y', strtotime($don['date_don'])); ?></small>
                                <br>
                                <small style="color: #666;"><?php echo date('H:i', strtotime($don['date_don'])); ?></small>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <!-- FIXED: Changed from index.php to controller/donC.php -->
                                    <a href="/aide_solitaire/controller/donC.php?action=view_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-view" title="Voir">
                                        👁️
                                    </a>
                                    <a href="/aide_solitaire/controller/donC.php?action=edit_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-edit" title="Modifier">
                                        ✏️
                                    </a>
                                    <a href="/aide_solitaire/controller/donC.php?action=delete_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-delete" title="Supprimer" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function printTable() {
            window.print();
        }

        function exportToCSV() {
            <?php if (isset($dons) && !empty($dons)): ?>
            const rows = [
                ['ID', 'Type', 'Quantité', 'État', 'Région', 'Date', 'Description']
            ];
            
            <?php foreach ($dons as $don): ?>
            rows.push([
                '<?php echo $don['id']; ?>',
                '<?php echo $don['type_don']; ?>',
                '<?php echo $don['quantite']; ?>',
                '<?php echo $don['etat_object']; ?>',
                '<?php echo $don['region']; ?>',
                '<?php echo date('d/m/Y H:i', strtotime($don['date_don'])); ?>',
                '<?php echo isset($don['description']) ? addslashes($don['description']) : ''; ?>'
            ]);
            <?php endforeach; ?>

            let csvContent = "data:text/csv;charset=utf-8,";
            rows.forEach(function(rowArray) {
                let row = rowArray.map(field => `"${field}"`).join(",");
                csvContent += row + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "dons_<?php echo date('Y-m-d'); ?>.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            <?php else: ?>
            alert('Aucun don à exporter!');
            <?php endif; ?>
        }

        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.message-alert');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>