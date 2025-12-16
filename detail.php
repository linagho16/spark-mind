<?php
require_once('controller/produitC.php');

$produitC = new ProduitC();
$message = '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    $message = "Identifiant du produit manquant.";
} else {
    try {
        $produit = $produitC->showProduit($id);
        if (!$produit) {
            $message = "Produit introuvable.";
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
    <title>SparkMind - Détails produit</title>
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
            <a href="liste.php" class="nav-item active">
                <span class="nav-icon">📦</span>
                <span>Produits</span>
            </a>
            <a href="#" class="nav-item logout">
                <span class="nav-icon">🚪</span>
                <span>Déconnexion</span>
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1>Détails du produit</h1>
            <div class="top-bar-actions">
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="stat-card urgent" style="margin-bottom: 20px;">
                <div class="stat-icon">⚠️</div>
                <div class="stat-info">
                    <h3>Information</h3>
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            </div>
        <?php elseif (!empty($produit)): ?>
            <?php 
            require_once('controller/categorieC.php');
            $categorieC = new CategorieC();
            $cat = $categorieC->showCategorie($produit['category']);
            $nomCategorie = $cat ? $cat['nomC'] : 'Inconnue';
            ?>
            <div class="stats-container">
                <div class="stat-card total">
                    <div class="stat-icon">🏷️</div>
                    <div class="stat-info">
                        <h3>#<?php echo htmlspecialchars($produit['id']); ?></h3>
                        <p>Identifiant</p>
                    </div>
                </div>
                <div class="stat-card processed">
                    <div class="stat-icon">📁</div>
                    <div class="stat-info">
                        <h3><?php echo htmlspecialchars($nomCategorie); ?></h3>
                        <p>Catégorie</p>
                    </div>
                </div>
                <div class="stat-card pending">
                    <div class="stat-icon">🧼</div>
                    <div class="stat-info">
                        <h3><?php echo htmlspecialchars($produit['condition']); ?></h3>
                        <p>Condition</p>
                    </div>
                </div>
                <div class="stat-card urgent">
                    <div class="stat-icon">📦</div>
                    <div class="stat-info">
                        <h3><?php echo htmlspecialchars(ucfirst($produit['statut'])); ?></h3>
                        <p>Statut</p>
                    </div>
                </div>
            </div>

            <div class="detail-wrapper">
                <div class="detail-card detail-photo">
                    <?php if (!empty($produit['photo'])): ?>
                        <?php
                        // Normaliser le chemin de l'image
                        $photoPath = $produit['photo'];
                        // Si le chemin ne commence pas par uploads/, l'ajouter
                        if (strpos($photoPath, 'uploads/') !== 0 && strpos($photoPath, '/produit/uploads/') === false) {
                            $photoPath = 'uploads/' . basename($photoPath);
                        }
                        // Nettoyer les doubles slashes
                        $photoPath = str_replace('//', '/', $photoPath);
                        ?>
                        <img src="<?php echo htmlspecialchars($photoPath); ?>" 
                             alt="<?php echo htmlspecialchars($produit['title']); ?>"
                             onerror="this.src='view/back office/logo.png'; this.style.opacity='0.3'; this.parentElement.innerHTML += '<p style=\'color:#999; margin-top:10px;\'>Image non disponible</p>';">
                    <?php else: ?>
                        <p style="color:#999;">Aucune image disponible.</p>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h3>Informations principales</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <strong>Titre</strong>
                            <span><?php echo htmlspecialchars($produit['title']); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Catégorie</strong>
                            <span><?php echo htmlspecialchars($nomCategorie); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Condition</strong>
                            <span><?php echo htmlspecialchars($produit['condition']); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Statut</strong>
                            <span><?php echo htmlspecialchars($produit['statut']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="detail-card" style="grid-column: 1 / -1;">
                    <h3>Description</h3>
                    <p style="line-height:1.5;"><?php echo nl2br(htmlspecialchars($produit['description'])); ?></p>
                    <div class="detail-actions">
                        <a href="liste.php" class="btn-back">← Retour à la liste</a>
                        <a href="update.php?id=<?php echo $produit['id']; ?>" class="btn-edit">Modifier ce produit</a>
                        <button class="btn-delete" onclick="deleteProduit(<?php echo $produit['id']; ?>)">Supprimer</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function deleteProduit(id) {
            if (confirm('Confirmer la suppression ?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>
</body>
</html>
