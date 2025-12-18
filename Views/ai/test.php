<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/AIHelper.php';

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_text'])) {
    $aiHelper = new AIHelper();
    $result = $aiHelper->analyze($_POST['test_text']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Test IA - SparkMind</title>
    <link rel="stylesheet" href="../../assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="../../assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>🧪 Test de l'IA Hugging Face</h1>
                <p class="subtitle">Testez les 3 modules d'intelligence artificielle</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr; max-width: 900px; margin: 0 auto;">
        <a href="../../index.php?action=admin" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au dashboard
        </a>

        <div class="post">
            <h2>📝 Testez un message</h2>
            <p style="color: #718096; margin-bottom: 20px;">
                Saisissez un texte pour tester les 3 modules IA : filtrage, suggestion de catégorie, et analyse de sentiment.
            </p>

            <form method="post">
                <div class="form-group">
                    <label>Texte à analyser</label>
                    <textarea name="test_text" rows="6" placeholder="Ex: Je suis harcelé à l'école, j'ai besoin d'aide..." required><?= htmlspecialchars($_POST['test_text'] ?? '') ?></textarea>
                </div>
                <button type="submit">🔍 Analyser avec l'IA</button>
            </form>
        </div>

        <?php if ($result): ?>
            <!-- MODULE 1: HATE SPEECH -->
            <div class="post-item" style="background: <?= $result['hate_speech']['is_hate_speech'] ? '#fff5f5' : '#f0fff4' ?>; border: 2px solid <?= $result['hate_speech']['is_hate_speech'] ? '#fc8181' : '#68d391' ?>;">
                <h3>🛡️ Module 1 : Filtrage des Propos Haineux</h3>
                <div style="margin: 20px 0;">
                    <p><strong>Résultat :</strong> 
                        <?php if ($result['hate_speech']['is_hate_speech']): ?>
                            <span style="color: #c53030; font-weight: bold;">⛔ CONTENU INAPPROPRIÉ DÉTECTÉ</span>
                        <?php else: ?>
                            <span style="color: #22543d; font-weight: bold;">✅ CONTENU ACCEPTABLE</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Confiance :</strong> <?= $result['hate_speech']['confidence'] ?>%</p>
                    <?php if ($result['hate_speech']['reason']): ?>
                        <p><strong>Raison :</strong> <?= htmlspecialchars($result['hate_speech']['reason']) ?></p>
                    <?php endif; ?>
                </div>
                <div style="background: white; padding: 15px; border-radius: 12px;">
                    <p style="font-size: 14px; color: #4a5568;">
                        <?php if ($result['hate_speech']['is_hate_speech']): ?>
                            ⚠️ Ce message serait <strong>bloqué</strong> lors de la publication.
                        <?php else: ?>
                            ✅ Ce message serait <strong>autorisé</strong> à être publié.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- MODULE 2: CATEGORY SUGGESTION -->
            <div class="post-item">
                <h3>📋 Module 2 : Suggestion de Catégorie</h3>
                <div style="margin: 20px 0;">
                    <?php if ($result['suggested_category']['suggested_category_id']): ?>
                        <p><strong>Catégorie suggérée :</strong> 
                            <span style="color: #2c5f5d; font-weight: bold; font-size: 18px;">
                                <?= htmlspecialchars($result['suggested_category']['category_name']) ?>
                            </span>
                        </p>
                        <p><strong>Confiance :</strong> <?= $result['suggested_category']['confidence'] ?>%</p>
                        <p><strong>Explication :</strong> <?= htmlspecialchars($result['suggested_category']['explanation']) ?></p>
                        
                        <div style="margin-top: 15px; padding: 15px; background: #fef8f3; border-radius: 12px; border: 2px solid #e8d5c4;">
                            <?php if ($result['suggested_category']['confidence'] > 60): ?>
                                <p style="color: #059669; font-weight: bold;">
                                    ✅ Confiance élevée → La catégorie serait <strong>auto-sélectionnée</strong>
                                </p>
                            <?php elseif ($result['suggested_category']['confidence'] > 30): ?>
                                <p style="color: #d97706; font-weight: bold;">
                                    💡 Confiance moyenne → L'utilisateur recevrait une <strong>suggestion</strong>
                                </p>
                            <?php else: ?>
                                <p style="color: #718096; font-weight: bold;">
                                    ℹ️ Confiance faible → Aucune suggestion affichée
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #718096;">Aucune catégorie claire identifiée dans ce texte.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MODULE 3: SENTIMENT ANALYSIS -->
            <div class="post-item" style="background: #eff6ff; border: 2px solid #93c5fd;">
                <h3>😊 Module 3 : Analyse de Sentiment</h3>
                <div style="margin: 20px 0;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                        <div style="background: white; padding: 15px; border-radius: 12px;">
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">Type</div>
                            <div style="font-size: 20px; font-weight: bold; text-transform: capitalize; color: #2c5f5d;">
                                <?php
                                $icons = ['positif' => '😊', 'négatif' => '😔', 'neutre' => '😐', 'urgent' => '⚡'];
                                echo $icons[$result['sentiment']['type']] . ' ' . $result['sentiment']['type'];
                                ?>
                            </div>
                        </div>
                        <div style="background: white; padding: 15px; border-radius: 12px;">
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">Score</div>
                            <div style="font-size: 20px; font-weight: bold; color: #2c5f5d;">
                                <?= $result['sentiment']['score'] ?>%
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 12px;">
                        <p style="font-size: 14px; line-height: 1.8; color: #4a5568;">
                            <?php if ($result['sentiment']['type'] === 'urgent'): ?>
                                ⚡ <strong>Message urgent détecté</strong> - Ce post pourrait être priorisé dans l'affichage pour recevoir de l'aide rapidement.
                            <?php elseif ($result['sentiment']['type'] === 'positif'): ?>
                                😊 <strong>Sentiment positif</strong> - Message constructif qui contribue à une bonne ambiance sur le forum.
                            <?php elseif ($result['sentiment']['type'] === 'négatif'): ?>
                                😔 <strong>Sentiment négatif</strong> - L'utilisateur exprime une difficulté ou frustration.
                            <?php else: ?>
                                😐 <strong>Sentiment neutre</strong> - Message factuel sans charge émotionnelle particulière.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- KEYWORDS -->
            <div class="post-item">
                <h3>🔑 Mots-clés Extraits</h3>
                <div style="margin-top: 15px;">
                    <?php if (!empty($result['keywords'])): ?>
                        <?php foreach ($result['keywords'] as $keyword): ?>
                            <span style="display: inline-block; padding: 8px 16px; background: #fef8f3; border: 1px solid #e8d5c4; border-radius: 20px; margin: 5px; font-size: 14px;">
                                <?= htmlspecialchars($keyword) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #718096;">Aucun mot-clé significatif détecté.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MODERATION FLAG -->
            <div class="post-item" style="background: <?= $result['needs_moderation'] ? '#fffbeb' : '#f0fdf4' ?>; border: 2px solid <?= $result['needs_moderation'] ? '#fbbf24' : '#86efac' ?>;">
                <h3>🚨 Besoin de Modération</h3>
                <p style="margin-top: 15px; font-size: 16px;">
                    <?php if ($result['needs_moderation']): ?>
                        <strong style="color: #92400e;">⚠️ OUI</strong> - Ce message nécessiterait une revue manuelle (propos haineux, spam potentiel, ou contenu suspect).
                    <?php else: ?>
                        <strong style="color: #14532d;">✅ NON</strong> - Ce message peut être publié sans intervention manuelle.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- EXEMPLES -->
        <div class="post-item" style="background: #f8fafc; border: 2px solid #cbd5e1;">
            <h3>💡 Exemples de Tests</h3>
            <ul style="list-style: none; padding: 0; line-height: 2;">
                <li>🔴 <strong>Propos haineux :</strong> "Je déteste ces imbéciles"</li>
                <li>🟢 <strong>Harcèlement :</strong> "Je suis harcelé à l'école tous les jours"</li>
                <li>🟡 <strong>Pression familiale :</strong> "Mes parents me forcent à me marier"</li>
                <li>🔴 <strong>Violence :</strong> "Mon conjoint me frappe, je suis en danger"</li>
                <li>🟢 <strong>Sentiment positif :</strong> "Merci beaucoup, tout va mieux maintenant"</li>
                <li>🟢 <strong>Échec scolaire :</strong> "J'ai raté mes examens, je ne sais pas quoi faire"</li>
            </ul>
        </div>
    </main>
</body>
</html>