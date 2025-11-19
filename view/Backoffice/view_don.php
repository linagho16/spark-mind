<?php
// view/Backoffice/view_don.php - Add this at the VERY TOP
session_start();

// Check if don data is passed (from controller)
if (!isset($don)) {
    // If accessed directly, redirect to dons list
    header('Location: /aide_solitaire/index.php?action=dons&message=not_found');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Don - Dashboard Admin</title>
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
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .don-icon {
            font-size: 3rem;
            margin-right: 1.5rem;
        }

        .don-title h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .don-title .don-id {
            color: #7f8c8d;
            font-size: 1.1rem;
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

        .badge {
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
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

        .photo-section {
            margin: 2rem 0;
        }

        .photo-section h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .photo-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .photo-item {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .photo-item img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
        }

        .no-photo {
            color: #6c757d;
            font-style: italic;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
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

            .details-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-back, .btn-edit {
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
            <a href="/aide_solitaire/index.php?action=dashboard" class="nav-item">
                <span class="icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="/aide_solitaire/index.php?action=dons" class="nav-item">
                <span class="icon">🎁</span>
                <span>Dons</span>
            </a>
            <a href="/aide_solitaire/index.php?action=statistics" class="nav-item">
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
                <h1>Détails du Don</h1>
                <p>Informations complètes sur ce don</p>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <div class="avatar">👤</div>
                </div>
            </div>
        </header>

        <!-- Details Container -->
        <div class="details-container">
            <!-- Donation Header -->
            <div class="details-header">
                <div class="don-icon">
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
                <div class="don-title">
                    <h2><?php echo htmlspecialchars($don['type_don']); ?></h2>
                    <div class="don-id">Don #<?php echo $don['id']; ?></div>
                </div>
            </div>

            <!-- Main Details Grid -->
            <div class="details-grid">
                <!-- Basic Information -->
                <div class="detail-section">
                    <h3>Informations de base</h3>
                    <div class="detail-item">
                        <span class="detail-label">ID:</span>
                        <span class="detail-value">#<?php echo $don['id']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Type:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($don['type_don']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Quantité:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($don['quantite']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Région:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($don['region']); ?></span>
                    </div>
                    <?php if (!empty($don['etat_object'])): ?>
                    <div class="detail-item">
                        <span class="detail-label">État:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Date Information -->
                <div class="detail-section">
                    <h3>Dates et statut</h3>
                    <div class="detail-item">
                        <span class="detail-label">Date de création:</span>
                        <span class="detail-value"><?php echo date('d/m/Y à H:i', strtotime($don['date_don'])); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Dernière modification:</span>
                        <span class="detail-value">
                            <?php echo isset($don['date_modification']) ? date('d/m/Y à H:i', strtotime($don['date_modification'])) : 'Jamais'; ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Statut:</span>
                        <span class="detail-value">
                            <span class="badge badge-active"><?php echo ucfirst($don['statut'] ?? 'actif'); ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="description-section">
                <h3>Description détaillée</h3>
                <div class="description-content">
                    <?php echo nl2br(htmlspecialchars($don['description'])); ?>
                </div>
            </div>

            <!-- Photos -->
            <div class="photo-section">
                <h3>Photos</h3>
                <div class="photo-container">
                    <?php if (!empty($don['photos'])): ?>
                        <div class="photo-item">
                            <?php if (file_exists($don['photos'])): ?>
                                <img src="/aide_solitaire/<?php echo $don['photos']; ?>" alt="Photo du don" onerror="this.style.display='none'">
                                <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #666;">
                                    <?php echo basename($don['photos']); ?>
                                </div>
                            <?php else: ?>
                                <div class="no-photo">
                                    ❌ Photo non trouvée<br>
                                    <small><?php echo basename($don['photos']); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-photo">
                            📷 Aucune photo disponible
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="/aide_solitaire/index.php?action=dons" class="btn-back">
                    ← Retour à la liste
                </a>
                <a href="/aide_solitaire/index.php?action=edit_don&id=<?php echo $don['id']; ?>" class="btn-edit">
                    ✏️ Modifier ce don
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