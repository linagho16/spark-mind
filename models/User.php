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
            (nom, prenom, naissance, tel, adresse, ville, profession, email, password_hash, role, created_at)
            VALUES
            (:nom, :prenom, :naissance, :tel, :adresse, :ville, :profession, :email, :password_hash, :role, NOW())";

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
        ]);
    }

    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findAll()
    {
        $sql = "SELECT id, nom, prenom, email, role, created_at 
                FROM users
                ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
