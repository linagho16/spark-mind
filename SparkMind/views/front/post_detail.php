<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= htmlspecialchars($post['titre'] ?? 'Post') ?> - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>SparkMind</h1>
                <p class="subtitle">Forum de donations</p>
            </div>
        </div>
    </header>
    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="index.php" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour à la liste
        </a>
        <div class="post-item" style="max-width: 800px; margin: 0 auto; width: 100%;">
            <div class="post-header">
                <span class="donation-badge" style="background-color: <?= $post['color'] ?? '#667eea' ?>">
                    <?= $post['icon'] ?? '🎁' ?> <?= htmlspecialchars($post['type_name'] ?? 'Autre') ?>
                </span>
        <div class="menu-container">
            <button class="menu-btn" onclick="toggleMenu(this)">⋮</button>
                    <div class="menu-options">
                        <a href="index.php?action=edit&id=<?= $post['id'] ?>">✏️ Modifier</a>
                        <form method="post" action="index.php?action=delete_front" onsubmit="return confirm('Supprimer ce post ?');">
                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                            <button type="submit" class="delete-link">🗑️ Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (!empty($post['image'])): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image du post" style="width: 100%;" />
            <?php endif; ?>

            <?php if (!empty($post['titre'])): ?>
                <h1 style="font-size: 28px; margin: 20px 0;"><?= htmlspecialchars($post['titre']) ?></h1>
            <?php endif; ?>

            <p style="font-size: 16px; line-height: 1.8; margin: 20px 0;">
                <?= nl2br(htmlspecialchars($post['contenu'])) ?>
            </p>

            <div class="post-footer">
                <span class="date">📅 Publié le <?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?></span>
            </div>

            <!-- SECTION COMMENTAIRES -->
            <div class="comments-section">
                <h2>💬 Commentaires (<?= count($comments) ?>)</h2>

                <!-- FORMULAIRE COMMENTAIRE -->
                <form method="post" action="index.php?action=add_comment" class="comment-form">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <div class="form-group">
                        <textarea name="content" rows="3" placeholder="Écrire un commentaire..." required></textarea>
                    </div>
                    <button type="submit">💬 Commenter</button>
                </form>

                <!-- LISTE DES COMMENTAIRES -->
                <div id="commentsList">
                    <?php if (empty($comments)): ?>
                        <p style="text-align: center; color: #718096; padding: 30px;">
                            Aucun commentaire pour le moment. Soyez le premier à commenter ! ✨
                        </p>
                    <?php else: ?>
                        <?php foreach($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <strong>😊 <?= htmlspecialchars($comment['username']) ?></strong>
                                    <span class="comment-date">
                                        <?= date('d/m/Y à H:i', strtotime($comment['created_at'])) ?>
                                    </span>
                                    <div class="menu-container">
                                        <button class="menu-btn" onclick="toggleMenu(this)" style="padding: 2px 8px; font-size: 16px;">⋮</button>
                                        <div class="menu-options">
                                            <form method="post" action="index.php?action=delete_comment" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                    <button type="submit" class="delete-link">🗑️ Supprimer</button>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleMenu(button) {
            const menu = button.nextElementSibling;
            const allMenus = document.querySelectorAll('.menu-options');
            allMenus.forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            menu.classList.toggle('show');
        }

        document.addEventListener('click', (e) => {
            if (!e.target.classList.contains('menu-btn')) {
                document.querySelectorAll('.menu-options').forEach(m => {
                    m.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>