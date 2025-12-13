<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/DonationType.php';

class AdminController {
    
    public function dashboard() {
        $postModel = new Post();
        $commentModel = new Comment();
        $donationTypeModel = new DonationType();
        
        $totalPosts = count($postModel->getAll());
        $totalComments = count($commentModel->getAll());
        $totalTypes = count($donationTypeModel->getAll());
        
        // Statistiques par jour (7 derniers jours)
        $dailyStats = $this->getDailyPublicationStats(7);
        
        include __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /**
     * Obtenir les statistiques de publication par jour
     */
    public function getDailyPublicationStats($days = 30) {
        require_once __DIR__ . '/../config/database.php';
        $db = (new Database())->pdo;
        
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Dashboard IA avec statistiques avancées
     */
    public function aiDashboard() {
        require_once __DIR__ . '/../models/AIHelper.php';
        
        $postModel = new Post();
        $commentModel = new Comment();
        $donationTypeModel = new DonationType();
        
        // Statistiques globales
        $globalStats = [
            'total_posts' => count($postModel->getAll()),
            'total_comments' => count($commentModel->getAll()),
            'posts_this_week' => $this->getPostsThisWeek(),
            'avg_comments_per_post' => $this->getAvgCommentsPerPost()
        ];
        
        // Tendances et analyse
        $trends = [
            'period' => '30 derniers jours',
            'sentiment_stats' => $this->getSentimentStats(),
            'most_commented' => $this->getMostCommentedPosts(5),
            'top_categories' => $this->getTopCategories(),
            'activity' => $this->getDailyPublicationStats(30),
            'daily_chart' => $this->prepareDailyChart(30)
        ];
        
        include __DIR__ . '/../views/admin/trends.php';
    }
    
    /**
     * Préparer les données pour le graphique
     */
    private function prepareDailyChart($days = 30) {
        $stats = $this->getDailyPublicationStats($days);
        
        // Créer un tableau avec tous les jours (même ceux sans posts)
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartData[$date] = 0;
        }
        
        // Remplir avec les vrais chiffres
        foreach ($stats as $stat) {
            $chartData[$stat['date']] = (int)$stat['count'];
        }
        
        return $chartData;
    }
    
    /**
     * Posts cette semaine
     */
    private function getPostsThisWeek() {
        require_once __DIR__ . '/../config/database.php';
        $db = (new Database())->pdo;
        $sql = "SELECT COUNT(*) as count FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Moyenne de commentaires par post
     */
    private function getAvgCommentsPerPost() {
        require_once __DIR__ . '/../config/database.php';
        $db = (new Database())->pdo;
        $sql = "SELECT 
                    COALESCE(ROUND(COUNT(c.id) / NULLIF(COUNT(DISTINCT p.id), 0), 1), 0) as avg
                FROM posts p
                LEFT JOIN comments c ON p.id = c.post_id";
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['avg'];
    }
    
    /**
     * Statistiques de sentiment (simplifié)
     */
    private function getSentimentStats() {
        require_once __DIR__ . '/../config/database.php';
        $db = (new Database())->pdo;
        
        // Mots positifs et négatifs pour analyse basique
        $positiveWords = ['merci', 'super', 'bien', 'génial', 'content', 'heureux'];
        $negativeWords = ['triste', 'mal', 'difficile', 'problème', 'peur', 'aide'];
        
        $sql = "SELECT contenu FROM posts";
        $stmt = $db->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stats = ['positive' => 0, 'negative' => 0, 'neutral' => 0];
        
        foreach ($posts as $post) {
            $content = mb_strtolower($post['contenu']);
            $hasPositive = false;
            $hasNegative = false;
            
            foreach ($positiveWords as $word) {
                if (strpos($content, $word) !== false) {
                    $hasPositive = true;
                    break;
                }
            }
            
            foreach ($negativeWords as $word) {
                if (strpos($content, $word) !== false) {
                    $hasNegative = true;
                    break;
                }
            }
            
            if ($hasPositive && !$hasNegative) {
                $stats['positive']++;
            } elseif ($hasNegative && !$hasPositive) {
                $stats['negative']++;
            } else {
                $stats['neutral']++;
            }
        }
        
        return $stats;
    }
    
    /**
     * Posts les plus commentés
     */
    private function getMostCommentedPosts($limit = 5) {
        require_once __DIR__ . '/../config/database.php';
        $db = (new Database())->pdo;
        $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color,
                       COUNT(c.id) as comment_count
                FROM posts p
                LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
                LEFT JOIN comments c ON p.id = c.post_id
                GROUP BY p.id
                HAVING comment_count > 0
                ORDER BY comment_count DESC
                LIMIT :limit";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Catégories les plus utilisées
     */
    private function getTopCategories() {
        require_once __DIR__ . '/../config/database.php';
        $db = (new Database())->pdo;
        $sql = "SELECT dt.name, dt.icon, dt.color, COUNT(p.id) as post_count
                FROM donation_types dt
                LEFT JOIN posts p ON dt.id = p.donation_type_id
                GROUP BY dt.id
                ORDER BY post_count DESC";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ========== MÉTHODES EXISTANTES ==========
    
    public function listPosts() {
        $postModel = new Post();
        $posts = $postModel->getAll();
        include __DIR__ . '/../views/admin/post_list.php';
    }
    
    public function deletePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $postModel = new Post();
                $postModel->delete($id);
            }
        }
        header('Location: index.php?action=admin_posts');
        exit;
    }
    
    public function listComments() {
        $commentModel = new Comment();
        $comments = $commentModel->getAll();
        include __DIR__ . '/../views/admin/comments_list.php';
    }
    
    public function deleteCommentAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $commentModel = new Comment();
                $commentModel->delete($id);
            }
        }
        header('Location: index.php?action=admin_comments');
        exit;
    }
    
    public function listDonationTypes() {
        $donationTypeModel = new DonationType();
        $types = $donationTypeModel->getAll();
        include __DIR__ . '/../views/admin/types_list.php';
    }
}