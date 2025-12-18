<?php
session_start();

// ✅ comme le fichier est dans /views/public/, on remonte de 2 niveaux vers la racine
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Reservation.php';
require_once __DIR__ . '/../../models/EventModel.php';

// ✅ ici, config.php doit fournir $pdo
if (!isset($pdo)) {
    die("Erreur: la connexion PDO (\$pdo) n'est pas disponible. Vérifie config/config.php");
}

$reservation = new Reservation($pdo);
$eventModel  = new EventModel($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php?page=events_list_public');
    exit;
}

// Validation
$errors = [];
$required = ['event_id', 'nom_client', 'email', 'telephone', 'nombre_places', 'methode_paiement'];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim((string)$_POST[$field]) === '') {
        $errors[] = "Le champ " . str_replace('_', ' ', $field) . " est requis";
    }
}

// Validation email
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'adresse email n'est pas valide";
}

$eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;

// Récupérer l'événement
$event = $eventId ? $eventModel->getEventById($eventId) : null;
if (!$event) {
    $errors[] = "Événement non trouvé";
}

// Vérifier places disponibles
if ($event) {
    $nbPlaces = (int)($_POST['nombre_places'] ?? 0);

    $eventReservations  = $reservation->getByEvent($eventId);
    $placesReservees    = array_sum(array_column($eventReservations, 'nombre_places'));
    $placesDisponibles  = 100 - $placesReservees;

    if ($nbPlaces > $placesDisponibles) {
        $errors[] = "Seulement $placesDisponibles places disponibles !";
    }
    if ($nbPlaces < 1) {
        $errors[] = "Veuillez réserver au moins 1 place.";
    }
}

if (!empty($errors)) {
    $_SESSION['message'] = implode('<br>', $errors);
    $_SESSION['message_type'] = 'error';
    header('Location: ../../index.php?page=booking_form&id=' . $eventId);
    exit;
}

// Préparer données
$nbPlaces = (int)$_POST['nombre_places'];

$data = [
    'event_id'        => $eventId,
    'nom_client'      => trim($_POST['nom_client']),
    'email'           => trim($_POST['email']),
    'telephone'       => trim($_POST['telephone']),
    'nombre_places'   => $nbPlaces,
    'montant_total'   => ((float)$event['prix']) * $nbPlaces,
    'methode_paiement'=> $_POST['methode_paiement'],
    'notes'           => trim($_POST['notes'] ?? '')
];

// Créer réservation
$reservationId = $reservation->create($data);

if ($reservationId) {
    $newReservation = $reservation->getById($reservationId);

    $_SESSION['message'] =
        "✅ Réservation confirmée ! Votre référence : <strong>" . htmlspecialchars($newReservation['reference'] ?? '') . "</strong><br>" .
        "Un email de confirmation vous a été envoyé à <strong>" . htmlspecialchars($data['email']) . "</strong>";
    $_SESSION['message_type'] = 'success';

    // ✅ IMPORTANT : garder l’email en session
    $_SESSION['last_reservation_email'] = $data['email'];

    // redirection vers tes réservations (public)
    header('Location: ../../index.php?page=my_reservations&email=' . urlencode($data['email']));
    exit;
}


exit;
