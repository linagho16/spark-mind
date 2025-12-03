<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Modifier le post - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>SparkMind</h1>
                <p class="subtitle">Forum de donations</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="index.php?action=show&id=<?= $post['id'] ?>" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au post
        </a>

        <div class="post" style="max-width: 700px; margin: 0 auto; width: 100%;">
            <h2>✏️ Modifier le post</h2>

            <form method="post" action="index.php?action=update" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $post['id'] ?>">

                <div class="form-group">
                    <label>Type de donation *</label>
                    <select name="donation_type_id" required>
                        <option value="">Sélectionner un type</option>
                        <?php foreach($donation_types as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= $post['donation_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Titre (optionnel)</label>
                    <input type="text" name="titre" value="<?= htmlspecialchars($post['titre'] ?? '') ?>" 
                           placeholder="Ex: Don de vêtements d'hiver" />
                </div>

                <div class="form-group">
                    <label>Message *</label>
                    <textarea name="contenu" rows="6" required><?= htmlspecialchars($post['contenu']) ?></textarea>
                </div>

                <?php if (!empty($post['image'])): ?>
                    <div class="form-group">
                        <label>Image actuelle</label>
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image actuelle" 
                             style="max-width: 300px; border-radius: 12px; margin-top: 10px;">
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label><?= !empty($post['image']) ? 'Changer l\'image' : 'Ajouter une image' ?></label>
                    <div class="file-input-wrapper">
                        <input type="file" name="image" id="imageInput" accept="image/*">
                        <label for="imageInput" class="file-input-label">
                            📷 <?= !empty($post['image']) ? 'Changer l\'image' : 'Ajouter une image' ?>
                        </label>
                    </div>
                </div>

                <button type="submit">💾 Enregistrer les modifications</button>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const label = this.nextElementSibling;
            if (this.files && this.files[0]) {
                label.textContent = '✅ ' + this.files[0].name;
            }
        });
    </script>
</body>
</html>