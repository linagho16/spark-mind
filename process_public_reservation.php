<?php
session_start();
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: public_index.php?action=events');
    exit;
}

// Validation
$errors = [];
$required = ['event_id', 'nom_client', 'email', 'telephone', 'nombre_places', 'methode_paiement'];

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

// Vérifier places disponibles
if ($event) {
    $eventReservations = $reservation->getByEvent($_POST['event_id']);
    $placesReservees = array_sum(array_column($eventReservations, 'nombre_places'));
    $placesDisponibles = 100 - $placesReservees;
    
    if ($_POST['nombre_places'] > $placesDisponibles) {
        $errors[] = "Seulement $placesDisponibles places disponibles !";
    }
}

if (!empty($errors)) {
    $_SESSION['message'] = implode('<br>', $errors);
    $_SESSION['message_type'] = 'error';
    header('Location: public_index.php?action=book&id=' . $_POST['event_id']);
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
    'methode_paiement' => $_POST['methode_paiement'],
    'notes' => trim($_POST['notes'] ?? '')
];

// Créer réservation
$reservationId = $reservation->create($data);

if ($reservationId) {
    // Récupérer la référence
    $newReservation = $reservation->getById($reservationId);
    
    $_SESSION['message'] = "✅ Réservation confirmée ! Votre référence : <strong>" . $newReservation['reference'] . "</strong><br>
                            Un email de confirmation vous a été envoyé à <strong>" . $data['email'] . "</strong>";
    $_SESSION['message_type'] = 'success';
    header('Location: public_index.php?action=my_reservations&email=' . urlencode($data['email']));
} else {
    $_SESSION['message'] = "❌ Erreur lors de la création de la réservation. Veuillez réessayer.";
    $_SESSION['message_type'] = 'error';
    header('Location: public_index.php?action=book&id=' . $_POST['event_id']);
}
exit;
?>
