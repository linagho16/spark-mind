<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Gestion des Posts - BackOffice</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>Gestion des Posts</h1>
                <p class="subtitle">BackOffice - Administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="index.php?action=admin" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au dashboard
        </a>

        <div class="post-item">
            <h2>📝 Liste des posts (<?= count($posts) ?>)</h2>
            
            <?php if (empty($posts)): ?>
                <p style="text-align: center; color: #718096; padding: 30px;">Aucun post pour le moment.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #fef8f3; border-bottom: 2px solid #e8d5c4;">
                                <th style="padding: 12px; text-align: left;">ID</th>
                                <th style="padding: 12px; text-align: left;">Type</th>
                                <th style="padding: 12px; text-align: left;">Titre</th>
                                <th style="padding: 12px; text-align: left;">Contenu</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($posts as $post): ?>
                                <tr style="border-bottom: 1px solid #e8d5c4;">
                                    <td style="padding: 12px;"><?= $post['id'] ?></td>
                                    <td style="padding: 12px;">
                                        <span class="donation-badge" style="background-color: <?= $post['color'] ?? '#667eea' ?>; font-size: 11px;">
                                            <?= $post['icon'] ?? '🎁' ?> <?= htmlspecialchars($post['type_name'] ?? 'Autre') ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($post['titre'] ?: '-') ?></td>
                                    <td style="padding: 12px; max-width: 300px;">
                                        <?= substr(htmlspecialchars($post['contenu']), 0, 80) ?>...
                                    </td>
                                    <td style="padding: 12px; font-size: 12px; color: #718096;">
                                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="index.php?action=show&id=<?= $post['id'] ?>" class="btn-comment" style="font-size: 12px;">👁️ Voir</a>
                                            <form method="post" action="index.php?action=admin_delete_post" onsubmit="return confirm('Supprimer ce post ?');" style="margin: 0;">
                                                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                                <button type="submit" style="background: #e53e3e; padding: 6px 12px; font-size: 12px; width: auto;">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>




























































































































































































































