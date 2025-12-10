<?php
// Liste de tous les événements
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $events = $eventModel->search($search);
} else {
    $events = $eventModel->getAllEvents();
}
?>

<div class="container">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-dark);">
        📅 Tous les événements
    </h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="" class="search-form">
            <input type="hidden" name="action" value="events">
            <input type="text" 
                   name="search" 
                   class="search-input" 
                   placeholder="Rechercher un événement par titre, lieu, description..."
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="search-btn">🔍 Rechercher</button>
            <?php if ($search): ?>
                <a href="?action=events" class="btn btn-secondary">✖ Effacer</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($search): ?>
    <div style="background: var(--bg-card); padding: 1rem 1.5rem; border-radius: var(--radius); margin-bottom: 2rem; border-left: 4px solid var(--primary);">
        <p style="margin: 0;">
            🔍 <strong><?= count($events) ?></strong> résultat<?= count($events) > 1 ? 's' : '' ?> pour 
            "<strong><?= htmlspecialchars($search) ?></strong>"
        </p>
    </div>
    <?php endif; ?>

    <!-- Events Grid -->
    <?php if (empty($events)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>Aucun événement trouvé</h3>
            <p><?= $search ? 'Essayez avec d\'autres mots-clés' : 'Aucun événement disponible pour le moment' ?></p>
        </div>
    <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $event): ?>
            <div class="event-card" onclick="window.location.href='?action=event_detail&id=<?= $event['id'] ?>'">
                <div class="event-image">
                    🎭
                </div>
                <div class="event-content">
                    <h3 class="event-title"><?= htmlspecialchars($event['titre']) ?></h3>
                    <p class="event-description">
                        <?= htmlspecialchars(substr($event['description'], 0, 120)) ?>...
                    </p>
                    <div class="event-meta">
                        <div class="meta-item">
                            <span>📅</span>
                            <span><?= date('d/m/Y', strtotime($event['date_event'])) ?></span>
                        </div>
                        <div class="meta-item">
                            <span>📍</span>
                            <span><?= htmlspecialchars($event['lieu']) ?></span>
                        </div>
                        <?php if (!empty($event['duree'])): ?>
                        <div class="meta-item">
                            <span>⏱️</span>
                            <span><?= htmlspecialchars($event['duree']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="event-footer">
                        <div class="event-price">
                            <?= number_format($event['prix'], 2, ',', ' ') ?> €
                            <small>/place</small>
                        </div>
                        <a href="?action=book&id=<?= $event['id'] ?>" 
                           class="btn btn-book" 
                           onclick="event.stopPropagation()">
                            Réserver
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
