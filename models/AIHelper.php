<?php
/**
 * AIHelper - Version Hugging Face GRATUITE pour forum social
 * Vraie IA avec modèles gratuits
 */
class AIHelper {
    
    private $hfToken; // Token Hugging Face gratuit
    
    // Modèles IA gratuits
    private $models = [
        'sentiment' => 'cardiffnlp/twitter-xlm-roberta-base-sentiment',
        'classification' => 'facebook/bart-large-mnli',
        'toxicity' => 'martin-ha/toxic-comment-model'
    ];
    
    // Mots-clés de secours si API ne répond pas
    private $categoryKeywords = [
        1 => ['école', 'université', 'étude', 'examen', 'échec', 'redoublement'],
        2 => ['harcèlement', 'harcelé', 'intimidation', 'bully', 'moquerie'],
        3 => ['palestine', 'gaza', 'israël', 'conflit'],
        4 => ['famille', 'parent', 'pression', 'mariage', 'obligation'],
        5 => ['violence', 'abus', 'maltraité', 'frappé', 'danger'],
        6 => ['autre']
    ];
    
    private $categoryNames = [
        1 => 'Échec scolaire / universitaire',
        2 => 'Harcèlement scolaire',
        3 => 'Palestine',
        4 => 'Pression familiale',
        5 => 'Violence domestique',
        6 => 'Autre'
    ];
    
    public function __construct() {
        // METTEZ VOTRE TOKEN ICI (gratuit sur huggingface.co/settings/tokens)
        $this->hfToken = "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"; 
        
        // Ou chargez depuis un fichier .env
        if (file_exists(__DIR__ . '/../.env')) {
            $env = parse_ini_file(__DIR__ . '/../.env');
            if (isset($env['HUGGINGFACE_TOKEN'])) {
                $this->hfToken = $env['HUGGINGFACE_TOKEN'];
            }
        }
    }
    
    /**
     * Analyse complète avec IA
     */
    public function analyze($content) {
        // Vérifier si le token est configuré
        if (!$this->hfToken || $this->hfToken === "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") {
            error_log("⚠️ Hugging Face token non configuré - utilisation du mode basique");
            return $this->analyzeBasic($content);
        }
        
        return [
            'hate_speech' => $this->detectToxicity($content),
            'suggested_category' => $this->suggestCategory($content),
            'sentiment' => $this->analyzeSentiment($content),
            'keywords' => $this->extractKeywords($content),
            'needs_moderation' => false
        ];
    }
    
    /**
     * Détection de toxicité avec VRAIE IA
     */
    private function detectToxicity($content) {
        try {
            $response = $this->callHuggingFace(
                $this->models['toxicity'],
                $content
            );
            
            if ($response && isset($response[0])) {
                $results = $response[0];
                $toxic = false;
                $confidence = 0;
                
                // Le modèle retourne plusieurs labels
                foreach ($results as $result) {
                    if (isset($result['label']) && $result['label'] === 'toxic') {
                        $toxic = true;
                        $confidence = round($result['score'] * 100);
                        break;
                    }
                }
                
                return [
                    'is_hate_speech' => $toxic,
                    'confidence' => $confidence,
                    'reason' => $toxic ? "Contenu toxique détecté par l'IA (confiance: {$confidence}%)" : '',
                    'detected_words' => []
                ];
            }
        } catch (Exception $e) {
            error_log("Toxicity API Error: " . $e->getMessage());
        }
        
        // Fallback sur détection basique
        return $this->detectToxicityBasic($content);
    }
    
