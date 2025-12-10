<?php
/**
 * Interface de scan de tickets
 * Permet de valider les tickets à l'entrée des événements
 */
?>

<div class="ticket-scanner">
    <div class="scanner-header">
        <h2>🎫 Scanner de Tickets</h2>
        <p>Scannez ou saisissez le code du ticket pour le valider</p>
    </div>

    <!-- Formulaire de validation -->
    <div class="scanner-form">
        <form id="ticketValidationForm" onsubmit="validateTicket(event)">
            <div class="form-group">
                <label for="ticketCode">Code du ticket</label>
                <input 
                    type="text" 
                    id="ticketCode" 
                    name="ticket_code" 
                    placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx.signature"
                    required
                    autofocus
                >
            </div>
            <button type="submit" class="btn-validate">
                🔍 Valider le Ticket
            </button>
        </form>
    </div>

    <!-- Zone de résultat -->
    <div id="validationResult" class="validation-result" style="display: none;"></div>

    <!-- Statistiques en temps réel -->
    <div class="scanner-stats">
        <h3>Statistiques en temps réel</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value" id="statTotal">-</div>
                <div class="stat-label">Total tickets</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="statIssued">-</div>
                <div class="stat-label">Émis</div>
            </div>
            <div class="stat-card success">
                <div class="stat-value" id="statUsed">-</div>
                <div class="stat-label">Utilisés</div>
            </div>
            <div class="stat-card danger">
                <div class="stat-value" id="statCancelled">-</div>
                <div class="stat-label">Annulés</div>
            </div>
        </div>
    </div>
</div>

<style>
.ticket-scanner {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.scanner-header {
    text-align: center;
    margin-bottom: 30px;
}

.scanner-header h2 {
    color: #8B7355;
    margin-bottom: 10px;
}

.scanner-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.scanner-form .form-group {
    margin-bottom: 20px;
}

.scanner-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #4A3F35;
}

.scanner-form input {
    width: 100%;
    padding: 12px;
    border: 2px solid #D4C5B9;
    border-radius: 5px;
    font-size: 14px;
    font-family: monospace;
}

.scanner-form input:focus {
    outline: none;
    border-color: #8B7355;
}

.btn-validate {
    width: 100%;
    padding: 15px;
    background: #8B7355;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-validate:hover {
    background: #6F5A42;
    transform: translateY(-2px);
}

.validation-result {
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.validation-result.success {
    background: #d4edda;
    border: 2px solid #28a745;
    color: #155724;
}

.validation-result.error {
    background: #f8d7da;
    border: 2px solid #dc3545;
    color: #721c24;
}

.validation-result.warning {
    background: #fff3cd;
    border: 2px solid #ffc107;
    color: #856404;
}

.result-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: 600;
}

.result-icon {
    font-size: 30px;
}

.result-details {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid rgba(0,0,0,0.1);
}

.result-details p {
    margin: 8px 0;
}

.scanner-stats {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.scanner-stats h3 {
    color: #4A3F35;
    margin-bottom: 20px;
    text-align: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.stat-card {
    background: #F5F1ED;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    border: 2px solid #D4C5B9;
}

.stat-card.success {
    background: #d4edda;
    border-color: #28a745;
}

.stat-card.danger {
    background: #f8d7da;
    border-color: #dc3545;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #8B7355;
    margin-bottom: 5px;
}

.stat-card.success .stat-value {
    color: #28a745;
}

.stat-card.danger .stat-value {
    color: #dc3545;
}

.stat-label {
    font-size: 14px;
    color: #666;
}
</style>

<script>
// Charger les statistiques au démarrage
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    // Rafraîchir les stats toutes les 10 secondes
    setInterval(loadStats, 10000);
});

// Fonction de validation de ticket
async function validateTicket(event) {
    event.preventDefault();
    
    const form = event.target;
    const ticketCode = form.ticket_code.value.trim();
    const resultDiv = document.getElementById('validationResult');
    
    // Afficher un loader
    resultDiv.style.display = 'block';
    resultDiv.className = 'validation-result';
    resultDiv.innerHTML = '<div style="text-align:center">⏳ Validation en cours...</div>';
    
    try {
        const formData = new FormData();
        formData.append('ticket_code', ticketCode);
        
        const response = await fetch('api/ticket_operations.php?action=validate', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Ticket valide
            resultDiv.className = 'validation-result success';
            resultDiv.innerHTML = `
                <div class="result-header">
                    <span class="result-icon">✅</span>
                    <span>TICKET VALIDE</span>
                </div>
                <div class="result-details">
                    <p><strong>Client:</strong> ${data.data.reservation.nom_client}</p>
                    <p><strong>Événement:</strong> ${data.data.reservation.event_titre}</p>
                    <p><strong>Places:</strong> ${data.data.reservation.nombre_places}</p>
                    <p><strong>Référence:</strong> ${data.data.reservation.reference}</p>
                    <p><strong>Validé à:</strong> ${new Date().toLocaleString('fr-FR')}</p>
                </div>
            `;
            
            // Son de succès (optionnel)
            playSound('success');
            
            // Recharger les stats
            loadStats();
            
            // Vider le formulaire
            form.reset();
            
        } else {
            // Ticket invalide
            let className = 'error';
            let icon = '❌';
            
            if (data.status === 'ALREADY_USED') {
                className = 'warning';
                icon = '⚠️';
            }
            
            resultDiv.className = 'validation-result ' + className;
            resultDiv.innerHTML = `
                <div class="result-header">
                    <span class="result-icon">${icon}</span>
                    <span>${data.status}</span>
                </div>
                <p>${data.message}</p>
            `;
            
            // Son d'erreur (optionnel)
            playSound('error');
        }
        
    } catch (error) {
        resultDiv.className = 'validation-result error';
        resultDiv.innerHTML = `
            <div class="result-header">
                <span class="result-icon">❌</span>
                <span>ERREUR</span>
            </div>
            <p>Erreur lors de la validation: ${error.message}</p>
        `;
    }
}

// Charger les statistiques
async function loadStats() {
    try {
        const response = await fetch('api/ticket_operations.php?action=stats');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('statTotal').textContent = data.data.total || 0;
            document.getElementById('statIssued').textContent = data.data.issued || 0;
            document.getElementById('statUsed').textContent = data.data.used || 0;
            document.getElementById('statCancelled').textContent = data.data.cancelled || 0;
        }
    } catch (error) {
        console.error('Erreur lors du chargement des stats:', error);
    }
}

// Fonction pour jouer un son (optionnel)
function playSound(type) {
    // Vous pouvez ajouter des fichiers audio ici
    // const audio = new Audio('sounds/' + type + '.mp3');
    // audio.play();
}

// Support du scan par lecteur de code-barres
document.getElementById('ticketCode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('ticketValidationForm').dispatchEvent(new Event('submit'));
    }
});
</script>
