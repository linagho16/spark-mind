<?php
class Event {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function all(): array {
        try {
            $stmt = $this->pdo->query("
                SELECT e.*, c.nom as categorie_nom, c.couleur as categorie_couleur 
                FROM evenement e 
                LEFT JOIN categories c ON e.categorie_id = c.id 
                ORDER BY e.date_event ASC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            $stmt = $this->pdo->query("SELECT * FROM evenement ORDER BY date_event ASC");
            return $stmt->fetchAll();
        }
    }
    
    public function find(int $id): ?array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT e.*, c.nom as categorie_nom, c.couleur as categorie_couleur 
                FROM evenement e 
                LEFT JOIN categories c ON e.categorie_id = c.id 
                WHERE e.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch() ?: null;
        } catch (Exception $e) {
            $stmt = $this->pdo->prepare("SELECT * FROM evenement WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch() ?: null;
        }
    }
    
    public function getCategories(): array {
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY nom");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function createWithCategory(array $data): int {
        $stmt = $this->pdo->prepare("INSERT INTO evenement (titre, description, date_event, lieu, prix, categorie_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['titre'], 
            $data['description'], 
            $data['date_event'], 
            $data['lieu'], 
            $data['prix'], 
            $data['categorie_id'] ?? null,
            $data['image'] ?? null
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    
    public function updateWithCategory(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("UPDATE evenement SET titre = ?, description = ?, date_event = ?, lieu = ?, prix = ?, categorie_id = ?, image = ? WHERE id = ?");
        return $stmt->execute([
            $data['titre'], 
            $data['description'], 
            $data['date_event'], 
            $data['lieu'], 
            $data['prix'], 
            $data['categorie_id'] ?? null,
            $data['image'] ?? null,
            $id
        ]);
    }
    
    public function create(array $data): int {
        return $this->createWithCategory($data);
    }
    
    public function update(int $id, array $data): bool {
        return $this->updateWithCategory($id, $data);
    }
    
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM evenement WHERE id = ?");
        return $stmt->execute([$id]);
    }
}