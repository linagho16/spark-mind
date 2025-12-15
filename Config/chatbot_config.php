<?php
// Obtenez votre clé API gratuite sur : https://platform.deepseek.com/

// OPTION 1 : Clé API directe
define('DEEPSEEK_API_KEY', 'sk-e065266c887749a086679ffd9b220bf8');

// OPTION 2 : Chargement depuis variable d'environnement
 define('DEEPSEEK_API_KEY', getenv('DEEPSEEK_API_KEY'));

// Configuration de l'API
define('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');
define('DEEPSEEK_MODEL', 'deepseek-chat');

// Paramètres par défaut
define('CHATBOT_MAX_TOKENS', 500);
define('CHATBOT_TEMPERATURE', 0.7);

// Activer/désactiver le mode débogage
define('CHATBOT_DEBUG', true);
?>