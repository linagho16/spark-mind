<?php
require_once __DIR__ . '/../Models/Event.php';

class EventController {
    private $model;
    
    public function __construct(PDO $pdo) {
        $this->model = new Event($pdo);
    }
    
    public function index() {
        $events = $this->model->all();
        include __DIR__ . '/../Views/events/index.php';
    }
    
    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        $event = $this->model->find($id);
        if (!$event) { 
            header("HTTP/1.0 404 Not Found"); 
            echo "Événement introuvable"; 
            exit; 
        }
        include __DIR__ . '/../Views/events/show.php';
    }
    
    public function create() {
        $errors = [];
        $data = [];
        $categories = $this->model->getCategories();
        include __DIR__ . '/../Views/events/create.php';
    }
    
    public function store() {
        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_event' => trim($_POST['date_event'] ?? ''),
            'lieu' => trim($_POST['lieu'] ?? ''),
            'prix' => trim($_POST['prix'] ?? '0.00'),
            'categorie_id' => !empty($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null
        ];
        
        // Validation avancée
        $errors = $this->validateAdvanced($data);
        
        // Gestion de l'upload d'image
        try {
            $data['image'] = $this->handleImageUpload();
        } catch (Exception $e) {
            $errors['image'] = $e->getMessage();
            $categories = $this->model->getCategories();
            include __DIR__ . '/../Views/events/create.php';
            return;
        }
        
        if (!empty($errors)) {
            $categories = $this->model->getCategories();
            include __DIR__ . '/../Views/events/create.php';
            return;
        }
        
        try {
            $this->model->createWithCategory($data);
            header("Location: index.php?route=events.index");
            exit;
        } catch (Exception $e) {
            $errors[] = "Erreur lors de la création: " . $e->getMessage();
            $categories = $this->model->getCategories();
            include __DIR__ . '/../Views/events/create.php';
        }
    }
    
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $event = $this->model->find($id);
        if (!$event) { 
            header("Location: index.php?route=events.index"); 
            exit; 
        }
        $errors = [];
        $categories = $this->model->getCategories();
        include __DIR__ . '/../Views/events/edit.php';
    }
    
    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_event' => trim($_POST['date_event'] ?? ''),
            'lieu' => trim($_POST['lieu'] ?? ''),
            'prix' => trim($_POST['prix'] ?? '0.00'),
            'categorie_id' => !empty($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null
        ];
        
        // Validation avancée
        $errors = $this->validateAdvanced($data);
        
        // Gestion de l'upload d'image
        try {
            $newImage = $this->handleImageUpload();
            if ($newImage) {
                $data['image'] = $newImage;
            }
        } catch (Exception $e) {
            $errors['image'] = $e->getMessage();
            $event = array_merge($this->model->find($id) ?: [], $data);
            $categories = $this->model->getCategories();
            include __DIR__ . '/../Views/events/edit.php';
            return;
        }
        
        if (!empty($errors)) {
            $event = array_merge($this->model->find($id) ?: [], $data);
            $categories = $this->model->getCategories();
            include __DIR__ . '/../Views/events/edit.php';
            return;
        }
        
        try {
            $this->model->updateWithCategory($id, $data);
            header("Location: index.php?route=events.index");
            exit;
        } catch (Exception $e) {
            $errors[] = "Erreur lors de la modification: " . $e->getMessage();
            $event = array_merge($this->model->find($id) ?: [], $data);
            $categories = $this->model->getCategories();
            include __DIR__ . '/../Views/events/edit.php';
        }
    }
    
    public function destroy() {
        $id = (int)($_GET['id'] ?? 0);
        try {
            $this->model->delete($id);
            header("Location: index.php?route=events.index");
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de la suppression: " . $e->getMessage();
        }
    }
    
    private function validateAdvanced(array $data): array {
        $errors = [];
        
        // Validation titre
        if (empty($data['titre'])) {
            $errors['titre'] = "Le titre est requis";
        } elseif (strlen($data['titre']) < 3) {
            $errors['titre'] = "Le titre doit faire au moins 3 caractères";
        } elseif (strlen($data['titre']) > 255) {
            $errors['titre'] = "Le titre ne peut pas dépasser 255 caractères";
        }
        
        // Validation description
        if (strlen($data['description']) > 1000) {
            $errors['description'] = "La description ne peut pas dépasser 1000 caractères";
        }
        
        // Validation date
        if (empty($data['date_event'])) {
            $errors['date_event'] = "La date est requise";
        } else {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_event'])) {
                $errors['date_event'] = "Format de date invalide (utilisez YYYY-MM-DD)";
            } else {
                $date = DateTime::createFromFormat('Y-m-d', $data['date_event']);
                if (!$date) {
                    $errors['date_event'] = "Date invalide";
                } else {
                    $today = new DateTime();
                    $today->setTime(0, 0, 0);
                    if ($date < $today) {
                        $errors['date_event'] = "La date ne peut pas être dans le passé";
                    }
                    
                    $maxDate = new DateTime();
                    $maxDate->modify('+2 years');
                    if ($date > $maxDate) {
                        $errors['date_event'] = "La date ne peut pas dépasser 2 ans dans le futur";
                    }
                }
            }
        }
        
        // Validation lieu
        if (empty($data['lieu'])) {
            $errors['lieu'] = "Le lieu est requis";
        } elseif (strlen($data['lieu']) < 2) {
            $errors['lieu'] = "Le lieu doit faire au moins 2 caractères";
        } elseif (strlen($data['lieu']) > 255) {
            $errors['lieu'] = "Le lieu ne peut pas dépasser 255 caractères";
        }
        
        // Validation prix
        if (empty($data['prix'])) {
            $data['prix'] = '0.00';
        }
        
        if (!is_numeric($data['prix'])) {
            $errors['prix'] = "Le prix doit être un nombre";
        } else {
            $prix = (float)$data['prix'];
            if ($prix < 0) {
                $errors['prix'] = "Le prix ne peut pas être négatif";
            } elseif ($prix > 10000) {
                $errors['prix'] = "Le prix ne peut pas dépasser 10 000€";
            } elseif ($prix != round($prix, 2)) {
                $errors['prix'] = "Le prix ne peut avoir que 2 décimales maximum";
            }
        }
        
        return $errors;
    }
    
    private function handleImageUpload(): ?string {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $basePath = dirname(__DIR__, 1);
        $uploadDir = $basePath . '/uplodes/events/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;
        
        $file = $_FILES['image'];
        
        // Vérifier le type de fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé. Formats acceptés: JPEG, PNG, GIF, WebP");
        }
        
        // Vérifier la taille
        if ($file['size'] > $maxSize) {
            throw new Exception("L'image est trop volumineuse. Taille maximum: 5MB");
        }
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Vérifier si le dossier est accessible en écriture
        if (!is_writable($uploadDir)) {
            throw new Exception("Le dossier d'upload n'est pas accessible en écriture");
        }
        
        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Déplacer le fichier
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception("Erreur lors de l'upload de l'image");
        }
        
        return $filename;
    }
}