// Configuration de l'API
const API_BASE = '../../controllers/DemandeController.php';

// Variables globales
let formData = {};

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ SparkMind - Formulaire de demande initialisé');
    initializeForm();
    loadSavedData();
    setupEventListeners();
    updateProgress();
});

// Initialiser le formulaire
function initializeForm() {
    const form = document.getElementById('helpForm');
    if (!form) {
        console.error('❌ Formulaire non trouvé');
        return;
    }
}

// Configurer les écouteurs d'événements
function setupEventListeners() {
    const form = document.getElementById('helpForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    // Auto-sauvegarde et mise à jour de la progression
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            saveFormData();
            updateProgress();
        });
        
        // Pour les champs texte, sauvegarder pendant la frappe
        if (input.tagName === 'TEXTAREA' || input.type === 'text') {
            input.addEventListener('input', () => {
                saveFormData();
            });
        }
        
        // Validation en temps réel
        input.addEventListener('blur', () => {
            validateField(input);
        });
        
        input.addEventListener('focus', () => {
            clearFieldError(input);
        });
    });
    
    // Soumission du formulaire
    form.addEventListener('submit', handleSubmit);
    
    // Réinitialisation
    form.addEventListener('reset', handleReset);
    
    // Auto-formatage du téléphone
    const phoneInput = form.querySelector('input[name="telephone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', formatPhoneNumber);
    }
}

// Mettre à jour la barre de progression
function updateProgress() {
    const form = document.getElementById('helpForm');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    
    if (!form || !progressBar) return;
    
    const requiredInputs = form.querySelectorAll('[required]');
    let totalFields = 0;
    let filledFields = 0;
    
    // Grouper les radio buttons par nom
    const radioGroups = {};
    
    requiredInputs.forEach(input => {
        if (input.type === 'radio') {
            if (!radioGroups[input.name]) {
                radioGroups[input.name] = form.querySelectorAll(`input[name="${input.name}"]`);
                totalFields++;
            }
        } else if (input.type === 'checkbox') {
            // Pour les checkboxes de catégories d'aide
            if (input.name === 'aide') {
                if (!radioGroups['aide']) {
                    radioGroups['aide'] = form.querySelectorAll('input[name="aide"]');
                    totalFields++;
                }
            } else {
                totalFields++;
            }
        } else {
            totalFields++;
        }
    });
    
    // Compter les champs remplis
    requiredInputs.forEach(input => {
        if (input.type === 'radio') {
            const group = radioGroups[input.name];
            const isChecked = Array.from(group).some(r => r.checked);
            if (isChecked && input.checked) {
                filledFields++;
            }
        } else if (input.type === 'checkbox') {
            if (input.name === 'aide') {
                const aideCheckboxes = form.querySelectorAll('input[name="aide"]:checked');
                if (aideCheckboxes.length > 0 && input === aideCheckboxes[0]) {
                    filledFields++;
                }
            } else if (input.checked) {
                filledFields++;
            }
        } else if (input.value && input.value.trim() !== '') {
            filledFields++;
        }
    });
    
    const progress = totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0;
    progressBar.style.width = progress + '%';
    if (progressPercent) {
        progressPercent.textContent = progress + '%';
    }
}

// Sauvegarder les données du formulaire
function saveFormData() {
    const form = document.getElementById('helpForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        const key = `sparkmind_${input.name}`;
        
        if (input.type === 'checkbox') {
            if (input.name === 'aide' || input.name === 'horaires_disponibles') {
                // Pour les checkboxes multiples, sauvegarder un tableau
                const checked = Array.from(form.querySelectorAll(`input[name="${input.name}"]:checked`))
                    .map(cb => cb.value);
                localStorage.setItem(key, JSON.stringify(checked));
            } else {
                localStorage.setItem(key, input.checked);
            }
        } else if (input.type === 'radio') {
            if (input.checked) {
                localStorage.setItem(key, input.value);
            }
        } else {
            localStorage.setItem(key, input.value);
        }
    });
}

