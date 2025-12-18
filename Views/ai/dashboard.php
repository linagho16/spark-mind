<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Analyse IA - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
    <style>
        .ai-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .ai-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1px solid #e8d5c4;
        }
        
        .ai-card h3 {
            margin-bottom: 20px;
            color: #2c5f5d;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .stat-big {
            font-size: 48px;
            font-weight: bold;
            color: #2c5f5d;
            text-align: center;
            margin: 20px 0;
        }
        
        .progress-bar {
            background: #e8d5c4;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .keyword-tag {
            display: inline-block;
            padding: 8px 16px;
            background: #fef8f3;
            border: 1px solid #e8d5c4;
            border-radius: 20px;
            margin: 5px;
            font-size: 14px;
        }
        
        .urgent-item {
            background: #fff5f5;
            border: 1px solid #fc8181;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 10px;
        }
        
        .trend-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .trend-forte { background: #fca5a5; color: #991b1b; }
        .trend-moyenne { background: #fed7aa; color: #9a3412; }
        .trend-faible { background: #bfdbfe; color: #1e3a8a; }
    </style>
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>🤖 Analyse IA - SparkMind</h1>
                <p class="subtitle">Intelligence Artificielle pour la modération et l'analyse des tendances</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr; max-width: 1400px;">
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <a href="index.php" class="btn-view">← Retour au forum</a>
            <a href="index.php?action=ai_test" class="btn-comment">🧪 Tester l'IA</a>
        </div>

        <!-- MODULE 1: FILTRAGE PROPOS HAINEUX -->
        <div class="ai-card" style="background: linear-gradient(135deg, #fff5f5 0%, #fecaca 100%);">
            <h3>🛡️ Module 1 : Filtrage des Propos Haineux</h3>
            <div class="stat-big"><?= $hateSpeechBlocked ?></div>
            <p style="text-align: center; color: #991b1b; font-weight: 600;">
                Contenus inappropriés bloqués sur <?= $totalAnalyzed ?> analysés
            </p>
            <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 12px;">
                <p style="font-size: 14px; line-height: 1.8;">
                    ✅ <strong>Fonctionnalité :</strong> Détection automatique de mots interdits, insultes, incitation à la haine<br>
                    ✅ <strong>Action :</strong> Blocage immédiat avec message d'erreur explicite<br>
                    ✅ <strong>Statut :</strong> <span style="color: #059669; font-weight: bold;">Opérationnel</span>
                </p>
            </div>
        </div>

        <!-- STATISTIQUES GLOBALES -->
        <div class="ai-grid">
            <div class="ai-card">
                <h3>📊 Statistiques Globales</h3>
                <div style="padding: 20px 0;">
                    <div style="margin-bottom: 20px;">
                        <strong>Total analysé</strong>
                        <div class="stat-big" style="font-size: 36px;"><?= $totalAnalyzed ?></div>
                        <p style="text-align: center; color: #718096; font-size: 14px;">Posts et commentaires</p>
                    </div>
                </div>
            </div>

            <div class="ai-card">
                <h3>😊 Sentiment Général</h3>
                <div class="stat-big" style="font-size: 32px; text-transform: capitalize;">
                    <?php
                    $icons = ['positif' => '😊', 'négatif' => '😔', 'neutre' => '😐', 'urgent' => '⚡'];
                    echo $icons[$dominantSentiment] . ' ' . $dominantSentiment;
                    ?>
                </div>
                <div style="font-size: 14px; color: #718096;">
                    <?php foreach ($sentimentCounts as $type => $count): ?>
                        <div style="margin: 8px 0;">
                            <strong style="text-transform: capitalize;"><?= $type ?>:</strong> 
                            <?= $count ?> (<?= $sentimentPercentages[$type] ?>%)
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- MODULE 2: SUGGESTION DE CATÉGORIES -->
        <div class="ai-card">
            <h3>📋 Module 2 : Suggestions de Catégories Automatiques</h3>
            <p style="margin-bottom: 20px; color: #718096;">
                L'IA analyse le contenu et suggère automatiquement la catégorie appropriée (auto-sélection si confiance > 60%)
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <?php foreach ($categorySuggestions as $category => $count): ?>
                    <div style="background: #fef8f3; padding: 15px; border-radius: 12px; border: 2px solid #e8d5c4;">
                        <div style="font-weight: bold; margin-bottom: 8px;"><?= htmlspecialchars($category) ?></div>
                        <div style="font-size: 24px; color: #2c5f5d; font-weight: bold;"><?= $count ?></div>
                        <div style="font-size: 12px; color: #718096;">suggestions</div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 12px;">
                <p style="font-size: 14px; line-height: 1.8;">
                    ✅ <strong>Fonctionnalité :</strong> Analyse des mots-clés pour identifier le type de donation<br>
                    ✅ <strong>Action :</strong> Suggestion + auto-sélection si confiance élevée<br>
                    ✅ <strong>Statut :</strong> <span style="color: #059669; font-weight: bold;">Opérationnel</span>
                </p>
            </div>
        </div>

        <!-- MODULE 3: ANALYSE DES TENDANCES SOCIALES -->
        <div class="ai-card">
            <h3>📈 Module 3 : Analyse des Tendances Sociales</h3>
            <p style="margin-bottom: 20px; color: #718096;">
                Identification des besoins récurrents et des thématiques émergentes dans la communauté
            </p>
            
            <?php if (!empty($socialTrends)): ?>
                <h4 style="margin: 20px 0 15px 0;">🔥 Besoins Dominants</h4>
                <?php foreach ($socialTrends as $trend): ?>
                    <div style="background: white; padding: 15px; border-radius: 12px; margin-bottom: 10px; border: 2px solid #e8d5c4;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="font-size: 16px;"><?= htmlspecialchars($trend['category']) ?></strong>
                            <span class="trend-badge trend-<?= $trend['trend'] ?>">
                                <?= strtoupper($trend['trend']) ?>
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; color: #718096; margin-bottom: 8px;">
                            <span><?= $trend['count'] ?> demandes</span>
                            <span><?= $trend['percentage'] ?>% du total</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $trend['percentage'] ?>%; background: <?= 
                                $trend['trend'] === 'forte' ? '#ef4444' : 
                                ($trend['trend'] === 'moyenne' ? '#f97316' : '#3b82f6') 
                            ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 30px; color: #718096;">
                    Pas encore assez de données pour identifier des tendances sociales
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 12px;">
                <p style="font-size: 14px; line-height: 1.8;">
                    ✅ <strong>Fonctionnalité :</strong> Agrégation des demandes par catégorie et analyse temporelle<br>
                    ✅ <strong>Action :</strong> Identification des besoins urgents et récurrents<br>
                    ✅ <strong>Statut :</strong> <span style="color: #059669; font-weight: bold;">Opérationnel</span>
                </p>
            </div>
        </div>

        <!-- POSTS URGENTS -->
        <?php if (!empty($urgentPosts)): ?>
            <div class="ai-card">
                <h3>⚡ Demandes Urgentes Détectées</h3>
                <?php foreach ($urgentPosts as $urgent): ?>
                    <div class="urgent-item">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <strong><?= htmlspecialchars($urgent['titre'] ?: 'Post #' . $urgent['id']) ?></strong>
                            <span style="font-size: 12px; color: #991b1b;"><?= $urgent['type'] ?></span>
                        </div>
                        <p style="font-size: 14px; color: #4a5568; margin-bottom: 8px;">
                            <?= htmlspecialchars($urgent['contenu']) ?>...
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 12px; color: #718096;">
                                <?= date('d/m/Y H:i', strtotime($urgent['date'])) ?>
                            </span>
                            <a href="index.php?action=show&id=<?= $urgent['id'] ?>" class="btn-view" style="padding: 6px 12px; font-size: 12px;">
                                Voir →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- MOTS-CLÉS POPULAIRES -->
        <div class="ai-card">
            <h3>🔑 Mots-clés les Plus Fréquents</h3>
            <div style="margin-top: 15px;">
                <?php foreach ($topKeywords as $keyword => $count): ?>
                    <span class="keyword-tag">
                        <?= htmlspecialchars($keyword) ?> <strong style="color: #2c5f5d;">(<?= $count ?>)</strong>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RECOMMANDATIONS -->
        <?php if (!empty($recommendations)): ?>
            <div class="ai-card" style="background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);">
                <h3>💡 Recommandations IA</h3>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($recommendations as $rec): ?>
                        <li style="padding: 12px 0; border-bottom: 1px solid #99f6e4; line-height: 1.6;">
                            <?= htmlspecialchars($rec) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </main>
</body>
</html>