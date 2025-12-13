<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>BackOffice - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
    <style>
        .stat-card {
            background: white;
            border: 2px solid #e8d5c4;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .chart-container {
            background: white;
            border: 2px solid #e8d5c4;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 200px;
            margin-top: 20px;
        }
        .bar {
            flex: 1;
            background: linear-gradient(to top, #2c5f5d, #4a9d9a);
            border-radius: 4px 4px 0 0;
            position: relative;
            min-height: 5px;
            transition: all 0.3s;
        }
        .bar:hover {
            background: linear-gradient(to top, #1a4644, #2c5f5d);
        }
        .bar-label {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: #718096;
            white-space: nowrap;
        }
        .bar-value {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            font-weight: bold;
            color: #2c5f5d;
        }
    </style>
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
        <!-- Navigation -->
        <div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
            <a href="index.php" class="btn-view">🏠 Retour au site</a>
            <a href="index.php?action=admin_posts" class="btn-view">📝 Posts</a>
            <a href="index.php?action=admin_comments" class="btn-view">💬 Commentaires</a>
            <a href="index.php?action=admin_types" class="btn-view">📋 Sujets</a>
            <a href="index.php?action=ai_dashboard" class="btn-view">🤖 Analyse IA</a>
        </div>

        <!-- Cards statistiques -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <!-- Card Posts -->
            <div class="stat-card">
                <div style="font-size: 48px; margin-bottom: 15px;">📝</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;"><?= $totalPosts ?></h2>
                <p style="color: #718096;">Posts publiés</p>
                <a href="index.php?action=admin_posts" class="btn-view" style="margin-top: 15px;">Gérer</a>
            </div>

            <!-- Card Commentaires -->
            <div class="stat-card">
                <div style="font-size: 48px; margin-bottom: 15px;">💬</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;"><?= $totalComments ?></h2>
                <p style="color: #718096;">Commentaires</p>
                <a href="index.php?action=admin_comments" class="btn-view" style="margin-top: 15px;">Gérer</a>
            </div>

            <!-- Card Types -->
            <div class="stat-card">
                <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                <h2 style="font-size: 36px; margin-bottom: 10px; color: #2c5f5d;"><?= $totalTypes ?></h2>
                <p style="color: #718096;">Sujets</p>
                <a href="index.php?action=admin_types" class="btn-view" style="margin-top: 15px;">Voir</a>
            </div>
        </div>

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
                    // Trouver le maximum pour normaliser les barres
                    $maxCount = max(array_column($dailyStats, 'count'));
                    $maxCount = $maxCount > 0 ? $maxCount : 1;
                    
                    // Inverser pour afficher du plus ancien au plus récent
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