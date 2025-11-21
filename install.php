<?php
// install.php - Script d'installation de la base de données événements

// Configuration de la base de données
$host = 'localhost';
$dbname = 'projet_groupe3';
$username = 'root'; // À modifier selon votre configuration
$password = ''; // À modifier selon votre configuration

try {
    // Connexion à MySQL sans sélection de base de données
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à MySQL réussie<br>";
    
    // Création de la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    
    echo "✅ Base de données '$dbname' prête<br>";
    
    // 1. Table categories
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nom VARCHAR(255) NOT NULL UNIQUE,
            description TEXT,
            couleur VARCHAR(7) DEFAULT '#007bff',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Table 'categories' créée<br>";
    
    // 2. Table evenement
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS evenement (
            id INT PRIMARY KEY AUTO_INCREMENT,
            titre VARCHAR(255) NOT NULL,
            description TEXT,
            date_event DATE NOT NULL,
            lieu VARCHAR(255),
            prix DECIMAL(10,2) DEFAULT 0,
            image VARCHAR(500),
            categorie_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
        )
    ");
    echo "✅ Table 'evenement' créée<br>";
    
    // 3. Table participants
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS participants (
            id INT PRIMARY KEY AUTO_INCREMENT,
            evenement_id INT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            telephone VARCHAR(20),
            nombre_places INT DEFAULT 1,
            statut ENUM('confirmé', 'en_attente', 'annulé') DEFAULT 'confirmé',
            date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (evenement_id) REFERENCES evenement(id) ON DELETE CASCADE,
            UNIQUE KEY unique_participation (evenement_id, email)
        )
    ");
    echo "✅ Table 'participants' créée<br>";
    
    // 4. Table utilisateurs (pour l'administration)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS utilisateurs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(255) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role ENUM('admin', 'organisateur') DEFAULT 'organisateur',
            nom_complet VARCHAR(255),
            active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Table 'utilisateurs' créée<br>";
    
    // Insertion des catégories par défaut
    $categories = [
        ['nom' => 'Conférence', 'description' => 'Conférences et séminaires'],
        ['nom' => 'Atelier', 'description' => 'Ateliers pratiques et formations'],
        ['nom' => 'Concert', 'description' => 'Concerts et spectacles musicaux'],
        ['nom' => 'Sport', 'description' => 'Événements sportifs et compétitions'],
        ['nom' => 'Culture', 'description' => 'Événements culturels et artistiques'],
        ['nom' => 'Business', 'description' => 'Événements professionnels et réseautage']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (nom, description) VALUES (?, ?)");
    foreach ($categories as $categorie) {
        $stmt->execute([$categorie['nom'], $categorie['description']]);
    }
    echo "✅ Catégories par défaut insérées<br>";
    
    // Insertion d'un utilisateur admin par défaut (mot de passe: admin123)
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO utilisateurs (username, email, password_hash, role, nom_complet) 
        VALUES (?, ?, ?, 'admin', ?)
    ");
    $stmt->execute(['admin', 'admin@evenements.com', $password_hash, 'Administrateur']);
    echo "✅ Utilisateur admin créé (username: admin, password: admin123)<br>";
    
    // Insertion d'événements d'exemple
    $evenements = [
        [
            'titre' => 'Conférence sur l\'IA',
            'description' => 'Une conférence sur les dernières avancées en intelligence artificielle',
            'date_event' => '2024-12-15',
            'lieu' => 'Salle Principale',
            'prix' => 25.00,
            'categorie_id' => 1
        ],
        [
            'titre' => 'Atelier de Photographie',
            'description' => 'Apprenez les bases de la photographie avec des professionnels',
            'date_event' => '2024-12-20',
            'lieu' => 'Studio Photo',
            'prix' => 75.00,
            'categorie_id' => 2
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO evenement (titre, description, date_event, lieu, prix, categorie_id) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($evenements as $event) {
        $stmt->execute([
            $event['titre'],
            $event['description'],
            $event['date_event'],
            $event['lieu'],
            $event['prix'],
            $event['categorie_id']
        ]);
    }
    echo "✅ Événements d'exemple créés<br>";
    
    echo "<br>🎉 <strong>Installation terminée avec succès !</strong><br>";
    echo "📝 <strong>Identifiants administrateur :</strong><br>";
    echo "👤 Username: <strong>admin</strong><br>";
    echo "🔑 Password: <strong>admin123</strong><br><br>";
    echo "⚠️ <strong>Important :</strong> Supprimez ce fichier install.php après l'installation pour des raisons de sécurité.";
    
} catch (PDOException $e) {
    echo "❌ Erreur lors de l'installation : " . $e->getMessage();
    echo "<br>Vérifiez vos paramètres de connexion à la base de données.";
}
?>