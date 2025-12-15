<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= htmlspecialchars($post['titre'] ?? 'Post') ?> - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
    <link rel="stylesheet" href="assets/css/reactions.css" />
    <style>
        /* Like Button Styles (kept for compatibility) */
        .like-section {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            margin-top: 12px;
        }
        
        .like-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.2s ease;
        }
        
        .like-btn:hover {
            transform: scale(1.15);
        }
        
        .like-icon {
            font-size: 20px;
            line-height: 1;
        }
        
        .like-count {
            font-size: 13px;
            color: #718096;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>SparkMind</h1>
                <p class="subtitle">Forum de messageries</p>
            </div>
        </div>
    </header>
    <main class="wrap" style="grid-template-columns: 1fr;">
        <?php if (isset($_SESSION['comment_error'])): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($_SESSION['comment_error']) ?>
    </div>
    <?php unset($_SESSION['comment_error']); ?>
<?php endif; ?>
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

            <!-- REACTIONS SECTION FOR POST -->
            <?php
            require_once __DIR__ . '/../../models/Reaction.php';
            $reactionModel = new Reaction();
            $currentUserId = $_SESSION['user_id'] ?? 1;
            $userReaction = $reactionModel->getUserReaction($currentUserId, $post['id']);
            $reactionCounts = $reactionModel->getReactionCounts($post['id']);
            ?>
            
            <div class="reaction-section" data-post-id="<?= $post['id'] ?>">
                <button type="button" class="reaction-btn" onclick="toggleReactionPicker(this)">
                    <span class="emoji"><?= $userReaction ? Reaction::REACTIONS[$userReaction] : '😊' ?></span>
                    <span>Réagir</span>
                </button>
                
                <div class="reaction-picker">
                    <?php foreach(Reaction::REACTIONS as $type => $emoji): ?>
                        <button type="button" onclick="addReaction('<?= $type ?>', <?= $post['id'] ?>, null, this)" title="<?= ucfirst($type) ?>">
                            <?= $emoji ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <div class="reaction-display" style="<?= empty($reactionCounts) ? 'display: none;' : '' ?>">
                    <?php if (!empty($reactionCounts)): 
                        arsort($reactionCounts);
                        foreach($reactionCounts as $type => $count): 
                            $emoji = Reaction::REACTIONS[$type] ?? '👍';
                    ?>
                        <span class="reaction-item"><?= $emoji ?> <?= $count ?></span>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- SECTION COMMENTAIRES -->
            <div class="comments-section">
                <h2>💬 Commentaires (<?= count($comments) ?>)</h2>

                <!-- FORMULAIRE COMMENTAIRE AVEC STICKERS -->
                <div class="comment-form-wrapper">
                    <form method="post" action="index.php?action=add_comment" class="comment-form">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <div class="form-group">
                            <textarea name="content" id="commentTextarea" rows="3" placeholder="Écrire un commentaire..."></textarea>
                            <button type="button" class="sticker-toggle-btn" onclick="toggleStickerPicker(document.getElementById('commentTextarea'))">
                                😊 Stickers
                            </button>
                        </div>
                        <button type="submit">💬 Commenter</button>
                    </form>
                </div>

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
                    <a href="index.php?action=edit_comment&id=<?= $comment['id'] ?>&post_id=<?= $post['id'] ?>">✏️ Modifier</a>
                    <form method="post" action="index.php?action=delete_comment" onsubmit="return confirm('Supprimer ce commentaire ?');">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit" class="delete-link">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        
        <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
        
        <!-- REACTIONS FOR COMMENT -->
        <?php
        $commentReaction = $reactionModel->getUserReaction($currentUserId, null, $comment['id']);
        $commentReactionCounts = $reactionModel->getReactionCounts(null, $comment['id']);
        ?>
        
        <div class="reaction-section" data-comment-id="<?= $comment['id'] ?>">
            <button type="button" class="reaction-btn" onclick="toggleReactionPicker(this)">
                <span class="emoji"><?= $commentReaction ? Reaction::REACTIONS[$commentReaction] : '😊' ?></span>
                <span>Réagir</span>
            </button>
            
            <div class="reaction-picker">
                <?php foreach(Reaction::REACTIONS as $type => $emoji): ?>
                    <button type="button" onclick="addReaction('<?= $type ?>', null, <?= $comment['id'] ?>, this)" title="<?= ucfirst($type) ?>">
                        <?= $emoji ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <div class="reaction-display" style="<?= empty($commentReactionCounts) ? 'display: none;' : '' ?>">
                <?php if (!empty($commentReactionCounts)): 
                    arsort($commentReactionCounts);
                    foreach($commentReactionCounts as $type => $count): 
                        $emoji = Reaction::REACTIONS[$type] ?? '👍';
                ?>
                    <span class="reaction-item"><?= $emoji ?> <?= $count ?></span>
                <?php endforeach; endif; ?>
            </div>
        </div>
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
            <!-- Floating Chatbot Button -->
        <div id="chatbotButton">💬</div>

        <!-- Chatbot Window -->
        <div id="chatbotBox" class="hidden">
            <div class="chat-header">
                Assistant IA
                <span id="closeChatbot">×</span>
            </div>

            <div id="chatWindow" class="chat-window"></div>

            <div class="chat-input">
                <input type="text" id="userMessage" placeholder="Écris un message…">
                <button onclick="sendMessage()">Envoyer</button>
            </div>
        </div>

        <script src="assets/js/chatbot.js"></script>
        <script src="assets/js/reactions.js"></script>
        <script src="assets/js/validationComment.js"></script>
        
</body>
</html>




































































































































































































