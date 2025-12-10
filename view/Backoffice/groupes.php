<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Groupes - Dashboard Admin</title>
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

        /* BOUTONS */
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

        /* FILTRES */
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

        /* MESSAGES */
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

        /* TABLEAU */
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

        /* BADGES */
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

        .badge-inactive {
            background: linear-gradient(135deg, #6c757d, #495057);
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
                <h1>Gestion des Groupes</h1>
                <p>Gérez tous les groupes de solidarité</p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="btn-primary">+ Nouveau Groupe</a>
                <div class="user-profile">
                    <span class="notification-badge">2</span>
                    <div class="avatar">👤</div>
                </div>
            </div>
        </header>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['message'])): ?>
            <?php
            $messages = [
                'created' => ['type' => 'success', 'text' => 'Groupe créé avec succès!'],
                'updated' => ['type' => 'success', 'text' => 'Groupe modifié avec succès!'],
                'deleted' => ['type' => 'success', 'text' => 'Groupe supprimé avec succès!'],
                'error' => ['type' => 'error', 'text' => 'Une erreur est survenue!'],
                'not_found' => ['type' => 'error', 'text' => 'Groupe non trouvé!']
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
        <form method="GET" action="/aide_solitaire/controller/groupeC.php"></form>
            <input type="hidden" name="action" value="groupes">
            <div class="filters">
                <div class="filter-group">
                    <label class="filter-label">Type de groupe</label>
                    <select class="filter-select" name="type">
                        <option value="">Tous les types</option>
                        <option value="Santé" <?php echo isset($_GET['type']) && $_GET['type'] == 'Santé' ? 'selected' : ''; ?>>🏥 Santé</option>
                        <option value="Éducation" <?php echo isset($_GET['type']) && $_GET['type'] == 'Éducation' ? 'selected' : ''; ?>>📚 Éducation</option>
                        <option value="Seniors" <?php echo isset($_GET['type']) && $_GET['type'] == 'Seniors' ? 'selected' : ''; ?>>👵 Seniors</option>
                        <option value="Jeunesse" <?php echo isset($_GET['type']) && $_GET['type'] == 'Jeunesse' ? 'selected' : ''; ?>>👦 Jeunesse</option>
                        <option value="Culture" <?php echo isset($_GET['type']) && $_GET['type'] == 'Culture' ? 'selected' : ''; ?>>🎨 Culture</option>
                        <option value="Urgence" <?php echo isset($_GET['type']) && $_GET['type'] == 'Urgence' ? 'selected' : ''; ?>>🚨 Urgence</option>
                        <option value="Animaux" <?php echo isset($_GET['type']) && $_GET['type'] == 'Animaux' ? 'selected' : ''; ?>>🐾 Animaux</option>
                        <option value="Environnement" <?php echo isset($_GET['type']) && $_GET['type'] == 'Environnement' ? 'selected' : ''; ?>>🌿 Environnement</option>
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

                <div class="filter-group">
                    <label class="filter-label">Statut</label>
                    <select class="filter-select" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="actif" <?php echo isset($_GET['statut']) && $_GET['statut'] == 'actif' ? 'selected' : ''; ?>>Actif</option>
                        <option value="inactif" <?php echo isset($_GET['statut']) && $_GET['statut'] == 'inactif' ? 'selected' : ''; ?>>Inactif</option>
                        <option value="en_attente" <?php echo isset($_GET['statut']) && $_GET['statut'] == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                    </select>
                </div>

                <button type="submit" class="btn-apply">🔍 Appliquer</button>
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-reset">🔄 Réinitialiser</a>
            </div>
        </form>

        <!-- Stats and Info -->
        <div class="stats-bar">
            <div class="total-count">
                👥 Total: 
                <?php 
                if (isset($groupes) && is_array($groupes)) {
                    echo count($groupes) . ' groupe(s)';
                } else {
                    echo '0 groupe(s)';
                }
                ?>
            </div>
        </div>

        <!-- Groupes Table -->
        <div class="table-container">
            <?php if (!isset($groupes) || empty($groupes)): ?>
                <div class="empty-state">
                    <div class="icon">👥</div>
                    <h3>Aucun groupe trouvé</h3>
                    <p>Aucun groupe ne correspond à vos critères de recherche.</p>
                    <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="btn-primary" style="margin-top: 1rem;">➕ Créer le premier groupe</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Région</th>
                            <th>Responsable</th>
                            <th>Membres</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupes as $groupe): ?>
                        <tr>
                            <td><strong>#<?php echo $groupe['id']; ?></strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.2rem;">
                                        <?php 
                                        $icons = [
                                            'Santé' => '🏥',
                                            'Éducation' => '📚',
                                            'Seniors' => '👵',
                                            'Jeunesse' => '👦',
                                            'Culture' => '🎨',
                                            'Urgence' => '🚨',
                                            'Animaux' => '🐾',
                                            'Environnement' => '🌿',
                                            'Religieux' => '🌙',
                                            'Social' => '🤝'
                                        ];
                                        echo $icons[$groupe['type']] ?? '👥';
                                        ?>
                                    </span>
                                    <div>
                                        <strong><?php echo htmlspecialchars($groupe['nom']); ?></strong>
                                        <br>
                                        <small style="color: #666;"><?php echo htmlspecialchars($groupe['email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($groupe['type']); ?></td>
                            <td><?php echo htmlspecialchars($groupe['region']); ?></td>
                            <td><?php echo htmlspecialchars($groupe['responsable']); ?></td>
                            <td>
                                <span style="background: #e1e5e9; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">
                                    <?php echo $groupe['membres_count']; ?> membres
                                </span>
                            </td>
                            <td>
                                <?php if ($groupe['statut'] == 'actif'): ?>
                                    <span class="badge badge-active">Actif</span>
                                <?php elseif ($groupe['statut'] == 'inactif'): ?>
                                    <span class="badge badge-inactive">Inactif</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="/aide_solitaire/controller/groupeC.php?action=view_groupe&id=<?php echo $groupe['id']; ?>" class="btn-icon btn-view" title="Voir">👁️</a>
                                        
                                    </a>
                                    <a href="/aide_solitaire/controller/groupeC.php?action=edit_groupe&id=<?php echo $groupe['id']; ?>" class="btn-icon btn-edit" title="Modifier">✏️</a>
                                        
                                    </a>
                                    <a href="/aide_solitaire/controller/groupeC.php?action=delete_groupe&id=<?php echo $groupe['id']; ?>" class="btn-icon btn-delete" title="Supprimer" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')">🗑️</a>
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