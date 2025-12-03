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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="formlaire.css">
    <style>
        /* Modern Overrides to match the template but look modern */
        body {
            font-family: 'Outfit', sans-serif;
            /* Background color is inherited from formlaire.css (#FBEDD7) */
        }
        
        .container {
            max-width: 1200px;
        }
        
        /* Navigation */
        .nav-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        
        .nav-btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
            color: #555;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 2px solid transparent;
        }
        
        .nav-btn.active {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.3);
        }
        
        .nav-btn:hover:not(.active) {
            transform: translateY(-2px);
            border-color: #1f8c87;
            color: #1f8c87;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            border-radius: 30px;
            padding: 60px 40px;
            text-align: center;
            color: white;
            margin-bottom: 60px;
            box-shadow: 0 15px 40px rgba(31, 140, 135, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-title {
            font-size: 3rem;
            margin-bottom: 20px;
            color: white;
            font-weight: 800;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 500;
        }

        /* Grid Layout */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
        }
        
        .product-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0,0,0,0.02);
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .product-img-container {
            position: relative;
            height: 240px;
            overflow: hidden;
        }
        
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.95);
            color: #1f8c87;
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
            color: #7d5aa6;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.3s;
        }
        
        .view-details:hover {
            gap: 10px;
            color: #1f8c87;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2rem; }
            .hero-stats { flex-direction: column; gap: 30px; }
            .nav-container { flex-direction: column; }
            .nav-btn { text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header from template -->
        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="SparkMind Logo" onerror="this.src='../../view/back office/logo.png'">
            </div>
            <h1>SparkMind</h1>
            <p class="subtitle">« Quand la pensée devient espoir. »</p>
        </div>

        <!-- Navigation -->
        <nav class="nav-container">
            <a href="index.php" class="nav-btn active">Accueil</a>
            <a href="#produits" class="nav-btn">Nos Produits</a>
            <a href="ajouterProduit.php" class="nav-btn">Faire un don</a>
            <a href="#" class="nav-btn">Connexion</a>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <h2 class="hero-title">Donnez une seconde vie<br>à vos objets préférés</h2>
            <p class="hero-subtitle">Rejoignez notre communauté solidaire. Échangez, donnez et trouvez des trésors uniques tout en préservant notre planète.</p>
            
            <div style="margin-bottom: 40px;">
                <a href="ajouterProduit.php" style="background: white; color: #1f8c87; padding: 15px 35px; border-radius: 50px; text-decoration: none; font-weight: 700; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    + Proposer un don
                </a>
            </div>
            
            <div class="hero-stats">
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
            <div style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.2rem; color: #1f8c87; margin-bottom: 10px;">Derniers Arrivages</h2>
                <p style="color: #666; font-size: 1.1rem;">Découvrez les pépites ajoutées par notre communauté</p>
            </div>

            <?php if (empty($produitsDisponibles)): ?>
                <div class="form-card" style="text-align: center; padding: 60px;">
                    <div style="font-size: 5rem; margin-bottom: 20px;">📦</div>
                    <h3 style="color: #333; margin-bottom: 15px;">Aucun produit pour le moment</h3>
                    <p style="color: #666; margin-bottom: 30px;">Soyez le premier à proposer un don !</p>
                    <a href="ajouterProduit.php" class="btn-submit" style="text-decoration: none; display: inline-block;">Ajouter un produit</a>
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
                                    $photo = '../../view/back office/logo.png';
                                }
                                ?>
                                <img src="<?= $photo ?>" alt="<?= htmlspecialchars($produit['title']) ?>" class="product-img"
                                     onerror="this.src='../../view/back office/logo.png'">
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
    </div>
</body>
</html>
