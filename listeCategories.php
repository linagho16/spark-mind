<?php
session_start();
require_once 'controller/CategorieC.php';

$categorieC = new CategorieC();
$categories = $categorieC->listCategories();

// Récupérer les messages de session
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$errorMessage = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Nettoyer les messages de session
unset($_SESSION['success']);
unset($_SESSION['error']);

// Calculer les statistiques
$total = count($categories);
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
        <div class="pagination">
            <button class="page-btn" disabled>« Précédent</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">Suivant »</button>
        </div>
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