// Charger les données sauvegardées
function loadSavedData() {
    const form = document.getElementById('helpForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        const key = `sparkmind_${input.name}`;
        const savedValue = localStorage.getItem(key);
        
        if (savedValue) {
            if (input.type === 'checkbox') {
                if (input.name === 'aide' || input.name === 'horaires_disponibles') {
                    try {
                        const values = JSON.parse(savedValue);
                        if (values.includes(input.value)) {
                            input.checked = true;
                        }
                    } catch (e) {
                        input.checked = savedValue === 'true';
                    }
                } else {
                    input.checked = savedValue === 'true';
                }
            } else if (input.type === 'radio') {
                if (input.value === savedValue) {
                    input.checked = true;
                }
            } else {
                input.value = savedValue;
            }
        }
    });
    
    updateProgress();
}

// Valider un champ
function validateField(input) {
    clearFieldError(input);
    
    if (input.required && !input.value.trim() && input.type !== 'checkbox' && input.type !== 'radio') {
        showFieldError(input, 'Ce champ est obligatoire');
        return false;
    }
    
    if (input.name === 'telephone' && input.value) {
        if (!validatePhone(input.value)) {
            showFieldError(input, 'Format invalide. Ex: +216 XX XXX XXX');
            return false;
        }
    }
    
    if (input.name === 'email' && input.value) {
        if (!validateEmail(input.value)) {
            showFieldError(input, 'Email invalide');
            return false;
        }
    }
    
    if (input.name === 'age' && input.value) {
        const age = parseInt(input.value);
        if (age < 1 || age > 120) {
            showFieldError(input, 'Âge invalide');
            return false;
        }
    }
    
    return true;
}

// Valider le téléphone tunisien
function validatePhone(phone) {
    const cleanPhone = phone.replace(/\s/g, '');
    const patterns = [
        /^\+216\d{8}$/,
        /^216\d{8}$/,
        /^\d{8}$/
    ];
    return patterns.some(pattern => pattern.test(cleanPhone));
}

// Valider l'email
function validateEmail(email) {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

// Afficher une erreur de champ
function showFieldError(input, message) {
    input.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = '⚠️ ' + message;
    
    input.parentElement.appendChild(errorDiv);
}

// Effacer l'erreur de champ
function clearFieldError(input) {
    input.classList.remove('error');
    
    const errorDiv = input.parentElement.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Formater le numéro de téléphone
function formatPhoneNumber(e) {
    let value = e.target.value.replace(/\s/g, '');
    
    // Ajouter +216 si nécessaire
    if (value.length === 8 && !value.startsWith('+216') && !value.startsWith('216')) {
        value = '+216' + value;
    }
    
    // Formater avec des espaces
    if (value.startsWith('+216')) {
        value = value.replace(/^\+216/, '+216 ');
        value = value.replace(/(\+216\s)(\d{2})(\d{3})(\d{3})/, '$1$2 $3 $4');
    }
    
    e.target.value = value;
}

// Gérer la soumission du formulaire
async function handleSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    
    // Validation complète
    if (!validateForm(form)) {
        return;
    }
    
    // Collecter les données
    const formData = collectFormData(form);
    
    console.log('📤 Envoi des données:', formData);
    
    // Désactiver le bouton de soumission
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = '⏳ Envoi en cours...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(`${API_BASE}?action=create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        console.log('📨 Réponse du serveur:', data);
        
        if (data.success) {
            showNotification(`✅ Votre demande a été envoyée avec succès!\n\nNuméro de demande: #${data.id || 'XXX'}\n\nVous recevrez une confirmation sous peu.`, 'success');
            
            // Nettoyer le localStorage
            clearFormData();
            
            // Réinitialiser le formulaire
            form.reset();
            updateProgress();
            
            // Scroller vers le haut
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            showNotification('❌ ' + (data.message || 'Erreur lors de l\'envoi de votre demande'), 'error');
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('❌ Erreur de connexion au serveur', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
}

// Valider tout le formulaire
function validateForm(form) {
    let isValid = true;
    let firstError = null;
    
    // Vérifier les champs requis
    const requiredInputs = form.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'checkbox' && input.type !== 'radio') {
            if (!input.value.trim()) {
                if (!firstError) firstError = input;
                showFieldError(input, 'Ce champ est obligatoire');
                isValid = false;
            } else if (!validateField(input)) {
                if (!firstError) firstError = input;
                isValid = false;
            }
        }
    });
    
    // Vérifier les catégories d'aide
    const aideCheckboxes = form.querySelectorAll('input[name="aide"]:checked');
    if (aideCheckboxes.length === 0) {
        showNotification('⚠️ Veuillez sélectionner au moins une catégorie d\'aide', 'error');
        const firstAide = form.querySelector('input[name="aide"]');
        if (firstAide && !firstError) firstError = firstAide;
        isValid = false;
    }
    
    // Vérifier les radio buttons requis
    const radioGroups = {};
    form.querySelectorAll('input[type="radio"][required]').forEach(radio => {
        if (!radioGroups[radio.name]) {
            radioGroups[radio.name] = form.querySelectorAll(`input[name="${radio.name}"]`);
        }
    });
    
    Object.entries(radioGroups).forEach(([name, group]) => {
        const isChecked = Array.from(group).some(r => r.checked);
        if (!isChecked) {
            showNotification(`⚠️ Veuillez sélectionner une option pour: ${name}`, 'error');
            if (!firstError) firstError = group[0];
            isValid = false;
        }
    });
    
    // Vérifier l'attestation
    const attestation = form.querySelector('input[name="attestation"]');
    if (!attestation.checked) {
        showNotification('⚠️ Vous devez attester que les informations sont exactes', 'error');
        if (!firstError) firstError = attestation;
        isValid = false;
    }
    
    // Scroller vers la première erreur
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    return isValid;
}