    /**
     * Analyse de sentiment avec VRAIE IA
     */
    private function analyzeSentiment($content) {
        try {
            $response = $this->callHuggingFace(
                $this->models['sentiment'],
                $content
            );
            
            if ($response && isset($response[0])) {
                $result = $response[0][0];
                $label = $result['label'];
                $score = round($result['score'] * 100);
                
                // Mapper les labels anglais vers français
                $typeMap = [
                    'positive' => 'positif',
                    'negative' => 'négatif',
                    'neutral' => 'neutre'
                ];
                
                // Détecter l'urgence par mots-clés en complément
                $urgentWords = ['urgent', 'aide', 'danger', 'vite', 'maintenant'];
                $contentLower = mb_strtolower($content);
                $isUrgent = false;
                
                foreach ($urgentWords as $word) {
                    if (strpos($contentLower, $word) !== false) {
                        $isUrgent = true;
                        break;
                    }
                }
                
                if ($isUrgent) {
                    return ['type' => 'urgent', 'score' => 90];
                }
                
                return [
                    'type' => $typeMap[$label] ?? 'neutre',
                    'score' => $score
                ];
            }
        } catch (Exception $e) {
            error_log("Sentiment API Error: " . $e->getMessage());
        }
        
        // Fallback
        return $this->analyzeSentimentBasic($content);
    }
    
