<?php
// Détail d'une réservation
$resId = $_GET['id'] ?? null;
$searchEmail = $_GET['email'] ?? '';

if (!$resId || !$searchEmail) {
    header('Location: ?action=my_reservations');
    exit;
}

// Récupérer la réservation avec vérification de l'email
$stmt = $pdo->prepare("
    SELECT r.*, e.titre as event_titre, e.description as event_description, 
           e.date_event, e.lieu, e.prix, e.duree
    FROM reservations r
    JOIN events e ON r.event_id = e.id
    WHERE r.id = :id AND r.email = :email
");
$stmt->execute([':id' => $resId, ':email' => $searchEmail]);
$res = $stmt->fetch();

if (!$res) {
    $_SESSION['message'] = "Réservation introuvable ou accès non autorisé.";
    $_SESSION['message_type'] = 'error';
    header('Location: ?action=my_reservations');
    exit;
}
?>

<div class="container">
    <a href="?action=my_reservations&email=<?= urlencode($searchEmail) ?>" style="display: inline-block; margin-bottom: 2rem; color: var(--primary); text-decoration: none; font-weight: 600;">
        ← Retour à mes réservations
    </a>

    <div class="event-detail">
        <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border-radius: var(--radius-lg) var(--radius-lg) 0 0; margin: -3rem -3rem 2rem -3rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎫</div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Détails de votre réservation</h1>
            <div style="font-size: 1.5rem; font-weight: 700; opacity: 0.95;">
                <?= htmlspecialchars($res['reference']) ?>
            </div>
        </div>

        <!-- Status Badge -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <span class="badge <?= $res['statut'] === 'confirmée' ? 'badge-success' : ($res['statut'] === 'annulée' ? 'badge-danger' : 'badge-warning') ?>" 
                  style="font-size: 1.1rem; padding: 0.75rem 2rem;">
                <?= $res['statut'] === 'confirmée' ? '✅ Réservation confirmée' : ($res['statut'] === 'annulée' ? '❌ Réservation annulée' : '⏳ En attente de confirmation') ?>
            </span>
        </div>

        <!-- Event Info -->
        <div style="background: var(--bg-main); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
                <span>🎭</span> Événement
            </h2>
            <h3 style="font-size: 1.8rem; color: var(--primary); margin-bottom: 1rem;">
                <?= htmlspecialchars($res['event_titre']) ?>
            </h3>
            <p style="color: var(--text-medium); line-height: 1.8; margin-bottom: 1.5rem;">
                <?= nl2br(htmlspecialchars($res['event_description'])) ?>
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">📅</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Date</div>
                        <div style="font-weight: 600;"><?= date('d/m/Y', strtotime($res['date_event'])) ?></div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">📍</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Lieu</div>
                        <div style="font-weight: 600;"><?= htmlspecialchars($res['lieu']) ?></div>
                    </div>
                </div>
                <?php if (!empty($res['duree'])): ?>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">⏱️</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Durée</div>
                        <div style="font-weight: 600;"><?= htmlspecialchars($res['duree']) ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Client Info -->
        <div style="background: var(--bg-card); border: 2px solid var(--secondary); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
                <span>👤</span> Informations client
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div class="info-item">
                    <span class="info-label">Nom complet</span>
                    <span class="info-value"><?= htmlspecialchars($res['nom_client']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($res['email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Téléphone</span>
                    <span class="info-value"><?= htmlspecialchars($res['telephone']) ?></span>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div style="background: var(--bg-card); border: 2px solid var(--primary); padding: 2rem; border-radius: var(--radius-lg);">
            <h2 style="margin-bottom: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
                <span>💰</span> Détails de la réservation
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div class="detail-item">
                    <div class="detail-label">🎫 Nombre de places</div>
                    <div class="detail-value" style="font-size: 2rem;"><?= $res['nombre_places'] ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">💵 Prix unitaire</div>
                    <div class="detail-value"><?= number_format($res['prix'], 2, ',', ' ') ?> €</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">💳 Méthode paiement</div>
                    <div class="detail-value"><?= htmlspecialchars($res['methode_paiement'] ?? 'Non spécifié') ?></div>
                </div>
                <div class="detail-item" style="background: var(--primary); color: white;">
                    <div class="detail-label" style="color: rgba(255,255,255,0.9);">💰 MONTANT TOTAL</div>
                    <div class="detail-value" style="font-size: 2rem; color: white;">
                        <?= number_format($res['montant_total'], 2, ',', ' ') ?> €
                    </div>
                </div>
            </div>

            <div style="padding: 1rem; background: var(--bg-main); border-radius: var(--radius);">
                <div style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0.25rem;">
                    📅 Date de réservation
                </div>
                <div style="font-weight: 600;">
                    <?= date('d/m/Y à H:i:s', strtotime($res['date_reservation'])) ?>
                </div>
            </div>

            <?php if (!empty($res['notes'])): ?>
            <div style="margin-top: 1.5rem; padding: 1rem; background: var(--bg-main); border-radius: var(--radius);">
                <div style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0.5rem;">📝 Notes :</div>
                <div style="color: var(--text-dark); line-height: 1.6;"><?= nl2br(htmlspecialchars($res['notes'])) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div style="margin-top: 2rem; text-align: center;">
            <a href="?action=event_detail&id=<?= $res['event_id'] ?>" class="btn btn-primary" style="margin-right: 1rem;">
                👁️ Voir l'événement
            </a>
            <a href="?action=my_reservations&email=<?= urlencode($searchEmail) ?>" class="btn btn-secondary">
                📋 Mes autres réservations
            </a>
        </div>
    </div>
</div>
