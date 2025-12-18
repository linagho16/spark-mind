<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Notifications - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
    <style>
        .notification-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border: 2px solid #e8d5c4;
            transition: all 0.3s ease;
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }
        
        .notification-item.unread {
            background: #fef8f3;
            border-color: #ec7546;
        }
        
        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .notification-icon {
            font-size: 32px;
            flex-shrink: 0;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-message {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2d3748;
        }
        
        .notification-post {
            font-size: 14px;
            color: #718096;
            margin-bottom: 8px;
        }
        
        .notification-time {
            font-size: 12px;
            color: #a0aec0;
        }
        
        .notification-actions {
            display: flex;
            gap: 10px;
        }
        
        .mark-read-btn {
            padding: 6px 12px;
            background: #2c5f5d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .mark-read-btn:hover {
            background: #234d4b;
        }
        
        .no-notifications {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }
        
        .no-notifications-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .mark-all-read {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>🔔 Notifications</h1>
                <p class="subtitle">Vos dernières notifications</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr; max-width: 900px; margin: 0 auto;">
        <a href="index.php" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au forum
        </a>

        <?php if (!empty($notifications)): ?>
            <div class="mark-all-read">
                <a href="index.php?action=mark_all_read" class="btn-view">
                    ✅ Tout marquer comme lu
                </a>
            </div>

            <?php foreach ($notifications as $notif): ?>
                <div class="notification-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                    <div class="notification-icon">
                        <?php if ($notif['type'] === 'like'): ?>
                            ❤️
                        <?php elseif ($notif['type'] === 'comment'): ?>
                            💬
                        <?php else: ?>
                            📢
                        <?php endif; ?>
                    </div>
                    
                    <div class="notification-content">
                        <div class="notification-message">
                            <?= htmlspecialchars($notif['message']) ?>
                        </div>
                        
                        <?php if ($notif['post_titre']): ?>
                            <div class="notification-post">
                                Post: <strong><?= htmlspecialchars($notif['post_titre']) ?></strong>
                            </div>
                        <?php elseif ($notif['post_contenu']): ?>
                            <div class="notification-post">
                                Post: <?= htmlspecialchars(substr($notif['post_contenu'], 0, 60)) ?>...
                            </div>
                        <?php endif; ?>
                        
                        <div class="notification-time">
                            <?= date('d/m/Y à H:i', strtotime($notif['created_at'])) ?>
                        </div>
                        
                        <div class="notification-actions" style="margin-top: 10px;">
                            <a href="index.php?action=show&id=<?= $notif['post_id'] ?>" class="mark-read-btn">
                                👁️ Voir le post
                            </a>
                            
                            <?php if (!$notif['is_read']): ?>
                                <form method="post" action="index.php?action=mark_notification_read" style="margin: 0;">
                                    <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                                    <input type="hidden" name="redirect" value="index.php?action=notifications">
                                    <button type="submit" class="mark-read-btn" style="background: #e8d5c4; color: #2d3748;">
                                        ✓ Marquer comme lu
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        <?php else: ?>
            <div class="no-notifications">
                <div class="no-notifications-icon">🔔</div>
                <h2>Aucune notification</h2>
                <p>Vous n'avez pas encore de notifications.</p>
                <p>Lorsque quelqu'un commente ou aime vos posts, vous serez notifié ici!</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>




























































