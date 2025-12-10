<?php
// Activer les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DÉSACTIVER l'affichage HTML pour que seul le JSON soit retourné
header('Content-Type: application/json; charset=utf-8');

// Base de connaissances étendue
$knowledgeBase = [
    'greeting' => [
        "👋 Bonjour ! Je suis l'assistant d'Aide Solidaire. Comment puis-je vous aider aujourd'hui ?",
        "🌞 Bonjour ! Je suis là pour vous accompagner dans vos démarches solidaires.",
        "🤝 Salutations ! Je peux vous aider avec les dons, groupes et questions générales."
    ],
    
    'thanks' => [
        "😊 De rien ! C'est un plaisir de vous aider.",
        "🤗 Avec plaisir ! Merci pour votre engagement.",
        "🌟 Je suis là pour ça ! N'hésitez pas si vous avez d'autres questions."
    ],
    
    'dons' => [
        // ... gardez votre contenu existant ...
    ],
    
    'groupes' => [
        'creation' => "👥 **CRÉER UN GROUPE :**\n\n" .
                     "1. **INSCRIPTION** : Créez un compte sur Aide Solidaire\n" .
                     "2. **FORMULAIRE** : Remplissez le formulaire de création\n" .
                     "3. **INFORMATIONS** : Fournissez nom, type, région, description\n" .
                     "4. **CONTACT** : Ajoutez coordonnées du responsable\n" .
                     "5. **VALIDATION** : Notre équipe valide sous 24h\n" .
                     "6. **ACTIVATION** : Vous recevez accès à l'espace groupe\n\n" .
                     "📋 **Documents utiles** : CIN, preuve d'adresse, références",
        
        'gestion' => "📊 **GESTION DE GROUPE :**\n\n" .
                    "• **Membres** : Invitez, gérez les rôles\n" .
                    "• **Activités** : Planifiez collectes, distributions\n" .
                    "• **Communication** : Messagerie interne\n" .
                    "• **Rapports** : Suivez vos actions\n" .
                    "• **Finance** : Gestion des dons reçus\n\n" .
                    "🛠️ **Outils disponibles** : Tableau de bord, calendrier, suivi",
        
        'rejoindre' => "🔍 **REJOINDRE UN GROUPE :**\n\n" .
                      "1. **PARCOURIR** : Consultez la liste des groupes\n" .
                      "2. **FILTRER** : Par type, région, activité\n" .
                      "3. **CONTACTER** : Envoyez un message au responsable\n" .
                      "4. **RENCONTRE** : Participez à une première réunion\n" .
                      "5. **INTÉGRATION** : Commencez vos activités\n\n" .
                      "🤝 **Conseil** : Précisez vos compétences et disponibilités"
    ],
    
    'general' => [
        'contact' => "📞 **CONTACT :**\n\n" .
                    "• **Support général** : contact@aidesolidaire.tn\n" .
                    "• **Dons** : dons@aidesolidaire.tn\n" .
                    "• **Groupes** : groupes@aidesolidaire.tn\n" .
                    "• **Partenariats** : partenariats@aidesolidaire.tn\n" .
                    "• **Urgences** : +216 98 765 432 (24h/24)\n" .
                    "• **Adresse** : Tunis, Tunisie\n\n" .
                    "⏰ **Horaires** : Lun-Ven 8h-18h, Sam 9h-13h",
        
        'urgence' => "🚨 **URGENCES :**\n\n" .
                    "Pour situations critiques (santé, expulsion, catastrophe) :\n\n" .
                    "1. **APPEL** : +216 98 765 432 (immédiat)\n" .
                    "2. **DESCRIPTION** : Expliquez la situation\n" .
                    "3. **LOCALISATION** : Donnez l'adresse exacte\n" .
                    "4. **BESOINS** : Liste des besoins prioritaires\n" .
                    "5. **INTERVENTION** : Équipe sur place sous 4h\n\n" .
                    "⚠️ **Priorités** : Santé, nourriture, abri, sécurité"
    ],
    
    'help' => "🆘 **JE PEUX VOUS AIDER AVEC :**\n\n" .
              "📦 **DONS**\n" .
              "• Types de dons acceptés\n" .
              "• Procédures de don\n" .
              "• Avantages fiscaux\n" .
              "• Collecte à domicile\n\n" .
              "👥 **GROUPES**\n" .
              "• Création de groupe\n" .
              "• Gestion et organisation\n" .
              "• Rejoindre un groupe\n" .
              "• Financement de groupe\n\n" .
              "📋 **GÉNÉRAL**\n" .
              "• Contacts et coordonnées\n" .
              "• Procédures d'urgence\n" .
              "• Questions régionales\n" .
              "• Sécurité et confiance\n\n" .
              "💡 **Astuce** : Posez-moi une question précise pour une réponse détaillée !"
];

