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
        .products-section {
            margin-top: 30px;
        }
        .products-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .products-header h2 {
            color: #ec7546;
            font-size: 2em;
        }
        .products-summary {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        .summary-card {
            background: white;
            border-radius: 14px;
            padding: 18px 24px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            min-width: 220px;
        }
        .summary-card span {
            display: block;
            color: #7d5aa6;
            font-size: 0.9em;
            margin-bottom: 6px;
        }
        .summary-card strong {
            font-size: 1.6em;
            color: #1f8c87;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-body {
            padding: 20px;
            flex: 1;
        }
        .product-body h3 {
            color: #1f8c87;
            margin-bottom: 8px;
        }
        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }
        .badge {
            padding: 5px 12px;
            border-radius: 40px;
            font-size: 0.8em;
            background: #f0f0f0;
            color: #555;
        }
        .badge.condition {
            background: #fff3e0;
            color: #e65100;
        }
        .badge.category {
            background: #e3f2fd;
            color: #1565c0;
        }
        .product-body p {
            color: #555;
            line-height: 1.4;
            margin-bottom: 15px;
        }
        .product-actions {
            padding: 0 20px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .product-actions a {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease;
        }
        .product-actions a:hover {
            transform: translateY(-2px);
        }
        .product-actions small {
            color: #7d5aa6;
            font-style: italic;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.12);
        }
        .empty-state h3 {
            color: #1f8c87;
            margin-bottom: 10px;
        }
        .empty-state p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="SparkMind Logo">
            </div>
            <h1>SparkMind</h1>
            <p class="subtitle">« Quand la pensée devient espoir. »</p>
        </div>

        <div class="form-card products-section">
            <div class="products-header">
                <h2>🎁 Produits disponibles</h2>
                <p>Découvrez les dons prêts à être remis.</p>
            </div>

            <div class="products-summary">
                <div class="summary-card">
                    <span>Total disponibles</span>
                    <strong><?php echo $totalDisponibles; ?></strong>
                </div>
                <div class="summary-card">
                    <span>Catégories représentées</span>
                    <strong><?php echo count($categoriesDisponibles); ?></strong>
                </div>
            </div>

            <?php if ($totalDisponibles === 0): ?>
                <div class="empty-state">
                    <h3>Aucun produit disponible pour le moment</h3>
                    <p>Revenez bientôt ou proposez un don depuis notre formulaire.</p>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($produitsDisponibles as $produit): ?>
                        <div class="product-card">
                            <?php if (!empty($produit['photo'])): ?>
                                <img src="<?php echo htmlspecialchars($produit['photo']); ?>" alt="<?php echo htmlspecialchars($produit['title']); ?>">
                            <?php else: ?>
                                <img src="logo.png" alt="SparkMind">
                            <?php endif; ?>
                            <div class="product-body">
                                <h3><?php echo htmlspecialchars($produit['title']); ?></h3>
                                <div class="product-meta">
                                    <span class="badge category"><?php echo htmlspecialchars($produit['category']); ?></span>
                                    <span class="badge condition"><?php echo htmlspecialchars($produit['condition']); ?></span>
                                </div>
                                <p><?php echo nl2br(htmlspecialchars(substr($produit['description'], 0, 140))); ?>...</p>
                            </div>
                            <div class="product-actions">
                                <small>Statut : Disponible</small>
                                <a href="../../detail.php?id=<?php echo $produit['id']; ?>">Voir plus</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