// Collecter les données du formulaire
function collectFormData(form) {
    const formDataObj = new FormData(form);
    
    // Construire l'objet de données
    const data = {
        nom: formDataObj.get('nom') || '',
        age: formDataObj.get('age') || '',
        gouvernorat: formDataObj.get('gouvernorat') || '',
        ville: formDataObj.get('ville') || '',
        situation_familiale: formDataObj.get('situation') || '',
        categories_aide: formDataObj.getAll('aide').join(','),
        urgence: formDataObj.get('urgence') || '',
        description_situation: formDataObj.get('description_situation') || '',
        demande_exacte: formDataObj.get('demande_exacte') || '',
        telephone: formDataObj.get('telephone') || '',
        email: formDataObj.get('email') || '',
        preference_contact: formDataObj.get('preference_contact') || '',
        horaires_disponibles: formDataObj.getAll('horaires_disponibles').join(','),
        visibilite: formDataObj.get('visibilite') || '',
        anonyme: formDataObj.get('anonyme') ? 1 : 0,
        statut: 'en_attente'
    };
    
    return data;
}

// Gérer la réinitialisation
function handleReset(e) {
    e.preventDefault();
    
    if (confirm('⚠️ Êtes-vous sûr de vouloir réinitialiser le formulaire?\n\nToutes les données seront perdues.')) {
        const form = e.target;
        
        // Nettoyer le localStorage
        clearFormData();
        
        // Réinitialiser le formulaire
        form.reset();
        
        // Effacer toutes les erreurs
        form.querySelectorAll('.error-message').forEach(error => error.remove());
        form.querySelectorAll('.error').forEach(input => input.classList.remove('error'));
        
        // Réinitialiser la progression
        updateProgress();
        
        // Scroller vers le haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        showNotification('🔄 Formulaire réinitialisé', 'info');
    }
}

// Nettoyer les données du localStorage
function clearFormData() {
    const form = document.getElementById('helpForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        localStorage.removeItem(`sparkmind_${input.name}`);
    });
}

// Afficher une notification
function showNotification(message, type = 'info') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 5000);
}

// Fonction d'aide
function showHelp() {
    alert(`📋 Aide - Formulaire de Demande\n\n` +
          `1. Remplissez tous les champs obligatoires (*)\n` +
          `2. Sélectionnez au moins une catégorie d'aide\n` +
          `3. Décrivez précisément votre situation\n` +
          `4. Vos données sont sauvegardées automatiquement\n\n` +
          `Pour toute question, contactez-nous au:\n+216 55 581 22`);
}