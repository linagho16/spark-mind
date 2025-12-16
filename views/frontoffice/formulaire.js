// Configuration de l'API
const API_BASE = '../../controllers/DemandeController.php';

// Variables globales
let formData = {};
let chatHistory = [];
let isChatbotOpen = false;

// Base de connaissances du chatbot
const chatbotKnowledge = {
    greetings: ['bonjour', 'salut', 'hello', 'hi', 'bonsoir'],
    help: ['aide', 'aider', 'assistance', 'support', 'help'],
    form: ['formulaire', 'remplir', 'compléter', 'form'],
    types: ['types', 'catégories', 'aide disponible', 'services'],
    urgent: ['urgent', 'urgence', 'rapide', 'vite'],
    contact: ['contacter', 'téléphone', 'email', 'joindre'],
    privacy: ['confidentialité', 'anonyme', 'privé', 'discrétion'],
    time: ['temps', 'délai', 'combien', 'quand', 'durée']
};

// Réponses du chatbot
const chatbotResponses = {
    greeting: "Bonjour ! 👋 Je suis ravi de vous aider. Je peux répondre à vos questions sur le formulaire, les types d'aide disponibles, ou tout autre aspect de votre demande.",
    
    form: "📝 Pour remplir le formulaire :\n\n1. Informations personnelles : Nom, âge, gouvernorat et ville\n2. Type d'aide : Choisissez une ou plusieurs catégories\n3. Description : Expliquez votre situation (min. 20 caractères)\n4. Contact : Comment vous joindre\n5. Confidentialité : Niveau de visibilité souhaité\n\nTous les champs marqués d'un * sont obligatoires. Vos données sont sauvegardées automatiquement !",
    
    types: "🤝 Types d'aide disponibles :\n\n• 🍽️ Alimentaire : Colis alimentaires, repas\n• 📚 Scolaire : Fournitures, livres, frais scolaires\n• 👕 Vestimentaire : Vêtements, chaussures\n• 🏥 Médicale : Consultations, médicaments\n• 💰 Financière : Aide ponctuelle\n• 🏠 Logement : Aide au loyer\n• 💼 Professionnelle : Formation, emploi\n• 💬 Psychologique : Écoute, soutien",
    
    urgent: "⏰ Degrés d'urgence :\n\n🔴 Très urgent : Traitement sous 24-48h\n🟠 Urgent : Cette semaine\n🟡 Important : Ce mois-ci\n🟢 Peut attendre : Délai flexible\n\nSélectionnez le degré qui correspond à votre situation réelle.",
    
    contact: "📞 Informations de contact :\n\nNous avons besoin de votre téléphone (obligatoire) pour vous joindre. L'email est optionnel.\n\nFormat téléphone : +216 XX XXX XXX\n\nChoisissez votre préférence :\n• Appel téléphonique\n• SMS/WhatsApp\n• Email\n\nN'oubliez pas d'indiquer vos horaires de disponibilité !",
    
    privacy: "🔒 Options de confidentialité :\n\n✅ Publique : Visible par tous (donateurs, associations)\n👥 Semi-privée : Associations uniquement\n🔒 Privée : Administrateurs uniquement\n\nVous pouvez aussi cocher \"rester anonyme\" pour masquer votre identité tout en gardant votre demande visible.",
    
    time: "⏱️ Délais de traitement :\n\n• Très urgent : Réponse sous 24-48h\n• Urgent : 3-5 jours ouvrables\n• Important : 1-2 semaines\n• Flexible : 2-4 semaines\n\nVous recevrez une notification dès qu'un donateur ou une association manifeste son intérêt !",
    
    default: "Je n'ai pas bien compris votre question. 🤔\n\nJe peux vous aider avec :\n• Le remplissage du formulaire\n• Les types d'aide disponibles\n• Les délais de réponse\n• Les options de confidentialité\n• Les informations de contact\n\nPosez-moi une question ou utilisez les boutons rapides !"
};

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ SparkMind - Formulaire de demande initialisé');
    initializeForm();
    loadSavedData();
    setupEventListeners();
    updateProgress();
    initializeChatbot();
    setupCharCounter();
});

