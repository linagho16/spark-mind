<?php
require_once '../../controller/produitC.php';
require_once '../../controller/CategorieC.php';
require_once '../../model/produit.php';

$produitC = new ProduitC();
$categorieC = new CategorieC();

// Récupérer toutes les catégories pour le dropdown
$categories = $categorieC->listCategories();

$error = null;
$success = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des données
        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['category']) || 
            empty($_POST['condition']) || empty($_POST['statut'])) {
            $error = "Tous les champs obligatoires doivent être remplis !";
        } else {
            // Gestion de l'upload de photo
            $photoPath = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $uploadDir = '../../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $photoPath = $uploadDir . basename($_FILES['photo']['name']);
                move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
            }

            // Créer l'objet produit
            $produit = new Produit(
                null,
                $_POST['title'],
                $_POST['description'],
                (int)$_POST['category'],
                $_POST['condition'],
                $_POST['statut'],
                $photoPath
            );

            // Ajouter le produit
            $success = $produitC->addProduit($produit);
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
    <title>SparkMind - Ajouter un Produit</title>
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

        /* Form Card Styles */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-md);
            margin-top: 30px;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .section-title {
            color: var(--primary);
            font-weight: 600;
        }

        .section-number {
            background: var(--primary);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.4);
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

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.1);
        }

        .radio-item input[type="radio"] {
            accent-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" class="nav-button">
                ← Retour à l'accueil
            </a>
        </div>

        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="SparkMind Logo">
            </div>
            <h1>Gestion des Produits</h1>
            <p class="subtitle">« Ajoutez vos produits facilement »</p>
        </div>

        <div class="form-card">
            <h2 style="color: var(--accent); text-align: center; margin-bottom: 10px;">📦 Ajouter un Produit</h2>
            <p class="tagline" style="color: var(--text-light);">
                Remplissez le formulaire pour ajouter un nouveau produit
            </p>

            <?php if ($error): ?>
                <div style="background: #fff5f5; color: #c0392b; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #e74c3c;">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: #f0fdf4; color: #166534; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #22c55e;">
                    ✅ <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form id="productForm" method="POST" enctype="multipart/form-data" novalidate>
                <!-- Section 1: Informations du Produit -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">1</span>
                        Informations du Produit
                    </h3>

                    <div class="form-group">
                        <label>Titre du produit <span class="required">*</span></label>
                        <input type="text" id="title" name="title" placeholder="Ex: iPhone 13 Pro, Laptop Dell...">
                        <span class="error-message" id="title-error"></span>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="required">*</span></label>
                        <textarea id="description" name="description" placeholder="Décrivez votre produit en détail..."></textarea>
                        <span class="error-message" id="description-error"></span>
                    </div>

                    <div class="form-group">
                        <label>Catégorie <span class="required">*</span></label>
                        <select id="category" name="category">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['idc'] ?>">
                                    <?= htmlspecialchars($cat['nomC']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div style="margin-top: 8px; font-size: 0.9em;">
                            <a href="../../ajouterCategorie.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">
                                + Ajouter une nouvelle catégorie
                            </a>
                        </div>
                        <span class="error-message" id="category-error"></span>
                    </div>
                </div>

                <!-- Section 2: État et Disponibilité -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">2</span>
                        État et Disponibilité
                    </h3>

                    <div class="form-group">
                        <label>Condition <span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="condition" value="neuf">
                                <span>✨ Neuf - Produit jamais utilisé</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="condition" value="bon etat">
                                <span>👍 Bon état - Légèrement utilisé</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="condition" value="usage">
                                <span>🔧 Usagé - Utilisé régulièrement</span>
                            </label>
                        </div>
                        <span class="error-message" id="condition-error"></span>
                    </div>

                    <div class="form-group">
                        <label>Statut <span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="statut" value="disponible">
                                <span>✅ Disponible - Prêt à être vendu</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="statut" value="reserve">
                                <span>⏳ Réservé - En attente</span>
                            </label>
                        </div>
                        <span class="error-message" id="statut-error"></span>
                    </div>
                </div>

                <!-- Section 3: Photo -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">3</span>
                        Photo du Produit
                    </h3>

                    <div class="privacy-notice" style="background: #f0f9ff; border-left-color: var(--primary);">
                        📸 <strong>Conseil :</strong> Ajoutez une photo claire de votre produit pour attirer plus d'acheteurs.
                    </div>

                    <div class="form-group">
                        <label>Photo du produit <span class="required">*</span></label>
                        <input type="file" id="photo" name="photo" accept="image/*" style="padding: 10px; background: #f8f8f8;">
                        <span class="error-message" id="photo-error"></span>
                    </div>
                </div>

                <!-- Section 4: Validation -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">4</span>
                        Validation
                    </h3>

                    <div class="attestation-box" style="border-color: var(--accent); background: #fff9f5;">
                        <label>
                            <input type="checkbox" id="attestation" name="attestation" style="accent-color: var(--accent);">
                            <span>J'atteste que les informations fournies sont exactes et que ce produit m'appartient.</span>
                        </label>
                        <span class="error-message" id="attestation-error"></span>
                    </div>

                    <div class="button-group">
                        <a href="index.php" class="btn-cancel" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Annuler</a>
                        <button type="submit" class="btn-submit">🚀 Ajouter le produit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="validation.js"></script>
</body>
</html>
