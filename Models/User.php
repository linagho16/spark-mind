<?php
class User
{
    private $pdo;

    public function __construct()
    {
        require __DIR__ . '/../config/config.php';
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO users 
            (nom, prenom, naissance, tel, adresse, ville, profession, email, password_hash, role, photo, site_role, created_at)
            VALUES
            (:nom, :prenom, :naissance, :tel, :adresse, :ville, :profession, :email, :password_hash, :role, :photo, :site_role, NOW())";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom'           => $data['nom'],
            ':prenom'        => $data['prenom'],
            ':naissance'     => $data['naissance'],
            ':tel'           => $data['tel'],
            ':adresse'       => $data['adresse'],
            ':ville'         => $data['ville'],
            ':profession'    => $data['profession'],
            ':email'         => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role'          => $data['role'] ?? 'user',
            ':photo'         => $data['photo'] ?? null,
            ':site_role'     => $data['site_role'] ?? 'seeker',
        ]);
    }

    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE users 
                SET nom = :nom,
                    prenom = :prenom,
                    naissance = :naissance,
                    tel = :tel,
                    adresse = :adresse,
                    ville = :ville,
                    profession = :profession,
                    email = :email
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom'        => $data['nom'],
            ':prenom'     => $data['prenom'],
            ':naissance'  => $data['naissance'],
            ':tel'        => $data['tel'],
            ':adresse'    => $data['adresse'],
            ':ville'      => $data['ville'],
            ':profession' => $data['profession'],
            ':email'      => $data['email'],
            ':id'         => $id,
        ]);
    }

    public function updatePhoto(int $id, string $path): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET photo = :photo WHERE id = :id");
        return $stmt->execute([
            ':photo' => $path,
            ':id'    => $id
        ]);
    }

    public function findAll()
    {
        $sql = "SELECT id, nom, prenom, email, role, site_role, created_at 
                FROM users
                ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Utilisé par AdminController pour filtrer par rôle SPARKMIND seul
    public function findAllBySiteRole(string $siteRole = 'all'): array
    {
        if ($siteRole === 'all') {
            $sql = "SELECT id, nom, prenom, email, role, site_role, created_at
                    FROM users
                    ORDER BY created_at DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        }

        $sql = "SELECT id, nom, prenom, email, role, site_role, created_at
                FROM users
                WHERE site_role = :site_role
                ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':site_role' => $siteRole]);

        return $stmt->fetchAll();
    }

    // 🔹 Filtre combiné rôle SPARKMIND + période d'inscription
    public function findAllBySiteRoleAndDate(string $siteRole = 'all', string $dateFilter = 'all'): array
    {
        $sql = "SELECT id, nom, prenom, email, role, site_role,status, created_at
                FROM users
                WHERE 1=1";
        $params = [];

        // Filtre rôle SPARKMIND
        if ($siteRole !== 'all') {
            $sql .= " AND site_role = :site_role";
            $params[':site_role'] = $siteRole;
        }

        // Filtre date
        switch ($dateFilter) {
            case 'today':
                $sql .= " AND DATE(created_at) = CURDATE()";
                break;
            case 'yesterday':
                $sql .= " AND DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                break;
            case 'week':
                $sql .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                break;
            case 'last_month':
                // mois dernier
                $sql .= " AND YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                          AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
                break;
            case 'last_year':
                // année dernière
                $sql .= " AND YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)";
                break;
            case 'all':
            default:
                // pas de filtre supplémentaire
                break;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function findByEmailOrTel(string $identifier)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :id OR tel = :id LIMIT 1");
        $stmt->execute([':id' => $identifier]);
        return $stmt->fetch();
    }

    public function updatePassword(int $id, string $plainPassword): bool
    {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        return $stmt->execute([
            ':hash' => $hash,
            ':id'   => $id,
        ]);
    }




    /* =======================
       🔹 NOUVELLES MÉTHODES
       ======================= */

    // Retourne l'admin (on suppose qu'il n'y en a qu'un)
    public function findAdmin(): ?array
    {
        $sql = "SELECT id, nom, prenom, email, ville, profession, photo, role, site_role
                FROM users
                WHERE role = 'admin'
                LIMIT 1";

        $stmt = $this->pdo->query($sql);
        $admin = $stmt->fetch();

        return $admin ?: null;
    }

    // Liste des utilisateurs "user" pour le front, filtrés par site_role
    public function findFrontUsersBySiteRole(string $siteRole = 'all'): array
    {
        $params = [];

        $sql = "SELECT id, nom, prenom, email, ville, profession, photo, role, site_role
                FROM users
                WHERE role = 'user'";

        if ($siteRole !== 'all') {
            $sql .= " AND site_role = :site_role";
            $params[':site_role'] = $siteRole;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        // On sécurise le statut côté PHP
        $allowed = ['active', 'blocked'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);
    }

    // 🔹 Création d’un utilisateur depuis le backoffice admin
    public static function createFromAdmin(array $data)
    {
        // On récupère la connexion PDO comme dans le constructeur
        require __DIR__ . '/../config/config.php'; // fournit $pdo

        $sql = "INSERT INTO users (nom, prenom, email, password_hash, role, site_role, created_at)
                VALUES (:nom, :prenom, :email, :password_hash, :role, :site_role, NOW())";

        $stmt = $pdo->prepare($sql);

        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            ':nom'           => $data['nom'],
            ':prenom'        => $data['prenom'],
            ':email'         => $data['email'],
            ':password_hash' => $passwordHash,
            ':role'          => $data['role'],
            ':site_role'     => $data['site_role'],
        ]);
    }
}
