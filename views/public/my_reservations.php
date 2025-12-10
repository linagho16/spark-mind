<?php
// Mes réservations - Recherche par email
$searchEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$myReservations = [];

if ($searchEmail) {
    // Rechercher les réservations par email
    $stmt = $pdo->prepare("
        SELECT r.*, e.titre as event_titre, e.date_event, e.lieu, e.prix
        FROM reservations r
        JOIN events e ON r.event_id = e.id
        WHERE r.email = :email
        ORDER BY r.date_reservation DESC
    ");
    $stmt->execute([':email' => $searchEmail]);
    $myReservations = $stmt->fetchAll();
}
?>

<div class="container">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-dark);">
        📋 Mes réservations
    </h1>

    <!-- Search by Email -->
    <div class="search-bar">
        <form method="GET" action="" class="search-form">
            <input type="hidden" name="action" value="my_reservations">
            <input type="email" 
                   name="email" 
                   class="search-input" 
                   placeholder="Entrez votre email pour retrouver vos réservations..."
                   value="<?= htmlspecialchars($searchEmail) ?>"
                   required>
            <button type="submit" class="search-btn">🔍 Rechercher mes réservations</button>
            <?php if ($searchEmail): ?>
                <a href="?action=my_reservations" class="btn btn-secondary">✖ Effacer</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!$searchEmail): ?>
        <div style="background: var(--bg-card); padding: 3rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📧</div>
            <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Retrouvez vos réservations</h3>
            <p style="color: var(--text-medium); max-width: 600px; margin: 0 auto;">
                Entrez l'adresse email utilisée lors de votre réservation pour consulter toutes vos réservations et leurs détails.
            </p>
        </div>
    <?php elseif (empty($myReservations)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>Aucune réservation trouvée</h3>
            <p>Aucune réservation n'a été trouvée pour l'email <strong><?= htmlspecialchars($searchEmail) ?></strong></p>
            <a href="?action=events" class="btn btn-primary">Découvrir nos événements</a>
        </div>
    <?php else: ?>
        <div style="background: var(--bg-card); padding: 1rem 1.5rem; border-radius: var(--radius); margin-bottom: 2rem; border-left: 4px solid var(--success);">
            <p style="margin: 0;">
                ✅ <strong><?= count($myReservations) ?></strong> réservation<?= count($myReservations) > 1 ? 's' : '' ?> trouvée<?= count($myReservations) > 1 ? 's' : '' ?> pour 
                <strong><?= htmlspecialchars($searchEmail) ?></strong>
            </p>
        </div>

        <?php foreach ($myReservations as $res): ?>
        <div class="reservation-card">
            <div class="reservation-header">
                <div>
                    <div class="reservation-ref">
                        🎫 <?= htmlspecialchars($res['reference']) ?>
                    </div>
                    <div style="color: var(--text-medium); font-size: 0.9rem; margin-top: 0.25rem;">
                        Réservé le <?= date('d/m/Y à H:i', strtotime($res['date_reservation'])) ?>
                    </div>
                </div>
                <span class="badge <?= $res['statut'] === 'confirmée' ? 'badge-success' : ($res['statut'] === 'annulée' ? 'badge-danger' : 'badge-warning') ?>">
                    <?= $res['statut'] === 'confirmée' ? '✅ Confirmée' : ($res['statut'] === 'annulée' ? '❌ Annulée' : '⏳ En attente') ?>
                </span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 0.5rem;">
                    <?= htmlspecialchars($res['event_titre']) ?>
                </h3>
            </div>

            <div class="reservation-body">
                <div class="info-item">
                    <span class="info-label">📅 Date événement</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($res['date_event'])) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">📍 Lieu</span>
                    <span class="info-value"><?= htmlspecialchars($res['lieu']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">👤 Nom</span>
                    <span class="info-value"><?= htmlspecialchars($res['nom_client']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">📞 Téléphone</span>
                    <span class="info-value"><?= htmlspecialchars($res['telephone']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">🎫 Places</span>
                    <span class="info-value"><?= $res['nombre_places'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">💰 Montant total</span>
                    <span class="info-value" style="color: var(--primary); font-size: 1.2rem;">
                        <?= number_format($res['montant_total'], 2, ',', ' ') ?> €
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">💳 Paiement</span>
                    <span class="info-value"><?= htmlspecialchars($res['methode_paiement'] ?? 'Non spécifié') ?></span>
                </div>
            </div>

            <?php if (!empty($res['notes'])): ?>
            <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-main); border-radius: var(--radius);">
                <div style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0.25rem;">📝 Notes :</div>
                <div style="color: var(--text-dark);"><?= nl2br(htmlspecialchars($res['notes'])) ?></div>
            </div>
            <?php endif; ?>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--secondary); display: flex; gap: 1rem;">
                <a href="?action=event_detail&id=<?= $res['event_id'] ?>" class="btn btn-secondary">
                    👁️ Voir l'événement
                </a>
                <a href="?action=reservation_detail&id=<?= $res['id'] ?>&email=<?= urlencode($searchEmail) ?>" class="btn btn-primary">
                    📄 Détails complets
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
