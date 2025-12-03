<?php
require_once __DIR__ . '/../Model/donmodel.php';
class DonController {
    private $model;

    public function __construct() {
        $this->model = new DonModel();
    }

    // Dashboard main page
    public function dashboard() {
        $data = [
            'stats' => $this->model->getDashboardStats(),
            'recent_dons' => $this->model->getAllDons()
        ];

        // Load the dashboard view
        $this->loadView('dashboard', $data);
    }

    // Don management methods
    public function dons() {
        $filters = [];
        
        // Apply filters if provided
        if (isset($_GET['type_don']) && !empty($_GET['type_don'])) {
            $filters['type_don'] = $_GET['type_don'];
        }
        
        if (isset($_GET['region']) && !empty($_GET['region'])) {
            $filters['region'] = $_GET['region'];
        }

        $dons = $this->model->getDonsWithFilters($filters);
        $this->loadView('dons', ['dons' => $dons, 'filters' => $filters]);
    }

   public function createDon() {
    require_once __DIR__ . '/../Model/Validation.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        
        // Validate each field
        $typeValidation = Validation::validateSelection($_POST['type_don'] ?? '', 'Type de don', [
            'Vêtements', 'Nourriture', 'Médicaments', 'Équipement', 'Argent', 'Services', 'Autre'
        ]);
        if ($typeValidation !== true) $errors[] = $typeValidation;
        
        $quantiteValidation = Validation::validateNumber($_POST['quantite'] ?? '', 'Quantité', 1, 1000);
        if ($quantiteValidation !== true) $errors[] = $quantiteValidation;
        
        $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
            'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 
            'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
        ]);
        if ($regionValidation !== true) $errors[] = $regionValidation;
        
        // État objet is optional
        if (!empty($_POST['etat_object'])) {
            $etatValidation = Validation::validateText($_POST['etat_object'], 'État', 0, 100);
            if ($etatValidation !== true) $errors[] = $etatValidation;
        }
        
        // Description is optional, but validate if provided
        if (!empty($_POST['description'])) {
            $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
            if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
        }
        
        // Validate file upload
        if (isset($_FILES['photos']) && $_FILES['photos']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileValidation = Validation::validateFile($_FILES['photos']);
            if ($fileValidation !== true) $errors[] = $fileValidation;
        }
        
        if (empty($errors)) {
            // Handle file upload
            $photos = '';
            if (isset($_FILES['photos']) && $_FILES['photos']['error'] === UPLOAD_ERR_OK) {
                $photos = $this->handleFileUpload($_FILES['photos']);
            }
            
            // Sanitize all inputs
            $data = [
                'type_don' => Validation::sanitize($_POST['type_don']),
                'quantite' => Validation::sanitize($_POST['quantite']),
                'etat_object' => Validation::sanitize($_POST['etat_object'] ?? ''),
                'photos' => $photos,
                'region' => Validation::sanitize($_POST['region']),
                'description' => Validation::sanitize($_POST['description'] ?? '')
            ];
            
            if ($this->model->createDon($data)) {
                header('Location: /aide_solitaire/controller/donC.php?action=dons&message=created');
                exit;
            } else {
                $error = "Erreur lors de la création du don";
            }
        } else {
            $error = implode("<br>", $errors);
        }
    }
    $this->loadView('adddon', ['error' => $error ?? null]);
}

    public function editDon($id = null) {
    require_once __DIR__ . '/../Model/Validation.php';
    
    if (empty($id) && isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    
    if (empty($id) || !is_numeric($id)) {
        header('Location: /aide_solitaire/controller/donC.php?action=dons&message=invalid_id');
        exit;
    }

    $don = $this->model->getDonById($id);
    
    if (!$don) {
        header('Location: /aide_solitaire/controller/donC.php?action=dons&message=not_found');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        
        // Validate each field
        $typeValidation = Validation::validateSelection($_POST['type_don'] ?? '', 'Type de don', [
            'Vêtements', 'Nourriture', 'Médicaments', 'Équipement', 'Argent', 'Services', 'Autre'
        ]);
        if ($typeValidation !== true) $errors[] = $typeValidation;
        
        $quantiteValidation = Validation::validateNumber($_POST['quantite'] ?? '', 'Quantité', 1, 1000);
        if ($quantiteValidation !== true) $errors[] = $quantiteValidation;
        
        $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
            'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 
            'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
        ]);
        if ($regionValidation !== true) $errors[] = $regionValidation;
        
        // État objet is optional
        if (!empty($_POST['etat_object'])) {
            $etatValidation = Validation::validateText($_POST['etat_object'], 'État', 0, 100);
            if ($etatValidation !== true) $errors[] = $etatValidation;
        }
        
        // Description is optional, but validate if provided
        if (!empty($_POST['description'])) {
            $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
            if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
        }
        
        // Validate file upload if new file is provided
        if (isset($_FILES['photos']) && $_FILES['photos']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileValidation = Validation::validateFile($_FILES['photos']);
            if ($fileValidation !== true) $errors[] = $fileValidation;
        }
        
        if (empty($errors)) {
            // Prepare data
            $data = [
                'type_don' => Validation::sanitize($_POST['type_don']),
                'quantite' => Validation::sanitize($_POST['quantite']),
                'etat_object' => Validation::sanitize($_POST['etat_object'] ?? ''),
                'region' => Validation::sanitize($_POST['region']),
                'description' => Validation::sanitize($_POST['description'] ?? '')
            ];

            // Handle photo update
            if (!empty($_FILES['photos']['name'])) {
                $fileValidation = Validation::validateFile($_FILES['photos']);
                if ($fileValidation === true) {
                    $data['photos'] = $this->handleFileUpload($_FILES['photos']);
                }
            } elseif (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
                $data['photos'] = ''; // Remove photo
            } else {
                // Keep existing photo
                $data['photos'] = $don['photos'];
            }
            
            if ($this->model->updateDon($id, $data)) {
                header('Location: /aide_solitaire/controller/donC.php?action=dons&message=updated');
                exit;
            } else {
                $error = "Erreur lors de la modification du don";
                $this->loadView('updatedon', ['don' => $don, 'error' => $error]);
            }
        } else {
            $error = implode("<br>", $errors);
            $this->loadView('updatedon', ['don' => $don, 'error' => $error]);
        }
    } else {
        $this->loadView('updatedon', ['don' => $don]);
    }
}
    public function deleteDon($id = null) {
        // Get ID from GET parameter if not passed directly
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            header('Location: /aide_solitaire/controller/donC.php?action=dons&message=invalid_id');
            exit;
        }

        // Get the donation data first
        $don = $this->model->getDonById($id);
        
        if (!$don) {
            header('Location: /aide_solitaire/controller/donC.php?action=dons&message=not_found');
            exit;
        }

        // If it's a POST request, process the deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->deleteDon($id)) {
                header('Location: /aide_solitaire/controller/donC.php?action=dons&message=deleted');
                exit;
            } else {
                header('Location: /aide_solitaire/controller/donC.php?action=dons&message=error');
                exit;
            }
        } else {
            // If it's a GET request, show the confirmation page
            $this->loadView('deletedon', ['don' => $don]);
        }
    }

    // View single donation
    public function viewDon($id = null) {
        // Get ID from GET parameter if not passed directly
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            header('Location: /aide_solitaire/controller/donC.php?action=dons&message=invalid_id');
            exit;
        }

        $don = $this->model->getDonById($id);
        if ($don) {
            $this->loadView('view_don', ['don' => $don]);
        } else {
            header('Location: /aide_solitaire/controller/donC.php?action=dons&message=not_found');
            exit;
        }
    }

    // Statistics page
    public function statistics() {
        $stats = $this->model->getDashboardStats();
        $this->loadView('statistics', ['stats' => $stats]);
    }

    // Debug method to check donations
    public function debugDons() {
        $allDons = $this->model->getAllDons();
        echo "<h2>Debug - All Donations in Database:</h2>";
        echo "<pre>";
        print_r($allDons);
        echo "</pre>";
        
        // Also test the getDonById method
        if (!empty($allDons)) {
            $firstDon = $this->model->getDonById($allDons[0]['id']);
            echo "<h2>Debug - First Donation by ID:</h2>";
            echo "<pre>";
            print_r($firstDon);
            echo "</pre>";
        } else {
            echo "<h2>No donations found in database!</h2>";
        }
    }

    // Helper method for file uploads
    private function handleFileUpload($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/dons/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid() . '_' . basename($file['name']);
            $uploadFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                return $uploadFile;
            }
        }
        return '';
    }

    // FIXED: Helper method to load views with correct paths
    private function loadView($view, $data = []) {
        extract($data);
        
        // Define the correct path for your file structure
        // From controller/ to view/Backoffice/
        $viewFile = __DIR__ . "/../view/Backoffice/{$view}.php"; 
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Show a simple error and the data for debugging
            echo "<div style='background: #fff3cd; padding: 20px; border: 2px solid #ffc107; margin: 20px; border-radius: 8px;'>";
            echo "<h3>⚠️ View File Not Found</h3>";
            echo "<p><strong>Looking for:</strong> {$viewFile}</p>";
            echo "<p><strong>Current directory:</strong> " . getcwd() . "</p>";
            
            // Try to list available files
            $backofficePath = "../view/Backoffice";
            if (is_dir($backofficePath)) {
                $files = scandir($backofficePath);
                echo "<p><strong>Available files in view/Backoffice/:</strong></p>";
                echo "<ul>";
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        $highlight = ($file == "{$view}.php") ? " style='color: green; font-weight: bold;'" : "";
                        echo "<li{$highlight}>{$file}</li>";
                    }
                }
                echo "</ul>";
            }
            echo "</div>";
            
            // Show the data that would be passed to the view
            echo "<h3>Data that would be passed to the view:</h3>";
            echo "<pre>" . print_r($data, true) . "</pre>";
        }
    }
    // FRONTOFFICE: Create donation from frontoffice (immediately visible)
