<?php
require_once('controller/produitC.php');


// Params de recherche
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : null;
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$etat = isset($_GET['etat']) ? $_GET['etat'] : null;
$condition = isset($_GET['condition']) ? $_GET['condition'] : null;
$tri = isset($_GET['tri']) ? $_GET['tri'] : null;

$produitC = new ProduitC();

// Pagination parameters
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;

// Count total filtered products
$totalProducts = $produitC->countFiltrerProduits($recherche, $categorie, $etat, $condition);
$totalPages = ceil($totalProducts / $perPage);

// Ensure page is valid
if ($page < 1) $page = 1;
if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

// Fetch paginated products
$produits = $produitC->filtrerProduits($recherche, $categorie, $etat, $condition, $tri, $page, $perPage);

// Pour le filtre par catégorie, on a besoin de la liste des catégories
require_once('controller/CategorieC.php');
$categorieC = new CategorieC();
$listeCategories = $categorieC->listCategories();

// Calculer les statistiques (basées sur le résultat filtré)
// Note: $totalProducts est le nombre total de résultats filtrés
// $disponibles et $reserves sont calculés sur la page COURANTE seulement
$total = $totalProducts;
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

        .btn-export-excel {
            background: linear-gradient(135deg, #28a745, #218838); /* Green for Excel */
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
        .btn-export-excel:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        /* Search Bar Styles */
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .search-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex : 1;
            min-width: 150px;
        }

        .search-group label {
            font-size: 0.9em;
            color: #666;
            font-weight: 500;
        }

        .search-input {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95em;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #1f8c87;
            outline: none;
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.1);
        }

        .search-btn {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease;
            height: 42px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
        }

        .reset-btn {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .reset-btn:hover {
            background: #e9ecef;
            color: #333;
        }

        /* Styles pour le code-barres zoomable */
        .barcode-container {
            position: relative;
            display: inline-block;
            cursor: zoom-in;
        }
        
        .barcode-preview {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 2px;
            background: white;
            vertical-align: middle;
        }
        
        .barcode-full {
            display: none;
            position: fixed; /* Utiliser fixed pour sortir du contexte du tableau (overflow) */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 50px rgba(0,0,0,0.3);
            z-index: 10000;
            border: 1px solid #eee;
            min-width: 300px;
            text-align: center;
        }
        
        /* Supprimé la flèche car le popup est centré à l'écran maintenant */
        .barcode-full::after {
            display: none;
        }

        .barcode-container:hover .barcode-full {
            display: block;
        }
        
        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            padding-bottom: 20px;
        }
        
        .page-btn {
            border: 1px solid #ddd;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        
        .page-btn:hover:not(.disabled) {
            background: #1f8c87;
            color: white;
            border-color: #1f8c87;
        }
        
        .page-btn.active {
            background: #1f8c87;
            color: white;
            border-color: #1f8c87;
        }
        
        .page-btn.disabled {
            background: #f8f9fa;
            color: #999;
            cursor: not-allowed;
            border-color: #eee;
            pointer-events: none;
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
            <a href="view/front office/ajouterProduit.php" class="nav-item">
                <span class="nav-icon">➕</span>
                <span>Ajouter produit</span>
            </a>
            <a href="liste.php" class="nav-item">
                <span class="nav-icon">📦</span>
                <span>Produits</span>
                <span class="badge"><?php echo $total; ?></span>
            </a>
            <a href="listeCategories.php" class="nav-item">
                <span class="nav-icon">🏷️</span>
                <span>Catégories</span>
            </a>
            <a href="stats.php" class="nav-item">
                <span class="nav-icon">📈</span>
                <span>Statistiques</span>
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
                <a href="export_excel.php?<?php echo http_build_query($_GET); ?>" class="btn-export-excel">📥 Exporter Excel</a>
                <a href="view/front office/ajouterProduit.php" class="btn-add-product">➕ Ajouter un produit</a>
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>


        <!-- Search & Filter Section -->
        <form method="GET" class="search-container">
            <div class="search-group">
                <label for="recherche">Rechercher</label>
                <input type="text" id="recherche" name="recherche" class="search-input" 
                       placeholder="Titre..." value="<?php echo htmlspecialchars($recherche); ?>">
            </div>
            
            <div class="search-group">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie" class="search-input">
                    <option value="">-- Toutes --</option>
                    <?php foreach ($listeCategories as $cat): ?>
                        <option value="<?php echo $cat['idc']; ?>" <?php if($categorie == $cat['idc']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['nomC']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="search-group">
                <label for="etat">Statut</label>
                <select id="etat" name="etat" class="search-input">
                    <option value="">-- Tous --</option>
                    <option value="disponible" <?php if($etat == 'disponible') echo 'selected'; ?>>Disponible</option>
                    <option value="reserve" <?php if($etat == 'reserve') echo 'selected'; ?>>Réservé</option>
                    <option value="vendu" <?php if($etat == 'vendu') echo 'selected'; ?>>Vendu</option>
                </select>
            </div>

            <div class="search-group">
                <label for="condition">Condition</label>
                <select id="condition" name="condition" class="search-input">
                    <option value="">-- Toutes --</option>
                    <option value="neuf" <?php if($condition == 'neuf') echo 'selected'; ?>>Neuf</option>
                    <option value="bon etat" <?php if($condition == 'bon etat') echo 'selected'; ?>>Bon état</option>
                    <option value="reconditionné" <?php if($condition == 'reconditionné') echo 'selected'; ?>>Reconditionné</option>
                </select>
            </div>

            <div class="search-group">
                <label for="tri">Trier par</label>
                <select id="tri" name="tri" class="search-input">
                    <option value="">-- Défaut --</option>
                    <option value="titre_asc" <?php if($tri == 'titre_asc') echo 'selected'; ?>>Titre (A-Z)</option>
                    <option value="titre_desc" <?php if($tri == 'titre_desc') echo 'selected'; ?>>Titre (Z-A)</option>
                    <option value="recents" <?php if($tri == 'recents') echo 'selected'; ?>>Plus récents</option>
                    <option value="anciens" <?php if($tri == 'anciens') echo 'selected'; ?>>Plus anciens</option>
                </select>
            </div>

            <button type="submit" class="search-btn">🔍 Rechercher</button>
            <a href="liste.php" class="reset-btn">Réinitialiser</a>
        </form>

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
                        <th style="text-align: center;">Code-barres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="produitsTableBody">
                    <?php if (empty($produits)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px;">
                                <p style="color: #999; font-size: 1.1em;">Aucun produit trouvé. <a href="view/front office/ajouterProduit.php" style="color: #1f8c87;">Ajouter un produit</a></p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produits as $produit): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($produit['id']); ?></td>
                                <td><strong><?php echo htmlspecialchars($produit['title']); ?></strong></td>
                                <td>
                                    <span class="type-badge alimentaire"><?php echo htmlspecialchars($produit['nomC']); ?></span>
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
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                             onerror="this.src='view/back office/logo.png'; this.style.opacity='0.3';">
                                    <?php else: ?>
                                        <span style="color: #999;">Aucune photo</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="barcode-container">
                                        <!-- Aperçu miniature -->
                                        <img id="barcode-mini-img-<?php echo $produit['id']; ?>" class="barcode-preview" src="" alt="QR Code">
                                        
                                        <!-- Code-barres complet au survol (Popup) -->
                                        <div class="barcode-full">
                                            <div style="text-align: center; margin-bottom: 5px; font-weight: bold; color: #333;">Scan Me</div>
                                            <img id="barcode-full-img-<?php echo $produit['id']; ?>" src="" alt="QR Code Full" style="width: 150px; height: 150px;">
                                        </div>
                                    </div>
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
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <!-- Bouton Précédent -->
            <?php 
                $prevLink = ($page > 1) ? '?' . http_build_query(array_merge($_GET, ['page' => $page - 1])) : '#';
                $prevClass = ($page <= 1) ? 'disabled' : '';
            ?>
            <button class="page-btn <?php echo $prevClass; ?>" 
                    <?php if($page <= 1) echo 'disabled'; ?>
                    onclick="window.location.href='<?php echo $prevLink; ?>'">« Précédent</button>
            
            <!-- Pages -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php 
                    $pageLink = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                    $activeClass = ($i == $page) ? 'active' : '';
                ?>
                <button class="page-btn <?php echo $activeClass; ?>" 
                        onclick="window.location.href='<?php echo $pageLink; ?>'"><?php echo $i; ?></button>
            <?php endfor; ?>
            
            <!-- Bouton Suivant -->
            <?php 
                $nextLink = ($page < $totalPages) ? '?' . http_build_query(array_merge($_GET, ['page' => $page + 1])) : '#';
                $nextClass = ($page >= $totalPages) ? 'disabled' : '';
            ?>
            <button class="page-btn <?php echo $nextClass; ?>" 
                    <?php if($page >= $totalPages) echo 'disabled'; ?>
                    onclick="window.location.href='<?php echo $nextLink; ?>'">Suivant »</button>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <?php 
                        // FULL INFO in QR Code
                        $pId = $produit['id'];
                        $pTitle = $produit['title'];
                        $pCat = isset($produit['nomC']) ? $produit['nomC'] : '';
                        $pCond = isset($produit['condition']) ? $produit['condition'] : '';
                        $pStat = isset($produit['statut']) ? $produit['statut'] : '';
                        
                        $data = "ID:$pId\nTitre: $pTitle\nCat: $pCat\nCond: $pCond\nStat: $pStat";
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);
                    ?>
                    
                    var qrImgMini = document.getElementById("barcode-mini-img-<?= $pId ?>");
                    if(qrImgMini) qrImgMini.src = "<?= $qrUrl ?>";
                    
                    var qrImgFull = document.getElementById("barcode-full-img-<?= $pId ?>");
                    if(qrImgFull) qrImgFull.src = "<?= $qrUrl ?>";

                <?php endforeach; ?>
            <?php endif; ?>
        });

        // Fonction de suppression
        function deleteProduit(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>
</body>
</html>