// Initialiser le chatbot
function initializeChatbot() {
    const savedHistory = localStorage.getItem('sparkmind_chat_history');
    if (savedHistory) {
        try {
            chatHistory = JSON.parse(savedHistory);
        } catch (e) {
            chatHistory = [];
        }
    }
    
    // S'assurer que le chatbot est caché au démarrage
    const widget = document.getElementById('chatbotWidget');
    if (widget) {
        widget.classList.remove('active');
        isChatbotOpen = false;
    }
}

// Toggle chatbot - FONCTION CORRIGÉE
function toggleChatbot() {
    const widget = document.getElementById('chatbotWidget');
    const floatingBtn = document.querySelector('.floating-chat-btn');
    
    if (!widget) {
        console.error('Widget chatbot non trouvé');
        return;
    }
    
    // Toggle l'état
    isChatbotOpen = !isChatbotOpen;
    
    if (isChatbotOpen) {
        // Ouvrir le chatbot
        widget.classList.add('active');
        widget.style.display = 'flex';
        
        // Cacher le bouton flottant
        if (floatingBtn) {
            floatingBtn.style.display = 'none';
        }
        
        // Focus sur l'input après un petit délai
        setTimeout(() => {
            const input = document.getElementById('chatbotInput');
            if (input) {
                input.focus();
            }
            scrollChatToBottom();
        }, 100);
    } else {
        // Fermer le chatbot
        widget.classList.remove('active');
        
        // Utiliser une transition avant de masquer complètement
        setTimeout(() => {
            widget.style.display = 'none';
        }, 300);
        
        // Réafficher le bouton flottant
        if (floatingBtn) {
            floatingBtn.style.display = 'flex';
        }
    }
}

// Analyser l'intention de l'utilisateur
function analyzeIntent(message) {
    const lowerMessage = message.toLowerCase();
    
    if (chatbotKnowledge.greetings.some(word => lowerMessage.includes(word))) {
        return 'greeting';
    }
    if (chatbotKnowledge.form.some(word => lowerMessage.includes(word))) {
        return 'form';
    }
    if (chatbotKnowledge.types.some(word => lowerMessage.includes(word))) {
        return 'types';
    }
    if (chatbotKnowledge.urgent.some(word => lowerMessage.includes(word))) {
        return 'urgent';
    }
    if (chatbotKnowledge.contact.some(word => lowerMessage.includes(word))) {
        return 'contact';
    }
    if (chatbotKnowledge.privacy.some(word => lowerMessage.includes(word))) {
        return 'privacy';
    }
    if (chatbotKnowledge.time.some(word => lowerMessage.includes(word))) {
        return 'time';
    }
    
    return 'default';
}

// Générer une réponse intelligente
function generateBotResponse(userMessage) {
    const intent = analyzeIntent(userMessage);
    let response = chatbotResponses[intent] || chatbotResponses.default;
    
    const suggestions = [];
    
    if (intent === 'greeting' || intent === 'default') {
        suggestions.push(
            { text: '📝 Aide formulaire', action: 'form' },
            { text: '🤝 Types d\'aide', action: 'types' },
            { text: '⏰ Délais', action: 'time' }
        );
    } else if (intent === 'form') {
        suggestions.push(
            { text: '🤝 Types d\'aide', action: 'types' },
            { text: '🔒 Confidentialité', action: 'privacy' }
        );
    } else if (intent === 'types') {
        suggestions.push(
            { text: '⏰ Urgence', action: 'urgent' },
            { text: '📝 Remplir le formulaire', action: 'form' }
        );
    }
    
    return { response, suggestions };
}

// Envoyer un message du chatbot
function sendChatMessage() {
    const input = document.getElementById('chatbotInput');
    if (!input) return;
    
    const message = input.value.trim();
    
    if (!message) return;
    
    addChatMessage(message, 'user');
    input.value = '';
    
    setTimeout(() => {
        const { response, suggestions } = generateBotResponse(message);
        addChatMessage(response, 'bot', suggestions);
    }, 500);
    
    saveChatHistory();
}

