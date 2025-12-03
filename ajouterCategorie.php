<?php
require_once 'controller/CategorieC.php';
require_once 'model/categorie.php';

$categorieC = new CategorieC();

$error = null;
$success = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des données
        if (empty($_POST['nomC']) || empty($_POST['descriptionC']) || 
            empty($_POST['dateC']) || empty($_POST['nom_Createur'])) {
            $error = "Tous les champs obligatoires doivent être remplis !";
        } else {
            // Créer l'objet catégorie
            $categorie = new Categorie(
                null,
                $_POST['nomC'],
                $_POST['descriptionC'],
                $_POST['dateC'],
                $_POST['nom_Createur']
            );

            // Ajouter la catégorie
            $success = $categorieC->addCategorie($categorie);
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
    <title>SparkMind - Ajouter une Catégorie</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="view/front office/formlaire.css">
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
        
        .error-message {
            display: none;
            color: #d32f2f;
            font-size: 0.85em;
            margin-top: 5px;
            font-weight: 500;
        }

        input.error,
        select.error,
        textarea.error {
            border-color: #c62828 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="view/front office/ajouterProduit.php" class="nav-button">
                ← Retour au formulaire produit
            </a>
        </div>

        <div class="header">
            <div class="logo">
                <img src="view/front office/logo.png" alt="SparkMind Logo" onerror="this.src='view/back office/logo.png'">
            </div>
            <h1>Gestion des Catégories</h1>
            <p class="subtitle">« Organisez vos produits par catégorie »</p>
        </div>

        <div class="form-card">
            <h2 style="color: var(--accent); text-align: center; margin-bottom: 10px;">🏷️ Ajouter une Catégorie</h2>
            <p class="tagline" style="color: var(--text-light);">
                Créez une nouvelle catégorie pour mieux organiser vos produits
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

            <form id="categoryForm" method="POST" novalidate>
                <!-- Section 1: Informations de la Catégorie -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">1</span>
                        Informations de la Catégorie
                    </h3>

                    <div class="form-group">
                        <label>Nom de la catégorie <span class="required">*</span></label>
                        <input type="text" id="nomC" name="nomC" placeholder="Ex: Électronique, Vêtements, Livres...">
                        <span class="error-message" id="nomC-error"></span>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="required">*</span></label>
                        <textarea id="descriptionC" name="descriptionC" placeholder="Décrivez brièvement cette catégorie..."></textarea>
                        <span class="error-message" id="descriptionC-error"></span>
                    </div>
                </div>

                <!-- Section 2: Informations Complémentaires -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">2</span>
                        Informations Complémentaires
                    </h3>

                    <div class="form-group">
                        <label>Date de création <span class="required">*</span></label>
                        <input type="date" id="dateC" name="dateC" value="<?= date('Y-m-d') ?>">
                        <span class="error-message" id="dateC-error"></span>
                    </div>

                    <div class="form-group">
                        <label>Nom du créateur <span class="required">*</span></label>
                        <input type="text" id="nom_Createur" name="nom_Createur" placeholder="Ex: Admin, Votre nom...">
                        <span class="error-message" id="nom_Createur-error"></span>
                    </div>
                </div>

                <!-- Section 3: Validation -->
                <div class="section">
                    <h3 class="section-title">
                        <span class="section-number">3</span>
                        Validation
                    </h3>

                    <div class="privacy-notice" style="background: #f0f9ff; border-left-color: var(--primary);">
                        ℹ️ <strong>Information :</strong> Les catégories créées ici seront disponibles pour la classification des produits.
                    </div>

                    <div class="attestation-box" style="border-color: var(--accent); background: #fff9f5;">
                        <label>
                            <input type="checkbox" id="attestation" name="attestation" style="accent-color: var(--accent);">
                            <span>J'atteste que les informations fournies sont exactes.</span>
                        </label>
                        <span class="error-message" id="attestation-error"></span>
                    </div>

                    <div class="button-group">
                        <a href="view/front office/ajouterProduit.php" class="btn-cancel" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Annuler</a>
                        <button type="submit" class="btn-submit">🚀 Ajouter la catégorie</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="controle_saisie.js"></script>
</body>
</html>
