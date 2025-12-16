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
    <link rel="stylesheet" href="formlaire.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>SparkMind</h2>
            <p>« Quand la pensée devient espoir. »</p>
        </div>
        
        <nav class="nav-menu">
            <a href="index.php" class="nav-item">
                <span>🏠</span>
                <span>Accueil</span>
            </a>
            <a href="liste_produits.php" class="nav-item">
                <span>📦</span>
                <span>Produits</span>
            </a>
            <a href="ajouterProduit.php" class="nav-item active">
                <span>➕</span>
                <span>Ajouter un don</span>
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
                <h1>Ajouter un Produit</h1>
                <p class="subtitle">Partagez vos objets avec la communauté</p>
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
            <form id="productForm" method="POST" enctype="multipart/form-data" novalidate>
                
                <!-- Section 1: Informations du Produit -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">📝</div>
                        <div>
                            <h2 class="section-title">Informations du Produit</h2>
                            <p class="section-description">Décrivez votre produit en détail</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Titre du produit <span class="required">*</span></label>
                            <input type="text" id="title" name="title" placeholder="Ex: iPhone 13 Pro, Laptop Dell...">
                            <span class="error-message" id="title-error"></span>
                        </div>

                        <div class="form-group full-width">
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
                            <p class="helper-text">
                                <a href="../../ajouterCategorie.php" style="color: #166e6a; text-decoration: none; font-weight: 600;">
                                    + Ajouter une nouvelle catégorie
                                </a>
                            </p>
                            <span class="error-message" id="category-error"></span>
                        </div>

                        <div class="form-group">
                            <label>Photo du produit <span class="required">*</span></label>
                            <input type="file" id="photo" name="photo" accept="image/*">
                            <p class="helper-text">📸 Ajoutez une photo claire de votre produit</p>
                            <span class="error-message" id="photo-error"></span>
                        </div>
                    </div>
                </section>

                <!-- Section 2: État et Disponibilité -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div>
                            <h2 class="section-title">État et Disponibilité</h2>
                            <p class="section-description">Précisez l'état de votre produit</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Condition <span class="required">*</span></label>
                            <div class="radio-grid">
                                <label class="radio-card">
                                    <input type="radio" name="condition" value="neuf">
                                    <div class="radio-content">
                                        <div class="urgency-badge" style="background: #e8f5e9;">✨</div>
                                        <strong>Neuf</strong>
                                        <small>Jamais utilisé</small>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="condition" value="bon etat">
                                    <div class="radio-content">
                                        <div class="urgency-badge" style="background: #fff3e0;">👍</div>
                                        <strong>Bon état</strong>
                                        <small>Légèrement utilisé</small>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="condition" value="usage">
                                    <div class="radio-content">
                                        <div class="urgency-badge" style="background: #fce4ec;">🔧</div>
                                        <strong>Usagé</strong>
                                        <small>Utilisé régulièrement</small>
                                    </div>
                                </label>
                            </div>
                            <span class="error-message" id="condition-error"></span>
                        </div>

                        <div class="form-group full-width">
                            <label>Statut <span class="required">*</span></label>
                            <div class="checkbox-grid-compact">
                                <label class="checkbox-compact">
                                    <input type="radio" name="statut" value="disponible">
                                    <span>✅ Disponible - Prêt à être donné</span>
                                </label>
                                <label class="checkbox-compact">
                                    <input type="radio" name="statut" value="reserve">
                                    <span>⏳ Réservé - En attente</span>
                                </label>
                            </div>
                            <span class="error-message" id="statut-error"></span>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Validation -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">✓</div>
                        <div>
                            <h2 class="section-title">Validation</h2>
                            <p class="section-description">Confirmez les informations</p>
                        </div>
                    </div>

                    <div class="attestation-box">
                        <label class="checkbox-special">
                            <input type="checkbox" id="attestation" name="attestation">
                            <span>J'atteste que les informations fournies sont exactes et que ce produit m'appartient.</span>
                        </label>
                        <span class="error-message" id="attestation-error"></span>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">Annuler</button>
                        <button type="submit" class="btn-primary">🚀 Ajouter le produit</button>
                    </div>
                </section>
            </form>
        </div>
    </main>
    
    <script src="validation.js"></script>
</body>
</html>