    /**
     * Suggestion de catégorie avec VRAIE IA
     */
    private function suggestCategory($content) {
        // Créer des descriptions pour chaque catégorie
        $categories = [
            "Ce texte parle d'échec scolaire, problèmes universitaires, difficultés d'études ou abandon scolaire",
            "Ce texte parle de harcèlement scolaire, intimidation, bullying ou violence à l'école",
            "Ce texte parle de la Palestine, du conflit israélo-palestinien ou de Gaza",
            "Ce texte parle de pression familiale, mariage forcé, conflits avec les parents ou obligations familiales",
            "Ce texte parle de violence domestique, abus conjugal, maltraitance ou danger à la maison",
            "Ce texte parle d'un autre sujet"
        ];
        
        try {
            $response = $this->callHuggingFace(
                $this->models['classification'],
                $content,
                ['candidate_labels' => $categories]
            );
            
            if ($response && isset($response['labels'][0])) {
                $topLabel = $response['labels'][0];
                $confidence = round($response['scores'][0] * 100);
                
                // Trouver l'index de la catégorie
                $categoryIndex = array_search($topLabel, $categories);
                
                if ($categoryIndex !== false && $confidence > 30) {
                    $categoryId = $categoryIndex + 1; // Les IDs commencent à 1
                    
                    return [
                        'suggested_category_id' => $categoryId,
                        'confidence' => $confidence,
                        'explanation' => "Détecté par IA : " . $this->categoryNames[$categoryId],
                        'category_name' => $this->categoryNames[$categoryId]
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Category API Error: " . $e->getMessage());
        }
        
        // Fallback
        return $this->suggestCategoryBasic($content);
    }
    
    /**
     * Appel à l'API Hugging Face
     */
    private function callHuggingFace($model, $text, $params = []) {
        $url = "https://router.huggingface.co/models/" . $model;
        
        $data = array_merge([
            'inputs' => $text
        ], $params);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Timeout de 15 secondes
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->hfToken,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // DEBUG - À SUPPRIMER APRÈS
        error_log("=== HUGGING FACE DEBUG ===");
        error_log("HTTP Code: " . $httpCode);
        error_log("Response: " . $response);
        error_log("==========================");
        
        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode === 503) {
            throw new Exception('Model is loading, please retry in a few seconds');
        }
        
        if ($httpCode !== 200) {
            throw new Exception('API Error: HTTP ' . $httpCode . ' - ' . $response);
        }
        
        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON Error: ' . json_last_error_msg());
        }
        
        return $decoded;
    }
    
    /**
     * Extraction de mots-clés
     */
    private function extractKeywords($content) {
        $contentLower = mb_strtolower($content);
        $words = preg_split('/\s+/', $contentLower);
        
        $stopWords = ['dans', 'pour', 'avec', 'sans', 'être', 'avoir', 
                      'faire', 'dire', 'mon', 'ma', 'mes', 'le', 'la'];
        
        $keywords = [];
        foreach ($words as $word) {
            $word = trim($word, '.,!?;:"\'-');
            if (strlen($word) >= 4 && !in_array($word, $stopWords)) {
                $keywords[] = $word;
            }
        }
        
        return array_values(array_unique(array_slice($keywords, 0, 5)));
    }
    
    // ==========================================
    // MÉTHODES FALLBACK (si API ne répond pas)
    // ==========================================
    
    private function analyzeBasic($content) {
        return [
            'hate_speech' => $this->detectToxicityBasic($content),
            'suggested_category' => $this->suggestCategoryBasic($content),
            'sentiment' => $this->analyzeSentimentBasic($content),
            'keywords' => $this->extractKeywords($content),
            'needs_moderation' => false
        ];
    }
    
    private function detectToxicityBasic($content) {
        $bannedWords = ['idiot', 'con', 'débile', 'connard', 'salaud', 'haine', 'tuer'];
        $contentLower = mb_strtolower($content);
        $detected = [];
        
        foreach ($bannedWords as $word) {
            if (strpos($contentLower, $word) !== false) {
                $detected[] = $word;
            }
        }
        
        return [
            'is_hate_speech' => !empty($detected),
            'confidence' => min(100, count($detected) * 30),
            'reason' => !empty($detected) ? "Mots interdits : " . implode(', ', $detected) : '',
            'detected_words' => $detected
        ];
    }
    
    private function suggestCategoryBasic($content) {
        $contentLower = mb_strtolower($content);
        $bestCategory = null;
        $bestScore = 0;
        
        foreach ($this->categoryKeywords as $catId => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strpos($contentLower, $keyword) !== false) {
                    $score++;
                }
            }
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestCategory = $catId;
            }
        }
        
        if ($bestCategory) {
            return [
                'suggested_category_id' => $bestCategory,
                'confidence' => min(100, $bestScore * 25),
                'explanation' => "Mots-clés détectés",
                'category_name' => $this->categoryNames[$bestCategory]
            ];
        }
        
        return [
            'suggested_category_id' => null,
            'confidence' => 0,
            'explanation' => 'Aucune catégorie claire',
            'category_name' => null
        ];
    }
    
    private function analyzeSentimentBasic($content) {
        $contentLower = mb_strtolower($content);
        
        $urgentWords = ['urgent', 'aide', 'danger', 'vite'];
        $positiveWords = ['merci', 'super', 'bien', 'mieux'];
        $negativeWords = ['triste', 'difficile', 'problème', 'mal'];
        
        $urgentScore = 0;
        foreach ($urgentWords as $word) {
            if (strpos($contentLower, $word) !== false) $urgentScore++;
        }
        
        if ($urgentScore > 0) {
            return ['type' => 'urgent', 'score' => min(100, $urgentScore * 40)];
        }
        
        $positiveScore = 0;
        $negativeScore = 0;
        
        foreach ($positiveWords as $word) {
            if (strpos($contentLower, $word) !== false) $positiveScore++;
        }
        
        foreach ($negativeWords as $word) {
            if (strpos($contentLower, $word) !== false) $negativeScore++;
        }
        
        if ($positiveScore > $negativeScore) {
            return ['type' => 'positif', 'score' => min(100, $positiveScore * 30)];
        } elseif ($negativeScore > $positiveScore) {
            return ['type' => 'négatif', 'score' => min(100, $negativeScore * 30)];
        }
        
        return ['type' => 'neutre', 'score' => 50];
    }
    
    public function quickCheck($content) {
        $result = $this->detectToxicity($content);
        return [
            'is_clean' => !$result['is_hate_speech'],
            'reason' => $result['reason']
        ];
    }
    
    public function getStats() {
        return [
            'total_analyzed' => 0,
            'hate_speech_detected' => 0,
            'categories_suggested' => 0
        ];
    }
}