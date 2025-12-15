<?php
require_once __DIR__ . '/../Model/groupemodel.php';

class GroupeController {
    private $model;

    public function __construct() {
        $this->model = new GroupeModel();
    }

    // Liste des groupes
    public function groupes() {
        $filters = [];
        
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }
        
        if (isset($_GET['region']) && !empty($_GET['region'])) {
            $filters['region'] = $_GET['region'];
        }

        if (isset($_GET['statut']) && !empty($_GET['statut'])) {
            $filters['statut'] = $_GET['statut'];
        }

        $groupes = $this->model->getGroupesWithFilters($filters);
        $this->loadView('groupes', ['groupes' => $groupes, 'filters' => $filters]);
    }

    // Créer un groupe
   public function createGroupe() {
    require_once __DIR__ . '/../Model/Validation.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        
        // Validate each field
        $nomValidation = Validation::validateText($_POST['nom'] ?? '', 'Nom', 3, 100);
        if ($nomValidation !== true) $errors[] = $nomValidation;
        
        $typeValidation = Validation::validateSelection($_POST['type'] ?? '', 'Type', [
            'Santé', 'Éducation', 'Seniors', 'Jeunesse', 'Culture', 
            'Urgence', 'Animaux', 'Environnement', 'Religieux', 'Social'
        ]);
        if ($typeValidation !== true) $errors[] = $typeValidation;
        
        $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
            'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 
            'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
        ]);
        if ($regionValidation !== true) $errors[] = $regionValidation;
        
        $responsableValidation = Validation::validateText($_POST['responsable'] ?? '', 'Responsable', 2, 100);
        if ($responsableValidation !== true) $errors[] = $responsableValidation;
        
        $emailValidation = Validation::validateEmail($_POST['email'] ?? '');
        if ($emailValidation !== true) $errors[] = $emailValidation;
        
        $telephoneValidation = Validation::validatePhone($_POST['telephone'] ?? '');
        if ($telephoneValidation !== true) $errors[] = $telephoneValidation;
        
        // Description is optional, but validate if provided
        if (!empty($_POST['description'])) {
            $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
            if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
        }
        
        if (empty($errors)) {
            // Sanitize all inputs
            $data = [
                'nom' => Validation::sanitize($_POST['nom']),
                'description' => Validation::sanitize($_POST['description'] ?? ''),
                'type' => Validation::sanitize($_POST['type']),
                'region' => Validation::sanitize($_POST['region']),
                'responsable' => Validation::sanitize($_POST['responsable']),
                'email' => Validation::sanitize($_POST['email']),
                'telephone' => Validation::sanitize($_POST['telephone'])
            ];
            
            if ($this->model->createGroupe($data)) {
                header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=created');
                exit;
            } else {
                $error = "Erreur lors de la création du groupe";
            }
        } else {
            $error = implode("<br>", $errors);
        }
    }
    $this->loadView('addgroupe', ['error' => $error ?? null]);
}

    // Modifier un groupe
    public function editGroupe($id = null) {
    require_once __DIR__ . '/../Model/Validation.php';
    
    if (empty($id) && isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    
    if (empty($id) || !is_numeric($id)) {
        header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=invalid_id');
        exit;
    }

    $groupe = $this->model->getGroupeById($id);
    
    if (!$groupe) {
        header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=not_found');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        
        // Validate each field
        $nomValidation = Validation::validateText($_POST['nom'] ?? '', 'Nom', 3, 100);
        if ($nomValidation !== true) $errors[] = $nomValidation;
        
        $typeValidation = Validation::validateSelection($_POST['type'] ?? '', 'Type', [
            'Santé', 'Éducation', 'Seniors', 'Jeunesse', 'Culture', 
            'Urgence', 'Animaux', 'Environnement', 'Religieux', 'Social'
        ]);
        if ($typeValidation !== true) $errors[] = $typeValidation;
        
        $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
            'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 
            'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
        ]);
        if ($regionValidation !== true) $errors[] = $regionValidation;
        
        $responsableValidation = Validation::validateText($_POST['responsable'] ?? '', 'Responsable', 2, 100);
        if ($responsableValidation !== true) $errors[] = $responsableValidation;
        
        $emailValidation = Validation::validateEmail($_POST['email'] ?? '');
        if ($emailValidation !== true) $errors[] = $emailValidation;
        
        $telephoneValidation = Validation::validatePhone($_POST['telephone'] ?? '');
        if ($telephoneValidation !== true) $errors[] = $telephoneValidation;
        
        $statutValidation = Validation::validateSelection($_POST['statut'] ?? '', 'Statut', [
            'actif', 'inactif', 'en_attente'
        ]);
        if ($statutValidation !== true) $errors[] = $statutValidation;
        
        // Description is optional, but validate if provided
        if (!empty($_POST['description'])) {
            $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
            if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
        }
        
        if (empty($errors)) {
            // Sanitize all inputs
            $data = [
                'nom' => Validation::sanitize($_POST['nom']),
                'description' => Validation::sanitize($_POST['description'] ?? ''),
                'type' => Validation::sanitize($_POST['type']),
                'region' => Validation::sanitize($_POST['region']),
                'responsable' => Validation::sanitize($_POST['responsable']),
                'email' => Validation::sanitize($_POST['email']),
                'telephone' => Validation::sanitize($_POST['telephone']),
                'statut' => Validation::sanitize($_POST['statut'])
            ];
            
            if ($this->model->updateGroupe($id, $data)) {
                header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=updated');
                exit;
            } else {
                $error = "Erreur lors de la modification du groupe";
                $this->loadView('editgroupe', ['groupe' => $groupe, 'error' => $error]);
            }
        } else {
            $error = implode("<br>", $errors);
            $this->loadView('editgroupe', ['groupe' => $groupe, 'error' => $error]);
        }
    } else {
        $this->loadView('editgroupe', ['groupe' => $groupe]);
    }
}

    // Supprimer un groupe
    public function deleteGroupe($id = null) {
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (empty($id) || !is_numeric($id)) {
            header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=invalid_id');
            exit;
        }

        $groupe = $this->model->getGroupeById($id);
        
        if (!$groupe) {
            header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->deleteGroupe($id)) {
                header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=deleted');
                exit;
            } else {
                header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=error');
                exit;
            }
        } else {
            $this->loadView('deletegroupe', ['groupe' => $groupe]);
        }
    }

    // Voir un groupe
    public function viewGroupe($id = null) {
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (empty($id) || !is_numeric($id)) {
            header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=invalid_id');
            exit;
        }

        $groupe = $this->model->getGroupeById($id);
        if ($groupe) {
            $this->loadView('viewgroupe', ['groupe' => $groupe]);
        } else {
            header('Location: /aide_solitaire/controller/groupeC.php?action=groupes&message=not_found');
            exit;
        }
    }
    // FRONTOFFICE: Create group from frontoffice (immediately visible)
