<?php
session_start();

// Charger la configuration
require_once __DIR__ . '/config/config.php';
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

$eventModel = new EventModel($pdo);
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titre' => trim($_POST['titre'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'lieu' => trim($_POST['lieu'] ?? ''),
                'prix' => floatval($_POST['prix'] ?? 0),
                'date_event' => $_POST['date_event'] ?? ''
            ];

            // Validation
            if (empty($data['titre']) || empty($data['description']) || empty($data['lieu']) || empty($data['date_event'])) {
                $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=create_event');
                exit;
            }

            $result = $eventModel->createEvent($data);
            
            if ($result) {
                $_SESSION['message'] = "✅ Événement créé avec succès!";
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?action=events');
            } else {
                $_SESSION['message'] = "❌ Erreur lors de la création de l'événement.";
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=create_event');
            }
            exit;
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $lieu = trim($_POST['lieu'] ?? '');
            $prix = floatval($_POST['prix'] ?? 0);
            $date_event = $_POST['date_event'] ?? '';

            // Validation
            if (empty($titre) || empty($description) || empty($lieu) || empty($date_event)) {
                $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=edit_event&id=' . $id);
                exit;
            }

            $result = $eventModel->updateEvent($id, $titre, $description, $lieu, $prix, $date_event);
            
            if ($result) {
                $_SESSION['message'] = "✅ Événement mis à jour avec succès!";
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?action=events');
            } else {
                $_SESSION['message'] = "❌ Erreur lors de la mise à jour de l'événement.";
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=edit_event&id=' . $id);
            }
            exit;
        }
        break;

    case 'delete':
        if ($id) {
            $result = $eventModel->deleteEvent($id);
            
            if ($result) {
                $_SESSION['message'] = "✅ Événement supprimé avec succès!";
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = "❌ Erreur lors de la suppression de l'événement.";
                $_SESSION['message_type'] = 'error';
            }
            header('Location: index.php?action=events');
            exit;
        }
        break;

    default:
        header('Location: index.php');
        exit;
}
?>
