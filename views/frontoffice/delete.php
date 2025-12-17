<?php
require_once __DIR__ . '/../../controllers/feedbackcontroller.php';

$feedbackController = new FeedbackController();

if (isset($_GET['id'])) {
    $feedbackController->deleteFeedback($_GET['id']);
}

header('Location: index.php');
exit();
?>