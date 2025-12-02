<?php
// Contrôleur pour les notifications côté FRONT (utilisateur)
require_once __DIR__ . '/../models/Notification.php';

class NotificationController
{
    /**
     * Liste des notifications pour l'utilisateur connecté
     * Vue : views/notifications/front.php
     */
    public function front(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // L'utilisateur doit être connecté
        $userId = $_SESSION['user_id'] ?? null;
        if (empty($userId) || !is_numeric($userId)) {
            session_unset();
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }

        $userId = (int)$userId;

        $notifModel    = new Notification();
        $notifications = [];

        // On anticipe proprement la suite sans casser le projet :
        // si le modèle a une méthode getForUser(), on l'utilise, sinon on laisse un tableau vide.
        if (method_exists($notifModel, 'getForUser')) {
            $notifications = $notifModel->getForUser($userId);
        }

        // On affiche une vue (et plus des echo dans le contrôleur → MVC propre)
        include __DIR__ . '/../views/notifications/front.php';
    }

    /**
     * Action d'un utilisateur sur une notification : accepter / refuser / marquer lu...
     * Route : index.php?page=notif_action
     */
    public function action(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // L'utilisateur doit être connecté
        $userId = $_SESSION['user_id'] ?? null;
        if (empty($userId) || !is_numeric($userId)) {
            session_unset();
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }
        $userId = (int)$userId;

        // On n'accepte que la méthode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=notifications");
            exit;
        }

        // Récupération sécurisée des données
        $notifIdRaw = $_POST['notification_id'] ?? null;
        $actionRaw  = $_POST['action']          ?? '';

        $notifId = is_numeric($notifIdRaw) ? (int)$notifIdRaw : 0;
        $action  = is_string($actionRaw)   ? trim($actionRaw) : '';

        // Liste des actions permises (à adapter selon ton métier)
        $allowedActions = ['accept', 'reject', 'seen'];

        if ($notifId > 0 && in_array($action, $allowedActions, true)) {
            $notifModel = new Notification();

            // Comme pour front(), on n'appelle une méthode du modèle que si elle existe,
            // pour ne pas casser le code tant que tu ne l'as pas encore codée.
            if (method_exists($notifModel, 'updateStatusForUser')) {
                // Exemple : updateStatusForUser(user_id, notification_id, action)
                $notifModel->updateStatusForUser($userId, $notifId, $action);
            }
        }

        // Retour à la liste des notifications, sans HTML5, uniquement redirection serveur
        header("Location: index.php?page=notifications");
        exit;
    }
}
