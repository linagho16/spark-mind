document.getElementById('chatbotButton').addEventListener('click', function() {
    const chatbox = document.getElementById('chatbotBox');
    chatbox.classList.toggle('hidden');
    
    // Message de bienvenue au premier clic
    const chatWindow = document.getElementById('chatWindow');
    if (chatWindow.children.length === 0) {
        addBotMessage("Bonjour ! Je suis l'assistant IA de SparkMind. 🤖\n\nJe peux vous aider à :\n• Analyser un message\n• Suggérer une catégorie\n• Détecter des propos inappropriés\n• Répondre à vos questions\n\nEssayez-moi !");
    }
});

// Fermer le chatbot
document.getElementById('closeChatbot').addEventListener('click', function() {
    document.getElementById('chatbotBox').classList.add('hidden');
});

// Envoyer un message (bouton)
function sendMessage() {
    const input = document.getElementById('userMessage');
    const message = input.value.trim();
    
    if (message === '') return;
    
    // Afficher le message de l'utilisateur
    addUserMessage(message);
    input.value = '';
    
    // Afficher un indicateur de chargement
    addBotMessage('💭 Analyse en cours...');
    
    // Appeler l'API IA
    analyzeWithAI(message);
}

// Envoyer avec Entrée
document.getElementById('userMessage').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Ajouter un message utilisateur
function addUserMessage(text) {
    const chatWindow = document.getElementById('chatWindow');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'chat-msg user-msg';
    msgDiv.textContent = text;
    chatWindow.appendChild(msgDiv);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

// Ajouter un message bot
function addBotMessage(text) {
    const chatWindow = document.getElementById('chatWindow');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'chat-msg bot-msg';
    
    // Gérer les retours à la ligne
    msgDiv.innerHTML = text.replace(/\n/g, '<br>');
    
    chatWindow.appendChild(msgDiv);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

// Supprimer le dernier message (pour enlever le "Analyse en cours...")
function removeLastBotMessage() {
    const chatWindow = document.getElementById('chatWindow');
    const botMessages = chatWindow.querySelectorAll('.bot-msg');
    if (botMessages.length > 0) {
        botMessages[botMessages.length - 1].remove();
    }
}

// Analyser avec l'IA via l'API backend
function analyzeWithAI(message) {
    const formData = new FormData();
    formData.append('message', message);
    
    fetch('index.php?action=chatbot_reply', {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        // Enlever le message "Analyse en cours..."
        removeLastBotMessage();
        
        if (data.reply) {
            addBotMessage(data.reply);
        } else if (data.error) {
            addBotMessage('❌ Erreur: ' + data.error);
        } else {
            addBotMessage('❌ Désolé, je n\'ai pas pu analyser votre message.');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        removeLastBotMessage();
        addBotMessage('❌ Erreur de connexion. Veuillez réessayer.');
    });
}