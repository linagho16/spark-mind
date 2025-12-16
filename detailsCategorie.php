<?php
require_once 'controller/CategorieC.php';
require_once 'controller/produitC.php';

$categorieC = new CategorieC();
$produitC = new ProduitC();

// Récupérer la catégorie
if (isset($_GET['id'])) {
    $categorie = $categorieC->showCategorie($_GET['id']);
    if (!$categorie) {
        header('Location: listeCategories.php');
        exit();
    }
} else {
    header('Location: listeCategories.php');
    exit();
}

// Récupérer les produits de cette catégorie
$allProduits = $produitC->listProduits();
$produits = array_filter($allProduits, function($p) use ($categorie) {
    return $p['category'] == $categorie['idc'];
});

$totalProduits = count($produits);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Catégorie - <?= htmlspecialchars($categorie['nomC']) ?></title>
    <link rel="stylesheet" href="view/back office/back.css">
    <style>
        .detail-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .detail-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .detail-card h3 {
            color: #1f8c87;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }
        .detail-item strong {
            display: block;
            font-size: 0.85em;
            color: #666;
            margin-bottom: 3px;
        }
        .detail-item span {
            font-weight: 600;
        }
        .detail-photo {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 260px;
            background: #f8f8f8;
            border-radius: 15px;
        }
        .detail-photo img {
            max-width: 100%;
            max-height: 320px;
            border-radius: 15px;
            object-fit: cover;
        }
        .detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }
        .detail-actions a,
        .detail-actions button {
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        .detail-actions .btn-back {
            background: #e0e0e0;
            color: #333;
        }
        .detail-actions .btn-edit {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
        }
        .detail-actions .btn-delete {
            background: #ffebee;
            color: #c62828;
        }
        
        /* Table styles */
        .demandes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .demandes-table th, .demandes-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .demandes-table th {
            background-color: #f8f9fa;
            color: #666;
            font-weight: 600;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
        }
        .status-badge.traite { background: #e8f5e9; color: #2e7d32; }
        .status-badge.en-cours { background: #fff3e0; color: #ef6c00; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="view/back office/logo.png" alt="SparkMind Logo" class="sidebar-logo">
            <h2>SparkMind</h2>
            <p class="admin-role">Administrateur</p>
        </div>
        <nav class="sidebar-nav">
            <a href="liste.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span>Tableau de bord</span>
            </a>
            <a href="ajout.php" class="nav-item">
                <span class="nav-icon">➕</span>
                <span>Ajouter produit</span>
            </a>
            <a href="liste.php" class="nav-item">
                <span class="nav-icon">📦</span>
                <span>Produits</span>
            </a>
             <a href="listeCategories.php" class="nav-item active">
                <span class="nav-icon">🏷️</span>
                <span>Catégories</span>
            </a>
            <a href="#" class="nav-item logout">
                <span class="nav-icon">🚪</span>
                <span>Déconnexion</span>
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1>Détails de la catégorie</h1>
            <div class="top-bar-actions">
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-icon">🏷️</div>
                <div class="stat-info">
                    <h3>#<?= htmlspecialchars($categorie['idc']) ?></h3>
                    <p>Identifiant</p>
                </div>
            </div>
            <div class="stat-card processed">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3><?= $totalProduits ?></h3>
                    <p>Produits</p>
                </div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3><?= htmlspecialchars($categorie['dateC']) ?></h3>
                    <p>Date Création</p>
                </div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon">👤</div>
                <div class="stat-info">
                    <h3><?= htmlspecialchars($categorie['nom_Createur']) ?></h3>
                    <p>Créateur</p>
                </div>
            </div>
        </div>

        <div class="detail-wrapper">
            <div class="detail-card detail-photo">
                 <div style="font-size: 5em;">🏷️</div>
            </div>

            <div class="detail-card">
                <h3>Informations principales</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <strong>Nom</strong>
                        <span><?= htmlspecialchars($categorie['nomC']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Créateur</strong>
                        <span><?= htmlspecialchars($categorie['nom_Createur']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Date de création</strong>
                        <span><?= htmlspecialchars($categorie['dateC']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Nombre de produits</strong>
                        <span><?= $totalProduits ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-card" style="grid-column: 1 / -1;">
                <h3>Description</h3>
                <p style="line-height:1.5;"><?= nl2br(htmlspecialchars($categorie['descriptionC'])) ?></p>
                <div class="detail-actions">
                    <a href="listeCategories.php" class="btn-back">← Retour à la liste</a>
                    <a href="modifierCategorie.php?id=<?= $categorie['idc'] ?>" class="btn-edit">Modifier cette catégorie</a>
                    <button class="btn-delete" onclick="deleteCategorie(<?= $categorie['idc'] ?>)">Supprimer</button>
                </div>
            </div>
        </div>
        
        <div class="detail-card" style="margin-top: 30px;">
            <h3>📦 Produits associés</h3>
            <?php if (empty($produits)): ?>
                <p style="color:#999;">Aucun produit associé.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="demandes-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Condition</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $produit): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($produit['id']) ?></td>
                                    <td><strong><?= htmlspecialchars($produit['title']) ?></strong></td>
                                    <td><?= htmlspecialchars($produit['condition']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $produit['statut'] == 'disponible' ? 'traite' : 'en-cours' ?>">
                                            <?= htmlspecialchars(ucfirst($produit['statut'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="detail.php?id=<?= $produit['id'] ?>" style="text-decoration:none;">👁️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script>
        function deleteCategorie(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                window.location.href = 'supprimerCategorie.php?id=' + id;
            }
        }
    </script>
</body>
</html>
