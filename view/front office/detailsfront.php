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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="formlaire.css">
    <style>
        :root {
            --primary: #1f8c87;
            --primary-dark: #166662;
            --secondary: #7d5aa6;
            --accent: #ec7546;
            --bg-light: #f8f9fa;
            --text-dark: #2d3436;
            --text-light: #636e72;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 32px rgba(0,0,0,0.12);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Header Styles from formulaire.css */
        .header {
            text-align: center;
            padding: 30px 0;
        }

        .logo {
            width: 140px;
            height: 140px;
            margin: 0 auto 15px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header h1 {
            color: #1f8c87;
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #7d5aa6;
            font-style: italic;
            font-size: 1.1em;
        }

        /* Detail Card Styles */
        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .detail-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0,0,0,0.03);
        }

        @media (min-width: 768px) {
            .detail-card {
                flex-direction: row;
            }
        }

        .product-image-section {
            flex: 1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-height: 500px;
        }

        .product-info-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            display: inline-block;
            background: rgba(31, 140, 135, 0.1);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
            align-self: flex-start;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .product-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            color: var(--text-light);
        }

        .meta-icon {
            font-size: 1.2rem;
        }

        .product-description {
            color: var(--text-light);
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
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
            border: none;
            cursor: pointer;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.4);
        }

        .btn-back {
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            color: var(--text-light);
            background: #f1f2f6;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #e1e2e6;
            color: var(--text-dark);
        }

        .nav-button {
            display: inline-block;
            padding: 10px 20px;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            border: 1px solid #eee;
        }

        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: var(--secondary);
        }
    </style>
</head>
<body>
    <div class="container detail-container">
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" class="nav-button">
                ← Retour à l'accueil
            </a>
        </div>

        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="SparkMind Logo" onerror="this.src='../../view/back office/logo.png'">
            </div>
            <h1>SparkMind</h1>
            <p class="subtitle">« Quand la pensée devient espoir. »</p>
        </div>

        <?php if (!empty($message)): ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 20px; box-shadow: var(--shadow-sm); margin-top: 30px;">
                <div style="font-size: 3rem; margin-bottom: 20px;">⚠️</div>
                <h3 style="color: var(--text-dark); margin-bottom: 10px;">Oups !</h3>
                <p style="color: var(--text-light);"><?= htmlspecialchars($message) ?></p>
                <a href="index.php" class="btn-contact" style="display: inline-block; margin-top: 20px; width: auto;">Retourner à l'accueil</a>
            </div>
        <?php elseif (!empty($produit)): ?>
            <div class="detail-card">
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
                        $photo = '../../view/back office/logo.png';
                    }
                    ?>
                    <img src="<?= $photo ?>" alt="<?= htmlspecialchars($produit['title']) ?>" class="product-image"
                         onerror="this.src='../../view/back office/logo.png'">
                </div>
                
                <div class="product-info-section">
                    <span class="product-category"><?= htmlspecialchars($nomCategorie) ?></span>
                    
                    <h2 class="product-title"><?= htmlspecialchars($produit['title']) ?></h2>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-icon">✨</span>
                            <span><?= htmlspecialchars($produit['condition']) ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📍</span>
                            <span>Tunis</span> <!-- Placeholder location -->
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📅</span>
                            <span>Ajouté récemment</span>
                        </div>
                    </div>

                    <div class="product-description">
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
        <?php endif; ?>

        <div style="text-align: center; padding: 40px 0; color: #666; margin-top: 40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
