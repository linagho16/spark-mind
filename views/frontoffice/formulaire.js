// ==========================================
// SPARKMIND - FORMULAIRE.JS
// Gestion du formulaire de demande d'aide
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Éléments du formulaire
    const form = document.getElementById('helpForm');
    const progressBar = document.getElementById('progressBar');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    // ==========================================
    // 1. AUTO-SAUVEGARDE DANS LOCALSTORAGE
    // ==========================================
    
    // Charger les données sauvegardées
    function loadSavedData() {
        inputs.forEach(input => {
            const savedValue = localStorage.getItem(`sparkmind_${input.name}`);
            
            if (savedValue) {
                if (input.type === 'checkbox') {
                    input.checked = savedValue === 'true';
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
    
    // Sauvegarder les données à chaque changement
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            if (input.type === 'checkbox') {
                localStorage.setItem(`sparkmind_${input.name}`, input.checked);
            } else {
                localStorage.setItem(`sparkmind_${input.name}`, input.value);
            }
            updateProgress();
        });
        
        // Pour les champs texte, sauvegarder aussi pendant la frappe
        if (input.tagName === 'TEXTAREA' || input.type === 'text') {
            input.addEventListener('input', function() {
                localStorage.setItem(`sparkmind_${input.name}`, input.value);
            });
        }
    });
    
    // ==========================================
    // 2. BARRE DE PROGRESSION
    // ==========================================
    
    function updateProgress() {
        if (!progressBar) return;
        
        let totalFields = 0;
        let filledFields = 0;
        
        inputs.forEach(input => {
            // Ne compter que les champs requis
            if (input.required) {
                totalFields++;
                
                if (input.type === 'checkbox' && input.checked) {
                    filledFields++;
                } else if (input.type === 'radio') {
                    const radioGroup = form.querySelectorAll(`input[name="${input.name}"]`);
                    const isChecked = Array.from(radioGroup).some(r => r.checked);
                    if (isChecked && input.checked) {
                        filledFields++;
                    }
                } else if (input.value && input.value.trim() !== '') {
                    filledFields++;
                }
            }
        });
        
        const progress = totalFields > 0 ? (filledFields / totalFields) * 100 : 0;
        progressBar.style.width = progress + '%';
    }
    
    // ==========================================
    // 3. VALIDATION DU FORMULAIRE
    // ==========================================
    
    // Validation du numéro de téléphone tunisien
    function validatePhoneTN(phone) {
        // Format accepté: +216 XX XXX XXX ou 216XXXXXXXX ou XXXXXXXX
        const patterns = [
            /^\+216\s?\d{2}\s?\d{3}\s?\d{3}$/,
            /^216\d{8}$/,
            /^\d{8}$/
        ];
        return patterns.some(pattern => pattern.test(phone.replace(/\s/g, '')));
    }
    
    // Validation de l'email
    function validateEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }
    
    // Afficher un message d'erreur
    function showError(input, message) {
        // Supprimer l'ancien message d'erreur s'il existe
        const existingError = input.parentElement.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Créer un nouveau message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#ec7546';
        errorDiv.style.fontSize = '0.9em';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;
        
        input.parentElement.appendChild(errorDiv);
        input.style.borderColor = '#ec7546';
    }
    
    // Supprimer le message d'erreur
    function clearError(input) {
        const errorDiv = input.parentElement.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '#e0e0e0';
    }
    
    // Validation en temps réel
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (input.required && !input.value.trim()) {
                showError(input, 'Ce champ est obligatoire');
            } else if (input.name === 'telephone' && input.value) {
                if (!validatePhoneTN(input.value)) {
                    showError(input, 'Format invalide. Ex: +216 XX XXX XXX');
                } else {
                    clearError(input);
                }
            } else if (input.name === 'email' && input.value) {
                if (!validateEmail(input.value)) {
                    showError(input, 'Email invalide');
                } else {
                    clearError(input);
                }
            } else {
                clearError(input);
            }
        });
        
        input.addEventListener('focus', function() {
            clearError(input);
        });
    });
    
    // ==========================================
    // 4. SOUMISSION DU FORMULAIRE
    // ==========================================
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validation finale
        let isValid = true;
        let firstError = null;
        
        // Vérifier que au moins une catégorie d'aide est sélectionnée
        const aideCheckboxes = form.querySelectorAll('input[name="aide"]:checked');
        if (aideCheckboxes.length === 0) {
            alert('⚠️ Veuillez sélectionner au moins une catégorie d\'aide.');
            const firstAideCheckbox = form.querySelector('input[name="aide"]');
            firstAideCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        // Vérifier tous les champs requis
        inputs.forEach(input => {
            if (input.required && !input.value.trim() && input.type !== 'checkbox' && input.type !== 'radio') {
                if (isValid) {
                    firstError = input;
                }
                isValid = false;
                showError(input, 'Ce champ est obligatoire');
            }
        });
        
        // Vérifier les radio buttons requis
        const radioGroups = {};
        form.querySelectorAll('input[type="radio"][required]').forEach(radio => {
            if (!radioGroups[radio.name]) {
                radioGroups[radio.name] = form.querySelectorAll(`input[name="${radio.name}"]`);
            }
        });
        
        Object.values(radioGroups).forEach(group => {
            const isChecked = Array.from(group).some(r => r.checked);
            if (!isChecked) {
                isValid = false;
                if (!firstError) {
                    firstError = group[0];
                }
                alert('⚠️ Veuillez sélectionner une option pour tous les champs obligatoires.');
            }
        });
        
        // Vérifier le téléphone
        const phoneInput = form.querySelector('input[name="telephone"]');
        if (phoneInput.value && !validatePhoneTN(phoneInput.value)) {
            isValid = false;
            if (!firstError) {
                firstError = phoneInput;
            }
            showError(phoneInput, 'Format de téléphone invalide');
        }
        
        // Vérifier l'email s'il est rempli
        const emailInput = form.querySelector('input[name="email"]');
        if (emailInput.value && !validateEmail(emailInput.value)) {
            isValid = false;
            if (!firstError) {
                firstError = emailInput;
            }
            showError(emailInput, 'Format d\'email invalide');
        }
        
        // Vérifier l'attestation
        const attestation = form.querySelector('input[name="attestation"]');
        if (!attestation.checked) {
            alert('⚠️ Vous devez attester que les informations sont exactes.');
            attestation.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        // Si validation échoue, scroller vers la première erreur
        if (!isValid) {
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Collecter les données du formulaire
        const formData = new FormData(form);
        const data = {
            nom: formData.get('nom'),
            age: formData.get('age'),
            gouvernorat: formData.get('gouvernorat'),
            ville: formData.get('ville'),
            situation: formData.get('situation'),
            categories_aide: formData.getAll('aide'),
            urgence: formData.get('urgence'),
            description_situation: formData.get('situation'),
            demande_exacte: formData.get('demande'),
            telephone: formData.get('telephone'),
            email: formData.get('email'),
            preference_contact: formData.get('preference-contact'),
            horaires_disponibles: formData.getAll('horaire'),
            visibilite: formData.get('visibilite'),
            anonyme: formData.get('anonyme') === 'on',
            date_soumission: new Date().toISOString()
        };
        
        // Afficher un indicateur de chargement
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = '⏳ Envoi en cours...';
        submitButton.disabled = true;
        
        // Envoyer les données au serveur
        fetch('/SparkMind/controllers/DemandeController.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Succès - Générer un numéro de demande
                const demandeId = result.id || Math.floor(Math.random() * 10000);
                
                // Afficher le message de succès
                alert(`✅ Votre demande a été envoyée avec succès!\n\nNuméro de demande: #${demandeId}\n\nVous recevrez une confirmation par ${data.preference_contact} sous peu.\n\nMerci pour votre confiance en SparkMind.`);
                
                // Vider le localStorage
                inputs.forEach(input => {
                    localStorage.removeItem(`sparkmind_${input.name}`);
                });
                
                // Réinitialiser le formulaire
                form.reset();
                updateProgress();
                
                // Scroller vers le haut
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Erreur du serveur
                alert('❌ Une erreur est survenue lors de l\'envoi de votre demande.\n\n' + (result.message || 'Veuillez réessayer plus tard.'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('❌ Une erreur de connexion est survenue.\n\nVérifiez votre connexion internet et réessayez.');
        })
        .finally(() => {
            // Restaurer le bouton
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    });
    
    // ==========================================
    // 5. BOUTON RÉINITIALISER
    // ==========================================
    
    const resetButton = form.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('⚠️ Êtes-vous sûr de vouloir réinitialiser le formulaire?\n\nToutes les données seront perdues.')) {
                // Vider le localStorage
                inputs.forEach(input => {
                    localStorage.removeItem(`sparkmind_${input.name}`);
                });
                
                // Réinitialiser le formulaire
                form.reset();
                
                // Supprimer tous les messages d'erreur
                form.querySelectorAll('.error-message').forEach(error => error.remove());
                
                // Réinitialiser les bordures
                inputs.forEach(input => {
                    input.style.borderColor = '#e0e0e0';
                });
                
                // Réinitialiser la barre de progression
                updateProgress();
                
                // Scroller vers le haut
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }
    
    // ==========================================
    // 6. AMÉLIORATION DE L'EXPÉRIENCE UTILISATEUR
    // ==========================================
    
    // Auto-formatage du numéro de téléphone
    const phoneInput = form.querySelector('input[name="telephone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            
            // Ajouter automatiquement +216 si l'utilisateur tape 8 chiffres
            if (value.length === 8 && !value.startsWith('+216') && !value.startsWith('216')) {
                value = '+216 ' + value;
            }
            
            // Formater avec des espaces
            if (value.startsWith('+216')) {
                value = value.replace(/^\+216/, '+216 ');
                value = value.replace(/(\+216\s)(\d{2})(\d{3})(\d{3})/, '$1$2 $3 $4');
            }
            
            e.target.value = value;
        });
    }
    
    // Animation de focus sur les sections
    const sections = form.querySelectorAll('.section');
    sections.forEach(section => {
        const firstInput = section.querySelector('input, select, textarea');
        if (firstInput) {
            firstInput.addEventListener('focus', function() {
                sections.forEach(s => s.style.opacity = '0.6');
                section.style.opacity = '1';
                section.style.transition = 'opacity 0.3s ease';
            });
            
            firstInput.addEventListener('blur', function() {
                sections.forEach(s => s.style.opacity = '1');
            });
        }
    });
    
    // ==========================================
    // 7. INITIALISATION
    // ==========================================
    
    // Charger les données sauvegardées au chargement de la page
    loadSavedData();
    
    // Message de bienvenue (optionnel)
    console.log('✅ SparkMind - Formulaire initialisé avec succès');
    console.log('📝 Auto-sauvegarde activée');
    console.log('🔒 Validation en temps réel activée');
});