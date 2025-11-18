<?php
// ==========================================
// SPARKMIND - DEMANDECONTROLLER.PHP
// Contrôleur pour gérer les demandes d'aide
// ==========================================

// Headers CORS pour permettre les requêtes AJAX
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Demande.php';

// Initialiser la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Vérifier la connexion
if($db === null) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Impossible de se connecter à la base de données."
    ]);
    exit();
}

// Initialiser l'objet Demande
$demande = new Demande($db);

// Récupérer l'action demandée
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Router selon l'action
switch($action) {
    
    // ==========================================
    // CREATE - Créer une nouvelle demande
    // ==========================================
    case 'create':
        // Récupérer les données POST
        $data = json_decode(file_get_contents("php://input"));
        
        // Vérifier que les données sont présentes
        if(!empty($data->nom) && !empty($data->age) && !empty($data->gouvernorat) && 
           !empty($data->ville) && !empty($data->urgence) && !empty($data->telephone)) {
            
            // Assigner les valeurs
            $demande->nom = $data->nom;
            $demande->age = $data->age;
            $demande->gouvernorat = $data->gouvernorat;
            $demande->ville = $data->ville;
            $demande->situation = isset($data->situation) ? $data->situation : '';
            $demande->categories_aide = json_encode($data->categories_aide);
            $demande->urgence = $data->urgence;
            $demande->description_situation = $data->description_situation;
            $demande->demande_exacte = $data->demande_exacte;
            $demande->telephone = $data->telephone;
            $demande->email = isset($data->email) ? $data->email : '';
            $demande->preference_contact = $data->preference_contact;
            $demande->horaires_disponibles = json_encode($data->horaires_disponibles);
            $demande->visibilite = $data->visibilite;
            $demande->anonyme = isset($data->anonyme) && $data->anonyme ? 1 : 0;
            $demande->statut = 'nouveau';
            
            // Créer la demande
            if($demande->create()) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Demande créée avec succès.",
                    "id" => $demande->id
                ]);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de créer la demande."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Données incomplètes. Veuillez remplir tous les champs obligatoires."
            ]);
        }
        break;
    
    // ==========================================
    // READ ALL - Récupérer toutes les demandes
    // ==========================================
    case 'getAll':
        $stmt = $demande->readAll();
        $num = $stmt->rowCount();
        
        if($num > 0) {
            $demandes_arr = [];
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                $demande_item = [
                    "id" => $id,
                    "nom" => $nom,
                    "age" => $age,
                    "gouvernorat" => $gouvernorat,
                    "ville" => $ville,
                    "situation" => $situation,
                    "categories_aide" => json_decode($categories_aide),
                    "urgence" => $urgence,
                    "description_situation" => $description_situation,
                    "demande_exacte" => $demande_exacte,
                    "telephone" => $telephone,
                    "email" => $email,
                    "preference_contact" => $preference_contact,
                    "horaires_disponibles" => json_decode($horaires_disponibles),
                    "visibilite" => $visibilite,
                    "anonyme" => $anonyme == 1,
                    "statut" => $statut,
                    "date_soumission" => $date_soumission,
                    "date_modification" => $date_modification
                ];
                
                array_push($demandes_arr, $demande_item);
            }
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "demandes" => $demandes_arr,
                "count" => $num
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "demandes" => [],
                "count" => 0,
                "message" => "Aucune demande trouvée."
            ]);
        }
        break;
    
    // ==========================================
    // READ ONE - Récupérer une demande spécifique
    // ==========================================
    case 'getOne':
        if(isset($_GET['id'])) {
            $demande->id = $_GET['id'];
            
            if($demande->readOne()) {
                $demande_arr = [
                    "id" => $demande->id,
                    "nom" => $demande->nom,
                    "age" => $demande->age,
                    "gouvernorat" => $demande->gouvernorat,
                    "ville" => $demande->ville,
                    "situation" => $demande->situation,
                    "categories_aide" => json_decode($demande->categories_aide),
                    "urgence" => $demande->urgence,
                    "description_situation" => $demande->description_situation,
                    "demande_exacte" => $demande->demande_exacte,
                    "telephone" => $demande->telephone,
                    "email" => $demande->email,
                    "preference_contact" => $demande->preference_contact,
                    "horaires_disponibles" => json_decode($demande->horaires_disponibles),
                    "visibilite" => $demande->visibilite,
                    "anonyme" => $demande->anonyme == 1,
                    "statut" => $demande->statut,
                    "date_soumission" => $demande->date_soumission,
                    "date_modification" => $demande->date_modification
                ];
                
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "demande" => $demande_arr
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Demande introuvable."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID de demande manquant."
            ]);
        }
        break;
    
    // ==========================================
    // UPDATE - Mettre à jour une demande
    // ==========================================
    case 'update':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $demande->id = $data->id;
            $demande->nom = $data->nom;
            $demande->age = $data->age;
            $demande->gouvernorat = $data->gouvernorat;
            $demande->ville = $data->ville;
            $demande->situation = $data->situation;
            $demande->categories_aide = json_encode($data->categories_aide);
            $demande->urgence = $data->urgence;
            $demande->description_situation = $data->description_situation;
            $demande->demande_exacte = $data->demande_exacte;
            $demande->telephone = $data->telephone;
            $demande->email = $data->email;
            $demande->preference_contact = $data->preference_contact;
            $demande->horaires_disponibles = json_encode($data->horaires_disponibles);
            $demande->visibilite = $data->visibilite;
            $demande->anonyme = $data->anonyme ? 1 : 0;
            $demande->statut = $data->statut;
            
            if($demande->update()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Demande mise à jour avec succès."
                ]);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de mettre à jour la demande."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Données incomplètes."
            ]);
        }
        break;
    
    // ==========================================
    // UPDATE STATUS - Mettre à jour le statut uniquement
    // ==========================================
    case 'updateStatus':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->statut)) {
            $demande->id = $data->id;
            $demande->statut = $data->statut;
            
            if($demande->updateStatus()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Statut mis à jour avec succès."
                ]);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de mettre à jour le statut."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID ou statut manquant."
            ]);
        }
        break;
    
    // ==========================================
    // DELETE - Supprimer une demande
    // ==========================================
    case 'delete':
        if(isset($_GET['id'])) {
            $demande->id = $_GET['id'];
            
            if($demande->delete()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Demande supprimée avec succès."
                ]);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de supprimer la demande."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID de demande manquant."
            ]);
        }
        break;
    
    // ==========================================
    // SEARCH - Rechercher des demandes
    // ==========================================
    case 'search':
        if(isset($_GET['keywords'])) {
            $keywords = $_GET['keywords'];
            $stmt = $demande->search($keywords);
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $demandes_arr = [];
                
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    $demande_item = [
                        "id" => $id,
                        "nom" => $nom,
                        "gouvernorat" => $gouvernorat,
                        "ville" => $ville,
                        "categories_aide" => json_decode($categories_aide),
                        "urgence" => $urgence,
                        "statut" => $statut,
                        "date_soumission" => $date_soumission
                    ];
                    
                    array_push($demandes_arr, $demande_item);
                }
                
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "demandes" => $demandes_arr,
                    "count" => $num
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "demandes" => [],
                    "count" => 0,
                    "message" => "Aucun résultat trouvé."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Mot-clé de recherche manquant."
            ]);
        }
        break;
    
    // ==========================================
    // STATISTICS - Obtenir les statistiques
    // ==========================================
    case 'statistics':
        $stats = $demande->getStatistics();
        
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "statistics" => $stats
        ]);
        break;
    
    // ==========================================
    // ACTION INVALIDE
    // ==========================================
    default:
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Action invalide ou non spécifiée."
        ]);
        break;
}
?>