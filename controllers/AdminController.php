<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/HelpRequest.php';

class AdminController
{
    /**
     * Vérifie que la personne est connectée en tant qu'ADMIN.
     * Sinon redirection vers la page de login.
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
     * Page d'accueil du backoffice (tableau de bord).
     * Vue : views/admin/home.php
     */
    public function home(): void
    {
        $this->ensureAdmin();

        include __DIR__ . '/../views/admin/home.php';
    }

    /**
     * Liste des utilisateurs.
     * Vue : views/admin/users.php
     */
    public function users(): void
    {
        $this->ensureAdmin();

        $userModel = new User();

        // Ici pas de HTML5 : on ne lit rien venant du formulaire.
        // Si plus tard tu ajoutes des filtres (GET), tu les valideras ici.
        $users = $userModel->findAll();

        include __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Page admin des notifications (stats acceptées/refusées/en attente).
     * Vue : views/admin/notifications.php
     */
    public function notifications(): void
    {
        $this->ensureAdmin();

        $notifModel    = new Notification();
        $notifications = $notifModel->getAllWithStats();

        include __DIR__ . '/../views/admin/notifications.php';
    }

    /**
     * Liste des demandes d'aide (gestion par l'admin).
     * Vue : views/admin/help_requests.php
     */
    public function helpRequests(): void
    {
        $this->ensureAdmin();

        $helpModel = new HelpRequest();

        // 🔎 Validation côté serveur du filtre "statut"
        $statut = $_GET['statut'] ?? 'all';
        $statut = is_string($statut) ? trim($statut) : 'all';

        $allowedStatus = ['all', 'pending', 'accepted', 'rejected'];
        if (!in_array($statut, $allowedStatus, true)) {
            $statut = 'all';
        }

        $requests = $helpModel->getAll($statut);

        include __DIR__ . '/../views/admin/help_requests.php';
    }

    /**
     * Action sur une demande d'aide : accepter ou refuser.
     * Route : index.php?page=admin_help_request_action
     */
    public function helpRequestAction(): void
    {
        $this->ensureAdmin();

        // On n'accepte que le POST : contrôle côté serveur
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

            // On pourrait aussi vérifier que la demande existe avant de la mettre à jour
            $helpModel->updateStatus($id, $statut);
        }

        // Dans tous les cas on retourne à la liste (pas de HTML5 ici)
        header("Location: index.php?page=admin_help_requests");
        exit;
    }
}
