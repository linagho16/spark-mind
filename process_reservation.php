<?php
session_start();

// Charger la configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Reservation.php';
require_once __DIR__ . '/models/EventModel.php';

// Connexion PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$reservation = new Reservation($pdo);
$eventModel = new EventModel($pdo);
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation
            $errors = [];
            $required = ['event_id', 'nom_client', 'email', 'telephone', 'nombre_places'];
            
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $errors[] = "Le champ " . str_replace('_', ' ', $field) . " est requis";
                }
            }

            // Validation email
            if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide";
            }

            // Récupérer l'événement
            $event = $eventModel->getEventById($_POST['event_id']);
            if (!$event) {
                $errors[] = "Événement non trouvé";
            }

            if (!empty($errors)) {
                $_SESSION['message'] = implode('<br>', $errors);
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=create_reservation');
                exit;
            }

            // Vérifier places disponibles
            $places_disponibles = $reservation->getAvailablePlaces($_POST['event_id']);
            if ($_POST['nombre_places'] > $places_disponibles) {
                $_SESSION['message'] = "Seulement $places_disponibles places disponibles !";
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=create_reservation');
                exit;
            }

            // Préparer données
            $data = [
                'event_id' => $_POST['event_id'],
                'nom_client' => trim($_POST['nom_client']),
                'email' => trim($_POST['email']),
                'telephone' => trim($_POST['telephone']),
                'nombre_places' => intval($_POST['nombre_places']),
                'montant_total' => $event['prix'] * intval($_POST['nombre_places']),
                'methode_paiement' => $_POST['methode_paiement'] ?? 'carte',
                'notes' => trim($_POST['notes'] ?? '')
            ];

            // Créer réservation
            $reservationId = $reservation->create($data);
            
            if ($reservationId) {
                $_SESSION['message'] = "✅ Réservation créée avec succès !";
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?action=reservations');
            } else {
                $_SESSION['message'] = "❌ Erreur lors de la création de la réservation";
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=create_reservation');
            }
            exit;
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            // Validation
            $errors = [];
            $required = ['event_id', 'nom_client', 'email', 'telephone', 'nombre_places', 'statut'];
            
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $errors[] = "Le champ " . str_replace('_', ' ', $field) . " est requis";
                }
            }

            // Validation email
            if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide";
            }

            if (!empty($errors)) {
                $_SESSION['message'] = implode('<br>', $errors);
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=edit_reservation&id=' . $id);
                exit;
            }

            // Préparer données
            $data = [
                'event_id' => intval($_POST['event_id']),
                'nom_client' => trim($_POST['nom_client']),
                'email' => trim($_POST['email']),
                'telephone' => trim($_POST['telephone']),
                'nombre_places' => intval($_POST['nombre_places']),
                'montant_total' => floatval($_POST['montant_total']),
                'statut' => $_POST['statut'],
                'methode_paiement' => $_POST['methode_paiement'] ?? 'carte',
                'notes' => trim($_POST['notes'] ?? '')
            ];

            // Mettre à jour la réservation
            try {
                $result = $reservation->update($id, $data);
                
                if ($result) {
                    $_SESSION['message'] = "✅ Réservation mise à jour avec succès!";
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?action=reservations');
                } else {
                    $_SESSION['message'] = "❌ Erreur lors de la mise à jour de la réservation.";
                    $_SESSION['message_type'] = 'error';
                    header('Location: index.php?action=edit_reservation&id=' . $id);
                }
            } catch (Exception $e) {
                $_SESSION['message'] = "❌ Erreur : " . $e->getMessage();
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=edit_reservation&id=' . $id);
            }
            exit;
        }
        break;

    case 'update_status':
        if ($id && isset($_GET['status'])) {
            $status = $_GET['status'];
            $allowed_status = ['en attente', 'confirmée', 'annulée'];
            
            if (in_array($status, $allowed_status)) {
                $result = $reservation->updateStatus($id, $status);
                
                if ($result) {
                    $_SESSION['message'] = "✅ Statut mis à jour avec succès!";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "❌ Erreur lors de la mise à jour du statut.";
                    $_SESSION['message_type'] = 'error';
                }
            }
            header('Location: index.php?action=reservations');
            exit;
        }
        break;

    case 'delete':
        if ($id) {
            $result = $reservation->delete($id);
            
            if ($result) {
                $_SESSION['message'] = "✅ Réservation supprimée avec succès!";
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = "❌ Erreur lors de la suppression de la réservation.";
                $_SESSION['message_type'] = 'error';
            }
            header('Location: index.php?action=reservations');
            exit;
        }
        break;

    default:
        header('Location: index.php');
        exit;
}
?>
