<?php include __DIR__ . '/../../templates/front_header.php'; ?>

<div class="container">
    <?php if(!empty($event['image'])): ?>
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="/evenement/uplodes/events/<?= htmlspecialchars($event['image']) ?>" 
                 alt="<?= htmlspecialchars($event['titre']) ?>" 
                 style="max-width: 100%; max-height: 400px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        </div>
    <?php endif; ?>
    
    <h1><?= htmlspecialchars($event['titre']) ?></h1>
    
    <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px;">
        <p><strong>📅 Date:</strong> <?= htmlspecialchars($event['date_event']) ?></p>
        <p><strong>📍 Lieu:</strong> <?= htmlspecialchars($event['lieu']) ?></p>
        <p><strong>💰 Prix:</strong> <?= htmlspecialchars($event['prix']) ?> €</p>
        
        <?php if(!empty($event['categorie_nom'])): ?>
            <p><strong>🏷️ Catégorie:</strong> 
                <span class="categorie-badge" style="background: <?= $event['categorie_couleur'] ?>; color: white; padding: 4px 8px; border-radius: 12px;">
                    <?= htmlspecialchars($event['categorie_nom']) ?>
                </span>
            </p>
        <?php endif; ?>
        
        <?php if(!empty($event['description'])): ?>
            <p><strong>📝 Description:</strong></p>
            <div style="background: white; padding: 1rem; border-radius: 4px; border-left: 4px solid #3498db;">
                <?= nl2br(htmlspecialchars($event['description'])) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="margin-top: 2rem;">
        <a href="index.php?route=events.index" class="btn">← Retour à la liste</a>
        <a href="index.php?route=events.edit&id=<?= $event['id'] ?>" class="btn" style="background: #f39c12;">✏️ Modifier</a>
    </div>
</div>

<?php include __DIR__ . '/../../templates/front_footer.php'; ?>