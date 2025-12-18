<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Modifier le commentaire - SparkMind</title>
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
        <a href="index.php?action=show&id=<?= $comment['post_id'] ?>" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au post
        </a>

        <div class="post" style="max-width: 700px; margin: 0 auto; width: 100%;">
            <h2>✏️ Modifier le commentaire</h2>

            <form method="post" action="index.php?action=update_comment">
                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                <input type="hidden" name="post_id" value="<?= $comment['post_id'] ?>">

                <div class="form-group">
                    <label>Commentaire *</label>
                    <textarea name="content" rows="4" required><?= htmlspecialchars($comment['content']) ?></textarea>
                </div>

                <button type="submit">💾 Enregistrer les modifications</button>
            </form>
        </div>
    </main>
</body>
</html>