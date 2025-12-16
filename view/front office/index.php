<?php
require_once __DIR__ . '/../../controller/produitC.php';

$produitC = new ProduitC();
$produits = $produitC->listProduits();

// Filtrer uniquement les produits disponibles
$produitsDisponibles = array_filter($produits, function ($produit) {
    return strtolower($produit['statut']) === 'disponible';
});

$totalDisponibles = count($produitsDisponibles);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Marketplace Solidaire</title>
    <link rel="stylesheet" href="formlaire.css">
    <style>
        /* Styles spécifiques pour l'index */
        .welcome-banner {
            background: linear-gradient(135deg, #166e6a, #744ba1);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            color: white;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(22, 110, 106, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-banner h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: white;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .welcome-banner p {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .welcome-stats {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,0.2);
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 500;
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: #166e6a;
            padding: 15px 35px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 2rem;
            color: #0a6661;
            margin-bottom: 10px;
        }

        .section-header p {
            color: #666;
            font-size: 1.05rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .product-img-container {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.95);
            color: #166e6a;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .product-info {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2d3436;
        }

        .product-description {
            color: #636e72;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.5;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .availability {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #27ae60;
            font-weight: 600;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #27ae60;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.2);
        }

        .view-details {
            color: #744ba1;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.3s;
        }

        .view-details:hover {
            gap: 10px;
            color: #166e6a;
        }

        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .empty-state h3 {
            color: #0a6661;
            margin-bottom: 15px;
            font-size: 1.8em;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }

        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .welcome-banner h2 {
                font-size: 1.8rem;
            }

            .welcome-stats {
                flex-direction: column;
                gap: 25px;
            }

            .products-grid {
                grid-template-columns: 1fr;
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
            <a href="index.php" class="nav-item active">
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
                <h1>Marketplace Solidaire</h1>
                <p class="subtitle">Donnez une seconde vie à vos objets</p>
            </div>
            <div class="header-actions">
                <button class="btn-help">❓ Aide</button>
            </div>
        </header>

        <!-- Welcome Banner -->
        <section class="welcome-banner">
            <h2>Bienvenue sur SparkMind</h2>
            <p>Rejoignez notre communauté solidaire. Échangez, donnez et trouvez des trésors uniques tout en préservant notre planète.</p>
            
            <a href="ajouterProduit.php" class="cta-button">
                + Proposer un don
            </a>
            
            <div class="welcome-stats">
                <div class="stat-item">
                    <span class="stat-value"><?= $totalDisponibles ?></span>
                    <span class="stat-label">Produits Disponibles</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">500+</span>
                    <span class="stat-label">Membres Actifs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">100%</span>
                    <span class="stat-label">Gratuit</span>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="produits">
            <div class="section-header">
                <h2>Derniers Arrivages</h2>
                <p>Découvrez les pépites ajoutées par notre communauté</p>
            </div>

            <?php if (empty($produitsDisponibles)): ?>
                <div class="empty-state">
                    <div style="font-size: 5rem; margin-bottom: 20px;">📦</div>
                    <h3>Aucun produit pour le moment</h3>
                    <p>Soyez le premier à proposer un don !</p>
                    <a href="ajouterProduit.php" class="btn-primary" style="text-decoration: none; display: inline-block;">
                        Ajouter un produit
                    </a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($produitsDisponibles as $produit): ?>
                        <article class="product-card">
                            <div class="product-img-container">
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
                                <img src="<?= $photo ?>" alt="<?= htmlspecialchars($produit['title']) ?>" class="product-img"
                                     onerror="this.src='logo.png'">
                                <span class="category-badge"><?= htmlspecialchars($produit['category']) ?></span>
                            </div>
                            
                            <div class="product-info">
                                <h3 class="product-title"><?= htmlspecialchars($produit['title']) ?></h3>
                                <p class="product-description">
                                    <?= nl2br(htmlspecialchars(substr($produit['description'], 0, 100))) ?>...
                                </p>
                                
                                <div class="product-footer">
                                    <div class="availability">
                                        <span class="dot"></span>
                                        Disponible
                                    </div>
                                    <a href="detailsfront.php?id=<?= $produit['id'] ?>" class="view-details">
                                        Voir détails <span>→</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Footer -->
        <div style="text-align: center; padding: 40px 0; color: #666; border-top: 1px solid rgba(0,0,0,0.05); margin-top: 40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </main>
</body>
</html>
