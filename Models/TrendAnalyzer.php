<?php
require_once __DIR__ . '/../config/database.php';

class TrendAnalyzer {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->pdo;
    }
    
    /**
     * ANALYSER LES TENDANCES
     */
    public function analyzeTrends($days = 7) {
        // 1. Posts les plus commentés
        $sql = "SELECT p.id, p.titre, p.contenu, dt.name as type_name, dt.icon,
                COUNT(c.id) as comment_count
                FROM posts p
                LEFT JOIN comments c ON p.id = c.post_id
                LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
                WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY p.id
                ORDER BY comment_count DESC
                LIMIT 5";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':days' => $days]);
        $mostCommented = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 2. Sujets les plus discutés
        $sql = "SELECT dt.name, dt.icon, dt.color, COUNT(p.id) as post_count
                FROM donation_types dt
                LEFT JOIN posts p ON dt.id = p.donation_type_id
                WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY dt.id
                ORDER BY post_count DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':days' => $days]);
        $topCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. Activité par jour
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count
                FROM posts
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':days' => $days]);
        $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 4. Analyse de sentiments globale
        $sql = "SELECT p.contenu FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':days' => $days]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $aiHelper = new AIHelper();
        $sentimentStats = ['positive' => 0, 'negative' => 0, 'neutral' => 0];
        
        foreach ($posts as $post) {
            $sentiment = $aiHelper->analyzeSentiment($post['contenu']);
            $sentimentStats[$sentiment['sentiment']]++;
        }
        
        return [
            'most_commented' => $mostCommented,
            'top_categories' => $topCategories,
            'activity' => $activity,
            'sentiment_stats' => $sentimentStats,
            'period' => $days . ' derniers jours'
        ];
    }
    
    /**
     * STATISTIQUES GLOBALES
     */
    public function getGlobalStats() {
        $stats = [];
        
        // Total posts
        $sql = "SELECT COUNT(*) as total FROM posts";
        $stmt = $this->db->query($sql);
        $stats['total_posts'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total commentaires
        $sql = "SELECT COUNT(*) as total FROM comments";
        $stmt = $this->db->query($sql);
        $stats['total_comments'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Posts cette semaine
        $sql = "SELECT COUNT(*) as total FROM posts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $this->db->query($sql);
        $stats['posts_this_week'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Moyenne commentaires par post
        $sql = "SELECT AVG(comment_count) as avg FROM 
                (SELECT COUNT(c.id) as comment_count FROM posts p 
                LEFT JOIN comments c ON p.id = c.post_id GROUP BY p.id) as counts";
        $stmt = $this->db->query($sql);
        $stats['avg_comments_per_post'] = round($stmt->fetch(PDO::FETCH_ASSOC)['avg'], 2);
        
        return $stats;
    }
}