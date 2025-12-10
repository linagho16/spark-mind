<?php
$pageTitle = "Détails de l'Événement";
ob_start();
?>

<div class="page-header">
    <h2>Détails de l'Événement</h2>
    <a href="index.php" class="btn btn-secondary">← Retour à la liste</a>
</div>

<div class="event-detail-card">
    <div class="event-detail-header">
        <h2><?php echo htmlspecialchars($event['titre']); ?></h2>
        <span class="event-price-large"><?php echo number_format($event['prix'], 2); ?> €</span>
    </div>
    
    <div class="event-detail-body">
        <div class="detail-section">
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        </div>

        <div class="detail-info-grid">
            <div class="detail-info-item">
                <strong>📍 Lieu:</strong>
                <p><?php echo htmlspecialchars($event['lieu']); ?></p>
            </div>
            <div class="detail-info-item">
                <strong>📅 Date:</strong>
                <p><?php echo date('d/m/Y', strtotime($event['date_event'])); ?></p>
            </div>
            <div class="detail-info-item">
                <strong>💰 Prix:</strong>
                <p><?php echo number_format($event['prix'], 2); ?> €</p>
            </div>
        </div>
    </div>

    <div class="event-detail-actions">
        <a href="index.php?action=edit&id=<?php echo $event['id']; ?>" class="btn btn-warning">Modifier</a>
        <a href="index.php?action=delete&id=<?php echo $event['id']; ?>" 
           class="btn btn-danger" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement?')">Supprimer</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>

