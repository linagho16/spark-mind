<?php
class HelpRequest
{
    private $pdo;

    public function __construct()
    {
        require __DIR__ . '/../config/config.php';
        $this->pdo = $pdo;
    }

    // Pour l'ADMIN : toutes les demandes, tri + filtre simple
    public function getAll($statut = 'all')
    {
        $where = '';
        $params = [];

        if ($statut !== 'all') {
            $where = 'WHERE statut = :statut';
            $params[':statut'] = $statut;
        }

        $sql = "SELECT hr.*, u.nom, u.prenom, u.email
                FROM help_requests hr
                JOIN users u ON u.id = hr.user_id
                $where
                ORDER BY hr.date_creation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $statut): bool
    {
        $sql = "UPDATE help_requests 
                SET statut = :statut 
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':statut' => $statut,
            ':id'     => $id,
        ]);
    }
}
