<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Gestion des Commentaires - BackOffice</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>Gestion des Commentaires</h1>
                <p class="subtitle">BackOffice - Administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="index.php?action=admin" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au dashboard
        </a>

        <div class="post-item">
            <h2>💬 Liste des commentaires (<?= count($comments) ?>)</h2>
            
            <?php if (empty($comments)): ?>
                <p style="text-align: center; color: #718096; padding: 30px;">Aucun commentaire pour le moment.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #fef8f3; border-bottom: 2px solid #e8d5c4;">
                                <th style="padding: 12px; text-align: left;">ID</th>
                                <th style="padding: 12px; text-align: left;">Post</th>
                                <th style="padding: 12px; text-align: left;">Contenu</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($comments as $comment): ?>
                                <tr style="border-bottom: 1px solid #e8d5c4;">
                                    <td style="padding: 12px;"><?= $comment['id'] ?></td>
                                    <td style="padding: 12px;">
                                        <a href="index.php?action=show&id=<?= $comment['post_id'] ?>" style="color: #2c5f5d; text-decoration: underline;">
                                            <?= htmlspecialchars($comment['post_titre'] ?: 'Post #' . $comment['post_id']) ?>
                                        </a>
                                    </td>
                                    <td style="padding: 12px; max-width: 400px;">
                                        <?= htmlspecialchars($comment['content']) ?>
                                    </td>
                                    <td style="padding: 12px; font-size: 12px; color: #718096;">
                                        <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <form method="post" action="index.php?action=admin_delete_comment" onsubmit="return confirm('Supprimer ce commentaire ?');" style="margin: 0;">
                                            <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                            <button type="submit" style="background: #e53e3e; padding: 6px 12px; font-size: 12px; width: auto;">🗑️ Supprimer</button>
                                        </form>
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