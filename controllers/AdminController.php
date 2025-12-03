<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/HelpRequest.php';
require_once __DIR__ . '/../services/MailService.php';

class AdminController
{
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

    public function home(): void
    {
        $this->ensureAdmin();
        include __DIR__ . '/../views/admin/home.php';
    }

    /**
     * Liste des utilisateurs avec filtrage site_role + filtre date.
     */
    public function users(): void
    {
        $this->ensureAdmin();

        // 1) Ajout d'utilisateur (formulaire)
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

        // 2) Filtre rôle SPARKMIND
        $siteRoleRaw = $_GET['site_role'] ?? 'all';
        $siteRole    = is_string($siteRoleRaw) ? trim($siteRoleRaw) : 'all';

        $allowedRoles = ['all', 'seeker', 'helper', 'both', 'speaker'];
        if (!in_array($siteRole, $allowedRoles, true)) {
            $siteRole = 'all';
        }

        // 3) Filtre date (today / yesterday / week / last_month / last_year / all)
        $dateFilterRaw = $_GET['date'] ?? 'all';
        $dateFilter    = is_string($dateFilterRaw) ? trim($dateFilterRaw) : 'all';

        $allowedDateFilters = ['all', 'today', 'yesterday', 'week', 'last_month', 'last_year'];
        if (!in_array($dateFilter, $allowedDateFilters, true)) {
            $dateFilter = 'all';
        }

        // 4) Récupération filtrée
        $users = $userModel->findAllBySiteRoleAndDate($siteRole, $dateFilter);

        $currentSiteRole   = $siteRole;
        $currentDateFilter = $dateFilter;

        include __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Voir le profil complet d'un utilisateur (mode admin).
     * Route : index.php?page=admin_user_profile&id=123
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
     * Supprimer un utilisateur (définitivement).
     * Route : index.php?page=admin_delete_user
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

    public function activateUser(): void
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

    public function notifications(): void
    {
        $this->ensureAdmin();

        $notifModel    = new Notification();
        $notifications = $notifModel->getAllWithStats();

        include __DIR__ . '/../views/admin/notifications.php';
    }

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
