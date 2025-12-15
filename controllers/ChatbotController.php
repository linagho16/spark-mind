<?php
require_once __DIR__ . '/../models/AIHelper.php';

class ChatbotController
{
    private $aiHelper;
    
    public function __construct() {
        $this->aiHelper = new AIHelper();
    }

    public function index()
    {
        require "views/chatbot.php";
    }

    /**
     * Réponse en JSON pour le chatbot flottant
     * Chatbot INTELLIGENT qui peut converser
     */
    public function reply()
    {
        header('Content-Type: application/json');
        
        if (!isset($_POST['message'])) {
            echo json_encode([
                "error" => "Message manquant"
            ]);
            return;
        }

        $message = trim($_POST['message']);
        
        if (empty($message)) {
            echo json_encode([
                "reply" => "🤖 Écrivez un message pour que je puisse vous aider !"
            ]);
            return;
        }

        // Analyser le type de message
        $response = $this->processMessage($message);
        
        echo json_encode([
            "reply" => $response
        ]);
    }
    
    /**
     * Traiter le message et choisir le type de réponse
     */
    private function processMessage($message) {
        $messageLower = mb_strtolower($message);
        
        // 1. QUESTIONS SUR LE CHATBOT
        if ($this->isAboutBot($messageLower)) {
            return $this->answerAboutBot($messageLower);
        }
        
        // 2. SALUTATIONS
        if ($this->isGreeting($messageLower)) {
            return $this->respondToGreeting();
        }
        
        // 3. REMERCIEMENTS
        if ($this->isThanks($messageLower)) {
            return $this->respondToThanks();
        }
        
        // 4. QUESTIONS D'AIDE
        if ($this->isHelpRequest($messageLower)) {
            return $this->provideHelp();
        }
        
        // 5. QUESTIONS SUR LE FONCTIONNEMENT
        if ($this->isHowToQuestion($messageLower)) {
            return $this->explainHowTo($messageLower);
        }
        
        // 6. ANALYSE DE CONTENU POUR PUBLICATION
        if ($this->isContentForAnalysis($message)) {
            return $this->analyzeContent($message);
        }
        
        // 7. RÉPONSE PAR DÉFAUT
        return $this->defaultResponse();
    }
    
