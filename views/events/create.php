<?php
// Vérifier les messages d'erreur/succès
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-' . ($_SESSION['message_type'] ?? 'success') . '">';
    echo htmlspecialchars($_SESSION['message']);
    echo '</div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<div class="page-title">
    <h1>🎭 Créer un Nouvel Événement</h1>
    <p>Remplissez le formulaire pour créer un nouvel événement</p>
</div>

<form action="process_event.php?action=create" method="POST" class="event-form">
    <div class="card">
        <h2>📝 Informations de base</h2>
        
        <div class="form-group">
            <label for="titre">Titre de l'événement *</label>
            <input type="text" id="titre" name="titre" class="form-control" 
                   required 
                   placeholder="Ex: Concert de Jazz" 
                   value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" class="form-control" rows="5" 
                      required 
                      placeholder="Décrivez votre événement..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <h2>📍 Détails pratiques</h2>
        
        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="lieu">Lieu *</label>
                <input type="text" id="lieu" name="lieu" class="form-control" 
                       required 
                       placeholder="Ex: Salle de concert municipale" 
                       value="<?php echo htmlspecialchars($_POST['lieu'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (€)</label>
                <input type="number" id="prix" name="prix" class="form-control" 
                       step="0.01" min="0" 
                       placeholder="0.00" 
                       value="<?php echo htmlspecialchars($_POST['prix'] ?? '0'); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="date_event">Date de l'événement *</label>
            <input type="date" id="date_event" name="date_event" class="form-control" 
                   required 
                   value="<?php echo htmlspecialchars($_POST['date_event'] ?? ''); ?>">
        </div>
    </div>
    
    <!-- Boutons d'action -->
    <div class="form-actions" style="display: flex; gap: 15px; margin-top: 30px;">
        <button type="submit" class="btn btn-primary" name="submit">
            ✅ Créer l'Événement
        </button>
        <a href="?action=events" class="btn btn-secondary">
            ↩️ Annuler
        </a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Définir la date d'aujourd'hui comme minimum
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('date_event');
    if (dateInput) {
        dateInput.min = today;
    }
    
    // Validation du formulaire
    const form = document.querySelector('.event-form');
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
            
            // Vérifier que la date est valide
            const dateField = document.getElementById('date_event');
            if (dateField.value && new Date(dateField.value) < new Date(today)) {
                alert('La date de l\'événement ne peut pas être dans le passé.');
                dateField.style.borderColor = '#f56565';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires (*) avec des valeurs valides.');
            }
        });
    }
});
</script>