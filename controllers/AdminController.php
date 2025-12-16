<?php

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/DonationType.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/HelpRequest.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../services/MailService.php';

class AdminController {
    
    /**
     * Vérifier que l'utilisateur est admin
     */
    private function ensureAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId   = $_SESSION['user_id']  ?? null;
        $userRole = $_SESSION['user_role'] ?? null;

        if (empty($userId) || $userRole !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }
    }

    /**
     * Page d'accueil admin
     */
    public function home(): void
    {
        $this->ensureAdmin();
        include __DIR__ . '/../views/admin/home.php';
    }

    /**
     * Dashboard principal avec statistiques
     */
    public function dashboard() {
        $this->ensureAdmin();
        
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
     * Dashboard IA avec statistiques avancées
     */
    public function aiDashboard() {
        $this->ensureAdmin();
        
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
    
    /**
     * Liste des posts
     */
    public function listPosts() {
        $this->ensureAdmin();
        
        $postModel = new Post();
        $posts = $postModel->getAll();
        include __DIR__ . '/../views/admin/post_list.php';
    }
    
    /**
     * Supprimer un post
     */
    public function deletePost() {
        $this->ensureAdmin();
        
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
    
    /**
     * Liste des commentaires
     */
    public function listComments() {
        $this->ensureAdmin();
        
        $commentModel = new Comment();
        $comments = $commentModel->getAll();
        include __DIR__ . '/../views/admin/comments_list.php';
    }
    
    /**
     * Supprimer un commentaire
     */
    public function deleteCommentAdmin() {
        $this->ensureAdmin();
        
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
    
    /**
     * Liste des types de dons
     */
    public function listDonationTypes() {
        $this->ensureAdmin();
        
        $donationTypeModel = new DonationType();
        $types = $donationTypeModel->getAll();
        include __DIR__ . '/../views/admin/types_list.php';
    }

    /**
     * Liste des utilisateurs avec filtrage
     */
    public function users(): void
    {
        $this->ensureAdmin();

        // Ajout d'utilisateur (formulaire)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create_user') {
                $nom      = isset($_POST['nom'])     ? trim($_POST['nom'])     : '';
                $prenom   = isset($_POST['prenom'])  ? trim($_POST['prenom'])  : '';
                $email    = isset($_POST['email'])   ? trim($_POST['email'])   : '';
                $password = $_POST['password']       ?? '';
                $roleRaw  = $_POST['role']           ?? 'user';
                $siteRoleRawForm = $_POST['site_role'] ?? 'seeker';

                $allowedRolesTech = ['user', 'admin'];
                $role = in_array($roleRaw, $allowedRolesTech, true) ? $roleRaw : 'user';

                $allowedSiteRolesInsert = ['seeker', 'helper', 'both', 'speaker'];
                $siteRoleInsert = in_array($siteRoleRawForm, $allowedSiteRolesInsert, true)
                    ? $siteRoleRawForm
                    : 'seeker';

                User::createFromAdmin([
                    'nom'       => $nom,
                    'prenom'    => $prenom,
                    'email'     => $email,
                    'password'  => $password,
                    'role'      => $role,
                    'site_role' => $siteRoleInsert,
                ]);

                header("Location: index.php?page=admin_users");
                exit;
            }
        }

        $userModel = new User();

        // Filtre rôle SPARKMIND
        $siteRoleRaw = $_GET['site_role'] ?? 'all';
        $siteRole    = is_string($siteRoleRaw) ? trim($siteRoleRaw) : 'all';

        $allowedRoles = ['all', 'seeker', 'helper', 'both', 'speaker'];
        if (!in_array($siteRole, $allowedRoles, true)) {
            $siteRole = 'all';
        }

        // Filtre date
        $dateFilterRaw = $_GET['date'] ?? 'all';
        $dateFilter    = is_string($dateFilterRaw) ? trim($dateFilterRaw) : 'all';

        $allowedDateFilters = ['all', 'today', 'yesterday', 'week', 'last_month', 'last_year'];
        if (!in_array($dateFilter, $allowedDateFilters, true)) {
            $dateFilter = 'all';
        }

        // Récupération filtrée
        $users = $userModel->findAllBySiteRoleAndDate($siteRole, $dateFilter);

        $currentSiteRole   = $siteRole;
        $currentDateFilter = $dateFilter;

        include __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Profil d'un utilisateur
     */
    public function userProfile(): void
    {
        $this->ensureAdmin();

        $idRaw = $_GET['id'] ?? null;
        $id    = is_numeric($idRaw) ? (int)$idRaw : 0;

        if ($id <= 0) {
            header("Location: index.php?page=admin_users");
            exit;
        }

        $userModel = new User();
        $user      = $userModel->findById($id);

        if (!$user) {
            header("Location: index.php?page=admin_users");
            exit;
        }

        include __DIR__ . '/../views/admin/user_profile.php';
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin_users");
            exit;
        }

        $idRaw = $_POST['user_id'] ?? null;
        $id    = is_numeric($idRaw) ? (int)$idRaw : 0;

        if ($id > 0) {
            $userModel = new User();
            $userModel->deleteById($id);
        }

        header("Location: index.php?page=admin_users");
        exit;
    }

    /**
     * Bloquer un utilisateur
     */
    public function blockUser(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin_users");
            exit;
        }

        $idRaw = $_POST['user_id'] ?? null;
        $id    = is_numeric($idRaw) ? (int)$idRaw : 0;

        if ($id > 0) {
            $userModel = new User();
            $user      = $userModel->findById($id);

            if ($user) {
                $userModel->updateStatus($id, 'blocked');

                $fullName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
                MailService::sendAccountBlocked($user['email'], $fullName);
            }
        }

        header("Location: index.php?page=admin_users");
        exit;
    }

    /**
     * Débloquer un utilisateur
     */
    public function unblockUser(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin_users");
            exit;
        }

        $idRaw = $_POST['user_id'] ?? null;
        $id    = is_numeric($idRaw) ? (int)$idRaw : 0;

        if ($id > 0) {
            $userModel = new User();
            $user      = $userModel->findById($id);

            if ($user) {
                $userModel->updateStatus($id, 'active');

                $fullName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
                MailService::sendAccountUnblocked($user['email'], $fullName);
            }
        }

        header("Location: index.php?page=admin_users");
        exit;
    }

    /**
     * Notifications
     */
    public function notifications(): void
    {
        $this->ensureAdmin();

        $notifModel    = new Notification();
        $notifications = $notifModel->getAllWithStats();

        include __DIR__ . '/../views/admin/notifications.php';
    }

    /**
     * Demandes d'aide
     */
    public function helpRequests(): void
    {
        $this->ensureAdmin();

        $helpModel = new HelpRequest();

        $statutRaw = $_GET['statut'] ?? 'all';
        $statut    = is_string($statutRaw) ? trim($statutRaw) : 'all';

        $allowedStatus = ['all', 'pending', 'accepted', 'rejected'];
        if (!in_array($statut, $allowedStatus, true)) {
            $statut = 'all';
        }

        $requests = $helpModel->getAll($statut);

        include __DIR__ . '/../views/admin/help_requests.php';
    }

    /**
     * Action sur une demande d'aide
     */
    public function helpRequestAction(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin_help_requests");
            exit;
        }

        $idRaw     = $_POST['id']     ?? null;
        $actionRaw = $_POST['action'] ?? '';

        $id     = is_numeric($idRaw) ? (int)$idRaw : 0;
        $action = is_string($actionRaw) ? trim($actionRaw) : '';

        $allowedActions = ['accept', 'reject'];

        if ($id > 0 && in_array($action, $allowedActions, true)) {
            $helpModel = new HelpRequest();
            $statut    = ($action === 'accept') ? 'accepted' : 'rejected';
            $helpModel->updateStatus($id, $statut);
        }

        header("Location: index.php?page=admin_help_requests");
        exit;
    }
}