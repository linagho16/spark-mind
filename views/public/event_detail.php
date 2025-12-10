<?php
// Détail d'un événement
if (!$eventId) {
    header('Location: ?action=events');
    exit;
}

$event = $eventModel->getEventById($eventId);
if (!$event) {
    $_SESSION['message'] = "Événement introuvable.";
    $_SESSION['message_type'] = 'error';
    header('Location: ?action=events');
    exit;
}

$eventReservations = $reservation->getByEvent($eventId);
$placesReservees = array_sum(array_column($eventReservations, 'nombre_places'));
$placesDisponibles = 100 - $placesReservees; // Assuming 100 places per event
?>

<div class="container">
    <a href="?action=events" style="display: inline-block; margin-bottom: 2rem; color: var(--primary); text-decoration: none; font-weight: 600;">
        ← Retour aux événements
    </a>

    <div class="event-detail">
        <div class="event-header">
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-dark);">
                <?= htmlspecialchars($event['titre']) ?>
            </h1>
            <p style="font-size: 1.2rem; color: var(--text-medium); line-height: 1.8;">
                <?= nl2br(htmlspecialchars($event['description'])) ?>
            </p>
        </div>

        <div class="event-detail-meta">
            <div class="detail-item">
                <div class="detail-label">📅 Date</div>
                <div class="detail-value"><?= date('d/m/Y', strtotime($event['date_event'])) ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">📍 Lieu</div>
                <div class="detail-value"><?= htmlspecialchars($event['lieu']) ?></div>
            </div>
            <?php if (!empty($event['duree'])): ?>
            <div class="detail-item">
                <div class="detail-label">⏱️ Durée</div>
                <div class="detail-value"><?= htmlspecialchars($event['duree']) ?></div>
            </div>
            <?php endif; ?>
            <div class="detail-item">
                <div class="detail-label">💰 Prix</div>
                <div class="detail-value"><?= number_format($event['prix'], 2, ',', ' ') ?> €</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">🎫 Places disponibles</div>
                <div class="detail-value" style="<?= $placesDisponibles < 10 ? 'color: var(--error);' : '' ?>">
                    <?= $placesDisponibles ?> / 100
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">👥 Réservations</div>
                <div class="detail-value"><?= count($eventReservations) ?></div>
            </div>
        </div>

        <?php if ($placesDisponibles > 0): ?>
        <div style="margin-top: 3rem; text-align: center; padding: 2rem; background: var(--bg-main); border-radius: var(--radius-lg);">
            <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Prêt à réserver ?</h3>
            <p style="color: var(--text-medium); margin-bottom: 2rem;">
                Réservez dès maintenant votre place pour cet événement !
            </p>
            <a href="?action=book&id=<?= $event['id'] ?>" class="btn btn-book" style="font-size: 1.2rem; padding: 1.25rem 3rem;">
                🎫 Réserver maintenant
            </a>
        </div>
        <?php else: ?>
        <div style="margin-top: 3rem; text-align: center; padding: 2rem; background: #FFF3CD; border-radius: var(--radius-lg); color: #856404;">
            <h3 style="margin-bottom: 0.5rem;">😔 Complet</h3>
            <p style="margin: 0;">Toutes les places pour cet événement ont été réservées.</p>
        </div>
        <?php endif; ?>

        <?php if (!empty($eventReservations)): ?>
        <div style="margin-top: 3rem;">
            <h3 style="margin-bottom: 1rem; color: var(--text-dark);">📊 Statistiques de l'événement</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?= $placesReservees ?></div>
                    <div style="color: var(--text-medium); font-size: 0.9rem;">Places réservées</div>
                </div>
                <div style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--success);">
                        <?= count(array_filter($eventReservations, fn($r) => $r['statut'] === 'confirmée')) ?>
                    </div>
                    <div style="color: var(--text-medium); font-size: 0.9rem;">Confirmées</div>
                </div>
                <div style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--warning);">
                        <?= count(array_filter($eventReservations, fn($r) => $r['statut'] === 'en attente')) ?>
                    </div>
                    <div style="color: var(--text-medium); font-size: 0.9rem;">En attente</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
