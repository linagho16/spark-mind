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

// Fonction pour valider le titre
function validateTitle() {
    const title = document.getElementById('title').value.trim();
    
    if (title === '') {
        showError('title', 'Le titre est obligatoire.');
        return false;
    }
    
    if (title.length < 3) {
        showError('title', 'Le titre doit contenir au moins 3 caractères.');
        return false;
    }
    
    if (title.length > 50) {
        showError('title', 'Le titre ne peut pas dépasser 50 caractères.');
        return false;
    }
    
    clearError('title');
    return true;
}

// Fonction pour valider la description
function validateDescription() {
    const description = document.getElementById('description').value.trim();
    
    if (description === '') {
        showError('description', 'La description est obligatoire.');
        return false;
    }
    
    if (description.length < 10) {
        showError('description', 'La description doit contenir au moins 10 caractères.');
        return false;
    }
    
    if (description.length > 500) {
        showError('description', 'La description ne peut pas dépasser 500 caractères.');
        return false;
    }
    
    clearError('description');
    return true;
}

// Fonction pour valider la catégorie
function validateCategory() {
    const category = document.getElementById('category').value;
    
    if (category === '') {
        showError('category', 'Veuillez sélectionner une catégorie.');
        return false;
    }
    
    clearError('category');
    return true;
}

// Fonction pour valider la condition
function validateCondition() {
    const conditions = document.querySelectorAll('input[name="condition"]:checked');
    
    if (conditions.length === 0) {
        showError('condition', 'Veuillez sélectionner l\'état du produit.');
        return false;
    }
    
    clearError('condition');
    return true;
}

// Fonction pour valider le statut
function validateStatut() {
    const statuts = document.querySelectorAll('input[name="statut"]:checked');
    
    if (statuts.length === 0) {
        showError('statut', 'Veuillez sélectionner le statut du produit.');
        return false;
    }
    
    clearError('statut');
    return true;
}

// Fonction pour valider la photo
function validatePhoto() {
    const photoInput = document.getElementById('photo');

    // ✅ Photo optionnelle : si aucune image, c'est OK
    if (!photoInput.files || photoInput.files.length === 0) {
        clearError('photo');
        return true;
    }

    const file = photoInput.files[0];
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp' , 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5 MB

    if (!allowedTypes.includes(file.type)) {
        showError('photo', 'Format de fichier non autorisé. Utilisez JPG, PNG ou WebP.');
        return false;
    }

    if (file.size > maxSize) {
        showError('photo', 'La taille du fichier ne doit pas dépasser 5 MB.');
        return false;
    }

    clearError('photo');
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
    if (!validateTitle()) isValid = false;
    if (!validateDescription()) isValid = false;
    if (!validateCategory()) isValid = false;
    if (!validateCondition()) isValid = false;
    if (!validateStatut()) isValid = false;
    if (!validatePhoto()) isValid = false;
    if (!validateAttestation()) isValid = false;
    
    return isValid;
}

// Event Listeners pour validation en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    
    // Validation lors de la saisie
    document.getElementById('title').addEventListener('blur', validateTitle);
    document.getElementById('title').addEventListener('input', function() {
        if (this.value.trim().length >= 3) {
            clearError('title');
        }
    });
    
    document.getElementById('description').addEventListener('blur', validateDescription);
    document.getElementById('description').addEventListener('input', function() {
        if (this.value.trim().length >= 10) {
            clearError('description');
        }
    });
    
    document.getElementById('category').addEventListener('change', validateCategory);
    
    // Validation des radio buttons
    const conditionRadios = document.querySelectorAll('input[name="condition"]');
    conditionRadios.forEach(function(radio) {
        radio.addEventListener('change', validateCondition);
    });
    
    const statutRadios = document.querySelectorAll('input[name="statut"]');
    statutRadios.forEach(function(radio) {
        radio.addEventListener('change', validateStatut);
    });
    
    document.getElementById('photo').addEventListener('change', validatePhoto);
    
    document.getElementById('attestation').addEventListener('change', validateAttestation);
    
    // Validation lors de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
    });
});

