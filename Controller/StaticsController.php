<?php 
require_once __DIR__ . "/../models/config.php";
require_once __DIR__ . "/../controllers/FeedbackController.php";
require_once __DIR__ . "/../controllers/ReactionController.php";
require_once __DIR__ . "/../controllers/commentcontroller.php";

class StatisticsController {
    
    private $feedbackC;
    private $reactionC;
    private $commentC;
    
    public function __construct() {
        $this->feedbackC = new FeedbackController();
        $this->reactionC = new ReactionController();
        $this->commentC = new CommentController();
    }
    
   
    public function getAllStatistics() {
        $db = Config::getConnexion();
        
        try {
     
            $sql = "SELECT 
                        Feedbacks.id,
                        Feedbacks.description,
                        Feedbacks.created_at,
                        Feedbacks.email,
                        IFNULL(Users.username, Feedbacks.email) AS username,
                        (SELECT COUNT(*) FROM Reactions WHERE Reactions.feedback_id = Feedbacks.id AND Reactions.type = 'heart') as like_count,
                        (SELECT COUNT(*) FROM Comments WHERE Comments.feedback_id = Feedbacks.id) as comment_count
                    FROM Feedbacks
                    LEFT JOIN Users ON Users.email = Feedbacks.email
                    ORDER BY like_count DESC, comment_count DESC, Feedbacks.created_at DESC";
            
            $query = $db->prepare($sql);
            $query->execute();
            $feedbacks = $query->fetchAll();
            
           
            $stats = [
                'total_feedbacks' => count($feedbacks),
                'total_likes' => 0,
                'total_comments' => 0,
                'average_likes_per_feedback' => 0,
                'average_comments_per_feedback' => 0,
                'most_liked_feedbacks' => [],
                'most_commented_feedbacks' => [],
                'feedbacks_by_date' => [],
                'likes_by_date' => [],
                'feedbacks_by_hour' => [],
                'engagement_rate' => 0,
                'feedbacks_data' => []
            ];
            
            $dateFeedbacks = [];
            $dateLikes = [];
            $hourFeedbacks = [];
            
            // Get top 5 most liked feedbacks
            $topLikedFeedbacks = array_slice($feedbacks, 0, 5);
            
            // Get top 5 most commented feedbacks
            usort($feedbacks, function($a, $b) {
                return (int)$b['comment_count'] - (int)$a['comment_count'];
            });
            $topCommentedFeedbacks = array_slice($feedbacks, 0, 5);
            
            // Reset sort for other processing
            usort($feedbacks, function($a, $b) {
                return (int)$b['like_count'] - (int)$a['like_count'];
            });
            
            foreach ($feedbacks as $feedback) {
                $likeCount = (int)$feedback['like_count'];
                $commentCount = (int)$feedback['comment_count'];
                $stats['total_likes'] += $likeCount;
                $stats['total_comments'] += $commentCount;
                
                $date = date('Y-m-d', strtotime($feedback['created_at']));
                if (!isset($dateFeedbacks[$date])) {
                    $dateFeedbacks[$date] = 0;
                    $dateLikes[$date] = 0;
                }
                $dateFeedbacks[$date]++;
                $dateLikes[$date] += $likeCount;
                
                $hour = date('H', strtotime($feedback['created_at']));
                if (!isset($hourFeedbacks[$hour])) {
                    $hourFeedbacks[$hour] = 0;
                }
                $hourFeedbacks[$hour]++;
                
                $stats['feedbacks_data'][] = [
                    'id' => $feedback['id'],
                    'description' => substr($feedback['description'], 0, 100) . (strlen($feedback['description']) > 100 ? '...' : ''),
                    'likes' => $likeCount,
                    'date' => $feedback['created_at'],
                    'author' => $feedback['username']
                ];
            }
            
            // Build most liked feedbacks array
            foreach ($topLikedFeedbacks as $feedback) {
                $stats['most_liked_feedbacks'][] = [
                    'id' => $feedback['id'],
                    'description' => $feedback['description'],
                    'likes' => (int)$feedback['like_count'],
                    'comments' => (int)$feedback['comment_count'],
                    'author' => $feedback['username'],
                    'date' => $feedback['created_at']
                ];
            }
            
            // Build most commented feedbacks array
            foreach ($topCommentedFeedbacks as $feedback) {
                $stats['most_commented_feedbacks'][] = [
                    'id' => $feedback['id'],
                    'description' => $feedback['description'],
                    'likes' => (int)$feedback['like_count'],
                    'comments' => (int)$feedback['comment_count'],
                    'author' => $feedback['username'],
                    'date' => $feedback['created_at']
                ];
            }
            
            if ($stats['total_feedbacks'] > 0) {
                $stats['average_likes_per_feedback'] = round($stats['total_likes'] / $stats['total_feedbacks'], 2);
                $stats['average_comments_per_feedback'] = round($stats['total_comments'] / $stats['total_feedbacks'], 2);
                $stats['engagement_rate'] = $stats['total_feedbacks'] > 0 ? round((($stats['total_likes'] + $stats['total_comments']) / $stats['total_feedbacks']) * 100, 2) : 0;
            }
            
            ksort($dateFeedbacks);
            ksort($dateLikes);
            $stats['feedbacks_by_date'] = $dateFeedbacks;
            $stats['likes_by_date'] = $dateLikes;
            
            ksort($hourFeedbacks);
            $stats['feedbacks_by_hour'] = $hourFeedbacks;
            
            return $stats;
        } catch (Exception $e) {
            error_log("Error in getAllStatistics: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAIInsights($stats) {
        if (!$stats || $stats['total_feedbacks'] == 0) {
            return "No data available for analysis. Start posting feedback to see your statistics and AI-powered insights!";
        }
        
        // Build summary of most liked and commented feedbacks for AI summarization
        $feedbacksSummary = "";
        if (!empty($stats['most_liked_feedbacks'])) {
            $feedbacksSummary = "Most Liked Feedbacks:\n";
            foreach ($stats['most_liked_feedbacks'] as $idx => $feedback) {
                $feedbacksSummary .= ($idx + 1) . ". " . $feedback['description'] . " (Likes: {$feedback['likes']}, Comments: {$feedback['comments']})\n";
            }
        }
        
        if (!empty($stats['most_commented_feedbacks'])) {
            $feedbacksSummary .= "\nMost Commented Feedbacks:\n";
            foreach ($stats['most_commented_feedbacks'] as $idx => $feedback) {
                $feedbacksSummary .= ($idx + 1) . ". " . $feedback['description'] . " (Likes: {$feedback['likes']}, Comments: {$feedback['comments']})\n";
            }
        }
        
        $dataSummary = "Feedback Statistics Summary:
- Total Feedbacks: {$stats['total_feedbacks']}
- Total Likes: {$stats['total_likes']}
- Total Comments: {$stats['total_comments']}
- Average Likes per Feedback: {$stats['average_likes_per_feedback']}
- Average Comments per Feedback: {$stats['average_comments_per_feedback']}
- Engagement Rate: {$stats['engagement_rate']}%

{$feedbacksSummary}

Please provide a comprehensive summary and analysis of the main themes, patterns, and key insights from these feedbacks. Focus on what users are saying, common topics, and actionable recommendations.";
        
        try {
            $aiInsights = $this->callHuggingFaceAPI($dataSummary);
            if (!empty($aiInsights) && strlen($aiInsights) > 20) {
                return $aiInsights;
            }
        } catch (Exception $e) {
            error_log("Error calling Hugging Face API: " . $e->getMessage());
        }
        
        return $this->generateSimpleInsights($stats);
    }
    
    private function callHuggingFaceAPI($dataSummary) {     
        $apiUrl = "https://api-inference.huggingface.co/models/gpt2";
        $apiToken = "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"; 
        
        if (empty($apiToken)) {
            throw new Exception("API token not configured");
        }
        
        $prompt = "Feedback Analysis and Summary:\n\n" . 
                  $dataSummary . 
                  "\n\nSummary:";
        
        $data = [
            'inputs' => $prompt,
            'parameters' => [
                'max_length' => 400,
                'temperature' => 0.9,
                'return_full_text' => false,
                'do_sample' => true,
                'top_p' => 0.9,
                'repetition_penalty' => 1.2
            ]
        ];
        
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiToken
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            throw new Exception("CURL Error: " . $curlError);
        }
        
        if ($httpCode == 200 && $response) {
            $result = json_decode($response, true);
            
            if (isset($result[0]['generated_text'])) {
                $generatedText = $result[0]['generated_text'];
                $generatedText = str_replace($prompt, '', $generatedText);
                $generatedText = trim($generatedText);
                return !empty($generatedText) ? $generatedText : null;
            } elseif (isset($result['generated_text'])) {
                $generatedText = $result['generated_text'];
                $generatedText = str_replace($prompt, '', $generatedText);
                $generatedText = trim($generatedText);
                return !empty($generatedText) ? $generatedText : null;
            } elseif (isset($result['error'])) {
                if (strpos($result['error'], 'loading') !== false) {
                    sleep(5); 
                    return $this->callHuggingFaceAPI($dataSummary); // Retry once
                }
                throw new Exception("API Error: " . $result['error']);
            }
        } elseif ($httpCode == 503) {
           
            sleep(5);
            return $this->callHuggingFaceAPI($dataSummary); 
        } else {
            throw new Exception("API call failed with HTTP code: " . $httpCode);
        }
        
        throw new Exception("Unexpected API response format");
    }
    
    private function generateSimpleInsights($stats) {
        $insights = [];
        
        if ($stats['total_feedbacks'] > 0) {
            $insights[] = "📈 You've received {$stats['total_feedbacks']} feedbacks with {$stats['total_likes']} total likes!";
            
            if ($stats['average_likes_per_feedback'] >= 10) {
                $insights[] = "🌟 Excellent engagement! Your feedbacks average {$stats['average_likes_per_feedback']} likes each - highly appreciated feedback!";
            } elseif ($stats['average_likes_per_feedback'] >= 5) {
                $insights[] = "✨ Great engagement! Your feedbacks average {$stats['average_likes_per_feedback']} likes each. Keep up the good work!";
            } elseif ($stats['average_likes_per_feedback'] >= 2) {
                $insights[] = "👍 Good engagement with an average of {$stats['average_likes_per_feedback']} likes per feedback.";
            } else {
                $insights[] = "💡 Your feedbacks average {$stats['average_likes_per_feedback']} likes.";
            }
            
            if (!empty($stats['most_liked_feedbacks'])) {
                $topFeedback = $stats['most_liked_feedbacks'][0];
                $insights[] = "🏆 Your most liked feedback received {$topFeedback['likes']} likes!";
                
                // Summarize top feedbacks
                $insights[] = "\n📝 Summary of Most Liked Feedbacks:";
                foreach (array_slice($stats['most_liked_feedbacks'], 0, 3) as $idx => $feedback) {
                    $summary = substr($feedback['description'], 0, 150);
                    if (strlen($feedback['description']) > 150) {
                        $summary .= '...';
                    }
                    $insights[] = ($idx + 1) . ". ({$feedback['likes']} likes) " . $summary;
                }
            }
            
            if (count($stats['feedbacks_by_hour']) > 0) {
                $bestHour = array_search(max($stats['feedbacks_by_hour']), $stats['feedbacks_by_hour']);
                $bestHourCount = $stats['feedbacks_by_hour'][$bestHour];
                $insights[] = "\n⏰ Most active feedback time is around {$bestHour}:00 ({$bestHourCount} feedbacks).";
            }
            
            if ($stats['engagement_rate'] > 50) {
                $insights[] = "🎯 Your engagement rate is {$stats['engagement_rate']}% - this is outstanding! Your feedbacks resonate well with users.";
            } elseif ($stats['engagement_rate'] > 20) {
                $insights[] = "📊 Your engagement rate is {$stats['engagement_rate']}% - good performance!";
            } else {
                $insights[] = "📊 Your engagement rate is {$stats['engagement_rate']}%.";
            }
        } else {
            $insights[] = "🚀 Start receiving feedback to see your statistics and AI-powered insights!";
        }
        
        return implode("\n\n", $insights);
    }
}

?>


