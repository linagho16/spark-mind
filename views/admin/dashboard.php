<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>BackOffice - SparkMind</title>

    <style>
      /* ===== SparkMind theme (identique au dernier) ===== */
      *{margin:0;padding:0;box-sizing:border-box;}

      body{
        min-height:100vh;
        font-family:'Poppins',system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
        color:#1A464F;
        background:
          radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
          radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
          #FBEDD7;
      }

      /* ===== Header (top-bar style) ===== */
      .toppage{
        position: sticky;
        top: 0;
        z-index: 90;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        background: rgba(251, 237, 215, 0.96);
        border-bottom: 1px solid rgba(0,0,0,.06);
        padding: 10px 24px;
      }

      .logo-title{
        display:flex;
        align-items:center;
        gap:12px;
      }

      .logo-title img{
        width:44px;
        height:44px;
        border-radius:50%;
        object-fit:cover;
      }

      .title-block h1{
        font-family:'Playfair Display',serif;
        font-size:20px;
        margin:0;
        color:#1A464F;
      }

      .subtitle{
        margin:0;
        font-size:12px;
        color:#1A464F;
        opacity:.8;
      }

      /* ===== Main wrapper like admin-main ===== */
      .wrap{
        max-width:1100px;
        margin: 28px auto 40px;
        padding: 0 18px 30px;
      }

      /* ===== Buttons (btn-nav look) ===== */
      .btn-view{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        border:none;
        cursor:pointer;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        background:#1A464F;
        color:#fff;
        text-decoration:none;
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        transition: transform .15s ease, filter .15s ease, box-shadow .15s ease;
      }

      .btn-view:hover{
        transform: translateY(-1px);
        filter: brightness(1.02);
      }

      .btn-view:active{ transform: translateY(0); }

      /* ===== Cards (same admin card style) ===== */
      .stat-card{
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 18px;
        padding: 16px;
        text-align:center;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        transition: transform .15s ease, box-shadow .15s ease;
      }

      .stat-card:hover{
        transform: translateY(-4px);
        box-shadow: 0 14px 26px rgba(0,0,0,0.12);
      }

      .stat-card h2{
        font-family:'Playfair Display',serif;
        color:#1A464F !important;
        letter-spacing:.2px;
      }

      /* ✅ manquant : liens qui contiennent une card */
      .stat-card-link{
        text-decoration:none;
        color:inherit;
        display:block;
      }

      .stat-card-link:focus{
        outline: none;
      }

      /* ===== Chart container (soft card like chart-container) ===== */
      .chart-container{
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 18px 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        margin-top: 20px;
        border: 1px solid rgba(0,0,0,.06);
      }

      .chart-container h2{
        margin:0 0 10px;
        font-family:'Playfair Display', serif;
        color:#1A464F;
      }

      /* ===== Bar chart (same vibe as evolution chart) ===== */
      .bar-chart{
        display:flex;
        align-items:flex-end;
        gap:10px;
        height:220px;
        margin-top:14px;
        padding: 10px 6px 28px;
      }

      .bar{
        flex:1;
        min-width: 46px;
        background:#1A464F;
        border-radius: 10px 10px 0 0;
        position: relative;
        transition: filter .15s ease, transform .15s ease;
      }

      .bar:hover{
        filter: brightness(0.95);
        transform: translateY(-2px);
      }

      .bar-label{
        position:absolute;
        bottom:-24px;
        left:50%;
        transform: translateX(-50%);
        font-size: 12px;
        color:#555;
        white-space: nowrap;
      }

      .bar-value{
        position:absolute;
        top:-22px;
        left:50%;
        transform: translateX(-50%);
        font-size: 12px;
        font-weight: 700;
        color:#1A464F;
      }

      /* ===== post-item (quick access cards) ===== */
      .post-item{
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 18px 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        border: 1px solid rgba(0,0,0,.06);
      }

      .post-item h3{
        margin:0 0 10px;
        font-family:'Playfair Display', serif;
        color:#1A464F;
      }

      /* override des couleurs inline trop "turquoise" */
      strong[style*="color: #2c5f5d"]{
        color:#1A464F !important;
      }

      p[style*="color: #718096"],
      span[style*="color: #718096"]{
        color:#6B5F55 !important;
      }

      /* ✅ petit fix pour les gros tableaux/sections dans wrap */
      main.wrap > *{
        max-width: 100%;
      }

      /* ===== Responsive ===== */
      @media (max-width: 768px){
        .toppage{ padding: 10px 14px; }
        .wrap{ margin: 18px auto 30px; padding: 0 14px 20px; }
        .logo-title img{ width:40px;height:40px; }
        .title-block h1{ font-size:18px; }
      }
    </style>
