<?php
// Vérifier que $events est défini et n'est pas vide
if (!isset($events) || empty($events)) {
    echo '<div class="alert alert-warning">Aucun événement disponible. Veuillez créer un événement d\'abord.</div>';
    echo '<a href="?action=create_event" class="btn btn-primary">Créer un événement</a>';
    return;
}
?>

<div class="page-title">
    <h1>➕ Nouvelle Réservation</h1>
    <p>Remplissez le formulaire pour créer une nouvelle réservation</p>
</div>

<form action="process_reservation.php?action=create" method="POST" class="reservation-form">
    <!-- Informations Client -->
    <div class="card">
        <h2>👤 Informations Client</h2>
        
        <div class="form-group">
            <label for="nom_client">Nom complet *</label>
            <input type="text" id="nom_client" name="nom_client" class="form-control" 
                   required 
                   placeholder="Ex: Jean Dupont">
        </div>
        
        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="exemple@email.com">
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" class="form-control" 
                       placeholder="06 12 34 56 78">
            </div>
        </div>
    </div>
    
    <!-- Sélection Événement -->
    <div class="card" style="margin-top: 30px;">
        <h2>🎭 Sélectionner un événement *</h2>
        
        <div class="form-group">
            <label for="event_id">Choisissez un événement</label>
            <select id="event_id" name="event_id" class="form-control" required>
                <option value="">-- Veuillez sélectionner un événement --</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>" 
                            data-price="<?php echo $event['prix']; ?>">
                        <?php echo htmlspecialchars($event['titre']); ?> 
                        (<?php echo date('d/m/Y', strtotime($event['date_event'])); ?> - 
                        <?php echo number_format($event['prix'], 2, ',', ' '); ?> €)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="nombre_places">Nombre de places *</label>
            <input type="number" id="nombre_places" name="nombre_places" 
                   class="form-control" min="1" value="1" required>
        </div>
        
        <div class="form-group">
            <label>Montant total</label>
            <div style="padding: 12px; background: #f8f9fa; border-radius: 6px; font-size: 1.2em;">
                <span id="montant_total">0.00</span> €
                <input type="hidden" id="montant_total_input" name="montant_total" value="0">
            </div>
        </div>
    </div>
    
    <!-- Informations complémentaires -->
    <div class="card" style="margin-top: 30px;">
        <h2>💳 Méthode de paiement</h2>
        
        <div class="form-group">
            <label for="methode_paiement">Méthode de paiement</label>
            <select id="methode_paiement" name="methode_paiement" class="form-control">
                <option value="carte">Carte bancaire</option>
                <option value="especes">Espèces</option>
                <option value="cheque">Chèque</option>
                <option value="virement">Virement</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes (optionnel)</label>
            <textarea id="notes" name="notes" class="form-control" rows="3" 
                      placeholder="Remarques particulières..."></textarea>
        </div>
    </div>
    
    <!-- Boutons d'action -->
    <div class="form-actions" style="display: flex; gap: 15px; margin-top: 30px;">
        <button type="submit" class="btn btn-primary">
            ✅ Créer la réservation
        </button>
        <a href="?action=reservations" class="btn btn-secondary">
            ↩️ Annuler
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
        
        if (montantTotal) montantTotal.textContent = total.toFixed(2);
        if (montantTotalInput) montantTotalInput.value = total.toFixed(2);
    }
    
    if (eventSelect && placesInput) {
        eventSelect.addEventListener('change', calculateTotal);
        placesInput.addEventListener('input', calculateTotal);
        calculateTotal(); // Calcul initial
    }
    
    // Validation du formulaire
    const form = document.querySelector('.reservation-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#f56565';
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