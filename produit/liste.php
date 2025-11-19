<?php
require_once('controller/produitC.php');

$produitC = new ProduitC();
$produits = $produitC->listProduits();

// Calculer les statistiques
$total = count($produits);
$disponibles = 0;
$reserves = 0;

foreach ($produits as $produit) {
    if ($produit['statut'] == 'disponible') {
        $disponibles++;
    } elseif ($produit['statut'] == 'reserve') {
        $reserves++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Back Office | Gestion des Produits</title>
    <link rel="stylesheet" href="view/back office/back.css">
    <style>
        .btn-add-product {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin-right: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-add-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 140, 135, 0.3);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="view/back office/logo.png" alt="SparkMind Logo" class="sidebar-logo">
            <h2>SparkMind</h2>
            <p class="admin-role">Administrateur</p>
        </div>

        <nav class="sidebar-nav">
            <a href="liste.php" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span>Tableau de bord</span>
            </a>
            <a href="view/front office/produit.html" class="nav-item">
                <span class="nav-icon">➕</span>
                <span>Ajouter produit</span>
            </a>
            <a href="liste.php" class="nav-item">
                <span class="nav-icon">📦</span>
                <span>Produits</span>
                <span class="badge"><?php echo $total; ?></span>
            </a>
            <a href="#" class="nav-item logout">
                <span class="nav-icon">🚪</span>
                <span>Déconnexion</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Gestion des Produits</h1>
            <div class="top-bar-actions">
                <a href="view/front office/produit.html" class="btn-add-product">➕ Ajouter un produit</a>
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-icon">📈</div>
                <div class="stat-info">
                    <h3><?php echo $total; ?></h3>
                    <p>Total produits</p>
                </div>
            </div>
            <div class="stat-card processed">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?php echo $disponibles; ?></h3>
                    <p>Disponibles</p>
                </div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3><?php echo $reserves; ?></h3>
                    <p>Réservés</p>
                </div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon">➕</div>
                <div class="stat-info">
                    <h3><a href="view/front office/produit.html" style="color: inherit; text-decoration: none;">Ajouter</a></h3>
                    <p>Nouveau produit</p>
                </div>
            </div>
        </div>

        <!-- Produits Table -->
        <div class="table-container">
            <table class="demandes-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Condition</th>
                        <th>Statut</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="produitsTableBody">
                    <?php if (empty($produits)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px;">
                                <p style="color: #999; font-size: 1.1em;">Aucun produit trouvé. <a href="view/front office/produit.html" style="color: #1f8c87;">Ajouter un produit</a></p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produits as $produit): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($produit['id']); ?></td>
                                <td><strong><?php echo htmlspecialchars($produit['title']); ?></strong></td>
                                <td>
                                    <span class="type-badge alimentaire"><?php echo htmlspecialchars($produit['category']); ?></span>
                                </td>
                                <td>
                                    <?php
                                    $condition = $produit['condition'];
                                    $conditionClass = '';
                                    if ($condition == 'neuf') $conditionClass = 'tres-urgent';
                                    elseif ($condition == 'bon etat') $conditionClass = 'urgent';
                                    else $conditionClass = 'important';
                                    ?>
                                    <span class="urgence-badge <?php echo $conditionClass; ?>"><?php echo htmlspecialchars($condition); ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statut = $produit['statut'];
                                    $statutClass = $statut == 'disponible' ? 'traite' : 'en-cours';
                                    ?>
                                    <span class="status-badge <?php echo $statutClass; ?>"><?php echo htmlspecialchars(ucfirst($statut)); ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($produit['photo'])): ?>
                                        <img src="<?php echo htmlspecialchars($produit['photo']); ?>" 
                                             alt="<?php echo htmlspecialchars($produit['title']); ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <span style="color: #999;">Aucune photo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" 
                                                title="Voir détails" 
                                                onclick="window.location.href='detail.php?id=<?php echo $produit['id']; ?>'">👁️</button>
                                        <button class="btn-action edit" 
                                                title="Modifier" 
                                                onclick="window.location.href='update.php?id=<?php echo $produit['id']; ?>'">✏️</button>
                                        <button class="btn-action delete" 
                                                title="Supprimer" 
                                                onclick="deleteProduit(<?php echo $produit['id']; ?>)">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn" disabled>« Précédent</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">Suivant »</button>
        </div>
    </div>

    <script>
        // Fonction de suppression
        function deleteProduit(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>
</body>
</html>

