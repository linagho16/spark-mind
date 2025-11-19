<?php
require_once('controller/produitC.php');
require_once('model/produit.php');

$produitC = new ProduitC();
$message = '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$produitData = null;

if ($id) {
    try {
        $produitData = $produitC->showProduit($id);
        if (!$produitData) {
            $message = "Produit introuvable.";
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
} else {
    $message = "Identifiant du produit manquant.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produitData) {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $condition = isset($_POST['condition']) ? trim($_POST['condition']) : '';
    $statut = isset($_POST['statut']) ? trim($_POST['statut']) : '';
    $currentPhoto = isset($_POST['current_photo']) ? $_POST['current_photo'] : '';

    $errors = [];

    if (empty($title)) $errors[] = "Le titre est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";
    if (empty($category)) $errors[] = "La catégorie est obligatoire.";
    if (empty($condition)) $errors[] = "La condition est obligatoire.";
    if (empty($statut)) $errors[] = "Le statut est obligatoire.";

    $photoPath = $currentPhoto;

    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photoPath = $targetPath;
        } else {
            $errors[] = "Erreur lors du téléchargement de l'image.";
        }
    }

    if (empty($photoPath)) {
        $errors[] = "La photo est obligatoire.";
    }

    if (empty($errors)) {
        $produitObj = new produit(
            $id,
            $title,
            $description,
            $category,
            $condition,
            $statut,
            $photoPath
        );

        try {
            $produitC->updateProduit($produitObj, $id);
            $message = "Produit mis à jour avec succès.";
            $produitData = $produitC->showProduit($id);
        } catch (Exception $e) {
            $message = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    } else {
        $message = implode('<br>', $errors);
    }
}

$categories = ['alimentaire', 'scolaire', 'vestimentaire', 'medicale', 'financiere', 'psychologique'];
$conditions = ['neuf', 'bon etat', 'usage'];
$statuts = ['disponible', 'reserve'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Mise à jour du produit</title>
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
        .preview {
            margin-top: 10px;
            border-radius: 10px;
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
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
            <h1>Modifier le produit</h1>
            <div class="top-bar-actions">
                <div class="user-profile">
                    <span class="user-name">Admin</span>
                    <div class="user-avatar">A</div>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo empty($errors) ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($produitData): ?>
            <div class="form-card">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($produitData['photo']); ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Titre</label>
                            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($produitData['title']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="category">Catégorie</label>
                            <select name="category" id="category">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat; ?>" <?php echo ($produitData['category'] === $cat) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="condition">Condition</label>
                            <select name="condition" id="condition">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($conditions as $cond): ?>
                                    <option value="<?php echo $cond; ?>" <?php echo ($produitData['condition'] === $cond) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($cond); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <select name="statut" id="statut">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($statuts as $stat): ?>
                                    <option value="<?php echo $stat; ?>" <?php echo ($produitData['statut'] === $stat) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($stat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"><?php echo htmlspecialchars($produitData['description']); ?></textarea>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/*">
                        <?php if (!empty($produitData['photo'])): ?>
                            <img src="<?php echo htmlspecialchars($produitData['photo']); ?>" alt="Aperçu" class="preview">
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Enregistrer</button>
                        <a href="detail.php?id=<?php echo $produitData['id']; ?>" class="btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center;">Annuler</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
