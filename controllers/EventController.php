<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/EventModel.php';

class EventController {
    private $model;

    public function __construct() {
        $this->model = new EventModel();
    }

    // Afficher la liste des événements
    public function index() {
        $events = $this->model->getAllEvents();
        require_once __DIR__ . '/../views/events/index.php';
    }

    // Afficher le formulaire de création
    public function create() {
        require_once __DIR__ . '/../views/events/create.php';
    }

    // Traiter la création d'un événement
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $lieu = $_POST['lieu'] ?? '';
            $prix = $_POST['prix'] ?? '';
            $date_event = $_POST['date_event'] ?? '';

            // Validation basique
            if (empty($titre) || empty($description) || empty($lieu) || empty($date_event)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
                header('Location: ' . BASE_URL . 'index.php?action=create');
                exit;
            }

            $result = $this->model->createEvent($titre, $description, $lieu, $prix, $date_event);
            
            if ($result) {
                $_SESSION['success'] = "Événement créé avec succès!";
                header('Location: ' . BASE_URL . 'index.php?action=events');
            } else {
                $_SESSION['error'] = "Erreur lors de la création de l'événement.";
                header('Location: ' . BASE_URL . 'index.php?action=create');
            }
            exit;
        }
    }

    // Afficher un événement
    public function show($id) {
        $event = $this->model->getEventById($id);
        if (!$event) {
            $_SESSION['error'] = "Événement introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=events');
            exit;
        }
        require_once __DIR__ . '/../views/events/show.php';
    }

    // Afficher le formulaire d'édition - CORRIGÉ
    public function edit($id) {
        $event = $this->model->getEventById($id);
        if (!$event) {
            $_SESSION['error'] = "Événement introuvable.";
            header('Location: ' . BASE_URL . 'index.php?action=events');
            exit;
        }
        // Passer l'événement à la vue
        require_once __DIR__ . '/../views/events/edit.php';
    }

    // Traiter la mise à jour d'un événement - CORRIGÉ
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $lieu = $_POST['lieu'] ?? '';
            $prix = $_POST['prix'] ?? '';
            $date_event = $_POST['date_event'] ?? '';

            // Validation basique
            if (empty($titre) || empty($description) || empty($lieu) || empty($date_event)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
                header('Location: ' . BASE_URL . 'index.php?action=edit&id=' . $id);
                exit;
            }

            $result = $this->model->updateEvent($id, $titre, $description, $lieu, $prix, $date_event);
            
            if ($result) {
                $_SESSION['success'] = "Événement mis à jour avec succès!";
                header('Location: ' . BASE_URL . 'index.php?action=events');
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour de l'événement.";
                header('Location: ' . BASE_URL . 'index.php?action=edit&id=' . $id);
            }
            exit;
        } else {
            // Si ce n'est pas une requête POST, rediriger
            header('Location: ' . BASE_URL . 'index.php?action=events');
            exit;
        }
    }

    // Supprimer un événement
    public function delete($id) {
        $result = $this->model->deleteEvent($id);
        
        if ($result) {
            $_SESSION['success'] = "Événement supprimé avec succès!";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de l'événement.";
        }
        
        header('Location: ' . BASE_URL . 'index.php?action=events');
        exit;
    }
}
?>