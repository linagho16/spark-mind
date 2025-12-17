<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Event.php';

class ReservationController {
    private $db;
    private $reservationModel;
    private $eventModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->reservationModel = new Reservation($db);
        $this->eventModel = new Event($db);
    }
    
    // Liste des réservations
    public function index() {
        $reservations = $this->reservationModel->getAll();
        $stats = $this->reservationModel->getStats();
        
        include __DIR__ . '/../views/reservations/index.php';
    }
    
    // Formulaire création
    public function create() {
        $events = $this->eventModel->getAll();
        
        // Si pas d'événements, rediriger
        if (empty($events)) {
            $_SESSION['message'] = "Vous devez d'abord créer un événement !";
            $_SESSION['message_type'] = 'warning';
            redirect('index.php?controller=event&action=create');
            return;
        }
        
        include __DIR__ . '/../views/reservations/create.php';
    }
    
    // Enregistrer nouvelle réservation
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?controller=reservation&action=create');
            return;
        }
        
        // Validation
        $errors = [];
        $required = ['event_id', 'nom_client', 'email', 'telephone', 'nombre_places'];
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = "Le champ " . $field . " est requis";
            }
        }
        
        // Calcul du montant
        $event = $this->eventModel->getById($_POST['event_id']);
        if (!$event) {
            $errors[] = "Événement non trouvé";
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('index.php?controller=reservation&action=create');
            return;
        }
        
        // Vérifier places disponibles
        $places_disponibles = $this->reservationModel->getAvailablePlaces($_POST['event_id']);
        if ($_POST['nombre_places'] > $places_disponibles) {
            $_SESSION['message'] = "Seulement $places_disponibles places disponibles !";
            $_SESSION['message_type'] = 'error';
            redirect('index.php?controller=reservation&action=create&event_id=' . $_POST['event_id']);
            return;
        }
        
        // Préparer données
        $data = [
            'event_id' => $_POST['event_id'],
            'nom_client' => $_POST['nom_client'],
            'email' => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'nombre_places' => $_POST['nombre_places'],
            'montant_total' => $event['prix'] * $_POST['nombre_places'],
            'methode_paiement' => $_POST['methode_paiement'] ?? 'carte',
            'notes' => $_POST['notes'] ?? ''
        ];
        
        // Créer réservation
        $id = $this->reservationModel->create($data);
        
        if ($id) {
            $_SESSION['message'] = "Réservation créée avec succès ! Référence: " . 
                                  $this->getReferenceFromId($id);
            $_SESSION['message_type'] = 'success';
            redirect('index.php?controller=reservation&action=show&id=' . $id);
        } else {
            $_SESSION['message'] = "Erreur lors de la création de la réservation";
            $_SESSION['message_type'] = 'error';
            redirect('index.php?controller=reservation&action=create');
        }
    }
    
    // Afficher une réservation
    public function show($id) {
        $reservation = $this->reservationModel->getById($id);
        
        if (!$reservation) {
            $_SESSION['message'] = "Réservation non trouvée";
            $_SESSION['message_type'] = 'error';
            redirect('index.php?controller=reservation');
            return;
        }
        
        include __DIR__ . '/../views/reservations/show.php';
    }
    
    // Formulaire édition
    public function edit($id) {
        $reservation = $this->reservationModel->getById($id);
        $events = $this->eventModel->getAll();
        
        if (!$reservation) {
            $_SESSION['message'] = "Réservation non trouvée";
            $_SESSION['message_type'] = 'error';
            redirect('index.php?controller=reservation');
            return;
        }
        
        include __DIR__ . '/../views/reservations/edit.php';
    }
    
    // Mettre à jour une réservation
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?controller=reservation&action=edit&id=' . $id);
            return;
        }
        
        // Récupérer l'événement pour recalculer le montant
        $event = $this->eventModel->getById($_POST['event_id']);
        
        $data = [
            'event_id' => $_POST['event_id'],
            'nom_client' => $_POST['nom_client'],
            'email' => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'nombre_places' => $_POST['nombre_places'],
            'montant_total' => $event['prix'] * $_POST['nombre_places'],
            'statut' => $_POST['statut'],
            'methode_paiement' => $_POST['methode_paiement'],
            'notes' => $_POST['notes'] ?? ''
        ];
        
        $result = $this->reservationModel->update($id, $data);
        
        if ($result) {
            $_SESSION['message'] = "Réservation mise à jour avec succès !";
            $_SESSION['message_type'] = 'success';
            redirect('index.php?controller=reservation&action=show&id=' . $id);
        } else {
            $_SESSION['message'] = "Erreur lors de la mise à jour";
            $_SESSION['message_type'] = 'error';
            redirect('index.php?controller=reservation&action=edit&id=' . $id);
        }
    }
    
    // Supprimer une réservation
    public function delete($id) {
        $result = $this->reservationModel->delete($id);
        
        if ($result) {
            $_SESSION['message'] = "Réservation supprimée avec succès";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression";
            $_SESSION['message_type'] = 'error';
        }
        
        redirect('index.php?controller=reservation');
    }
    
    // Confirmer une réservation
    public function confirm($id) {
        $result = $this->reservationModel->updateStatus($id, 'confirmée');
        
        if ($result) {
            $_SESSION['message'] = "Réservation confirmée !";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Erreur lors de la confirmation";
            $_SESSION['message_type'] = 'error';
        }
        
        redirect('index.php?controller=reservation');
    }
    
    // Annuler une réservation
    public function cancel($id) {
        $result = $this->reservationModel->updateStatus($id, 'annulée');
        
        if ($result) {
            $_SESSION['message'] = "Réservation annulée";
            $_SESSION['message_type'] = 'warning';
        } else {
            $_SESSION['message'] = "Erreur lors de l'annulation";
            $_SESSION['message_type'] = 'error';
        }
        
        redirect('index.php?controller=reservation');
    }
    
    // Méthode privée pour récupérer la référence
    private function getReferenceFromId($id) {
        $sql = "SELECT reference FROM reservations WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result['reference'] ?? '';
    }
}
?>