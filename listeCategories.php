<?php
session_start();
require_once 'controller/CategorieC.php';

$categorieC = new CategorieC();

// Params de recherche
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : null;
$createur = isset($_GET['createur']) ? $_GET['createur'] : null;
$tri = isset($_GET['tri']) ? $_GET['tri'] : null;

// Pagination parameters
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;

// Count total filtered categories
$totalCategories = $categorieC->countFiltrerCategories($recherche, $createur);
$totalPages = ceil($totalCategories / $perPage);

// Ensure page is valid
if ($page < 1) $page = 1;
if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

// Fetch paginated categories
$categories = $categorieC->filtrerCategories($recherche, $createur, $tri, $page, $perPage);

// Récupérer les messages de session
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$errorMessage = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Nettoyer les messages de session
unset($_SESSION['success']);
unset($_SESSION['error']);

// Calculer les statistiques
$total = $totalCategories; // count($categories) only gives current page count
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Back Office | Gestion des Catégories</title>
    <link rel="stylesheet" href="view/back office/back.css">
    <style>
        .btn-add-category {
            background: linear-gradient(135deg, #f093fb, #f5576c);
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
        .btn-add-category:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
        }
        
        /* Messages de notification */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);
        }
        
        .alert-error {
            background: linear-gradient(135deg, #eb3349, #f45c43);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 92, 67, 0.3);
        }
        
        .alert-icon {
            font-size: 1.5em;
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
            flex: 1;
            min-width: 200px;
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
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #f5576c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        }

        .search-btn {
            background: linear-gradient(135deg, #f093fb, #f5576c);
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
            background: #f5576c;
            color: white;
            border-color: #f5576c;
        }
        
        .page-btn.active {
            background: #f5576c;
            color: white;
            border-color: #f5576c;
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
            <a href="liste.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span>Tableau de bord</span>
            </a>
            <a href="liste.php" class="nav-item">
                <span class="nav-icon">📦</span>
                <span>Produits</span>
            </a>
            <a href="listeCategories.php" class="nav-item active">
                <span class="nav-icon">🏷️</span>
                <span>Catégories</span>
                <span class="badge"><?php echo $total; ?></span>
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
            <h1>Gestion des Catégories</h1>
            <div class="top-bar-actions">
                <a href="ajouterCategorie.php" class="btn-add-category">➕ Ajouter une catégorie</a>
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>

        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <span><?php echo htmlspecialchars($successMessage); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠</span>
                <span><?php echo htmlspecialchars($errorMessage); ?></span>
            </div>
        <?php endif; ?>

        <!-- Search & Filter Section -->
        <form method="GET" class="search-container">
            <div class="search-group">
                <label for="recherche">Rechercher</label>
                <input type="text" id="recherche" name="recherche" class="search-input" 
                       placeholder="Nom ou description..." value="<?php echo htmlspecialchars($recherche); ?>">
            </div>
            
            <div class="search-group">
                <label for="createur">Créateur</label>
                <input type="text" id="createur" name="createur" class="search-input" 
                       placeholder="Nom du créateur..." value="<?php echo htmlspecialchars($createur); ?>">
            </div>

            <div class="search-group">
                <label for="tri">Trier par</label>
                <select id="tri" name="tri" class="search-input">
                    <option value="">-- Défaut --</option>
                    <option value="nom_asc" <?php if($tri == 'nom_asc') echo 'selected'; ?>>Nom (A-Z)</option>
                    <option value="nom_desc" <?php if($tri == 'nom_desc') echo 'selected'; ?>>Nom (Z-A)</option>
                    <option value="date_asc" <?php if($tri == 'date_asc') echo 'selected'; ?>>Date (Ancien)</option>
                    <option value="date_desc" <?php if($tri == 'date_desc') echo 'selected'; ?>>Date (Récent)</option>
                </select>
            </div>

            <button type="submit" class="search-btn">🔍 Rechercher</button>
            <a href="listeCategories.php" class="reset-btn">Réinitialiser</a>
        </form>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-icon">📈</div>
                <div class="stat-info">
                    <h3><?php echo $total; ?></h3>
                    <p>Total catégories</p>
                </div>
            </div>
            <div class="stat-card processed">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?php echo $total; ?></h3>
                    <p>Actives</p>
                </div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon">🏷️</div>
                <div class="stat-info">
                    <h3>0</h3>
                    <p>Archivées</p>
                </div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon">➕</div>
                <div class="stat-info">
                    <h3><a href="ajouterCategorie.php" style="color: inherit; text-decoration: none;">Ajouter</a></h3>
                    <p>Nouvelle catégorie</p>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="table-container">
            <table class="demandes-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Date de création</th>
                        <th>Créateur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">
                                <p style="color: #999; font-size: 1.1em;">Aucune catégorie trouvée. <a href="ajouterCategorie.php" style="color: #f5576c;">Ajouter une catégorie</a></p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($cat['idc']); ?></td>
                                <td><strong><?php echo htmlspecialchars($cat['nomC']); ?></strong></td>
                                <td><?php echo htmlspecialchars($cat['descriptionC']); ?></td>
                                <td>
                                    <span class="urgence-badge important">📅 <?php echo htmlspecialchars($cat['dateC']); ?></span>
                                </td>
                                <td>
                                    <span class="type-badge alimentaire">👤 <?php echo htmlspecialchars($cat['nom_Createur']); ?></span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" 
                                                title="Voir détails" 
                                                onclick="window.location.href='detailsCategorie.php?id=<?php echo $cat['idc']; ?>'">👁️</button>
                                        <button class="btn-action edit" 
                                                title="Modifier" 
                                                onclick="window.location.href='modifierCategorie.php?id=<?php echo $cat['idc']; ?>'">✏️</button>
                                        <button class="btn-action delete" 
                                                title="Supprimer" 
                                                onclick="deleteCategorie(<?php echo $cat['idc']; ?>)">🗑️</button>
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
        // Fonction de suppression
        function deleteCategorie(id) {
            if (confirm('⚠️ ATTENTION ⚠️\n\nVoulez-vous vraiment supprimer cette catégorie ?\n\n❗ TOUS les produits de cette catégorie seront également supprimés définitivement !\n\nCette action est irréversible.')) {
                window.location.href = 'supprimerCategorie.php?id=' + id;
            }
        }
        
        // Faire disparaître automatiquement les notifications après 5 secondes
        window.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000); // 5 secondes
            });
        });
    </script>
</body>
</html>
