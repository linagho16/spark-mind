<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Analyse IA - BackOffice</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
    <style>
        .chart-container {
            background: white;
            border: 2px solid #e8d5c4;
            border-radius: 12px;
            padding: 20px;
        }
        .line-chart {
            position: relative;
            height: 300px;
            margin: 30px 0;
            border-left: 2px solid #cbd5e0;
            border-bottom: 2px solid #cbd5e0;
        }
        .chart-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .chart-point {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #2c5f5d;
            border-radius: 50%;
            transform: translate(-50%, 50%);
            cursor: pointer;
        }
        .chart-point:hover {
            width: 12px;
            height: 12px;
            background: #1a4644;
        }
        .chart-tooltip {
            position: absolute;
            background: #2c5f5d;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            pointer-events: none;
            display: none;
        }
        .chart-point:hover + .chart-tooltip {
            display: block;
        }
    </style>
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>🤖 Analyse IA & Tendances</h1>
                <p class="subtitle">Intelligence Artificielle - Analyse des Données</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="index.php?action=admin" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au dashboard
        </a>

        <!-- STATISTIQUES GLOBALES -->
        <div class="post-item">
            <h2>📊 Statistiques Globales</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                <div style="text-align: center; padding: 20px; background: #fef8f3; border-radius: 12px; border: 1px solid #e8d5c4;">
                    <div style="font-size: 36px; font-weight: bold; color: #2c5f5d;"><?= $globalStats['total_posts'] ?></div>
                    <div style="color: #718096;">Posts totaux</div>
                </div>
                <div style="text-align: center; padding: 20px; background: #fef8f3; border-radius: 12px; border: 1px solid #e8d5c4;">
                    <div style="font-size: 36px; font-weight: bold; color: #2c5f5d;"><?= $globalStats['total_comments'] ?></div>
                    <div style="color: #718096;">Commentaires</div>
                </div>
                <div style="text-align: center; padding: 20px; background: #fef8f3; border-radius: 12px; border: 1px solid #e8d5c4;">
                    <div style="font-size: 36px; font-weight: bold; color: #2c5f5d;"><?= $globalStats['posts_this_week'] ?></div>
                    <div style="color: #718096;">Posts cette semaine</div>
                </div>
                <div style="text-align: center; padding: 20px; background: #fef8f3; border-radius: 12px; border: 1px solid #e8d5c4;">
                    <div style="font-size: 36px; font-weight: bold; color: #2c5f5d;"><?= $globalStats['avg_comments_per_post'] ?></div>
                    <div style="color: #718096;">Moy. commentaires/post</div>
                </div>
            </div>
        </div>

        <!-- GRAPHIQUE 30 JOURS -->
        <div class="chart-container">
            <h2>📈 Publications par jour (30 derniers jours)</h2>
            
            <?php if (!empty($trends['daily_chart'])): ?>
                <?php 
                $chartData = $trends['daily_chart'];
                $maxValue = max($chartData) ?: 1;
                $dates = array_keys($chartData);
                $values = array_values($chartData);
                $total = array_sum($values);
                $avg = round($total / count($values), 1);
                ?>
                
                <div class="line-chart">
                    <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                        <polyline
                            fill="none"
                            stroke="#2c5f5d"
                            stroke-width="2"
                            points="
                            <?php 
                            foreach($values as $index => $value) {
                                $x = ($index / (count($values) - 1)) * 100;
                                $y = 100 - (($value / $maxValue) * 90);
                                echo "$x%,$y% ";
                            }
                            ?>"
                        />
                        <polygon
                            fill="rgba(44, 95, 93, 0.1)"
                            points="
                            0%,100% 
                            <?php 
                            foreach($values as $index => $value) {
                                $x = ($index / (count($values) - 1)) * 100;
                                $y = 100 - (($value / $maxValue) * 90);
                                echo "$x%,$y% ";
                            }
                            ?>
                            100%,100%"
                        />
                    </svg>
                    
                    <?php foreach($values as $index => $value): 
                        $x = ($index / (count($values) - 1)) * 100;
                        $y = 100 - (($value / $maxValue) * 90);
                        $date = date('d/m', strtotime($dates[$index]));
                    ?>
                        <div class="chart-point" style="left: <?= $x ?>%; bottom: <?= $y ?>%;"></div>
                        <div class="chart-tooltip" style="left: <?= $x ?>%; bottom: <?= $y + 15 ?>%;">
                            <?= $date ?>: <?= $value ?> post<?= $value > 1 ? 's' : '' ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 20px; text-align: center; color: #718096; font-size: 14px;">
                    📈 Total 30 jours: <strong style="color: #2c5f5d;"><?= $total ?> publications</strong> | 
                    Moyenne: <strong style="color: #2c5f5d;"><?= $avg ?> par jour</strong> |
                    Maximum: <strong style="color: #2c5f5d;"><?= $maxValue ?> posts</strong>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #718096; padding: 40px;">
                    Aucune donnée disponible pour les 30 derniers jours
                </p>
            <?php endif; ?>
        </div>

        <!-- ANALYSE DE SENTIMENTS -->
        <div class="post-item">
            <h2>😊 Analyse des Sentiments (IA)</h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;">
                <div style="text-align: center; padding: 20px; background: #f0fff4; border-radius: 12px; border: 2px solid #68d391;">
                    <div style="font-size: 48px;">😊</div>
                    <div style="font-size: 28px; font-weight: bold; color: #22543d; margin: 10px 0;">
                        <?= $trends['sentiment_stats']['positive'] ?>
                    </div>
                    <div style="color: #718096;">Messages positifs</div>
                </div>
                <div style="text-align: center; padding: 20px; background: #fff5f5; border-radius: 12px; border: 2px solid #fc8181;">
                    <div style="font-size: 48px;">😢</div>
                    <div style="font-size: 28px; font-weight: bold; color: #c53030; margin: 10px 0;">
                        <?= $trends['sentiment_stats']['negative'] ?>
                    </div>
                    <div style="color: #718096;">Messages négatifs</div>
                </div>
                <div style="text-align: center; padding: 20px; background: #fef8f3; border-radius: 12px; border: 2px solid #e8d5c4;">
                    <div style="font-size: 48px;">😐</div>
                    <div style="font-size: 28px; font-weight: bold; color: #2c5f5d; margin: 10px 0;">
                        <?= $trends['sentiment_stats']['neutral'] ?>
                    </div>
                    <div style="color: #718096;">Messages neutres</div>
                </div>
            </div>
        </div>

        <!-- POSTS LES PLUS POPULAIRES -->
        <div class="post-item">
            <h2>🔥 Posts les Plus Populaires (30 derniers jours)</h2>
            <?php if (empty($trends['most_commented'])): ?>
                <p style="text-align: center; color: #718096; padding: 20px;">Pas assez de données</p>
            <?php else: ?>
                <?php foreach ($trends['most_commented'] as $post): ?>
                    <div style="padding: 15px; background: #fef8f3; border-radius: 12px; margin-bottom: 10px; border: 1px solid #e8d5c4;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <span style="font-size: 20px;"><?= $post['icon'] ?></span>
                                <strong><?= htmlspecialchars($post['titre'] ?: substr($post['contenu'], 0, 50) . '...') ?></strong>
                            </div>
                            <div style="background: #2c5f5d; color: white; padding: 5px 15px; border-radius: 20px;">
                                💬 <?= $post['comment_count'] ?> commentaires
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- SUJETS LES PLUS DISCUTÉS -->
        <div class="post-item">
            <h2>📋 Sujets les Plus Discutés</h2>
            <?php foreach ($trends['top_categories'] as $cat): ?>
                <div style="padding: 15px; background: #fef8f3; border-radius: 12px; margin-bottom: 10px; border: 1px solid #e8d5c4;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 24px;"><?= $cat['icon'] ?></span>
                            <strong><?= htmlspecialchars($cat['name']) ?></strong>
                        </div>
                        <div style="background: <?= $cat['color'] ?>; color: white; padding: 5px 15px; border-radius: 20px;">
                            <?= $cat['post_count'] ?> posts
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ACTIVITÉ QUOTIDIENNE DÉTAILLÉE -->
        <div class="post-item">
            <h2>📅 Activité Quotidienne (Détails)</h2>
            <?php if (empty($trends['activity'])): ?>
                <p style="text-align: center; color: #718096; padding: 20px;">Pas de données</p>
            <?php else: ?>
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($trends['activity'] as $day): ?>
                        <div style="padding: 10px; display: flex; justify-content: space-between; border-bottom: 1px solid #e8d5c4;">
                            <span><?= date('d/m/Y', strtotime($day['date'])) ?></span>
                            <span style="font-weight: bold; color: #2c5f5d;"><?= $day['count'] ?> posts</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>




























































































































































































































