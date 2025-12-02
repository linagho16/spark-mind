<?php
class Notification
{
    private $pdo;

    public function __construct()
    {
        require __DIR__ . '/../config/config.php';
        $this->pdo = $pdo;
    }

    public function getAllWithStats()
    {
        $sql = "SELECT n.*,
                       SUM(CASE WHEN un.statut = 'accepted' THEN 1 ELSE 0 END) AS nb_accepted,
                       SUM(CASE WHEN un.statut = 'rejected' THEN 1 ELSE 0 END) AS nb_rejected,
                       SUM(CASE WHEN un.statut = 'pending'  THEN 1 ELSE 0 END) AS nb_pending
                FROM notifications n
                LEFT JOIN user_notifications un ON un.notification_id = n.id
                GROUP BY n.id
                ORDER BY n.date_publication DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
