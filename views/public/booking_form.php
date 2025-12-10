<?php
// Formulaire de réservation
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
$placesDisponibles = 100 - $placesReservees;
?>

<div class="container">
    <a href="?action=event_detail&id=<?= $eventId ?>" style="display: inline-block; margin-bottom: 2rem; color: var(--primary); text-decoration: none; font-weight: 600;">
        ← Retour à l'événement
    </a>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
        <!-- Event Summary -->
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); position: sticky; top: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">📋 Résumé de l'événement</h2>
            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--secondary);">
                <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem;">
                    <?= htmlspecialchars($event['titre']) ?>
                </h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">📅</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Date</div>
                        <div style="font-weight: 600;"><?= date('d/m/Y', strtotime($event['date_event'])) ?></div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">📍</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Lieu</div>
                        <div style="font-weight: 600;"><?= htmlspecialchars($event['lieu']) ?></div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">💰</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Prix par place</div>
                        <div style="font-weight: 600; font-size: 1.3rem; color: var(--primary);">
                            <?= number_format($event['prix'], 2, ',', ' ') ?> €
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">🎫</span>
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text-medium);">Places disponibles</div>
                        <div style="font-weight: 600; color: <?= $placesDisponibles < 10 ? 'var(--error)' : 'var(--success)' ?>;">
                            <?= $placesDisponibles ?> / 100
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="totalPreview" style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-main); border-radius: var(--radius); text-align: center;">
                <div style="font-size: 0.9rem; color: var(--text-medium); margin-bottom: 0.5rem;">Montant total</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                    <span id="totalAmount">0.00</span> €
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="booking-form">
            <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">🎫 Réserver votre place</h2>
            
            <form action="process_public_reservation.php" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="event_id" value="<?= $eventId ?>">
                <input type="hidden" name="prix_unitaire" value="<?= $event['prix'] ?>">
                
                <div class="form-group">
                    <label class="form-label">👤 Nom complet *</label>
                    <input type="text" name="nom_client" class="form-control" required 
                           placeholder="Votre nom et prénom">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">📧 Email *</label>
                        <input type="email" name="email" class="form-control" required 
                               placeholder="votre@email.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">📞 Téléphone *</label>
                        <input type="tel" name="telephone" class="form-control" required 
                               placeholder="06 12 34 56 78">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">🎫 Nombre de places * (max: <?= $placesDisponibles ?>)</label>
                    <input type="number" name="nombre_places" id="nombrePlaces" 
                           class="form-control" required min="1" max="<?= $placesDisponibles ?>" 
                           value="1" onchange="updateTotal()">
                </div>
                
                <div class="form-group">
                    <label class="form-label">💳 Méthode de paiement *</label>
                    <select name="methode_paiement" class="form-control" required>
                        <option value="carte">Carte bancaire</option>
                        <option value="especes">Espèces</option>
                        <option value="virement">Virement</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">📝 Notes (optionnel)</label>
                    <textarea name="notes" class="form-control" rows="3" 
                              placeholder="Informations complémentaires..."></textarea>
                </div>
                
                <div style="background: #E8F5E9; padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: #2E7D32; font-size: 0.9rem;">
                        ℹ️ Vous recevrez un email de confirmation avec votre référence de réservation.
                    </p>
                </div>
                
                <button type="submit" class="btn btn-book" style="width: 100%; font-size: 1.1rem; padding: 1.25rem;">
                    ✅ Confirmer la réservation
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const prixUnitaire = <?= $event['prix'] ?>;

function updateTotal() {
    const places = parseInt(document.getElementById('nombrePlaces').value) || 0;
    const total = (places * prixUnitaire).toFixed(2);
    document.getElementById('totalAmount').textContent = total;
}

function validateForm() {
    const places = parseInt(document.getElementById('nombrePlaces').value);
    const maxPlaces = <?= $placesDisponibles ?>;
    
    if (places < 1) {
        alert('Veuillez réserver au moins 1 place.');
        return false;
    }
    
    if (places > maxPlaces) {
        alert(`Seulement ${maxPlaces} places disponibles.`);
        return false;
    }
    
    return confirm(`Confirmer la réservation de ${places} place(s) pour un total de ${(places * prixUnitaire).toFixed(2)} € ?`);
}

// Initialize total
updateTotal();
</script>
