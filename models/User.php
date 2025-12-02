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
            (nom, prenom, naissance, tel, adresse, ville, profession, email, password_hash, role, photo, created_at)
            VALUES
            (:nom, :prenom, :naissance, :tel, :adresse, :ville, :profession, :email, :password_hash, :role, :photo, NOW())";

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
        $sql = "SELECT id, nom, prenom, email, role, created_at 
                FROM users
                ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
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

}
