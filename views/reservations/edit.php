<?php
// Récupérer la réservation
$reservationData = $reservation->getById($id);
if (!$reservationData) {
    $_SESSION['message'] = "Réservation introuvable.";
    $_SESSION['message_type'] = 'error';
    header('Location: index.php?action=reservations');
    exit;
}

// Récupérer tous les événements pour le select
$events = $eventModel->getAllEvents();
?>

<div class="page-header">
    <div class="page-title">
        <h1>✏️ Modifier la Réservation</h1>
        <a href="?action=reservations" class="btn btn-secondary">← Retour</a>
    </div>
    <p class="page-subtitle">Référence: <?= htmlspecialchars($reservationData['reference']) ?></p>
</div>

<form action="process_reservation.php?action=update&id=<?= $reservationData['id'] ?>" method="POST" class="reservation-form">
    <!-- Informations Client -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">👤 Informations Client</h2>
        </div>
        
        <div class="form-group">
            <label for="nom_client" class="form-label">Nom complet *</label>
            <input type="text" id="nom_client" name="nom_client" class="form-control" 
                   required value="<?= htmlspecialchars($reservationData['nom_client']) ?>">
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-control" 
                       required value="<?= htmlspecialchars($reservationData['email']) ?>">
            </div>
            
            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone *</label>
                <input type="tel" id="telephone" name="telephone" class="form-control" 
                       required value="<?= htmlspecialchars($reservationData['telephone']) ?>">
            </div>
        </div>
    </div>
    
    <!-- Informations Réservation -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">🎭 Détails de la Réservation</h2>
        </div>
        
        <div class="form-group">
            <label for="event_id" class="form-label">Événement *</label>
            <select id="event_id" name="event_id" class="form-control" required>
                <option value="">-- Sélectionner un événement --</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['id'] ?>" 
                            data-price="<?= $event['prix'] ?>"
                            <?= ($event['id'] == $reservationData['event_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($event['titre']) ?> 
                        (<?= date('d/m/Y', strtotime($event['date_event'])) ?> - 
                        <?= number_format($event['prix'], 2, ',', ' ') ?> €)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="nombre_places" class="form-label">Nombre de places *</label>
                <input type="number" id="nombre_places" name="nombre_places" 
                       class="form-control" min="1" required 
                       value="<?= $reservationData['nombre_places'] ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Montant total</label>
                <div style="padding: 0.75rem 1rem; background: var(--bg-main); border-radius: var(--radius); font-size: 1.25rem; font-weight: 600; color: var(--primary);">
                    <span id="montant_total"><?= number_format($reservationData['montant_total'], 2, ',', ' ') ?></span> €
                    <input type="hidden" id="montant_total_input" name="montant_total" value="<?= $reservationData['montant_total'] ?>">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statut et Paiement -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">📊 Statut et Paiement</h2>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="statut" class="form-label">Statut *</label>
                <select id="statut" name="statut" class="form-control" required>
                    <option value="en attente" <?= ($reservationData['statut'] == 'en attente') ? 'selected' : '' ?>>En attente</option>
                    <option value="confirmée" <?= ($reservationData['statut'] == 'confirmée') ? 'selected' : '' ?>>Confirmée</option>
                    <option value="annulée" <?= ($reservationData['statut'] == 'annulée') ? 'selected' : '' ?>>Annulée</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="methode_paiement" class="form-label">Méthode de paiement</label>
                <select id="methode_paiement" name="methode_paiement" class="form-control">
                    <option value="carte" <?= ($reservationData['methode_paiement'] == 'carte') ? 'selected' : '' ?>>Carte bancaire</option>
                    <option value="especes" <?= ($reservationData['methode_paiement'] == 'especes') ? 'selected' : '' ?>>Espèces</option>
                    <option value="cheque" <?= ($reservationData['methode_paiement'] == 'cheque') ? 'selected' : '' ?>>Chèque</option>
                    <option value="virement" <?= ($reservationData['methode_paiement'] == 'virement') ? 'selected' : '' ?>>Virement</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="notes" class="form-label">Notes (optionnel)</label>
            <textarea id="notes" name="notes" class="form-control" rows="3"><?= htmlspecialchars($reservationData['notes'] ?? '') ?></textarea>
        </div>
    </div>
    
    <!-- Boutons d'action -->
    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">
            ✅ Enregistrer les modifications
        </button>
        <a href="?action=reservations" class="btn btn-secondary">
            ↩️ Annuler
        </a>
        <a href="process_reservation.php?action=delete&id=<?= $reservationData['id'] ?>" 
           class="btn btn-danger" 
           style="margin-left: auto;"
           onclick="return confirm('Supprimer définitivement cette réservation ?')">
            🗑️ Supprimer
        </a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventSelect = document.getElementById('event_id');
    const placesInput = document.getElementById('nombre_places');
    const montantTotal = document.getElementById('montant_total');
    const montantTotalInput = document.getElementById('montant_total_input');
    
    function calculateTotal() {
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const places = parseInt(placesInput.value) || 0;
        const total = price * places;
        
        if (montantTotal) montantTotal.textContent = total.toFixed(2).replace('.', ',');
        if (montantTotalInput) montantTotalInput.value = total.toFixed(2);
    }
    
    if (eventSelect && placesInput) {
        eventSelect.addEventListener('change', calculateTotal);
        placesInput.addEventListener('input', calculateTotal);
    }
    
    // Validation du formulaire
    const form = document.querySelector('.reservation-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--error)';
                    isValid = false;
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires (*).');
            }
        });
    }
});
</script>
```