<?php
// ==========================================
// SPARKMIND - INSTALL.PHP
// Script d'installation de la base de données
// ==========================================

// Inclure le fichier de configuration
require_once 'config/config.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .install-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #1f8c87;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            text-align: center;
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            color: #2e7d32;
        }
        
        .error {
            background: #ffebee;
            border-left: 4px solid #f44336;
            color: #c62828;
        }
        
        .info {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            color: #1565c0;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #1f8c87, #7d5aa6);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            margin-top: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .credentials {
            background: #fff3e0;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .credentials h3 {
            color: #e65100;
            margin-bottom: 10px;
        }
        
        .credentials p {
            margin: 5px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="logo">🕊️</div>
        <h1>Installation de SparkMind</h1>
        
        <?php
        if(isset($_POST['install'])) {
            // Lancer l'installation
            echo '<div class="message info">⏳ Installation en cours...</div>';
            
            try {
                // Connexion sans spécifier la base de données
                $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Créer la base de données
                $pdo->exec("CREATE DATABASE IF NOT EXISTS sparkmind_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo '<div class="message success">✅ Base de données créée avec succès</div>';
                
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
                echo '<div class="message success">✅ Table "demandes" créée avec succès</div>';
                
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
                echo '<div class="message success">✅ Table "reponses" créée avec succès</div>';
                
                // Créer la table utilisateurs
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
                echo '<div class="message success">✅ Table "utilisateurs" créée avec succès</div>';
                
                // Créer un utilisateur admin
                $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
                $sql_insert_admin = "INSERT IGNORE INTO utilisateurs (username, password, email, role) 
                                    VALUES ('admin', :password, 'admin@sparkmind.tn', 'admin')";
                $stmt = $pdo->prepare($sql_insert_admin);
                $stmt->execute(['password' => $hashedPassword]);
                echo '<div class="message success">✅ Utilisateur admin créé</div>';
                
                // Insérer des données de test
                $demandes_test = [
                    [
                        'nom' => 'Mohamed Ben Ali',
                        'age' => 42,
                        'gouvernorat' => 'Ariana',
                        'ville' => 'La Gazelle',
                        'situation' => 'famille',
                        'categories_aide' => json_encode(['alimentaire']),
                        'urgence' => 'tres-urgent',
                        'description_situation' => 'Je suis père de 3 enfants et je traverse actuellement une période difficile.',
                        'demande_exacte' => 'Aide alimentaire pour 2 semaines.',
                        'telephone' => '+216 98 765 432',
                        'email' => 'mohamed@email.com',
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
                        'description_situation' => 'Mes enfants ont besoin de fournitures scolaires.',
                        'demande_exacte' => '2 cartables et fournitures.',
                        'telephone' => '+216 22 123 456',
                        'email' => 'fatma@email.com',
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
                        'description_situation' => 'Besoin urgent de consultation médicale.',
                        'demande_exacte' => 'Consultation et médicaments.',
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
                        // Ignorer si existe déjà
                    }
                }
                
                echo '<div class="message success">✅ Données de test insérées</div>';
                
                echo '<div class="message success" style="margin-top: 30px;">
                        <h2 style="margin-bottom: 15px;">🎉 Installation terminée avec succès !</h2>
                      </div>';
                
                echo '<div class="credentials">
                        <h3>🔐 Identifiants de connexion</h3>
                        <p><strong>Username:</strong> admin</p>
                        <p><strong>Password:</strong> admin123</p>
                        <p style="margin-top: 15px; color: #e65100;"><strong>⚠️ Changez ce mot de passe immédiatement !</strong></p>
                      </div>';
                
                echo '<div style="text-align: center;">
                        <a href="views/backoffice/back.html" class="btn">Accéder au Back Office</a>
                        <a href="views/frontoffice/formulaire.html" class="btn">Accéder au Formulaire</a>
                      </div>';
                
            } catch(PDOException $e) {
                echo '<div class="message error">❌ Erreur: ' . $e->getMessage() . '</div>';
            }
            
        } else {
            // Afficher le formulaire d'installation
            ?>
            <div class="message info">
                <p><strong>Bienvenue dans l'installation de SparkMind !</strong></p>
                <p style="margin-top: 10px;">Ce script va créer la base de données et les tables nécessaires.</p>
                <p style="margin-top: 10px;"><strong>Configuration requise:</strong></p>
                <ul style="margin-left: 20px; margin-top: 5px;">
                    <li>PHP 7.4 ou supérieur</li>
                    <li>MySQL 5.7 ou supérieur</li>
                    <li>Extension PDO activée</li>
                </ul>
            </div>
            
            <form method="POST" style="text-align: center;">
                <button type="submit" name="install" class="btn">🚀 Lancer l'installation</button>
            </form>
            <?php
        }
        ?>
    </div>
</body>
</html>