// FRONTOFFICE: View single donation (public)
public function frontofficeView($id) {
    try {
        $don = $this->model->getDonById($id);
        
        // CHANGED: Only check if donation exists, not status
        if (!$don) {
            $this->loadFrontOfficeView('error', [
                'message' => 'Don non trouvé'
            ]);
            return;
        }
        
        $this->loadFrontOfficeView('view_don', ['don' => $don]);
    } catch (Exception $e) {
        $this->loadFrontOfficeView('error', ['message' => $e->getMessage()]);
    }
}    
public function frontofficeCreate() {
    require_once __DIR__ . '/../Model/Validation.php';
    
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $errors = [];
            
            // Validate inputs
            $typeValidation = Validation::validateSelection($_POST['type_don'] ?? '', 'Type de don', [
                'Vêtements', 'Nourriture', 'Médicaments', 'Équipement', 'Argent', 'Services', 'Autre'
            ]);
            if ($typeValidation !== true) $errors[] = $typeValidation;
            
            $quantiteValidation = Validation::validateNumber($_POST['quantite'] ?? '', 'Quantité', 1, 1000);
            if ($quantiteValidation !== true) $errors[] = $quantiteValidation;
            
            $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
                'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
            ]);
            if ($regionValidation !== true) $errors[] = $regionValidation;
            
            // Validate contact info (for frontoffice)
            if (empty($_POST['contact_name']) || strlen($_POST['contact_name']) < 2) {
                $errors[] = "Nom de contact requis (minimum 2 caractères)";
            }
            
            if (empty($_POST['contact_email']) || !filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email de contact invalide";
            }
            
            // État objet is optional
            if (!empty($_POST['etat_object'])) {
                $etatValidation = Validation::validateText($_POST['etat_object'], 'État', 0, 100);
                if ($etatValidation !== true) $errors[] = $etatValidation;
            }
            
            // Description is optional, but validate if provided
            if (!empty($_POST['description'])) {
                $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
                if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
            }
            
            if (empty($errors)) {
                // CHANGED: FrontOffice donations are now 'actif' immediately
               // In your frontofficeCreate() method in donC.php:
                $data = [
                    'type_don' => Validation::sanitize(trim($_POST['type_don'])),
                    'quantite' => (int)$_POST['quantite'],
                    'etat_object' => isset($_POST['etat_object']) ? Validation::sanitize(trim($_POST['etat_object'])) : '',
                    'photos' => '', // FrontOffice doesn't handle file uploads
                    'region' => Validation::sanitize(trim($_POST['region'])),
                    'description' => isset($_POST['description']) ? Validation::sanitize(trim($_POST['description'])) : '',
                    'statut' => 'actif' // CHANGED: Now immediately active for frontoffice
                ];
                
                // Save donation
                if ($this->model->createDon($data)) {
                    $success = "✅ Votre don a été ajouté avec succès ! Il est maintenant visible sur le site.";
                    $_POST = []; // Clear form
                    
                    // Optional: Redirect to the donations list
                    // header('Location: /aide_solitaire/controller/donC.php?action=list&context=frontoffice');
                    // exit;
                } else {
                    $error = "❌ Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";
                }
            } else {
                $error = "❌ " . implode("<br>❌ ", $errors);
            }
            
        } catch (Exception $e) {
            $error = "❌ Erreur système: " . $e->getMessage();
        }
    }
    
    $this->loadFrontOfficeView('create_don', [
        'error' => $error,
        'success' => $success
    ]);
}
// FRONTOFFICE: List all donations for public viewing (including newly created ones)
public function frontofficeList() {
    try {
        // CHANGED: Use 'frontoffice' status to show both actif and en_attente
        $dons = $this->model->getDonsWithFilters(['statut' => 'frontoffice']);
        
        // Get unique types and regions for filters
        $allDons = $this->model->getAllDons();
        $types = array_unique(array_column($allDons, 'type_don'));
        $regions = array_unique(array_column($allDons, 'region'));
        
        $this->loadFrontOfficeView('browse_dons', [
            'dons' => $dons,
            'types' => $types,
            'regions' => $regions
        ]);
    } catch (Exception $e) {
        $error = $e->getMessage();
        $this->loadFrontOfficeView('browse_dons', [
            'dons' => [],
            'types' => [],
            'regions' => [],
            'error' => $error
        ]);
    }
}

    // Route requests
    public function handleRequest() {
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'dashboard':
                $this->dashboard();
                break;
            case 'dons':
                $this->dons();
                break;
            case 'create_don':
                $this->createDon();
                break;
            case 'edit_don':
                $this->editDon($_GET['id'] ?? null);
                break;
            case 'delete_don':
                $this->deleteDon($_GET['id'] ?? null);
                break;
            case 'view_don':
                $this->viewDon($_GET['id'] ?? null);
                break;
            case 'statistics':
                $this->statistics();
                break;
            case 'debugDons':
                $this->debugDons();
                break;
            default:
                $this->dashboard();
                break;
        }
    }
}

// Initialize and handle the request
if (basename($_SERVER['PHP_SELF']) == 'donC.php') {
    $controller = new DonController();
    $controller->handleRequest();
}
?>