// Ajouter un message au chat
function addChatMessage(message, sender, suggestions = null) {
    const messagesContainer = document.getElementById('chatbotMessages');
    if (!messagesContainer) return;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `chatbot-message ${sender}`;
    
    if (sender === 'bot') {
        const suggestionsHTML = suggestions && suggestions.length > 0 ? `
            <div class="quick-replies">
                ${suggestions.map(s => `
                    <button onclick="askBot('${s.action}')">${s.text}</button>
                `).join('')}
            </div>
        ` : '';
        
        messageDiv.innerHTML = `
            <div class="message-avatar">🤖</div>
            <div class="message-content">
                <p>${message.replace(/\n/g, '<br>')}</p>
                ${suggestionsHTML}
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="message-content">
                <p>${message}</p>
            </div>
            <div class="message-avatar">👤</div>
        `;
    }
    
    messagesContainer.appendChild(messageDiv);
    scrollChatToBottom();
    
    chatHistory.push({ message, sender, timestamp: Date.now() });
}

// Question rapide du bot - FONCTION CORRIGÉE
function askBot(action) {
    const response = chatbotResponses[action] || chatbotResponses.default;
    
    // Ajouter directement la réponse sans afficher de message utilisateur
    const { suggestions } = generateBotResponse(response);
    addChatMessage(response, 'bot', suggestions);
    
    saveChatHistory();
}

// Gérer la touche Entrée
function handleChatKeypress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendChatMessage();
    }
}

// Scroller vers le bas du chat
function scrollChatToBottom() {
    const messagesContainer = document.getElementById('chatbotMessages');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Sauvegarder l'historique du chat
function saveChatHistory() {
    try {
        localStorage.setItem('sparkmind_chat_history', JSON.stringify(chatHistory));
    } catch (e) {
        console.error('Erreur lors de la sauvegarde de l\'historique:', e);
    }
}

// Initialiser le formulaire
function initializeForm() {
    const form = document.getElementById('helpForm');
    if (!form) {
        console.error('❌ Formulaire non trouvé');
        return;
    }
    
    removeHTMLValidation(form);
}

// Supprimer la validation HTML native
function removeHTMLValidation(form) {
    form.setAttribute('novalidate', 'novalidate');
    
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.removeAttribute('required');
        input.removeAttribute('min');
        input.removeAttribute('max');
        input.removeAttribute('pattern');
    });
}

// Configurer les écouteurs d'événements
function setupEventListeners() {
    const form = document.getElementById('helpForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            saveFormData();
            updateProgress();
            updateStepIndicators();
        });
        
        if (input.tagName === 'TEXTAREA' || input.type === 'text') {
            input.addEventListener('input', () => {
                saveFormData();
                updateProgress();
            });
        }
        
        input.addEventListener('blur', () => {
            validateField(input);
        });
        
        input.addEventListener('focus', () => {
            clearFieldError(input);
        });
    });
    
    form.addEventListener('submit', handleSubmit);
    form.addEventListener('reset', handleReset);
    
    const phoneInput = form.querySelector('input[name="telephone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', formatPhoneNumber);
    }
}

// Configurer le compteur de caractères
function setupCharCounter() {
    const descTextarea = document.querySelector('textarea[name="description_situation"]');
    if (descTextarea) {
        descTextarea.addEventListener('input', (e) => {
            const counter = document.getElementById('descCounter');
            if (counter) {
                counter.textContent = e.target.value.length;
            }
        });
    }
}

// Mettre à jour les indicateurs d'étapes
function updateStepIndicators() {
    const form = document.getElementById('helpForm');
    const sections = form.querySelectorAll('.form-card');
    
    sections.forEach((section, index) => {
        const sectionNum = index + 1;
        const stepItem = document.querySelector(`.step-item[data-step="${sectionNum}"]`);
        
        if (stepItem && isSectionComplete(section)) {
            stepItem.classList.add('completed');
        }
    });
}