// Fonction pour détecter le type de question
function detectIntent($message) {
    $message = strtolower($message);
    
    // Salutations
    $greetings = ['bonjour', 'salut', 'hello', 'salam', 'hi', 'coucou', 'bonsoir', 'hey'];
    foreach ($greetings as $greeting) {
        if (strpos($message, $greeting) !== false) {
            return 'greeting';
        }
    }
    
    // Remerciements
    $thanks = ['merci', 'thank', 'chokran', 'grac', 'remercie'];
    foreach ($thanks as $thank) {
        if (strpos($message, $thank) !== false) {
            return 'thanks';
        }
    }
    
    // Dons
    $donKeywords = ['don', 'dons', 'donner', 'donation', 'argent', 'financier', 
                   'vêtement', 'nourriture', 'médicament', 'collecte', 'ramassage',
                   'fiscal', 'impôt', 'avantage fiscal', 'déduction'];
    foreach ($donKeywords as $keyword) {
        if (strpos($message, $keyword) !== false) {
            return 'dons';
        }
    }
    
    // Groupes
    $groupeKeywords = ['groupe', 'groupes', 'créer groupe', 'rejoindre groupe',
                      'responsable', 'membre', 'activité groupe', 'gestion groupe'];
    foreach ($groupeKeywords as $keyword) {
        if (strpos($message, $keyword) !== false) {
            return 'groupes';
        }
    }
    
    // Urgences
    if (strpos($message, 'urgence') !== false || strpos($message, 'urgent') !== false) {
        return 'urgence';
    }
    
    // Contact
    $contactKeywords = ['contact', 'contacter', 'appeler', 'téléphone', 'email', 'mail', 'adresse'];
    foreach ($contactKeywords as $keyword) {
        if (strpos($message, $keyword) !== false) {
            return 'contact';
        }
    }
    
    // Aide
    if (strpos($message, 'aide') !== false || strpos($message, 'help') !== false ||
        strpos($message, 'quoi') !== false || strpos($message, 'comment')) {
        return 'help';
    }
    
    return 'unknown';
}

