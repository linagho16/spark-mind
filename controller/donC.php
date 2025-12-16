<?php
ob_start();

require_once __DIR__ . '/../Model/donmodel.php';

class DonController
{
    private DonModel $model;

    public function __construct()
    {
        $this->model = new DonModel();
    }

    /* =========================
       BACKOFFICE
    ========================== */

    public function dashboard(): void
    {
        $data = [
            'stats' => $this->model->getDashboardStats(),
            'recent_dons' => $this->model->getAllDons()
        ];
        $this->loadView('dashboard', $data);
    }

    public function dons(): void
    {
        $filters = [];

        if (isset($_GET['type_don']) && $_GET['type_don'] !== '') {
            $filters['type_don'] = $_GET['type_don'];
        }
        if (isset($_GET['region']) && $_GET['region'] !== '') {
            $filters['region'] = $_GET['region'];
        }

        $dons = $this->model->getDonsWithFilters($filters);
        $this->loadView('dons', ['dons' => $dons, 'filters' => $filters]);
    }

    public function createDon(): void
    {
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
                    header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=created');
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

    public function editDon($id = null): void
    {
        require_once __DIR__ . '/../Model/Validation.php';

        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if (empty($id) || !is_numeric($id)) {
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=invalid_id');
            exit;
        }

        $don = $this->model->getDonById((int)$id);
        if (!$don) {
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=not_found');
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

            // upload optionnel
            if (isset($_FILES['photos']) && $_FILES['photos']['error'] !== UPLOAD_ERR_NO_FILE) {
                $fileValidation = Validation::validateFile($_FILES['photos']);
                if ($fileValidation !== true) $errors[] = $fileValidation;
            }

            if (!empty($errors)) {
                $error = implode("<br>", $errors);
                $this->loadView('updatedon', ['don' => $don, 'error' => $error]);
                return;
            }

            $data = [
                'type_don' => Validation::sanitize($_POST['type_don']),
                'quantite' => Validation::sanitize($_POST['quantite']),
                'etat_object' => Validation::sanitize($_POST['etat_object'] ?? ''),
                'region' => Validation::sanitize($_POST['region']),
                'description' => Validation::sanitize($_POST['description'] ?? '')
            ];

            if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'])) {
                $fileValidation = Validation::validateFile($_FILES['photos']);
                if ($fileValidation === true) {
                    $data['photos'] = $this->handleFileUpload($_FILES['photos']);
                } else {
                    $data['photos'] = $don['photos'];
                }
            } elseif (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
                $data['photos'] = '';
            } else {
                $data['photos'] = $don['photos'];
            }

            if ($this->model->updateDon((int)$id, $data)) {
                // ✅ Backoffice : retour sur la liste admin
                header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=updated');
                exit;
            }

            $error = "Erreur lors de la modification du don";
            $this->loadView('updatedon', ['don' => $don, 'error' => $error]);
            return;
        }

        // GET => afficher formulaire
        $this->loadView('updatedon', ['don' => $don]);
    }

    public function deleteDon($id = null): void
    {
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if (empty($id) || !is_numeric($id)) {
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=invalid_id');
            exit;
        }

        $don = $this->model->getDonById((int)$id);
        if (!$don) {
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->deleteDon((int)$id)) {
                header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=deleted');
                exit;
            }
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=error');
            exit;
        }

        $this->loadView('deletedon', ['don' => $don]);
    }

    public function viewDon($id = null): void
    {
        if (empty($id) && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if (empty($id) || !is_numeric($id)) {
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=invalid_id');
            exit;
        }

        $don = $this->model->getDonById((int)$id);
        if (!$don) {
            header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=not_found');
            exit;
        }

        $this->loadView('view_don', ['don' => $don]);
    }

    public function statistics(): void
    {
        $stats = $this->model->getDetailedStatistics();
        $recent_dons = $this->model->getAllDons();

        $this->loadView('statistics', [
            'stats' => $stats,
            'recent_dons' => array_slice($recent_dons, 0, 10)
        ]);
    }

    public function debugDons(): void
    {
        $allDons = $this->model->getAllDons();
        echo "<h2>Debug - All Donations in Database:</h2><pre>";
        print_r($allDons);
        echo "</pre>";
    }

    /* =========================
       HELPERS
    ========================== */

    private function handleFileUpload(array $file): string
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $uploadDir = __DIR__ . '/../uploads/dons/'; // plus sûr (chemin absolu)
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid('don_', true) . '_' . basename($file['name']);
        $target = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            // Chemin public stocké en DB
            return 'uploads/dons/' . $fileName;
        }

        return '';
    }

    private function loadView(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . "/../view/Backoffice/{$view}.php";

        if (file_exists($viewFile)) {
            require $viewFile;
            return;
        }

        echo "<div style='background:#fff3cd;padding:20px;border:2px solid #ffc107;margin:20px;border-radius:8px;'>";
        echo "<h3>⚠️ View File Not Found</h3>";
        echo "<p><strong>Looking for:</strong> {$viewFile}</p>";
        echo "</div>";
    }

    /* =========================
       ROUTER
    ========================== */

    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'dashboard':   $this->dashboard(); break;
            case 'dons':        $this->dons(); break;
            case 'create_don':  $this->createDon(); break;
            case 'edit_don':    $this->editDon($_GET['id'] ?? null); break;
            case 'delete_don':  $this->deleteDon($_GET['id'] ?? null); break;
            case 'view_don':    $this->viewDon($_GET['id'] ?? null); break;
            case 'statistics':  $this->statistics(); break;
            case 'debugDons':   $this->debugDons(); break;

            // ✅ si tu as encore des actions front/stripe dans une autre version,
            // on ne fait PAS de fatal : on redirige proprement
            default:
                header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=invalid_action');
                exit;
        }
    }
}

/* =========================
   BOOTSTRAP
========================= */

if (basename($_SERVER['PHP_SELF']) === 'donC.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $controller = new DonController();
    $controller->handleRequest();
}

ob_end_flush();
