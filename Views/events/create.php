<?php include __DIR__ . '/../../templates/front_header.php'; ?>

<div class="container">
    <h1>Créer un nouvel événement</h1>
    
    <?php if(!empty($errors)): ?>
        <div class="alert alert-error">
            <h3>❌ Erreurs de validation :</h3>
            <ul>
                <?php foreach($errors as $field => $message): ?>
                    <li><strong><?= ucfirst($field) ?>:</strong> <?= htmlspecialchars($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?route=events.create" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre" class="required">Titre de l'événement</label>
            <input type="text" id="titre" name="titre" 
                   value="<?= htmlspecialchars($data['titre'] ?? '') ?>" 
                   placeholder="Entrez le titre de l'événement" 
                   class="<?= isset($errors['titre']) ? 'error-field' : '' ?>">
            <?php if(isset($errors['titre'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['titre']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" 
                      placeholder="Décrivez l'événement..."
                      class="<?= isset($errors['description']) ? 'error-field' : '' ?>"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
            <?php if(isset($errors['description'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['description']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="date_event" class="required">Date de l'événement</label>
            <input type="date" id="date_event" name="date_event" 
                   value="<?= htmlspecialchars($data['date_event'] ?? '') ?>" 
                   class="<?= isset($errors['date_event']) ? 'error-field' : '' ?>">
            <?php if(isset($errors['date_event'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['date_event']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="lieu" class="required">Lieu</label>
            <input type="text" id="lieu" name="lieu" 
                   value="<?= htmlspecialchars($data['lieu'] ?? '') ?>" 
                   placeholder="Où se déroule l'événement ?"
                   class="<?= isset($errors['lieu']) ? 'error-field' : '' ?>">
            <?php if(isset($errors['lieu'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['lieu']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="prix">Prix (€)</label>
            <input type="number" id="prix" name="prix" step="0.01" 
                   value="<?= htmlspecialchars($data['prix'] ?? '0.00') ?>" 
                   placeholder="0.00"
                   class="<?= isset($errors['prix']) ? 'error-field' : '' ?>">
            <?php if(isset($errors['prix'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['prix']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="categorie_id">Catégorie</label>
            <select id="categorie_id" name="categorie_id" class="<?= isset($errors['categorie_id']) ? 'error-field' : '' ?>">
                <option value="">-- Sélectionnez une catégorie --</option>
                <?php foreach($categories as $categorie): ?>
                    <option value="<?= $categorie['id'] ?>" 
                        <?= (isset($data['categorie_id']) && $data['categorie_id'] == $categorie['id']) ? 'selected' : '' ?>
                        style="color: <?= $categorie['couleur'] ?>; font-weight: bold;">
                        <?= htmlspecialchars($categorie['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($errors['categorie_id'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['categorie_id']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="image">Image de l'événement</label>
            <input type="file" id="image" name="image" 
                   accept="image/jpeg, image/png, image/gif, image/webp"
                   class="<?= isset($errors['image']) ? 'error-field' : '' ?>">
            <small style="color: #6c757d; display: block; margin-top: 5px;">
                Formats acceptés: JPEG, PNG, GIF, WebP (max. 5MB)
            </small>
            <?php if(isset($errors['image'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['image']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">💾 Enregistrer l'événement</button>
            <a href="index.php?route=events.index" class="btn" style="background: #95a5a6;">❌ Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../templates/front_footer.php'; ?>