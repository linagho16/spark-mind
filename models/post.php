<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $db;

    // Active ce flag à true si tu veux afficher les erreurs SQL en var_dump
    private $debugSql = false;

    public function __construct() {
        $this->db = (new Database())->pdo;
    }

    /* =========================
       CREATE
    ========================= */
    public function create($titre, $contenu, $imagePath, $donation_type_id = null) {
        // donation_type_id peut être null si tu veux supprimer le champ
        $donation_type_id = ($donation_type_id !== null && $donation_type_id !== '') ? (int)$donation_type_id : null;

        $sql = "INSERT INTO posts (titre, contenu, image, donation_type_id, user_id)
                VALUES (:titre, :contenu, :image, :donation_type_id, :user_id)";
        $stmt = $this->db->prepare($sql);

        $ok = $stmt->execute([
            ':titre'            => ($titre !== '' ? $titre : null),
            ':contenu'          => $contenu,
            ':image'            => $imagePath,
            ':donation_type_id' => $donation_type_id,
            ':user_id'          => $_SESSION['user_id'] ?? 1
        ]);

        if (!$ok && $this->debugSql) {
            var_dump($stmt->errorInfo());
            exit;
        }

        return $ok;
    }

    /* =========================
       GET ALL (Back/Front)
    ========================= */
    public function getAll($donation_type_id = null) {
        if ($donation_type_id !== null && $donation_type_id !== '') {
            $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color
                    FROM posts p
                    LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
                    WHERE p.donation_type_id = :type_id
                    ORDER BY p.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':type_id' => (int)$donation_type_id]);
        } else {
            $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color
                    FROM posts p
                    LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
                    ORDER BY p.created_at DESC";
            $stmt = $this->db->query($sql);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Alias pour ton index.php (corrige "undefined method Post::getAllFront()")
    public function getAllFront($donation_type_id = null) {
        return $this->getAll($donation_type_id);
    }

    /* =========================
       GET BY ID
    ========================= */
    public function getById($id) {
        $sql = "SELECT p.*, dt.name as type_name, dt.icon, dt.color
                FROM posts p
                LEFT JOIN donation_types dt ON p.donation_type_id = dt.id
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================
       UPDATE
       ✅ donation_type_id devient optionnel
       ✅ si donation_type_id est null => on ne touche pas à cette colonne
    ========================= */
    public function update($id, $titre, $contenu, $imagePath = null, $donation_type_id = null) {
        $id = (int)$id;

        // Si donation_type_id non fourni => on ne modifie pas la colonne
        $hasDonationType = ($donation_type_id !== null && $donation_type_id !== '');
        if ($hasDonationType) {
            $donation_type_id = (int)$donation_type_id;
        }

        $fields = [
            "titre = :titre",
            "contenu = :contenu",
        ];

        $params = [
            ':id'      => $id,
            ':titre'   => ($titre !== '' ? $titre : null),
            ':contenu' => $contenu,
        ];

        // Image : si tu as une nouvelle image => update
        if (!empty($imagePath)) {
            $fields[] = "image = :image";
            $params[':image'] = $imagePath;
        }

        // Donation type : seulement si envoyé
        if ($hasDonationType) {
            $fields[] = "donation_type_id = :donation_type_id";
            $params[':donation_type_id'] = $donation_type_id;
        }

        $sql = "UPDATE posts SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $ok = $stmt->execute($params);

        if (!$ok && $this->debugSql) {
            var_dump($stmt->errorInfo());
            exit;
        }

        return $ok;
    }

    /* =========================
       DELETE
    ========================= */
    public function delete($id) {
        $post = $this->getById($id);

        if ($post && !empty($post['image'])) {
            $imagePath = __DIR__ . '/../public/' . $post['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $ok = $stmt->execute([':id' => (int)$id]);

        if (!$ok && $this->debugSql) {
            var_dump($stmt->errorInfo());
            exit;
        }

        return $ok;
    }

    /* =========================
       COMMENTS COUNT
    ========================= */
    public function getCommentsCount($post_id) {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE post_id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => (int)$post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
}