public function frontofficeCreate() {
    require_once __DIR__ . '/../Model/Validation.php';
    
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $errors = [];
            
            // Validate inputs
            $nomValidation = Validation::validateText($_POST['nom'] ?? '', 'Nom', 3, 100);
            if ($nomValidation !== true) $errors[] = $nomValidation;
            
            $typeValidation = Validation::validateSelection($_POST['type'] ?? '', 'Type', [
                'Santé', 'Éducation', 'Seniors', 'Jeunesse', 'Culture', 
                'Urgence', 'Animaux', 'Environnement', 'Religieux', 'Social'
            ]);
            if ($typeValidation !== true) $errors[] = $typeValidation;
            
            $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
                'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 
                'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
            ]);
            if ($regionValidation !== true) $errors[] = $regionValidation;
            
            $responsableValidation = Validation::validateText($_POST['responsable'] ?? '', 'Responsable', 2, 100);
            if ($responsableValidation !== true) $errors[] = $responsableValidation;
            
            $emailValidation = Validation::validateEmail($_POST['email'] ?? '');
            if ($emailValidation !== true) $errors[] = $emailValidation;
            
            $telephoneValidation = Validation::validatePhone($_POST['telephone'] ?? '');
            if ($telephoneValidation !== true) $errors[] = $telephoneValidation;
            
            // Description is optional, but validate if provided
            if (!empty($_POST['description'])) {
                $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
                if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
            }
            
            if (empty($errors)) {
                // CHANGED: FrontOffice groups are now 'actif' immediately
                $data = [
                    'nom' => Validation::sanitize(trim($_POST['nom'])),
                    'description' => isset($_POST['description']) ? Validation::sanitize(trim($_POST['description'])) : '',
                    'type' => Validation::sanitize(trim($_POST['type'])),
                    'region' => Validation::sanitize(trim($_POST['region'])),
                    'responsable' => Validation::sanitize(trim($_POST['responsable'])),
                    'email' => Validation::sanitize(trim($_POST['email'])),
                    'telephone' => Validation::sanitize(trim($_POST['telephone'])),
                    'statut' => 'actif' // CHANGED: Now immediately active
                ];
                
                // Save group
                if ($this->model->createGroupe($data)) {
                    $success = "✅ Votre groupe a été créé avec succès ! Il est maintenant visible sur le site.";
                    $_POST = []; // Clear form
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
    
    $this->loadFrontOfficeView('create_groupe', [
        'error' => $error,
        'success' => $success
    ]);
}
// FRONTOFFICE: List all groups for public viewing (including newly created ones)
public function frontofficeList() {
    try {
        // CHANGED: Use 'frontoffice' status to show both actif and en_attente
        $groupes = $this->model->getGroupesWithFilters(['statut' => 'frontoffice']);
        
        // Get unique types and regions for filters
        $allGroupes = $this->model->getAllGroupes();
        $types = array_unique(array_column($allGroupes, 'type'));
        $regions = array_unique(array_column($allGroupes, 'region'));
        
        $this->loadFrontOfficeView('browse_groupes', [
            'groupes' => $groupes,
            'types' => $types,
            'regions' => $regions
        ]);
    } catch (Exception $e) {
        $error = $e->getMessage();
        $this->loadFrontOfficeView('browse_groupes', [
            'groupes' => [],
            'types' => [],
            'regions' => [],
            'error' => $error
        ]);
    }
}

    // Méthode helper pour charger les vues
    private function loadView($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../view/Backoffice/{$view}.php";
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<div style='background: #fff3cd; padding: 20px; border: 2px solid #ffc107; margin: 20px; border-radius: 8px;'>";
            echo "<h3>⚠️ View File Not Found</h3>";
            echo "<p><strong>Looking for:</strong> {$viewFile}</p>";
            echo "</div>";
            
            echo "<h3>Data that would be passed to the view:</h3>";
            echo "<pre>" . print_r($data, true) . "</pre>";
        }
    }

    // Router
    public function handleRequest() {
        $action = $_GET['action'] ?? 'groupes';

        switch ($action) {
            case 'groupes':
                $this->groupes();
                break;
            case 'create_groupe':
                $this->createGroupe();
                break;
            case 'edit_groupe':
                $this->editGroupe($_GET['id'] ?? null);
                break;
            case 'delete_groupe':
                $this->deleteGroupe($_GET['id'] ?? null);
                break;
            case 'view_groupe':
                $this->viewGroupe($_GET['id'] ?? null);
                break;
            default:
                $this->groupes();
                break;
        }
    }
}

// Initialisation
if (basename($_SERVER['PHP_SELF']) == 'groupeC.php') {
    $controller = new GroupeController();
    $controller->handleRequest();
}
?>