<?php include __DIR__ . '/../../templates/front_header.php'; ?>

<div class="container">
    <h1>🎉 Événements Communautaires</h1>
    
    <div class="header-actions">
        <a href="index.php?route=events.create" class="btn">➕ Créer un événement</a>
    </div>

    <?php if(empty($events)): ?>
        <div class="no-events">
            <p>📭 Aucun événement pour le moment. Créez le premier !</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th>Lieu</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $e): ?>
                <tr>
                    <td>
                        <?php if(!empty($e['image'])): ?>
                            <img src="/evenement/uplodes/events/<?= htmlspecialchars($e['image']) ?>" 
                                 alt="<?= htmlspecialchars($e['titre']) ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                📷
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($e['id'] ?? '') ?></td>
                    <td><strong><?= htmlspecialchars($e['titre'] ?? '') ?></strong></td>
                    <td>
                        <?php if(!empty($e['categorie_nom'])): ?>
                            <span class="categorie-badge" style="background: <?= $e['categorie_couleur'] ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                <?= htmlspecialchars($e['categorie_nom']) ?>
                            </span>
                        <?php else: ?>
                            <span style="color: #6c757d; font-style: italic;">Aucune</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($e['date_event'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['lieu'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['prix'] ?? '') ?> €</td>
                    <td class="actions">
                        <a href="index.php?route=events.show&id=<?= $e['id'] ?>">👁️ Voir</a>
                        <a href="index.php?route=events.edit&id=<?= $e['id'] ?>">✏️ Éditer</a>
                        <a href="index.php?route=events.delete&id=<?= $e['id'] ?>" 
                           class="delete"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../templates/front_footer.php'; ?>