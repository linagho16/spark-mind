<?php
// view/frontoffice/view_don.php - View single donation
session_start();
require_once __DIR__ . '/../../model/donmodel.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /aide_solitaire/controller/donC.php?action=list&context=frontoffice');
    exit;
}

$donId = (int)$_GET['id'];

try {
    $model = new DonModel();
    $don = $model->getDonById($donId);
    
    // Check if donation exists (NO STATUS CHECK - show all)
    if (!$don) {
    header('Location: browse_dons.php');
    exit;
}
    
} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    $don = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Don - Aide Solidaire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #FBEDD7;
            color: #333;
            line-height: 1.6;
        }

        /* Header - Dashboard Style */
        .header {
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            color: white;
            padding: 3rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            border-radius: 0 0 20px 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .pigeon-bg {
            position: absolute;
            bottom: 20px;
            right: 5%;
            font-size: 8rem;
            opacity: 0.15;
            z-index: 1;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

        /* Back Button - Dashboard Style */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            color: #1f8c87;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .back-link:hover {
            background: rgba(31, 140, 135, 0.1);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        /* Donation Details Card */
        .details-card {
            background: white;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            margin-bottom: 2.5rem;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .don-header {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
            padding-bottom: 1.8rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .don-icon {
            font-size: 5rem;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(31,140,135,0.1), rgba(126,221,213,0.15));
        }

        .don-title h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }

        .don-meta {
            color: #666;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
            margin-bottom: 2.5rem;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .detail-section h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid #f1f3f5;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-value {
            color: #2c3e50;
            text-align: right;
            font-weight: 500;
        }

        /* Description */
        .description-box {
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.5), rgba(31, 140, 135, 0.05));
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            border-left: 5px solid #1f8c87;
        }

        .description-box h3 {
            color: #333;
            margin-bottom: 1.2rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .description-content {
            line-height: 1.7;
            color: #495057;
            font-size: 1.05rem;
        }

        /* Status Badge - Dashboard Style */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 1px solid #b1dfbb;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border: 1px solid #ffecb5;
        }

        .status-inactif {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border: 1px solid #f1b0b7;
        }

        /* Contact Section */
        .contact-box {
            background: linear-gradient(135deg, rgba(232, 244, 253, 0.3), rgba(31, 140, 135, 0.1));
            border-radius: 15px;
            padding: 2rem;
            margin: 2.5rem 0;
            border-left: 5px solid #1f8c87;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .contact-box h3 {
            color: #1f8c87;
            margin-bottom: 1.2rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-box p {
            margin-bottom: 0.8rem;
            color: #555;
        }

        /* Action Buttons - Dashboard Style */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.2rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #f1f3f5;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            padding: 1.2rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-primary {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
        }

        .action-secondary {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
        }

        .action-tertiary {
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Related Items */
        .related-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            margin-top: 2.5rem;
        }

        .related-section h3 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .related-section p {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
        }

        .related-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .related-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .related-link:nth-child(2) {
            background: linear-gradient(135deg, #ec7546, #f4a261);
        }

        .related-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* Error State */
        .error-state {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .error-state h2 {
            color: #dc3545;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .error-state p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 3rem;
        }

        .footer p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        /* ID Badge */
        .id-badge {
            background: linear-gradient(135deg, #ec7546, #f4a261);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                max-width: 100%;
                padding: 0 1.5rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 2rem 1rem 3rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .don-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
            
            .don-icon {
                width: 80px;
                height: 80px;
                font-size: 3.5rem;
            }
            
            .don-title h2 {
                font-size: 1.6rem;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .related-links {
                flex-direction: column;
            }
            
            .related-link {
                width: 100%;
                justify-content: center;
            }
            
            .details-card,
            .related-section {
                padding: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem 2rem;
            }
            
            .don-meta {
                flex-direction: column;
                gap: 0.5rem;
                align-items: center;
            }
            
            .detail-item {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .detail-label,
            .detail-value {
                text-align: left;
                width: 100%;
            }
            
            .description-box,
            .contact-box {
                padding: 1.5rem;
            }
            
            .action-btn {
                padding: 1rem;
                font-size: 0.95rem;
            }
            
            .footer {
                padding: 2rem 1rem;
            }
        }

        /* Icon animations */
        .don-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>🎁 Détails du Don</h1>
        <p>Informations complètes sur ce don</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <main class="container">
        <?php if ($don): ?>
            <!-- Back Button -->
            <a href="/aide_solitaire/view/frontoffice/browse_dons.php?action=list&context=frontoffice" class="back-link"><span>←Retour aux dons</span></a>
                
            </a>
            
            <!-- Donation Details -->
            <div class="details-card">
                <!-- Header -->
                <div class="don-header">
                    <div class="don-icon">
                        <?php 
                        $icons = [
                            'Vêtements' => '👕',
                            'Nourriture' => '🍞',
                            'Médicaments' => '💊',
                            'Équipement' => '🔧',
                            'Argent' => '💰',
                            'Services' => '🤝',
                            'Autre' => '🎁'
                        ];
                        echo $icons[$don['type_don']] ?? '🎁';
                        ?>
                    </div>
                    <div class="don-title">
                        <h2><?php echo htmlspecialchars($don['type_don']); ?></h2>
                        <div class="don-meta">
                            <span class="id-badge">#<?php echo $don['id']; ?></span>
                            <span class="status-badge status-<?php echo $don['statut']; ?>">
                                <?php 
                                $statusText = [
                                    'actif' => 'Actif',
                                    'en_attente' => 'En attente',
                                    'inactif' => 'Inactif'
                                ];
                                echo $statusText[$don['statut']] ?? $don['statut'];
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Details Grid -->
                <div class="details-grid">
                    <div class="detail-section">
                        <h3><span>📋</span> Informations de base</h3>
                        <div class="detail-item">
                            <span class="detail-label"><span>🏷️</span> Type:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($don['type_don']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><span>📦</span> Quantité:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($don['quantite']); ?> unités</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><span>📍</span> Région:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($don['region']); ?></span>
                        </div>
                        <?php if (!empty($don['etat_object'])): ?>
                        <div class="detail-item">
                            <span class="detail-label"><span>⭐</span> État:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="detail-section">
                        <h3><span>📅</span> Informations temporelles</h3>
                        <div class="detail-item">
                            <span class="detail-label"><span>📅</span> Date de création:</span>
                            <span class="detail-value"><?php echo date('d/m/Y à H:i', strtotime($don['date_don'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><span>🔄</span> Statut:</span>
                            <span class="detail-value">
                                <span class="status-badge status-<?php echo $don['statut']; ?>">
                                    <?php echo $statusText[$don['statut']] ?? $don['statut']; ?>
                                </span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><span>👁️</span> Visibilité:</span>
                            <span class="detail-value">
                                <?php echo $don['statut'] == 'actif' ? 'Public' : 'En attente de validation'; ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <?php if (!empty($don['description'])): ?>
                <div class="description-box">
                    <h3><span>📝</span> Description</h3>
                    <div class="description-content">
                        <?php echo nl2br(htmlspecialchars($don['description'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Contact Information -->
                <div class="contact-box">
                    <h3><span>📞</span> Comment contacter ?</h3>
                    <p>Les informations de contact sont protégées pour garantir la confidentialité des donateurs.</p>
                    <p>Notre équipe de coordination sert d'intermédiaire pour mettre en relation les donateurs et les bénéficiaires.</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/aide_solitaire/view/Frontoffice/browse_dons.php?action=list&context=frontoffice&type_don=<?php echo urlencode($don['type_don']); ?>" class="action-btn action-primary"><span>🔍 Voir d'autres dons similaires</span></a>
                        
                        
                    </a>
                    <a href="/aide_solitaire/view/Frontoffice/create_don.php?action=create&context=frontoffice" class="action-btn action-secondary"><span>🎁 Faire un don similaire</span></a>
                        
                        
                    </a>
                </div>
            </div>
            
            <!-- Related Items -->
            <div class="related-section">
                <h3><span>💡</span> Suggestions</h3>
                <p>Découvrez d'autres opportunités de solidarité qui pourraient vous intéresser.</p>
                <div class="related-links">
                    <a href="/aide_solitaire/controller/donC.php?action=list&context=frontoffice&region=<?php echo urlencode($don['region']); ?>" class="related-link">
                        <span>📍</span>
                        <span>Voir les dons à <?php echo htmlspecialchars($don['region']); ?></span>
                    </a>
                    <a href="/aide_solitaire/controller/donC.php?action=list&context=frontoffice&type_don=<?php echo urlencode($don['type_don']); ?>" class="related-link"><span>🎁 Voir tous les dons <?php echo htmlspecialchars($don['type_don']); ?></span></a>
                        
                        
                    </a>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Error State -->
            <div class="error-state">
                <h2><span>❌</span> Don non trouvé</h2>
                <p>Le don que vous recherchez n'existe pas ou a été retiré.</p>
                <div class="action-buttons" style="margin-top: 2rem; border-top: none; padding-top: 0;">
                    <a href="/aide_solitaire/controller/donC.php?action=list&context=frontoffice" class="action-btn action-primary"></a>
                        <span>🔍</span>
                        <span>Parcourir les dons</span>
                    </a>
                    <a href="/aide_solitaire/view/frontoffice/index.php" class="action-btn action-tertiary"></a>
                        <span>🏠</span>
                        <span>Retour à l'accueil</span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Aide Solidaire - Merci pour votre intérêt pour la solidarité ❤️</p>
    </footer>

    <?php if (isset($error)): ?>
    <script>
        alert('Erreur: <?php echo $error; ?>');
    </script>
    <?php endif; ?>
</body>
</html>