// Fonction pour obtenir la réponse
function getResponse($message) {
    global $knowledgeBase;
    
    $intent = detectIntent($message);
    $lowerMessage = strtolower($message);
    
    switch ($intent) {
        case 'greeting':
            return $knowledgeBase['greeting'][array_rand($knowledgeBase['greeting'])];
            
        case 'thanks':
            return $knowledgeBase['thanks'][array_rand($knowledgeBase['thanks'])];
            
        case 'dons':
            // Sous-catégories des dons
            if (strpos($lowerMessage, 'type') !== false || strpos($lowerMessage, 'quoi donner') !== false) {
                return $knowledgeBase['dons']['types'] ?? $knowledgeBase['help'];
            }
            if (strpos($lowerMessage, 'procédure') !== false || strpos($lowerMessage, 'comment donner') !== false) {
                return $knowledgeBase['dons']['procedure'] ?? $knowledgeBase['help'];
            }
            if (strpos($lowerMessage, 'argent') !== false || strpos($lowerMessage, 'financier') !== false) {
                return $knowledgeBase['dons']['argent'] ?? $knowledgeBase['help'];
            }
            if (strpos($lowerMessage, 'fiscal') !== false || strpos($lowerMessage, 'impôt') !== false) {
                return $knowledgeBase['dons']['avantages_fiscaux'] ?? $knowledgeBase['help'];
            }
            if (strpos($lowerMessage, 'collecte') !== false || strpos($lowerMessage, 'ramassage') !== false) {
                return $knowledgeBase['dons']['collecte'] ?? $knowledgeBase['help'];
            }
            return $knowledgeBase['dons']['types'] ?? $knowledgeBase['help'];
            
        case 'groupes':
            // Sous-catégories des groupes
            if (strpos($lowerMessage, 'créer') !== false) {
                return $knowledgeBase['groupes']['creation'] ?? $knowledgeBase['help'];
            }
            if (strpos($lowerMessage, 'gérer') !== false || strpos($lowerMessage, 'gestion') !== false) {
                return $knowledgeBase['groupes']['gestion'] ?? $knowledgeBase['help'];
            }
            if (strpos($lowerMessage, 'rejoindre') !== false) {
                return $knowledgeBase['groupes']['rejoindre'] ?? $knowledgeBase['help'];
            }
            return $knowledgeBase['groupes']['creation'] ?? $knowledgeBase['help'];
            
        case 'urgence':
            return $knowledgeBase['general']['urgence'] ?? "Pour les urgences, appelez le +216 98 765 432 (24h/24)";
            
        case 'contact':
            return $knowledgeBase['general']['contact'] ?? "Contact : contact@aidesolidaire.tn | +216 70 123 456";
            
        case 'help':
            return $knowledgeBase['help'];
            
        default:
            // Réponse par défaut pour questions inconnues
            return "🤔 Je comprends votre question mais je suis spécialisé dans l'aide solidaire.\n\n" .
                   "Je peux vous aider avec :\n" .
                   "• 📦 **Dons** et collectes\n" .
                   "• 👥 **Groupes** solidaires\n" .
                   "• 📋 **Procédures** générales\n" .
                   "• 🚨 **Situations d'urgence**\n\n" .
                   "Pour d'autres questions, contactez :\n" .
                   "📧 contact@aidesolidaire.tn\n" .
                   "📞 +216 70 123 456\n\n" .
                   "Pour une question sur l'aide solidaire, je suis là ! 😊";
    }
}

// Gestion de la requête
try {
    // Récupérer le message
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Support pour les tests POST simples
    if (!$input && isset($_POST['message'])) {
        $input = $_POST;
    }
    
    // Validation
    if (!$input || !isset($input['message']) || empty(trim($input['message']))) {
        echo json_encode([
            'success' => false,
            'error' => 'Message vide',
            'message' => 'Veuillez entrer un message pour que je puisse vous aider.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $message = trim($input['message']);
    
    // Obtenir la réponse
    $response = getResponse($message);
    $intent = detectIntent($message);
    
    // Ajouter contexte personnalisé
    $context = $input['context'] ?? [];
    if (isset($context['groupName']) && !empty($context['groupName'])) {
        $response = "En lien avec le groupe '{$context['groupName']}' :\n\n" . $response;
    }
    
    // Réponse JSON
    echo json_encode([
        'success' => true,
        'message' => $response,
        'intent' => $intent,
        'source' => 'aide_solidaire_assistant',
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // En cas d'erreur
    error_log('Chatbot API error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'message' => "🤔 Je rencontre des difficultés techniques. Contactez-nous directement :\n\n" .
                    "📧 contact@aidesolidaire.tn\n" .
                    "📞 +216 70 123 456\n\n" .
                    "Nous répondons rapidement !",
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}
?>