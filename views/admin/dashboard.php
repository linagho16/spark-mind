<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>BackOffice - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>SparkMind - BackOffice</h1>
                <p class="subtitle">Panneau d'administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <a href="index.php" class="btn-view">🏠 Retour au site</a>
            <a href="index.php?action=admin_posts" class="btn-view">📝 Posts</a>
            <a href="index.php?action=admin_comments" class="btn-view">💬 Commentaires</a>
            <a href="index.php?action=admin_types" class="btn-view">📋 Sujet</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <!-- Card Posts -->
            <div class="post-item" style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">📝</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;"><?= $totalPosts ?></h2>
                <p style="color: #718096;">Posts publiés</p>
                <a href="index.php?action=admin_posts" class="btn-view" style="margin-top: 15px;">Gérer</a>
            </div>

            <!-- Card Commentaires -->
            <div class="post-item" style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">💬</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;"><?= $totalComments ?></h2>
                <p style="color: #718096;">Commentaires</p>
                <a href="index.php?action=admin_comments" class="btn-view" style="margin-top: 15px;">Gérer</a>
            </div>

            <!-- Card Types -->
            <div class="post-item" style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;"><?= $totalTypes ?></h2>
                <p style="color: #718096;">Sujet</p>
                <a href="index.php?action=admin_types" class="btn-view" style="margin-top: 15px;">Voir</a>
            </div>
        </div>
    </main>
</body>
</html>