    /**
     * Détecter si c'est une question sur le bot
     */
    private function isAboutBot($message) {
        $patterns = [
            'qui es-tu', 'qui es tu', 'c quoi ton role', 'ton role', 
            'tu es qui', 'tu fais quoi', 'tu sers à quoi', 'c est quoi',
            'tu es quoi', 'tu es une ia', 'tu es un robot', 'es-tu une ia',
            'comment tu t\'appelles', 'ton nom'
        ];
        
        foreach ($patterns as $pattern) {
            if (strpos($message, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Répondre aux questions sur le bot
     */
    private function answerAboutBot($message) {
        if (strpos($message, 'nom') !== false || strpos($message, 'appell') !== false) {
            return "🤖 Je suis l'Assistant IA de SparkMind!\n\n"
                 . "Je suis là pour vous aider à utiliser le forum. 😊";
        }
        
        return "🤖 Je suis l'Assistant IA de SparkMind!\n\n"
             . "Mon rôle est de vous aider à :\n"
             . "• Analyser vos messages\n"
             . "• Suggérer la meilleure catégorie pour votre post\n"
             . "• Détecter des propos inappropriés\n"
             . "• Répondre à vos questions sur le forum\n\n"
             . "Comment puis-je vous aider aujourd'hui ? 💬";
    }
    
    /**
     * Détecter les salutations
     */
    private function isGreeting($message) {
        $greetings = ['bonjour', 'salut', 'hello', 'hi', 'bonsoir', 'hey', 'coucou'];
        
        foreach ($greetings as $greeting) {
            if ($message === $greeting || strpos($message, $greeting) === 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Répondre aux salutations
     */
    private function respondToGreeting() {
        $responses = [
            "Bonjour ! 👋 Comment puis-je vous aider ?",
            "Salut ! 😊 Que puis-je faire pour vous ?",
            "Hello ! Je suis là pour vous aider. Que voulez-vous savoir ?",
            "Bonjour ! Bienvenue sur SparkMind ! 🌟"
        ];
        
        return $responses[array_rand($responses)];
    }
    
    /**
     * Détecter les remerciements
     */
    private function isThanks($message) {
        $thanks = ['merci', 'thanks', 'merci beaucoup', 'merci bcp', 'cool', 'super', 'génial'];
        
        foreach ($thanks as $thank) {
            if (strpos($message, $thank) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Répondre aux remerciements
     */
    private function respondToThanks() {
        $responses = [
            "De rien ! 😊 N'hésitez pas si vous avez d'autres questions !",
            "Avec plaisir ! Je suis là pour vous aider ! 💚",
            "Content d'avoir pu vous aider ! 🌟",
            "Pas de problème ! C'est mon rôle ! 🤖"
        ];
        
        return $responses[array_rand($responses)];
    }
    
    /**
     * Détecter les demandes d'aide
     */
    private function isHelpRequest($message) {
        $help = ['aide', 'help', 'comment', 'comment ça marche', 'comment faire', 'peux-tu', 'peux tu'];
        
        foreach ($help as $h) {
            if (strpos($message, $h) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Fournir de l'aide
     */
    private function provideHelp() {
        return "🆘 Voici comment je peux vous aider :\n\n"
             . "1️⃣ **Analyser votre message**\n"
             . "Décrivez votre situation et je vous suggérerai la catégorie appropriée.\n\n"
             . "2️⃣ **Détecter des problèmes**\n"
             . "Je peux vous prévenir si votre message contient des termes inappropriés.\n\n"
             . "3️⃣ **Répondre à vos questions**\n"
             . "Posez-moi vos questions sur le fonctionnement du forum !\n\n"
             . "Que voulez-vous faire ? 💬";
    }
    
    /**
     * Détecter les questions "comment"
     */
    private function isHowToQuestion($message) {
        return strpos($message, 'comment') !== false;
    }
    
    /**
     * Expliquer comment faire
     */
    private function explainHowTo($message) {
        if (strpos($message, 'publier') !== false || strpos($message, 'poster') !== false) {
            return "📝 **Comment publier un post :**\n\n"
                 . "1. Sélectionnez un type de donation\n"
                 . "2. Écrivez votre message (au moins 5 caractères)\n"
                 . "3. Vous pouvez ajouter un titre (optionnel)\n"
                 . "4. Cliquez sur 'Publier'\n\n"
                 . "💡 Astuce : Décrivez-moi votre situation et je vous suggérerai la meilleure catégorie !";
        }
        
        if (strpos($message, 'catégorie') !== false || strpos($message, 'choisir') !== false) {
            return "📂 **Comment choisir une catégorie :**\n\n"
                 . "Dites-moi de quoi parle votre message et je vous suggérerai la catégorie appropriée !\n\n"
                 . "Les catégories disponibles :\n"
                 . "• Échec scolaire / universitaire\n"
                 . "• Harcèlement scolaire\n"
                 . "• Palestine\n"
                 . "• Pression familiale\n"
                 . "• Violence domestique\n"
                 . "• Autre\n\n"
                 . "Essayez de me décrire votre situation ! 💬";
        }
        
        return $this->provideHelp();
    }
    
    /**
     * Détecter si c'est un contenu à analyser
     */
    private function isContentForAnalysis($message) {
        // Si le message est long (>20 caractères) et ne contient pas de question
        if (strlen($message) > 20 && strpos(mb_strtolower($message), '?') === false) {
            return true;
        }
        
        // Si le message contient des mots-clés de catégories
        $keywords = ['école', 'université', 'examen', 'harcèlement', 'famille', 
                     'parents', 'violence', 'palestine', 'gaza', 'problème'];
        
        $messageLower = mb_strtolower($message);
        foreach ($keywords as $keyword) {
            if (strpos($messageLower, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Analyser le contenu avec l'IA
     */
    private function analyzeContent($message) {
        try {
            // Analyser avec l'IA
            $analysis = $this->aiHelper->analyze($message);
            
            // Construire la réponse
            return $this->buildAnalysisResponse($message, $analysis);
            
        } catch (Exception $e) {
            error_log("Chatbot AI Error: " . $e->getMessage());
            
            // Fallback
            return $this->getFallbackAnalysis($message);
        }
    }
    
    /**
     * Construire la réponse d'analyse
     */
    private function buildAnalysisResponse($message, $analysis) {
        $response = "";
        
        // 1. Propos haineux
        if ($analysis['hate_speech']['is_hate_speech']) {
            return "⚠️ Attention ! Votre message contient des termes inappropriés.\n\n" 
                 . "Ce type de message serait bloqué. Pouvez-vous le reformuler de manière respectueuse ?";
        }
        
        // 2. Suggestion de catégorie
        if ($analysis['suggested_category']['suggested_category_id']) {
            $category = $analysis['suggested_category']['category_name'];
            $confidence = $analysis['suggested_category']['confidence'];
            
            if ($confidence > 70) {
                $response .= "✅ Votre message correspond à :\n";
                $response .= "<strong>{$category}</strong>\n\n";
                $response .= "Je vous suggère de sélectionner cette catégorie.\n\n";
            } elseif ($confidence > 40) {
                $response .= "💡 Votre message semble parler de :\n";
                $response .= "<strong>{$category}</strong>\n\n";
                $response .= "Cette catégorie pourrait convenir.\n\n";
            } else {
                $response .= "💡 Peut-être : <strong>{$category}</strong>\n\n";
                $response .= "Mais vous pouvez en choisir une autre.\n\n";
            }
        } else {
            $response .= "🤔 Je n'ai pas détecté de catégorie claire.\n\n";
            $response .= "Pouvez-vous préciser de quoi parle votre message ?\n\n";
        }
        
        // 3. Sentiment
        $sentiment = $analysis['sentiment'];
        
        if ($sentiment['type'] === 'urgent') {
            $response .= "⚡ Votre message semble urgent. Il sera priorisé.\n\n";
        } elseif ($sentiment['type'] === 'négatif') {
            $response .= "Je comprends que la situation soit difficile. La communauté est là pour vous soutenir. 💚\n\n";
        } elseif ($sentiment['type'] === 'positif') {
            $response .= "😊 Merci pour votre message positif !\n\n";
        }
        
        // 4. Conclusion
        $response .= "Vous pouvez maintenant publier votre message ! 📝";
        
        return $response;
    }
    
    /**
     * Analyse de secours
     */
    private function getFallbackAnalysis($message) {
        $messageLower = mb_strtolower($message);
        
        $categories = [
            'échec scolaire / universitaire' => ['échec', 'université', 'école', 'études', 'examen'],
            'harcèlement scolaire' => ['harcèlement', 'harceler', 'intimidation', 'bully'],
            'Palestine' => ['palestine', 'gaza', 'israël'],
            'pression familiale' => ['famille', 'parents', 'pression'],
            'violence domestique' => ['violence', 'abus', 'danger', 'frapper']
        ];
        
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($messageLower, $keyword) !== false) {
                    return "💡 Votre message semble parler de :\n"
                         . "<strong>{$category}</strong>\n\n"
                         . "Je vous suggère de sélectionner cette catégorie.\n\n"
                         . "Vous pouvez maintenant publier ! 📝";
                }
            }
        }
        
        return "🤔 Je n'ai pas bien compris.\n\n"
             . "Pouvez-vous préciser de quoi parle votre message ?\n\n"
             . "Par exemple : problèmes à l'école, harcèlement, famille, etc.";
    }
    
    /**
     * Réponse par défaut
     */
    private function defaultResponse() {
        return "🤖 Je suis là pour vous aider !\n\n"
             . "Vous pouvez :\n"
             . "• Me décrire votre situation pour que je suggère une catégorie\n"
             . "• Me poser des questions sur le forum\n"
             . "• Me demander comment publier un post\n\n"
             . "Que voulez-vous faire ? 💬";
    }
}
















































































































































































