// Vérifier si une section est complète
function isSectionComplete(section) {
    const inputs = section.querySelectorAll('input, select, textarea');
    let allFilled = true;
    
    inputs.forEach(input => {
        if (input.hasAttribute('required') || input.closest('.form-group')?.querySelector('.required')) {
            if (input.type === 'radio' || input.type === 'checkbox') {
                const name = input.name;
                const checked = section.querySelector(`input[name="${name}"]:checked`);
                if (!checked) allFilled = false;
            } else if (!input.value.trim()) {
                allFilled = false;
            }
        }
    });
    
    return allFilled;
}

// Mettre à jour la barre de progression circulaire
function updateProgress() {
    const form = document.getElementById('helpForm');
    const progressCircle = document.getElementById('progressCircle');
    const progressPercent = document.getElementById('progressPercent');
    
    if (!form || !progressCircle) return;
    
    const requiredFields = [
        'nom', 'age', 'gouvernorat', 'ville', 'urgence',
        'description_situation', 'demande_exacte', 'telephone',
        'preference_contact', 'visibilite'
    ];
    
    let totalFields = requiredFields.length + 2;
    let filledFields = 0;
    
    requiredFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (!field) return;
        
        if (field.type === 'radio') {
            const checked = form.querySelector(`input[name="${fieldName}"]:checked`);
            if (checked) filledFields++;
        } else if (field.value && field.value.trim() !== '') {
            filledFields++;
        }
    });
    
    const aideChecked = form.querySelectorAll('input[name="aide"]:checked');
    if (aideChecked.length > 0) filledFields++;
    
    const attestation = form.querySelector('input[name="attestation"]');
    if (attestation && attestation.checked) filledFields++;
    
    const progress = totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0;
    
    // Mettre à jour le cercle de progression
    const circumference = 2 * Math.PI * 54;
    const offset = circumference - (progress / 100) * circumference;
    progressCircle.style.strokeDashoffset = offset;
    
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

// Valider un champ individuellement
function validateField(input) {
    clearFieldError(input);
    
    const fieldName = input.name;
    const fieldValue = input.value ? input.value.trim() : '';
    
    const requiredFields = [
        'nom', 'age', 'gouvernorat', 'ville', 'urgence',
        'description_situation', 'demande_exacte', 'telephone',
        'preference_contact', 'visibilite'
    ];
    
    if (requiredFields.includes(fieldName)) {
        if (input.type === 'radio') {
            const form = input.closest('form');
            const checked = form.querySelector(`input[name="${fieldName}"]:checked`);
            if (!checked) {
                showFieldError(input, 'Veuillez sélectionner une option');
                return false;
            }
        } else if (!fieldValue) {
            showFieldError(input, 'Ce champ est obligatoire');
            return false;
        }
    }
    
    if (fieldName === 'nom' && fieldValue) {
        if (fieldValue.length < 3) {
            showFieldError(input, 'Le nom doit contenir au moins 3 caractères');
            return false;
        }
        if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(fieldValue)) {
            showFieldError(input, 'Le nom ne doit contenir que des lettres');
            return false;
        }
    }
    
    if (fieldName === 'age' && fieldValue) {
        const age = parseInt(fieldValue);
        if (isNaN(age) || age < 1 || age > 120) {
            showFieldError(input, 'Âge invalide (entre 1 et 120 ans)');
            return false;
        }
    }
    
    if (fieldName === 'telephone' && fieldValue) {
        if (!validatePhone(fieldValue)) {
            showFieldError(input, 'Format invalide. Ex: +216 XX XXX XXX');
            return false;
        }
    }
    
    if (fieldName === 'email' && fieldValue) {
        if (!validateEmail(fieldValue)) {
            showFieldError(input, 'Email invalide');
            return false;
        }
    }
    
    if (fieldName === 'ville' && fieldValue) {
        if (fieldValue.length < 2) {
            showFieldError(input, 'Le nom de la ville est trop court');
            return false;
        }
    }
    
    if (fieldName === 'description_situation' && fieldValue) {
        if (fieldValue.length < 20) {
            showFieldError(input, 'Description trop courte (minimum 20 caractères)');
            return false;
        }
        if (fieldValue.length > 1000) {
            showFieldError(input, 'Description trop longue (maximum 1000 caractères)');
            return false;
        }
    }
    
    if (fieldName === 'demande_exacte' && fieldValue) {
        if (fieldValue.length < 10) {
            showFieldError(input, 'Veuillez décrire plus précisément votre demande');
            return false;
        }
    }
    
    return true;
}

