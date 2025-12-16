<?php
require_once '../../controller/produitC.php';
require_once '../../controller/categorieC.php';

$produitC = new ProduitC();
$categorieC = new CategorieC();
$message = '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    $message = "Identifiant du produit manquant.";
} else {
    try {
        $produit = $produitC->showProduit($id);
        if (!$produit) {
            $message = "Produit introuvable.";
        } else {
            // Fetch category name
            $cat = $categorieC->showCategorie($produit['category']);
            $nomCategorie = $cat ? $cat['nomC'] : 'Inconnue';
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Détails du Produit</title>
    <link rel="stylesheet" href="formlaire.css">
    <style>
        /* Styles spécifiques pour la page de détails */
        .detail-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .product-image-section {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info-section {
            padding: 40px;
            display: flex;
            flex-direction: column;
        }

        .product-category-badge {
            display: inline-block;
            background: rgba(22, 110, 106, 0.1);
            color: #166e6a;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            align-self: flex-start;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0a6661;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .product-meta {
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            color: #555;
        }

        .meta-icon {
            font-size: 1.3rem;
        }

        .product-description {
            color: #666;
            line-height: 1.8;
            margin-bottom: 30px;
            font-size: 1.05rem;
        }

        .action-buttons {
            margin-top: auto;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-contact {
            flex: 1;
            background: linear-gradient(135deg, #0d6e69, #6e3da7);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 105, 0.3);
        }

        .btn-back {
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            color: #666;
            background: #e0e0e0;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #d0d0d0;
        }

        .error-state {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .error-state h3 {
            color: #0a6661;
            margin-bottom: 15px;
            font-size: 1.5em;
        }

        .error-state p {
            color: #666;
            margin-bottom: 25px;
        }

        @media (max-width: 1024px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .product-image-section {
                min-height: 400px;
            }
        }

        @media (max-width: 768px) {
            .product-info-section {
                padding: 25px;
            }

            .product-title {
                font-size: 1.5rem;
            }

            .product-meta {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>SparkMind</h2>
            <p>« Quand la pensée devient espoir. »</p>
        </div>
        
        <nav class="nav-menu">
            <a href="index.php" class="nav-item">
                <span>🏠</span>
                <span>Accueil</span>
            </a>
            <a href="liste_produits.php" class="nav-item">
                <span>📦</span>
                <span>Produits</span>
            </a>
            <a href="ajouterProduit.php" class="nav-item">
                <span>➕</span>
                <span>Ajouter un don</span>
            </a>
            <a href="#" class="nav-item">
                <span>👤</span>
                <span>Mon compte</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="info-box">
                <h4>Besoin d'aide ?</h4>
                <p>Contactez notre équipe pour toute question</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div>
                <h1>Détails du Produit</h1>
                <p class="subtitle">Découvrez les informations complètes</p>
            </div>
            <div class="header-actions">
                <button class="btn-help" onclick="window.location.href='index.php'">← Retour</button>
            </div>
        </header>

        <?php if (!empty($message)): ?>
            <div class="error-state">
                <div style="font-size: 4rem; margin-bottom: 20px;">⚠️</div>
                <h3>Oups !</h3>
                <p><?= htmlspecialchars($message) ?></p>
                <button class="btn-contact" onclick="window.location.href='index.php'" style="display: inline-block; width: auto;">
                    Retourner à l'accueil
                </button>
            </div>
        <?php elseif (!empty($produit)): ?>
            <div class="detail-container">
                <div class="detail-grid">
                    <div class="product-image-section">
                        <?php 
                        // Construire le chemin de l'image
                        if (!empty($produit['photo'])) {
                            $photo = htmlspecialchars($produit['photo']);
                            // Si le chemin commence par 'uploads/', ajouter ../../ pour remonter depuis view/front office/
                            if (strpos($photo, 'uploads/') === 0) {
                                $photo = '../../' . $photo;
                            }
                            // Si le chemin ne commence pas par http ou ../../, l'ajouter
                            elseif (strpos($photo, 'http') !== 0 && strpos($photo, '../../') !== 0) {
                                $photo = '../../uploads/' . $photo;
                            }
                        } else {
                            // Image par défaut
                            $photo = 'logo.png';
                        }
                        ?>
                        <img src="<?= $photo ?>" alt="<?= htmlspecialchars($produit['title']) ?>" class="product-image"
                             onerror="this.src='logo.png'">
                    </div>
                    
                    <div class="product-info-section">
                        <span class="product-category-badge"><?= htmlspecialchars($nomCategorie) ?></span>
                        
                        <h2 class="product-title"><?= htmlspecialchars($produit['title']) ?></h2>
                        
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-icon">✨</span>
                                <span><strong>État:</strong> <?= htmlspecialchars($produit['condition']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">📍</span>
                                <span><strong>Statut:</strong> <?= htmlspecialchars($produit['statut']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">📅</span>
                                <span>Ajouté récemment</span>
                            </div>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <img id="qrcode" alt="QR Code" style="max-width: 150px;">
                        </div>

                        <div class="product-description">
                            <h3 style="color: #0a6661; margin-bottom: 15px; font-size: 1.2em;">Description</h3>
                            <?= nl2br(htmlspecialchars($produit['description'])) ?>
                        </div>

                        <div class="action-buttons">
                            <a href="#" class="btn-contact">
                                💬 Contacter le donneur
                            </a>
                            <a href="index.php" class="btn-back">
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div style="text-align: center; padding: 40px 0; color: #666; margin-top: 40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($produit)): ?>
            <?php
                // FULL INFO in QR Code
                $pId = $id;
                $pTitle = $produit['title'];
                $pCat = isset($nomCategorie) ? $nomCategorie : '';
                $pCond = isset($produit['condition']) ? $produit['condition'] : '';
                $pStat = isset($produit['statut']) ? $produit['statut'] : '';
                
                $data = "ID:$pId\nTitre: $pTitle\nCat: $pCat\nCond: $pCond\nStat: $pStat";
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);
            ?>
            var qrImg = document.getElementById("qrcode");
            if(qrImg) qrImg.src = "<?= $qrUrl ?>";
            <?php endif; ?>
        });
    </script>
</body>
</html>