</head>

<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" />

            <div class="title-block">
                <h1>SparkMind - BackOffice</h1>
                <p class="subtitle">Panneau d'administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <!-- Navigation -->
        <div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
            <a href="/sparkmind_mvc_100percent/index.php?page=admin_users" class="btn-view">🏠 Retour au site</a>

            <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_posts" class="btn-view">📝 Posts</a>
            <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_comments" class="btn-view">💬 Commentaires</a>
            <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_types" class="btn-view">📋 Sujets</a>
            <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_ai" class="btn-view">🤖 Analyse IA</a>

        </div>

        <!-- Cards statistiques -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
<!-- Card Posts -->
        <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_posts" class="stat-card-link">
            <div class="stat-card">
                <div style="font-size: 48px; margin-bottom: 15px;">📝</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;">
                    <?= $totalPosts ?>
                </h2>
                <p style="color: #718096;">Posts publiés</p>
                <span class="btn-view" style="margin-top: 15px;">Gérer</span>
            </div>
        </a>

        <!-- Card Commentaires -->
        <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_comments" class="stat-card-link">
            <div class="stat-card">
                <div style="font-size: 48px; margin-bottom: 15px;">💬</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;">
                    <?= $totalComments ?>
                </h2>
                <p style="color: #718096;">Commentaires</p>
                <span class="btn-view" style="margin-top: 15px;">Gérer</span>
            </div>
        </a>

        <!-- Card Types -->
        <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum_types" class="stat-card-link">
            <div class="stat-card">
                <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;">
                    <?= $totalTypes ?>
                </h2>
                <p style="color: #718096;">Sujets</p>
                <span class="btn-view" style="margin-top: 15px;">Voir</span>
            </div>
        </a>


        <!-- Graphique publications par jour (7 derniers jours) -->
        <div class="chart-container">
            <h2 style="margin-bottom: 20px;">📊 Publications par jour (7 derniers jours)</h2>

            <?php if (empty($dailyStats)): ?>
                <p style="text-align: center; color: #718096; padding: 40px;">
                    Aucune publication ces 7 derniers jours
                </p>
            <?php else: ?>
                <div class="bar-chart">
                    <?php
                    $maxCount = max(array_column($dailyStats, 'count'));
                    $maxCount = $maxCount > 0 ? $maxCount : 1;

                    $dailyStats = array_reverse($dailyStats);

                    foreach($dailyStats as $stat):
                        $height = ($stat['count'] / $maxCount) * 100;
                        $date = date('d/m', strtotime($stat['date']));
                    ?>
                        <div class="bar" style="height: <?= $height ?>%;">
                            <div class="bar-value"><?= $stat['count'] ?></div>
                            <div class="bar-label"><?= $date ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 50px; text-align: center; color: #718096; font-size: 14px;">
                    <?php
                    $total = array_sum(array_column($dailyStats, 'count'));
                    $avg = round($total / count($dailyStats), 1);
                    ?>
                    📈 Total: <strong style="color: #2c5f5d;"><?= $total ?> publications</strong> |
                    Moyenne: <strong style="color: #2c5f5d;"><?= $avg ?> par jour</strong>
                </div>
            <?php endif; ?>
        </div>

        <!-- Accès rapide -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
            <div class="post-item">
                <h3>🚀 Actions rapides</h3>
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <a href="index.php?action=admin_posts" class="btn-view">📝 Voir tous les posts</a>
                    <a href="index.php?action=admin_comments" class="btn-view">💬 Modérer les commentaires</a>
                    <a href="index.php?action=ai_dashboard" class="btn-view">🤖 Analyse IA complète</a>
                </div>
            </div>

            <div class="post-item">
                <h3>📈 Statistiques</h3>
                <div style="margin-top: 15px;">
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e8d5c4;">
                        <span>Posts totaux:</span>
                        <strong style="color: #2c5f5d;"><?= $totalPosts ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e8d5c4;">
                        <span>Commentaires:</span>
                        <strong style="color: #2c5f5d;"><?= $totalComments ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span>Catégories:</span>
                        <strong style="color: #2c5f5d;"><?= $totalTypes ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
