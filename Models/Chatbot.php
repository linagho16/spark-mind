<?php

class Chatbot {

    private $hateWords = [
        "pute", "salope", "fdp", "bâtard", "nique", "raciste", "suicide"
    ];

    private $categories = [
        "échec", "université", "études" => "Échec scolaire / universitaire",
        "famille", "parents", "pression" => "Pression familiale",
        "harcèlement", "insulte", "agression" => "Harcèlement scolaire",
        "violence", "coup", "peur" => "Violence domestique",
        "palestine", "guerre", "occupation" => "Palestine",
    ];

    public function analyze($message) {

        // 1. Hate speech detection
        foreach ($this->hateWords as $word) {
            if (str_contains(strtolower($message), $word)) {
                return "⚠️ Votre message contient possiblement des propos inappropriés. 
Je vous conseille de reformuler.";
            }
        }

        // 2. Category suggestion
        foreach ($this->categories as $keyword => $category) {
            if (str_contains(strtolower($message), $keyword)) {
                return "📌 Ce sujet semble lié à : **$category**.";
            }
        }

        // 3. Sentiment analysis (simple)
        if (preg_match('/(triste|déprimé|mal|fatigué|peur|angoisse)/i', $message)) {
            return "💬 Je ressens beaucoup de négativité dans votre message. Courage, vous n’êtes pas seul(e).";
        }

        if (preg_match('/(merci|bien|heureux|content)/i', $message)) {
            return "😊 Super ! Je suis ravi que tout se passe bien pour vous.";
        }

        // Default
        return "🤖 Merci pour votre message ! Je suis là pour vous aider. Parlez-moi de ce que vous ressentez.";
    }
}
