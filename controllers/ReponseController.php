<?php
/**
 * Contrôleur ReponseController - API REST pour les réponses
 * SparkMind - Plateforme de solidarité
 */

// Headers CORS et JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion des fichiers nécessaires
require_once '../config/config.php';
require_once '../models/Reponse.php';

// Initialisation de la base de données
$database = new Database();
$db = $database->getConnection();

// Vérification de la connexion
if ($db === null) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur de connexion à la base de données"
    ]);
    exit();
}

// Initialisation du modèle
$reponse = new Reponse($db);

// Récupération de l'action demandée
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Router - Traitement des différentes actions
switch($action) {
    
    /**
     * CREATE - Créer une nouvelle réponse
     * Méthode: POST
     * Paramètres: demande_id, administrateur, message
     */
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données
            $reponse->demande_id = isset($_POST['demande_id']) ? $_POST['demande_id'] : '';
            $reponse->administrateur = isset($_POST['administrateur']) ? $_POST['administrateur'] : '';
            $reponse->message = isset($_POST['message']) ? $_POST['message'] : '';
            
            // Validation des données
            if (empty($reponse->demande_id) || empty($reponse->administrateur) || empty($reponse->message)) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Données incomplètes. Veuillez remplir tous les champs obligatoires."
                ]);
                break;
            }
            
            // Création de la réponse
            if ($reponse->create()) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Réponse créée avec succès",
                    "id" => $reponse->id
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de créer la réponse"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "message" => "Méthode non autorisée"
            ]);
        }
        break;
    
    /**
     * GET ALL - Récupérer toutes les réponses
     * Méthode: GET
     */
    case 'getAll':
        $stmt = $reponse->readAll();
        
        if ($stmt && $stmt->rowCount() > 0) {
            $reponses_arr = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reponse_item = [
                    "id" => $row['id'],
                    "demande_id" => $row['demande_id'],
                    "administrateur" => $row['administrateur'],
                    "message" => $row['message'],
                    "date_reponse" => $row['date_reponse'],
                    "demandeur_nom" => $row['demandeur_nom'] ?? 'N/A',
                    "demande_statut" => $row['demande_statut'] ?? 'N/A'
                ];
                
                array_push($reponses_arr, $reponse_item);
            }
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "count" => count($reponses_arr),
                "reponses" => $reponses_arr
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "count" => 0,
                "reponses" => []
            ]);
        }
        break;
    
    /**
     * GET ONE - Récupérer une réponse spécifique
     * Méthode: GET
     * Paramètre: id
     */
    case 'getOne':
        if (isset($_GET['id'])) {
            $reponse->id = $_GET['id'];
            $reponse_data = $reponse->readOne();
            
            if ($reponse_data) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "reponse" => $reponse_data
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Réponse non trouvée"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID manquant"
            ]);
        }
        break;
    
    /**
     * GET BY DEMANDE - Récupérer toutes les réponses d'une demande
     * Méthode: GET
     * Paramètre: demande_id
     */
    case 'getByDemande':
        if (isset($_GET['demande_id'])) {
            $demande_id = $_GET['demande_id'];
            $reponses_arr = $reponse->getByDemande($demande_id);
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "count" => count($reponses_arr),
                "reponses" => $reponses_arr
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID de demande manquant"
            ]);
        }
        break;
    
    /**
     * UPDATE - Mettre à jour une réponse
     * Méthode: POST (PUT simulé)
     * Paramètres: id, administrateur, message
     */
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reponse->id = isset($_POST['id']) ? $_POST['id'] : '';
            $reponse->administrateur = isset($_POST['administrateur']) ? $_POST['administrateur'] : '';
            $reponse->message = isset($_POST['message']) ? $_POST['message'] : '';
            
            if (empty($reponse->id) || empty($reponse->administrateur) || empty($reponse->message)) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Données incomplètes"
                ]);
                break;
            }
            
            if ($reponse->update()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Réponse mise à jour avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de mettre à jour la réponse"
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "message" => "Méthode non autorisée"
            ]);
        }
        break;
    
    /**
     * DELETE - Supprimer une réponse
     * Méthode: DELETE (ou GET avec paramètre)
     * Paramètre: id
     */
    case 'delete':
        if (isset($_GET['id'])) {
            $reponse->id = $_GET['id'];
            
            if ($reponse->delete()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Réponse supprimée avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de supprimer la réponse"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID manquant"
            ]);
        }
        break;
    
    /**
     * DELETE BY DEMANDE - Supprimer toutes les réponses d'une demande
     * Méthode: DELETE (ou GET avec paramètre)
     * Paramètre: demande_id
     */
    case 'deleteByDemande':
        if (isset($_GET['demande_id'])) {
            $demande_id = $_GET['demande_id'];
            
            if ($reponse->deleteByDemande($demande_id)) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Toutes les réponses de la demande ont été supprimées"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de supprimer les réponses"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID de demande manquant"
            ]);
        }
        break;
    
    /**
     * COUNT - Compter les réponses d'une demande
     * Méthode: GET
     * Paramètre: demande_id
     */
    case 'count':
        if (isset($_GET['demande_id'])) {
            $demande_id = $_GET['demande_id'];
            $count = $reponse->countByDemande($demande_id);
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "count" => $count
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID de demande manquant"
            ]);
        }
        break;
    
    /**
     * STATISTICS - Obtenir les statistiques des réponses
     * Méthode: GET
     */
    case 'getStatistics':
        $statistics = $reponse->getStatistics();
        
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "statistics" => $statistics
        ]);
        break;
    
    /**
     * SEARCH - Rechercher des réponses
     * Méthode: GET
     * Paramètre: keyword
     */
    case 'search':
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $reponses_arr = $reponse->search($keyword);
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "count" => count($reponses_arr),
                "reponses" => $reponses_arr
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Mot-clé manquant"
            ]);
        }
        break;
    
    /**
     * GET RECENT - Obtenir les réponses récentes
     * Méthode: GET
     * Paramètre optionnel: limit (défaut: 10)
     */
    case 'getRecent':
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $reponses_arr = $reponse->getRecent($limit);
        
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "count" => count($reponses_arr),
            "reponses" => $reponses_arr
        ]);
        break;
    
    /**
     * HAS REPONSES - Vérifier si une demande a des réponses
     * Méthode: GET
     * Paramètre: demande_id
     */
    case 'hasReponses':
        if (isset($_GET['demande_id'])) {
            $demande_id = $_GET['demande_id'];
            $has_reponses = $reponse->hasReponses($demande_id);
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "hasReponses" => $has_reponses
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID de demande manquant"
            ]);
        }
        break;
    
    /**
     * DEFAULT - Action non trouvée
     */
    default:
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Action non reconnue",
            "actions_disponibles" => [
                "create" => "POST - Créer une réponse",
                "getAll" => "GET - Toutes les réponses",
                "getOne" => "GET - Une réponse (id)",
                "getByDemande" => "GET - Réponses d'une demande (demande_id)",
                "update" => "POST - Mettre à jour (id, administrateur, message)",
                "delete" => "DELETE - Supprimer (id)",
                "deleteByDemande" => "DELETE - Supprimer toutes les réponses (demande_id)",
                "count" => "GET - Compter les réponses (demande_id)",
                "getStatistics" => "GET - Statistiques",
                "search" => "GET - Rechercher (keyword)",
                "getRecent" => "GET - Réponses récentes (limit optionnel)",
                "hasReponses" => "GET - Vérifier si demande a réponses (demande_id)"
            ]
        ]);
        break;
}
?>