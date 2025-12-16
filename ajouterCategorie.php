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
    <link rel="stylesheet" href="view/front office/formlaire.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>SparkMind</h2>
            <p>« Quand la pensée devient espoir. »</p>
        </div>
        
        <nav class="nav-menu">
            <a href="view/front office/index.php" class="nav-item">
                <span>🏠</span>
                <span>Accueil</span>
            </a>
            <a href="view/front office/liste_produits.php" class="nav-item">
                <span>📦</span>
                <span>Produits</span>
            </a>
            <a href="view/front office/ajouterProduit.php" class="nav-item">
                <span>➕</span>
                <span>Ajouter un don</span>
            </a>
            <a href="ajouterCategorie.php" class="nav-item active">
                <span>🏷️</span>
                <span>Nouvelle Catégorie</span>
            </a>
            <a href="#" class="nav-item">
                <span>👤</span>
                <span>Mon compte</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="info-box">
                <h4>Besoin d'aide ?</h4>
                <p>Contactez notre équipe pour toute question</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div>
                <h1>Ajouter une Catégorie</h1>
                <p class="subtitle">Organisez vos produits efficacement</p>
            </div>
            <div class="header-actions">
                <button class="btn-help">❓ Aide</button>
            </div>
        </header>

        <!-- Notifications -->
        <?php if ($error): ?>
            <div class="notification error show">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="notification success show">
                ✅ <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Form Container -->
        <div class="form-container">
            <form id="categoryForm" method="POST" novalidate>
                
                <!-- Section 1: Informations de la Catégorie -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">📝</div>
                        <div>
                            <h2 class="section-title">Informations de la Catégorie</h2>
                            <p class="section-description">Définissez les détails de la catégorie</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Nom de la catégorie <span class="required">*</span></label>
                            <input type="text" id="nomC" name="nomC" placeholder="Ex: Électronique, Vêtements, Livres...">
                            <span class="error-message" id="nomC-error"></span>
                        </div>

                        <div class="form-group full-width">
                            <label>Description <span class="required">*</span></label>
                            <textarea id="descriptionC" name="descriptionC" placeholder="Décrivez brièvement cette catégorie..."></textarea>
                            <span class="error-message" id="descriptionC-error"></span>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Informations Complémentaires -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">ℹ️</div>
                        <div>
                            <h2 class="section-title">Informations Complémentaires</h2>
                            <p class="section-description">Détails administratifs</p>
                        </div>
                    </div>

                    <div class="form-grid">
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
                </section>

                <!-- Section 3: Validation -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">✓</div>
                        <div>
                            <h2 class="section-title">Validation</h2>
                            <p class="section-description">Confirmez la création</p>
                        </div>
                    </div>

                    <div class="attestation-box">
                        <label class="checkbox-special">
                            <input type="checkbox" id="attestation" name="attestation">
                            <span>J'atteste que les informations fournies sont exactes.</span>
                        </label>
                        <span class="error-message" id="attestation-error"></span>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='view/front office/ajouterProduit.php'">Annuler</button>
                        <button type="submit" class="btn-primary">🚀 Ajouter la catégorie</button>
                    </div>
                </section>
            </form>
        </div>
    </main>
    
    <script src="controle_saisie.js"></script>
</body>
</html>
