<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Groupe - Dashboard Admin</title>
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

        /* DETAILS CONTAINER */
        .details-container {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin: 1rem 0;
            max-width: 900px;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .groupe-icon {
            font-size: 3rem;
            margin-right: 1.5rem;
        }

        .groupe-title h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .groupe-title .groupe-id {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .groupe-status {
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-section h3 {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e1e5e9;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .detail-value {
            color: #2c3e50;
            text-align: right;
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-icon {
            font-size: 1.2rem;
        }

        .description-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1.5rem 0;
        }

        .description-section h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .description-content {
            line-height: 1.6;
            color: #495057;
        }

        .no-description {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .btn-back {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
        }

        .btn-edit {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .details-grid {
                grid-template-columns: 1fr;
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

            .details-container {
                padding: 1.5rem;
            }

            .details-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .groupe-status {
                align-self: flex-start;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-back, .btn-edit, .btn-delete {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo">
            <h2>🕊️ Admin</h2>
        </div>
        <nav class="nav-menu">
            <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="nav-item">
                <span class="icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="/aide_solitaire/controller/donC.php?action=dons" class="nav-item">
                <span class="icon">🎁</span>
                <span>Dons</span>
            </a>
            <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="nav-item active">
                <span class="icon">👥</span>
                <span>Groupes</span>
            </a>
            <a href="/aide_solitaire/controller/donC.php?action=statistics" class="nav-item">
                <span class="icon">📈</span>
                <span>Statistiques</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="#" class="nav-item">
                <span class="icon">🚪</span>
                <span>Déconnexion</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1>Détails du Groupe</h1>
                <p>Informations complètes sur ce groupe de solidarité</p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-back">
                    ← Retour à la liste
                </a>
            </div>
        </header>

        <!-- Details Container -->
        <div class="details-container">
            <!-- Groupe Header -->
            <div class="details-header">
                <div style="display: flex; align-items: center;">
                    <div class="groupe-icon">
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
                    </div>
                    <div class="groupe-title">
                        <h2><?php echo htmlspecialchars($groupe['nom']); ?></h2>
                        <div class="groupe-id">Groupe #<?php echo $groupe['id']; ?></div>
                    </div>
                </div>
                <div class="groupe-status status-<?php echo $groupe['statut'] ?? 'actif'; ?>">
                    <?php 
                    $statusText = [
                        'actif' => 'Actif',
                        'inactif' => 'Inactif', 
                        'en_attente' => 'En attente'
                    ];
                    echo $statusText[$groupe['statut']] ?? 'Actif';
                    ?>
                </div>
            </div>

            <!-- Main Details Grid -->
            <div class="details-grid">
                <!-- Groupe Information -->
                <div class="detail-section">
                    <h3>Informations du groupe</h3>
                    <div class="detail-item">
                        <span class="detail-label">ID:</span>
                        <span class="detail-value">#<?php echo $groupe['id']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nom:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($groupe['nom']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Type:</span>
                        <span class="detail-value">
                            <?php 
                            $typeIcon = $icons[$groupe['type']] ?? '👥';
                            echo $typeIcon . ' ' . htmlspecialchars($groupe['type']);
                            ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Région:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($groupe['region']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Statut:</span>
                        <span class="detail-value">
                            <span class="groupe-status status-<?php echo $groupe['statut'] ?? 'actif'; ?>" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;">
                                <?php echo $statusText[$groupe['statut']] ?? 'Actif'; ?>
                            </span>
                        </span>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="detail-section">
                    <h3>Contact et responsable</h3>
                    <div class="detail-item">
                        <span class="detail-label">Responsable:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($groupe['responsable']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">
                            <div class="contact-info">
                                <span class="contact-icon">📧</span>
                                <?php echo htmlspecialchars($groupe['email']); ?>
                            </div>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Téléphone:</span>
                        <span class="detail-value">
                            <div class="contact-info">
                                <span class="contact-icon">📞</span>
                                <?php echo htmlspecialchars($groupe['telephone']); ?>
                            </div>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date de création:</span>
                        <span class="detail-value"><?php echo isset($groupe['created_at']) ? date('d/m/Y à H:i', strtotime($groupe['created_at'])) : 'Non disponible'; ?></span>
                    </div>
                    <?php if (isset($groupe['membres_count'])): ?>
                    <div class="detail-item">
                        <span class="detail-label">Nombre de membres:</span>
                        <span class="detail-value"><?php echo $groupe['membres_count']; ?> membres</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Description -->
            <div class="description-section">
                <h3>Description du groupe</h3>
                <div class="description-content">
                    <?php if (!empty($groupe['description'])): ?>
                        <?php echo nl2br(htmlspecialchars($groupe['description'])); ?>
                    <?php else: ?>
                        <div class="no-description">
                            📝 Aucune description disponible pour ce groupe
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-back">
                    ← Retour à la liste
                </a>
                <a href="/aide_solitaire/controller/groupeC.php?action=edit_groupe&id=<?php echo $groupe['id']; ?>" class="btn-edit">
                    ✏️ Modifier ce groupe
                </a>
                <a href="/aide_solitaire/controller/groupeC.php?action=delete_groupe&id=<?php echo $groupe['id']; ?>" class="btn-delete"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')">
                    🗑️ Supprimer ce groupe
                </a>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide messages after 5 seconds (if any)
        setTimeout(function() {
            const messages = document.querySelectorAll('.message-success, .message-error');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>