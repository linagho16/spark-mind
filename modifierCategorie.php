<?php
require_once 'controller/CategorieC.php';
require_once 'model/categorie.php';

$categorieC = new CategorieC();

$error = null;
$success = null;
$categorie = null;

// Récupérer la catégorie à modifier
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

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des données
        if (empty($_POST['nomC']) || empty($_POST['descriptionC']) || 
            empty($_POST['dateC']) || empty($_POST['nom_Createur'])) {
            $error = "Tous les champs obligatoires doivent être remplis !";
        } else {
            // Créer l'objet catégorie
            $categorieObj = new Categorie(
                null,
                $_POST['nomC'],
                $_POST['descriptionC'],
                $_POST['dateC'],
                $_POST['nom_Createur']
            );

            // Mettre à jour la catégorie
            if ($categorieC->updateCategorie($categorieObj, $_GET['id'])) {
                $success = "Catégorie modifiée avec succès !";
                // Recharger les données
                $categorie = $categorieC->showCategorie($_GET['id']);
            } else {
                $error = "Erreur lors de la modification.";
            }
        }
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Modifier une Catégorie</title>
    <link rel="stylesheet" href="view/back office/back.css">
    <style>
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group label {
            font-weight: 600;
            color: #555;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            font-family: inherit;
            font-size: 1em;
        }
        .form-group textarea {
            min-height: 140px;
        }
        .form-actions {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .message {
            margin-bottom: 20px;
            padding: 15px 20px;
            border-radius: 12px;
            font-weight: 600;
        }
        .message.success {
            background: #e8f5e9;
            color: #166E73;
        }
        .message.error {
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
            <h1>Modifier la catégorie</h1>
            <div class="top-bar-actions">
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($categorie): ?>
            <div class="form-card">
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nomC">Nom de la catégorie</label>
                            <input type="text" name="nomC" id="nomC" value="<?= htmlspecialchars($categorie['nomC']) ?>" placeholder="Ex: Électronique">
                        </div>
                        <div class="form-group">
                            <label for="nom_Createur">Créateur</label>
                            <input type="text" name="nom_Createur" id="nom_Createur" value="<?= htmlspecialchars($categorie['nom_Createur']) ?>" placeholder="Nom du créateur">
                        </div>
                        <div class="form-group">
                            <label for="dateC">Date de création</label>
                            <input type="date" name="dateC" id="dateC" value="<?= htmlspecialchars($categorie['dateC']) ?>">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label for="descriptionC">Description</label>
                        <textarea name="descriptionC" id="descriptionC" placeholder="Description de la catégorie"><?= htmlspecialchars($categorie['descriptionC']) ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Enregistrer</button>
                        <a href="detailsCategorie.php?id=<?= $categorie['idc'] ?>" class="btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center;">Annuler</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
