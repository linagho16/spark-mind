// Fonction pour afficher un message d'erreur sous un champ
function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + '-error');
    const inputElement = document.getElementById(fieldId);
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    if (inputElement) {
        inputElement.classList.add('error');
    }
}

// Fonction pour supprimer un message d'erreur
function clearError(fieldId) {
    const errorElement = document.getElementById(fieldId + '-error');
    const inputElement = document.getElementById(fieldId);
    
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
    
    if (inputElement) {
        inputElement.classList.remove('error');
    }
}

// Fonction pour valider le nom de la catégorie
function validateNomC() {
    const nomC = document.getElementById('nomC').value.trim();
    
    if (nomC === '') {
        showError('nomC', 'Le nom de la catégorie est obligatoire.');
        return false;
    }
    
    if (nomC.length < 3) {
        showError('nomC', 'Le nom doit contenir au moins 3 caractères.');
        return false;
    }
    
    if (nomC.length > 50) {
        showError('nomC', 'Le nom ne peut pas dépasser 50 caractères.');
        return false;
    }
    
    // Vérifier que le nom contient au moins une lettre
    if (!/[a-zA-ZÀ-ÿ]/.test(nomC)) {
        showError('nomC', 'Le nom doit contenir au moins une lettre.');
        return false;
    }
    
    clearError('nomC');
    return true;
}

// Fonction pour valider la description
function validateDescriptionC() {
    const descriptionC = document.getElementById('descriptionC').value.trim();
    
    if (descriptionC === '') {
        showError('descriptionC', 'La description est obligatoire.');
        return false;
    }
    
    if (descriptionC.length < 5) {
        showError('descriptionC', 'La description doit contenir au moins 5 caractères.');
        return false;
    }
    
    if (descriptionC.length > 50) {
        showError('descriptionC', 'La description ne peut pas dépasser 50 caractères.');
        return false;
    }
    
    clearError('descriptionC');
    return true;
}

// Fonction pour valider la date
function validateDateC() {
    const dateC = document.getElementById('dateC').value;
    
    if (dateC === '') {
        showError('dateC', 'La date de création est obligatoire.');
        return false;
    }
    
    // Vérifier que la date n'est pas dans le futur
    const selectedDate = new Date(dateC);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate > today) {
        showError('dateC', 'La date ne peut pas être dans le futur.');
        return false;
    }
    
    // Vérifier que la date n'est pas trop ancienne (plus de 10 ans)
    const tenYearsAgo = new Date();
    tenYearsAgo.setFullYear(tenYearsAgo.getFullYear() - 10);
    
    if (selectedDate < tenYearsAgo) {
        showError('dateC', 'La date ne peut pas être antérieure à 10 ans.');
        return false;
    }
    
    clearError('dateC');
    return true;
}

// Fonction pour valider le nom du créateur
function validateNomCreateur() {
    const nomCreateur = document.getElementById('nom_Createur').value.trim();
    
    if (nomCreateur === '') {
        showError('nom_Createur', 'Le nom du créateur est obligatoire.');
        return false;
    }
    
    if (nomCreateur.length < 2) {
        showError('nom_Createur', 'Le nom du créateur doit contenir au moins 2 caractères.');
        return false;
    }
    
    if (nomCreateur.length > 50) {
        showError('nom_Createur', 'Le nom du créateur ne peut pas dépasser 50 caractères.');
        return false;
    }
    
    // Vérifier que le nom contient uniquement des lettres, espaces et tirets
    if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(nomCreateur)) {
        showError('nom_Createur', 'Le nom ne peut contenir que des lettres, espaces et tirets.');
        return false;
    }
    
    clearError('nom_Createur');
    return true;
}

// Fonction pour valider l'attestation
function validateAttestation() {
    const attestation = document.getElementById('attestation').checked;
    
    if (!attestation) {
        showError('attestation', 'Vous devez accepter l\'attestation pour continuer.');
        return false;
    }
    
    clearError('attestation');
    return true;
}

// Fonction de validation complète du formulaire
function validateForm() {
    let isValid = true;
    
    // Valider tous les champs
    if (!validateNomC()) isValid = false;
    if (!validateDescriptionC()) isValid = false;
    if (!validateDateC()) isValid = false;
    if (!validateNomCreateur()) isValid = false;
    if (!validateAttestation()) isValid = false;
    
    return isValid;
}

// Event Listeners pour validation en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    
    // Validation lors de la saisie pour le nom
    document.getElementById('nomC').addEventListener('blur', validateNomC);
    document.getElementById('nomC').addEventListener('input', function() {
        if (this.value.trim().length >= 3) {
            clearError('nomC');
        }
    });
    
    // Validation lors de la saisie pour la description
    document.getElementById('descriptionC').addEventListener('blur', validateDescriptionC);
    document.getElementById('descriptionC').addEventListener('input', function() {
        if (this.value.trim().length >= 5) {
            clearError('descriptionC');
        }
    });
    
    // Validation de la date
    document.getElementById('dateC').addEventListener('change', validateDateC);
    document.getElementById('dateC').addEventListener('blur', validateDateC);
    
    // Validation du nom du créateur
    document.getElementById('nom_Createur').addEventListener('blur', validateNomCreateur);
    document.getElementById('nom_Createur').addEventListener('input', function() {
        if (this.value.trim().length >= 2) {
            clearError('nom_Createur');
        }
    });
    
    // Validation de l'attestation
    document.getElementById('attestation').addEventListener('change', validateAttestation);
    
    // Validation lors de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            
            // Scroll vers le premier champ en erreur
            const firstError = document.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            
            return false;
        }
    });
});
