<?php
require_once __DIR__ . '/../../Controller/FeedbackController.php';

$feedbackController = new FeedbackController();

if (isset($_GET['id'])) {
    $feedbackController->deleteFeedback($_GET['id']);
}

header('Location: index.php');
exit();
?>