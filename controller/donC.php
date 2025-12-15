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

        $this->loadView('dashboard', $data);
    }

    // Don management methods
    public function dons() {
        $filters = [];
        
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
        
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
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
            
            if (!empty($_POST['etat_object'])) {
                $etatValidation = Validation::validateText($_POST['etat_object'], 'État', 0, 100);
                if ($etatValidation !== true) $errors[] = $etatValidation;
            }
            
            if (!empty($_POST['description'])) {
                $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
                if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
            }
            
            if (isset($_FILES['photos']) && $_FILES['photos']['error'] !== UPLOAD_ERR_NO_FILE) {
                $fileValidation = Validation::validateFile($_FILES['photos']);
                if ($fileValidation !== true) $errors[] = $fileValidation;
            }
            
            if (empty($errors)) {
                $photos = '';
                if (isset($_FILES['photos']) && $_FILES['photos']['error'] === UPLOAD_ERR_OK) {
                    $photos = $this->handleFileUpload($_FILES['photos']);
                }
                
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
        $this->loadView('adddon', ['error' => $error]);
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
            
            if (!empty($_POST['etat_object'])) {
                $etatValidation = Validation::validateText($_POST['etat_object'], 'État', 0, 100);
                if ($etatValidation !== true) $errors[] = $etatValidation;
            }
            
            if (!empty($_POST['description'])) {
                $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
                if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
            }
            
            if (isset($_FILES['photos']) && $_FILES['photos']['error'] !== UPLOAD_ERR_NO_FILE) {
                $fileValidation = Validation::validateFile($_FILES['photos']);
                if ($fileValidation !== true) $errors[] = $fileValidation;
            }
            
            if (empty($errors)) {
                $data = [
                    'type_don' => Validation::sanitize($_POST['type_don']),
                    'quantite' => Validation::sanitize($_POST['quantite']),
                    'etat_object' => Validation::sanitize($_POST['etat_object'] ?? ''),
                    'region' => Validation::sanitize($_POST['region']),
                    'description' => Validation::sanitize($_POST['description'] ?? '')
                ];

                if (!empty($_FILES['photos']['name'])) {
                    $fileValidation = Validation::validateFile($_FILES['photos']);
                    if ($fileValidation === true) {
                        $data['photos'] = $this->handleFileUpload($_FILES['photos']);
                    }
                } elseif (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
                    $data['photos'] = '';
                } else {
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
            if ($this->model->deleteDon($id)) {
                header('Location: /aide_solitaire/controller/donC.php?action=dons&message=deleted');
                exit;
            } else {
                header('Location: /aide_solitaire/controller/donC.php?action=dons&message=error');
                exit;
            }
        } else {
            $this->loadView('deletedon', ['don' => $don]);
        }
    }

    public function viewDon($id = null) {
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
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

    public function statistics() {
        $stats = $this->model->getDetailedStatistics();
        $recent_dons = $this->model->getAllDons();
        
        $this->loadView('statistics', [
            'stats' => $stats,
            'recent_dons' => array_slice($recent_dons, 0, 10)
        ]);
    }

    public function debugDons() {
        $allDons = $this->model->getAllDons();
        echo "<h2>Debug - All Donations in Database:</h2>";
        echo "<pre>";
        print_r($allDons);
        echo "</pre>";
        
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
        }
    }
    
    public function frontofficeView($id) {
        try {
            $don = $this->model->getDonById($id);
            
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

     public function saveDonAfterPayment() {
    // NETTOYER TOUTE SORTIE AVANT JSON
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    header('Content-Type: application/json');
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            throw new Exception('Données manquantes');
        }
        
        // CORRECTION : Récupérer groupe_id
        $donData = [
            'type_don' => $input['type_don'] ?? 'Argent',
            'quantite' => $input['quantite'] ?? 1,
            'region' => $input['region'] ?? '',
            'description' => $input['description'] ?? 'Don financier',
            'etat_object' => $input['etat_object'] ?? '',
            'photos' => $input['photos'] ?? '',
            'statut' => $input['statut'] ?? 'payé',
            'payment_intent_id' => $input['payment_intent_id'] ?? '',
            'contact_name' => $input['contact_name'] ?? '',
            'contact_email' => $input['contact_email'] ?? '',
            'contact_phone' => $input['contact_phone'] ?? '',
            'groupe_id' => isset($input['groupe_id']) && $input['groupe_id'] !== 'null' ? (int)$input['groupe_id'] : null // CORRECTION ICI
        ];
        
        // DEBUG: Ajouter un log pour voir les données
        error_log("=== saveDonAfterPayment - Données reçues ===");
        error_log(print_r($donData, true));
        error_log("Groupe ID: " . ($donData['groupe_id'] ?? 'NULL'));
        error_log("=== Fin des données ===");
        
        $donId = $this->model->createDonAndReturnId($donData);
        
        if ($donId) {
            echo json_encode([
                'success' => true,
                'don_id' => $donId,
                'message' => 'Don enregistré avec succès'
            ]);
        } else {
            throw new Exception('Erreur lors de la création du don');
        }
        
    } catch (Exception $e) {
        error_log("Erreur saveDonAfterPayment: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    
    exit;
}
    
    public function frontofficeList() {
        try {
            $dons = $this->model->getDonsWithFilters(['statut' => 'frontoffice']);
            
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
    
    private function loadFrontOfficeView($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../view/frontoffice/{$view}.php"; 
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<div style='background: #fff3cd; padding: 20px; border: 2px solid #ffc107; margin: 20px; border-radius: 8px;'>";
            echo "<h3>⚠️ View File Not Found (FrontOffice)</h3>";
            echo "<p><strong>Looking for:</strong> {$viewFile}</p>";
            echo "</div>";
        }
    }

    public function createPaymentIntent() {
        // NETTOYER TOUTE SORTIE
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['amount']) || $input['amount'] <= 0) {
                throw new Exception('Montant invalide ou manquant');
            }
            
            require_once __DIR__ . '/../config/stripe_config.php';
            
            if (!class_exists('StripeConfig')) {
                throw new Exception('Classe StripeConfig non chargée');
            }
            
            $secretKey = StripeConfig::getSecretKey();
            
            if (empty($secretKey)) {
                throw new Exception('Clé Stripe invalide');
            }
            
            $amount = intval($input['amount']);
            // CHANGEMENT IMPORTANT : Utiliser EUR au lieu de TND
            $currency = 'eur'; // Stripe en Tunisie supporte EUR, USD, GBP
            $description = substr($input['description'] ?? 'Don solidaire', 0, 100);
            
            $ch = curl_init();
            
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    'amount' => $amount,
                    'currency' => $currency, // EUR au lieu de TND
                    'description' => $description,
                    'metadata[email]' => $input['email'] ?? ''
                ]),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $secretKey,
                    'Content-Type: application/x-www-form-urlencoded'
                ],
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2
            ]);
            
            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            
            if ($curlError) {
                throw new Exception('Erreur cURL: ' . $curlError);
            }
            
            if ($httpCode !== 200) {
                $errorMsg = 'Erreur HTTP ' . $httpCode;
                if ($response) {
                    $stripeError = json_decode($response, true);
                    if (isset($stripeError['error']['message'])) {
                        $errorMsg .= ': ' . $stripeError['error']['message'];
                    }
                }
                throw new Exception($errorMsg);
            }
            
            $result = json_decode($response, true);
            
            if (!isset($result['client_secret'])) {
                throw new Exception('Réponse Stripe incomplète: client_secret manquant');
            }
            
            echo json_encode([
                'success' => true,
                'client_secret' => $result['client_secret'],
                'payment_intent_id' => $result['id'],
                'currency' => $currency
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
        
        exit;
    }

    public function updateDonStatus() {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['don_id'])) {
                throw new Exception('Missing data');
            }
            
            $donId = $input['don_id'];
            $statut = $input['statut'] ?? 'payé';
            $paymentIntentId = $input['payment_intent_id'] ?? '';
            
            if ($this->model->updateDonStatut($donId, $statut, $paymentIntentId)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Statut mis à jour avec succès'
                ]);
            } else {
                throw new Exception('Erreur lors de la mise à jour du statut');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function paiementSuccess() {
        session_start();
        $paiementId = $_GET['paiement_id'] ?? '';
        $donId = $_GET['don_id'] ?? '';
        
        if ($donId) {
            $this->model->updateDonStatut($donId, 'payé');
        }
        
        $this->loadFrontOfficeView('paiement_success', [
            'paiement_id' => $paiementId,
            'don_id' => $donId,
            'message' => '✅ Paiement confirmé ! Merci pour votre générosité.',
            'amount' => $_SESSION['temp_don_data']['montant'] ?? 0
        ]);
        
        unset($_SESSION['temp_don_data']);
    }

    public function paiementCancel() {
        session_start();
        $paiementId = $_GET['paiement_id'] ?? '';
        $donId = $_GET['don_id'] ?? '';
        
        if ($donId) {
            $this->model->updateDonStatut($donId, 'annulé');
        }
        
        $this->loadFrontOfficeView('paiement_cancel', [
            'paiement_id' => $paiementId,
            'don_id' => $donId
        ]);
        
        unset($_SESSION['temp_don_data']);
    }

    public function processStripeDon() {
        session_start();
        
        if (!isset($_SESSION['temp_don_data'])) {
            header('Location: /aide_solitaire/view/frontoffice/create_don.php?error=session_expired');
            exit;
        }
        
        $tempData = $_SESSION['temp_don_data'];
        
        try {
            $donData = [
                'type_don' => 'Argent',
                'quantite' => 1,
                'etat_object' => '',
                'photos' => '',
                'region' => $tempData['region'],
                'description' => $tempData['description'] ?? '',
                'statut' => 'en_attente_paiement',
                'contact_name' => $tempData['contact_name'],
                'contact_email' => $tempData['contact_email']
            ];
            
            if (!$this->model->createDon($donData)) {
                throw new Exception('Erreur création don');
            }
            
            $donId = $this->model->getLastInsertId();
            
            if (!$donId) {
                throw new Exception('Impossible de récupérer l\'ID du don');
            }
            
            $this->loadFrontOfficeView('stripe_payment', $tempData);
            
        } catch (Exception $e) {
            echo "<div style='padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>";
            echo "<h3>❌ Erreur lors du traitement du paiement</h3>";
            echo "<p><strong>Message d'erreur:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='/aide_solitaire/view/frontoffice/create_don.php' style='color: #721c24; text-decoration: underline;'>Retour au formulaire</a></p>";
            echo "</div>";
        }
    }

    public function frontofficeCreate() {
    require_once __DIR__ . '/../Model/Validation.php';
    
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $errors = [];
            
            $allowedTypes = ['Vêtements', 'Nourriture', 'Médicaments', 'Équipement', 'Argent', 'Services', 'Autre'];
            if (empty($_POST['type_don']) || !in_array($_POST['type_don'], $allowedTypes)) {
                $errors[] = "Type de don invalide";
            }
            
            if ($_POST['type_don'] !== 'Argent') {
                if (empty($_POST['quantite']) || !is_numeric($_POST['quantite']) || $_POST['quantite'] <= 0) {
                    $errors[] = "Quantité invalide (doit être un nombre positif)";
                }
            }
            
            $allowedRegions = ['Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'];
            if (empty($_POST['region']) || !in_array($_POST['region'], $allowedRegions)) {
                $errors[] = "Région invalide";
            }
            
            if (empty($_POST['contact_name']) || strlen($_POST['contact_name']) < 2) {
                $errors[] = "Nom de contact requis (minimum 2 caractères)";
            }
            
            if (empty($_POST['contact_email']) || !filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email de contact invalide";
            }
            
            if ($_POST['type_don'] === 'Argent') {
                if (empty($_POST['montant']) || !is_numeric($_POST['montant']) || $_POST['montant'] <= 0) {
                    $errors[] = "Montant invalide (minimum 1 €)";
                }
                if ($_POST['montant'] > 1000) {
                    $errors[] = "Montant maximum: 1,000 €";
                }
            }
            
            // Gestion de l'image
            $photos = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                // Validation de l'image
                $allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['photo']['tmp_name']);
                
                if (!in_array($fileType, $allowedImageTypes)) {
                    $errors[] = "Format d'image non supporté. Utilisez JPG, PNG ou GIF.";
                }
                
                if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                    $errors[] = "L'image ne doit pas dépasser 5MB";
                }
            }
            
            if (empty($errors)) {
                // Traitement de l'upload de l'image
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../uploads/dons/';
                    
                    // Créer le dossier s'il n'existe pas
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    // Générer un nom de fichier unique
                    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid('don_') . '_' . time() . '.' . $extension;
                    $uploadFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                        $photos = 'uploads/dons/' . $fileName;
                        error_log("Image uploadée avec succès: " . $photos);
                    } else {
                        $errors[] = "Erreur lors du téléchargement de l'image";
                        error_log("Erreur move_uploaded_file");
                    }
                }
                
                if (empty($errors)) {
                    // CORRECTION : Récupérer groupe_id
                    $groupe_id = isset($_POST['groupe_id']) && $_POST['groupe_id'] !== '' ? (int)$_POST['groupe_id'] : null;
                    
                    if ($_POST['type_don'] === 'Argent') {
                        $_SESSION['temp_don_data'] = [
                            'montant' => $_POST['montant'],
                            'contact_name' => $_POST['contact_name'],
                            'contact_email' => $_POST['contact_email'],
                            'contact_phone' => $_POST['contact_phone'] ?? '',
                            'region' => $_POST['region'],
                            'description' => $_POST['description'] ?? '',
                            'type_don' => 'Argent',
                            'etat_object' => $_POST['etat_object'] ?? '',
                            'photos' => $photos,
                            'groupe_id' => $groupe_id // AJOUTÉ
                        ];
                        
                        error_log("Redirection vers stripe_payment");
                        $this->loadFrontOfficeView('stripe_payment', $_SESSION['temp_don_data']);
                        return;
                        
                    } else {
                        $data = [
                            'type_don' => htmlspecialchars(trim($_POST['type_don'])),
                            'quantite' => (int)$_POST['quantite'],
                            'etat_object' => isset($_POST['etat_object']) ? htmlspecialchars(trim($_POST['etat_object'])) : '',
                            'photos' => $photos,
                            'region' => htmlspecialchars(trim($_POST['region'])),
                            'description' => isset($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : '',
                            'contact_name' => htmlspecialchars(trim($_POST['contact_name'])),
                            'contact_email' => htmlspecialchars(trim($_POST['contact_email'])),
                            'groupe_id' => $groupe_id, // AJOUTÉ
                            'statut' => 'actif'
                        ];
                        
                        error_log("Données à créer: " . print_r($data, true));
                        error_log("Groupe ID: " . ($groupe_id ?? 'NULL'));
                        
                        if ($this->model->createDon($data)) {
                            // CORRECTION ICI : Redirection correcte
                            header('Location: /aide_solitaire/view/frontoffice/browse_dons.php?message=don_created');
                            exit;
                        } else {
                            $error = "❌ Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";
                        }
                    }
                }
            } else {
                $error = "❌ " . implode("<br>❌ ", $errors);
            }
            
        } catch (Exception $e) {
            $error = "❌ Erreur système: " . $e->getMessage();
            error_log("Exception frontofficeCreate: " . $e->getMessage());
        }
    }
    
    $this->loadFrontOfficeView('create_don', [
        'error' => $error,
        'success' => $success
    ]);
}

    public function handleRequest() {
        session_start();
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
            case 'create_payment_intent':
                $this->createPaymentIntent();
                break;
            case 'save_don_after_payment':
                $this->saveDonAfterPayment();
                break;
            case 'update_don_status':
                $this->updateDonStatus();
                break;
            case 'paiement_success':
                $this->paiementSuccess();
                break;
            case 'paiement_cancel':
                $this->paiementCancel();
                break;
            case 'process_stripe_don':
                $this->processStripeDon();
                break;
            case 'frontoffice_create':
                $this->frontofficeCreate();
                break;
            case 'frontoffice_list':
                $this->frontofficeList();
                break;
            case 'frontoffice_view':
                $this->frontofficeView($_GET['id'] ?? null);
                break;
            default:
                $this->dashboard();
                break;
        }
    }
}

if (basename($_SERVER['PHP_SELF']) == 'donC.php') {
    $controller = new DonController();
    $controller->handleRequest();
}
?>