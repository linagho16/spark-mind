<?php
require_once __DIR__ . '/../../controller/produitC.php';

$produitC = new ProduitC();
$produits = $produitC->listProduits();

$produitsDisponibles = array_filter($produits, function ($produit) {
    return strtolower($produit['statut']) === 'disponible';
});

$totalDisponibles = count($produitsDisponibles);
$categoriesDisponibles = [];

foreach ($produitsDisponibles as $produit) {
    $cat = $produit['category'] ?? 'Divers';
    if (!isset($categoriesDisponibles[$cat])) {
        $categoriesDisponibles[$cat] = 0;
    }
    $categoriesDisponibles[$cat]++;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Produits disponibles</title>
    <link rel="stylesheet" href="formlaire.css">
    <style>
        /* Styles spécifiques pour la liste des produits */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stat-card .stat-label {
            display: block;
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stat-card .stat-value {
            font-size: 2.5em;
            font-weight: 700;
            color: #166e6a;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 60px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .product-body {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-body h3 {
            color: #0a6661;
            margin-bottom: 12px;
            font-size: 1.3em;
            font-weight: 700;
        }

        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .badge.condition {
            background: #fff3e0;
            color: #e65100;
        }

        .badge.category {
            background: rgba(22, 110, 106, 0.1);
            color: #166e6a;
        }

        .product-body p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .product-actions a {
            background: linear-gradient(135deg, #0d6e69, #6e3da7);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .product-actions a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 105, 0.3);
        }

        .product-actions small {
            color: #27ae60;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .product-actions small::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #27ae60;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.2);
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

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .stats-section {
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
            <a href="index.php" class="nav-item">
                <span>🏠</span>
                <span>Accueil</span>
            </a>
            <a href="liste_produits.php" class="nav-item active">
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
                <h1>Produits Disponibles</h1>
                <p class="subtitle">Découvrez tous les dons prêts à être remis</p>
            </div>
            <div class="header-actions">
                <button class="btn-help">❓ Aide</button>
            </div>
        </header>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <span class="stat-label">Total disponibles</span>
                <div class="stat-value"><?php echo $totalDisponibles; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏷️</div>
                <span class="stat-label">Catégories</span>
                <div class="stat-value"><?php echo count($categoriesDisponibles); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✨</div>
                <span class="stat-label">Nouveautés</span>
                <div class="stat-value"><?php echo min($totalDisponibles, 12); ?></div>
            </div>
        </div>

        <!-- Products Section -->
        <?php if ($totalDisponibles === 0): ?>
            <div class="empty-state">
                <div style="font-size: 5rem; margin-bottom: 20px;">📭</div>
                <h3>Aucun produit disponible pour le moment</h3>
                <p>Revenez bientôt ou proposez un don depuis notre formulaire.</p>
                <a href="ajouterProduit.php" class="btn-primary" style="text-decoration: none; display: inline-block;">
                    + Proposer un don
                </a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($produitsDisponibles as $produit): ?>
                    <div class="product-card">
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
                        <img src="<?php echo $photo; ?>" alt="<?php echo htmlspecialchars($produit['title']); ?>"
                             onerror="this.src='logo.png'">
                        <div class="product-body">
                            <h3><?php echo htmlspecialchars($produit['title']); ?></h3>
                            <div class="product-meta">
                                <span class="badge category"><?php echo htmlspecialchars($produit['category']); ?></span>
                                <span class="badge condition"><?php echo htmlspecialchars($produit['condition']); ?></span>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars(substr($produit['description'], 0, 140))); ?>...</p>
                            <div class="product-actions">
                                <small>Disponible</small>
                                <a href="detailsfront.php?id=<?php echo $produit['id']; ?>">Voir détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div style="text-align: center; padding: 40px 0; color: #666; border-top: 1px solid rgba(0,0,0,0.05); margin-top: 40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </main>
</body>
</html>
