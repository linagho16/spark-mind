<?php
// view/frontoffice/view_groupe.php - View single group
session_start();
require_once __DIR__ . '/../../model/groupemodel.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /aide_solitaire/controller/groupeC.php?action=list&context=frontoffice');
    exit;
}

$groupeId = (int)$_GET['id'];

try {
    $model = new GroupeModel();
    $groupe = $model->getGroupeById($groupeId);
    
    // Check if group exists (NO STATUS CHECK - show all)
    if (!$groupe) {
        header('Location: /aide_solitaire/controller/groupeC.php?action=list&context=frontoffice');
        exit;
    }
    
} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    $groupe = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Groupe - Aide Solidaire</title>
    <style>
        /* Using same base styles with group-specific adjustments */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            background-color: #f5f9ff;
            color: #333;
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #7d5aa6 0%, #b58ce0 50%, #7eddd5 100%);
            color: white;
            padding: 3rem 1rem 4rem;
            text-align: center;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            position: relative;
            overflow: hidden;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 1rem;
        }

        .pigeon-bg {
            position: absolute;
            bottom: 10px;
            right: 5%;
            font-size: 6rem;
            opacity: 0.2;
        }

        /* Main Container */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

        /* Back Button */
        .back-btn {
            display: inline-block;
            margin: -1rem 0 2rem;
            padding: 0.6rem 1.2rem;
            background: #6c757d;
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Group Details Card */
        .details-card {
            background: white;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .group-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .group-icon {
            font-size: 4rem;
        }

        .group-title h2 {
            font-size: 1.8rem;
            color: #7d5aa6;
            margin-bottom: 0.5rem;
        }

        .group-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 1rem;
            background: #e2d9f3;
            color: #4a235a;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-section h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e1e5e9;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .detail-value {
            color: #2c3e50;
            text-align: right;
        }

        /* Contact Items */
        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .contact-icon {
            font-size: 1.5rem;
            color: #7d5aa6;
        }

        .contact-info h4 {
            margin: 0;
            color: #333;
            font-size: 1rem;
        }

        .contact-info p {
            margin: 0.2rem 0 0;
            color: #666;
            font-size: 0.9rem;
        }

        /* Description */
        .description-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
        }

        .description-box h3 {
            color: #333;
            margin-bottom: 1rem;
        }

        .description-content {
            line-height: 1.6;
            color: #495057;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e1e5e9;
        }

        .action-btn {
            flex: 1;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-primary {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
        }

        .action-secondary {
            background: #f1f3f5;
            color: #333;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Join Form */
        .join-form {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-top: 2rem;
        }

        .join-form h3 {
            color: #7d5aa6;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
        }

        /* Footer */
        .footer {
            background: #7d5aa6;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .group-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            .details-grid {
                grid-template-columns: 1fr;
            }
            .action-buttons {
                flex-direction: column;
            }
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>👥 Détails du Groupe</h1>
        <p>Découvrez cette initiative solidaire et rejoignez-la</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <main class="container">
        <?php if ($groupe): ?>
            <!-- Back Button -->
           <a href="/aide_solitaire/controller/groupeC.php?action=list&context=frontoffice" class="back-btn"></a>
            
            <!-- Group Details -->
            <div class="details-card">
                <!-- Header -->
                <div class="group-header">
                    <div class="group-icon">
                        <?php 
                        $icons = [
                            'Santé' => '🏥',
                            'Éducation' => '📚',
                            'Seniors' => '👵',
                            'Jeunesse' => '👦',
                            'Culture' => '🎨',
                            'Urgence' => '🚨',
                            'Animaux' => '🐾',
                            'Environnement' => '🌿',
                            'Religieux' => '🌙',
                            'Social' => '🤝'
                        ];
                        echo $icons[$groupe['type']] ?? '👥';
                        ?>
                    </div>
                    <div class="group-title">
                        <h2><?php echo htmlspecialchars($groupe['nom']); ?></h2>
                        <div class="group-subtitle">
                            <span class="type-badge"><?php echo htmlspecialchars($groupe['type']); ?></span>
                            <span class="status-badge status-<?php echo $groupe['statut']; ?>">
                                <?php 
                                $statusText = [
                                    'actif' => 'Actif',
                                    'en_attente' => 'En attente',
                                    'inactif' => 'Inactif'
                                ];
                                echo $statusText[$groupe['statut']] ?? $groupe['statut'];
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Details Grid -->
                <div class="details-grid">
                    <div class="detail-section">
                        <h3>📋 Informations du groupe</h3>
                        <div class="detail-item">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($groupe['type']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Région:</span>
                            <span class="detail-value">📍 <?php echo htmlspecialchars($groupe['region']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date de création:</span>
                            <span class="detail-value">
                                <?php echo isset($groupe['created_at']) ? date('d/m/Y', strtotime($groupe['created_at'])) : 'Non spécifiée'; ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Statut:</span>
                            <span class="detail-value">
                                <span class="status-badge status-<?php echo $groupe['statut']; ?>">
                                    <?php echo $statusText[$groupe['statut']] ?? $groupe['statut']; ?>
                                </span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h3>👤 Responsable</h3>
                        <div class="contact-item">
                            <div class="contact-icon">👤</div>
                            <div class="contact-info">
                                <h4><?php echo htmlspecialchars($groupe['responsable']); ?></h4>
                                <p>Responsable du groupe</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">📧</div>
                            <div class="contact-info">
                                <h4>Email</h4>
                                <p><?php echo htmlspecialchars($groupe['email']); ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">📞</div>
                            <div class="contact-info">
                                <h4>Téléphone</h4>
                                <p><?php echo htmlspecialchars($groupe['telephone']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <?php if (!empty($groupe['description'])): ?>
                <div class="description-box">
                    <h3>📝 Description du groupe</h3>
                    <div class="description-content">
                        <?php echo nl2br(htmlspecialchars($groupe['description'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="mailto:<?php echo htmlspecialchars($groupe['email']); ?>" class="action-btn action-primary">
                        📧 Contacter le responsable
                    </a>
                    <a href="browse_groupes.php?type=<?php echo urlencode($groupe['type']); ?>" class="action-btn action-secondary">
                        🔍 Voir d'autres groupes similaires
                    </a>
                </div>
            </div>
            
            <!-- Join Form -->
            <div class="join-form">
                <h3>🤝 Rejoindre ce groupe</h3>
                <p>Intéressé par ce groupe ? Laissez vos coordonnées et le responsable vous contactera.</p>
                
                <form id="joinForm" style="margin-top: 1.5rem;">
                    <div class="form-group">
                        <input type="text" class="form-input" placeholder="Votre nom" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-input" placeholder="Votre email" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-input" placeholder="Votre téléphone">
                    </div>
                    <div class="form-group">
                        <textarea class="form-input" placeholder="Pourquoi souhaitez-vous rejoindre ce groupe ?" rows="3"></textarea>
                    </div>
                    <button type="submit" class="action-btn action-primary" style="width: 100%;">
                        ✨ Envoyer ma demande
                    </button>
                </form>
            </div>
            
            <!-- Related Groups -->
            <div class="join-form">
                <h3>💡 Groupes similaires</h3>
                <p>Découvrez d'autres groupes dans la même région ou du même type.</p>
                <div style="margin-top: 1rem;">
                    <a href="/aide_solitaire/controller/groupeC.php?action=list&context=frontoffice&region=<?php echo urlencode($groupe['region']); ?>" class="action-btn action-secondary">

                        📍 Groupes à <?php echo htmlspecialchars($groupe['region']); ?>
                    </a>
                    <a href="/aide_solitaire/controller/groupeC.php?action=list&context=frontoffice&type=<?php echo urlencode($groupe['type']); ?>" class="action-btn action-secondary"></a>
                        👥 Tous les groupes <?php echo htmlspecialchars($groupe['type']); ?>
                    </a>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Error State -->
            <div class="details-card" style="text-align: center; padding: 3rem;">
                <h2 style="color: #dc3545; margin-bottom: 1rem;">❌ Groupe non trouvé</h2>
                <p>Le groupe que vous cherchez n'existe pas ou n'est plus disponible.</p>
                <div style="margin-top: 2rem;">
                    <a href="/aide_solitaire/controller/groupeC.php?action=list&context=frontoffice" class="action-btn action-primary"></a>
                        🔍 Parcourir les groupes
                    </a>
                    <a href="/aide_solitaire/view/frontoffice/index.php" class="action-btn action-secondary">

                        🏠 Retour à l'accueil
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Aide Solidaire - Ensemble, créons des communautés fortes ! ❤️</p>
    </footer>

    <script>
        // Join form submission
        document.getElementById('joinForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('✅ Votre demande a été envoyée !\n\nLe responsable du groupe vous contactera dans les prochains jours.');
            this.reset();
        });

        <?php if (isset($error)): ?>
        alert('Erreur: <?php echo $error; ?>');
        <?php endif; ?>
    </script>
</body>
</html>