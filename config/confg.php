<?php
// ==========================================
// SPARKMIND - CONFIG.PHP
// Configuration de la base de données
// ==========================================

class Database {
    
    // Paramètres de connexion
    private $host = "localhost";
    private $db_name = "sparkmind_db";
    private $username = "root";  // Changez selon votre configuration
    private $password = "";      // Changez selon votre configuration
    private $charset = "utf8mb4";
    
    public $conn;
    
    /**
     * Établir la connexion à la base de données
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Construction du DSN (Data Source Name)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            
            // Options PDO pour améliorer la sécurité et les performances
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            // Création de la connexion PDO
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
            error_log("Erreur DB: " . $exception->getMessage());
        }
        
        return $this->conn;
    }
    
    /**
     * Fermer la connexion
     */
    public function closeConnection() {
        $this->conn = null;
    }
}

// ==========================================
// SCRIPT DE CRÉATION DE LA BASE DE DONNÉES
// ==========================================

/**
 * Fonction pour créer la base de données et les tables
 * À exécuter une seule fois lors de l'installation
 */
function createDatabase() {
    try {
        // Connexion sans spécifier la base de données
        $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Créer la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS sparkmind_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        echo "✅ Base de données créée avec succès<br>";
        
        // Sélectionner la base de données
        $pdo->exec("USE sparkmind_db");
        
        // Créer la table demandes
        $sql_demandes = "CREATE TABLE IF NOT EXISTS demandes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            age INT NOT NULL,
            gouvernorat VARCHAR(100) NOT NULL,
            ville VARCHAR(100) NOT NULL,
            situation VARCHAR(100),
            categories_aide TEXT NOT NULL,
            urgence ENUM('tres-urgent', 'urgent', 'important', 'peut-attendre') NOT NULL,
            description_situation TEXT NOT NULL,
            demande_exacte TEXT NOT NULL,
            telephone VARCHAR(20) NOT NULL,
            email VARCHAR(255),
            preference_contact VARCHAR(50) NOT NULL,
            horaires_disponibles TEXT,
            visibilite ENUM('publique', 'semi-privee', 'privee') NOT NULL,
            anonyme BOOLEAN DEFAULT FALSE,
            statut ENUM('nouveau', 'en-cours', 'traite', 'refuse') DEFAULT 'nouveau',
            date_soumission TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_statut (statut),
            INDEX idx_urgence (urgence),
            INDEX idx_gouvernorat (gouvernorat),
            INDEX idx_date (date_soumission)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql_demandes);
        echo "✅ Table 'demandes' créée avec succès<br>";
        
        // Créer la table reponses
        $sql_reponses = "CREATE TABLE IF NOT EXISTS reponses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            demande_id INT NOT NULL,
            administrateur VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            date_reponse TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (demande_id) REFERENCES demandes(id) ON DELETE CASCADE,
            INDEX idx_demande (demande_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql_reponses);
        echo "✅ Table 'reponses' créée avec succès<br>";
        
        // Créer la table utilisateurs (pour le back office)
        $sql_users = "CREATE TABLE IF NOT EXISTS utilisateurs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            role ENUM('admin', 'moderateur') DEFAULT 'moderateur',
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            derniere_connexion TIMESTAMP NULL,
            INDEX idx_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql_users);
        echo "✅ Table 'utilisateurs' créée avec succès<br>";
        
        // Créer un utilisateur admin par défaut
        $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
        $sql_insert_admin = "INSERT IGNORE INTO utilisateurs (username, password, email, role) 
                            VALUES ('admin', :password, 'admin@sparkmind.tn', 'admin')";
        $stmt = $pdo->prepare($sql_insert_admin);
        $stmt->execute(['password' => $hashedPassword]);
        echo "✅ Utilisateur admin créé (username: admin, password: admin123)<br>";
        
        // Insérer quelques données de test
        insertTestData($pdo);
        
        echo "<br>🎉 Installation terminée avec succès !<br>";
        echo "📝 Vous pouvez maintenant utiliser l'application.<br>";
        
    } catch(PDOException $e) {
        echo "❌ Erreur : " . $e->getMessage();
        error_log("Erreur création DB: " . $e->getMessage());
    }
}

/**
 * Insérer des données de test
 */
function insertTestData($pdo) {
    $demandes_test = [
        [
            'nom' => 'Mohamed Ben Ali',
            'age' => 42,
            'gouvernorat' => 'Ariana',
            'ville' => 'La Gazelle',
            'situation' => 'famille',
            'categories_aide' => json_encode(['alimentaire']),
            'urgence' => 'tres-urgent',
            'description_situation' => 'Je suis père de 3 enfants et je traverse actuellement une période difficile après avoir perdu mon emploi il y a 2 mois.',
            'demande_exacte' => 'Aide alimentaire pour ma famille (riz, pâtes, huile, lait) pour 2 semaines.',
            'telephone' => '+216 98 765 432',
            'email' => 'mohamed.benali@email.com',
            'preference_contact' => 'appel',
            'horaires_disponibles' => json_encode(['matin', 'apres-midi']),
            'visibilite' => 'publique',
            'anonyme' => 0,
            'statut' => 'nouveau'
        ],
        [
            'nom' => 'Fatma Trabelsi',
            'age' => 35,
            'gouvernorat' => 'Tunis',
            'ville' => 'Bab El Khadra',
            'situation' => 'enfants',
            'categories_aide' => json_encode(['scolaire']),
            'urgence' => 'urgent',
            'description_situation' => 'Mes deux enfants ont besoin de fournitures scolaires pour la rentrée.',
            'demande_exacte' => '2 cartables, cahiers, stylos et livres scolaires.',
            'telephone' => '+216 22 123 456',
            'email' => 'fatma.t@email.com',
            'preference_contact' => 'sms',
            'horaires_disponibles' => json_encode(['soir']),
            'visibilite' => 'publique',
            'anonyme' => 0,
            'statut' => 'en-cours'
        ],
        [
            'nom' => 'Ahmed Karoui',
            'age' => 58,
            'gouvernorat' => 'Sfax',
            'ville' => 'Sfax Ville',
            'situation' => 'seul',
            'categories_aide' => json_encode(['medicale']),
            'urgence' => 'tres-urgent',
            'description_situation' => 'J\'ai besoin d\'une consultation médicale urgente mais je n\'ai pas les moyens.',
            'demande_exacte' => 'Consultation médicale et médicaments pour diabète.',
            'telephone' => '+216 50 789 123',
            'email' => '',
            'preference_contact' => 'appel',
            'horaires_disponibles' => json_encode(['matin']),
            'visibilite' => 'semi-privee',
            'anonyme' => 0,
            'statut' => 'nouveau'
        ]
    ];
    
    $sql = "INSERT INTO demandes (nom, age, gouvernorat, ville, situation, categories_aide, urgence, 
            description_situation, demande_exacte, telephone, email, preference_contact, 
            horaires_disponibles, visibilite, anonyme, statut) 
            VALUES (:nom, :age, :gouvernorat, :ville, :situation, :categories_aide, :urgence, 
            :description_situation, :demande_exacte, :telephone, :email, :preference_contact, 
            :horaires_disponibles, :visibilite, :anonyme, :statut)";
    
    $stmt = $pdo->prepare($sql);
    
    foreach ($demandes_test as $demande) {
        try {
            $stmt->execute($demande);
        } catch(PDOException $e) {
            // Ignorer si les données existent déjà
        }
    }
    
    echo "✅ Données de test insérées<br>";
}

// Décommenter la ligne suivante pour créer la base de données
// createDatabase();

?>