// Valider le téléphone tunisien
function validatePhone(phone) {
    const cleanPhone = phone.replace(/\s/g, '');
    const patterns = [
        /^\+216[0-9]{8}$/,
        /^216[0-9]{8}$/,
        /^[0-9]{8}$/
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
    
    const existingError = input.parentElement.querySelector('.error-message');
    if (existingError) {
        existingError.textContent = '⚠️ ' + message;
        return;
    }
    
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
    
    if (value.length === 8 && !value.startsWith('+216') && !value.startsWith('216')) {
        value = '+216' + value;
    }
    
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
    
    if (!validateForm(form)) {
        return;
    }
    
    const formData = collectFormData(form);
    
    console.log('📤 Envoi des données:', formData);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span>⏳</span> Envoi en cours...';
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
            
            clearFormData();
            form.reset();
            updateProgress();
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            showNotification('❌ ' + (data.message || 'Erreur lors de l\'envoi de votre demande'), 'error');
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
        showNotification('❌ Erreur de connexion au serveur', 'error');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Valider tout le formulaire
function validateForm(form) {
    let isValid = true;
    let firstError = null;
    let errors = [];
    
    form.querySelectorAll('.error-message').forEach(error => error.remove());
    form.querySelectorAll('.error').forEach(input => input.classList.remove('error'));
    
    const nom = form.querySelector('input[name="nom"]');
    if (!nom.value.trim()) {
        showFieldError(nom, 'Le nom est obligatoire');
        if (!firstError) firstError = nom;
        errors.push('Nom manquant');
        isValid = false;
    } else if (!validateField(nom)) {
        if (!firstError) firstError = nom;
        errors.push('Nom invalide');
        isValid = false;
    }
    
    const age = form.querySelector('input[name="age"]');
    if (!age.value.trim()) {
        showFieldError(age, 'L\'âge est obligatoire');
        if (!firstError) firstError = age;
        errors.push('Âge manquant');
        isValid = false;
    } else if (!validateField(age)) {
        if (!firstError) firstError = age;
        errors.push('Âge invalide');
        isValid = false;
    }
    
    const gouvernorat = form.querySelector('select[name="gouvernorat"]');
    if (!gouvernorat.value) {
        showFieldError(gouvernorat, 'Le gouvernorat est obligatoire');
        if (!firstError) firstError = gouvernorat;
        errors.push('Gouvernorat manquant');
        isValid = false;
    }
    
    const ville = form.querySelector('input[name="ville"]');
    if (!ville.value.trim()) {
        showFieldError(ville, 'La ville est obligatoire');
        if (!firstError) firstError = ville;
        errors.push('Ville manquante');
        isValid = false;
    } else if (!validateField(ville)) {
        if (!firstError) firstError = ville;
        errors.push('Ville invalide');
        isValid = false;
    }
    
    const aideCheckboxes = form.querySelectorAll('input[name="aide"]:checked');
    if (aideCheckboxes.length === 0) {
        const firstAide = form.querySelector('input[name="aide"]');
        if (firstAide && !firstError) firstError = firstAide;
        errors.push('Aucune catégorie d\'aide sélectionnée');
        showNotification('⚠️ Veuillez sélectionner au moins une catégorie d\'aide', 'error');
        isValid = false;
    }
    
    const urgenceChecked = form.querySelector('input[name="urgence"]:checked');
    if (!urgenceChecked) {
        const firstUrgence = form.querySelector('input[name="urgence"]');
        if (firstUrgence && !firstError) firstError = firstUrgence;
        errors.push('Degré d\'urgence non sélectionné');
        showNotification('⚠️ Veuillez sélectionner le degré d\'urgence', 'error');
        isValid = false;
    }
    
    const description = form.querySelector('textarea[name="description_situation"]');
    if (!description.value.trim()) {
        showFieldError(description, 'La description de votre situation est obligatoire');
        if (!firstError) firstError = description;
        errors.push('Description manquante');
        isValid = false;
    } else if (!validateField(description)) {
        if (!firstError) firstError = description;
        errors.push('Description invalide');
        isValid = false;
    }
    
    const demande = form.querySelector('textarea[name="demande_exacte"]');
    if (!demande.value.trim()) {
        showFieldError(demande, 'La description de votre demande est obligatoire');
        if (!firstError) firstError = demande;
        errors.push('Demande exacte manquante');
        isValid = false;
    } else if (!validateField(demande)) {
        if (!firstError) firstError = demande;
        errors.push('Demande exacte invalide');
        isValid = false;
    }
    
    const telephone = form.querySelector('input[name="telephone"]');
    if (!telephone.value.trim()) {
        showFieldError(telephone, 'Le téléphone est obligatoire');
        if (!firstError) firstError = telephone;
        errors.push('Téléphone manquant');
        isValid = false;
    } else if (!validateField(telephone)) {
        if (!firstError) firstError = telephone;
        errors.push('Téléphone invalide');
        isValid = false;
    }
    
    const email = form.querySelector('input[name="email"]');
    if (email.value.trim() && !validateField(email)) {
        if (!firstError) firstError = email;
        errors.push('Email invalide');
        isValid = false;
    }
    
    const preference = form.querySelector('select[name="preference_contact"]');
    if (!preference.value) {
        showFieldError(preference, 'La préférence de contact est obligatoire');
        if (!firstError) firstError = preference;
        errors.push('Préférence de contact manquante');
        isValid = false;
    }
    
    const visibiliteChecked = form.querySelector('input[name="visibilite"]:checked');
    if (!visibiliteChecked) {
        const firstVisibilite = form.querySelector('input[name="visibilite"]');
        if (firstVisibilite && !firstError) firstError = firstVisibilite;
        errors.push('Visibilité non sélectionnée');
        showNotification('⚠️ Veuillez sélectionner la visibilité de votre demande', 'error');
        isValid = false;
    }
    
    const attestation = form.querySelector('input[name="attestation"]');
    if (!attestation.checked) {
        showFieldError(attestation, 'Vous devez attester que les informations sont exactes');
        if (!firstError) firstError = attestation;
        errors.push('Attestation non cochée');
        showNotification('⚠️ Vous devez attester que les informations sont exactes', 'error');
        isValid = false;
    }
    
    if (!isValid && errors.length > 1) {
        showNotification(`⚠️ ${errors.length} erreur(s) détectée(s). Veuillez corriger les champs en rouge.`, 'error');
    }
    
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => firstError.focus(), 500);
    }
    
    return isValid;
}

// Collecter les données du formulaire
function collectFormData(form) {
    const formDataObj = new FormData(form);
    
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
        
        clearFormData();
        form.reset();
        
        form.querySelectorAll('.error-message').forEach(error => error.remove());
        form.querySelectorAll('.error').forEach(input => input.classList.remove('error'));
        
        updateProgress();
        
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
    if (!notification) return;
    
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 5000);
}

// Fonction d'aide
function showHelp() {
    alert(`📋 Aide - Formulaire de Demande\n\n` +
          `1. Remplissez tous les champs obligatoires\n` +
          `2. Sélectionnez au moins une catégorie d'aide\n` +
          `3. Décrivez précisément votre situation (min. 20 caractères)\n` +
          `4. Vos données sont sauvegardées automatiquement\n` +
          `5. La validation se fait automatiquement\n\n` +
          `💬 Utilisez l'Assistant Chatbot pour plus d'aide!\n\n` +
          `Pour toute question, contactez-nous au:\n+216 55 581 022`);
}

// Toggle menu mobile
function toggleMobileMenu() {
    const navLinks = document.querySelector('.nav-links');
    if (navLinks) {
        navLinks.classList.toggle('mobile-active');
    }
}