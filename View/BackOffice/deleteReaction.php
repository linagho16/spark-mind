<?php
require_once __DIR__ . '/../../Controller/ReactionController.php';

$reactionC = new ReactionController();

if (isset($_GET['id'])) {
    $reactionId = $_GET['id'];
    $feedbackId = isset($_GET['feedback_id']) ? $_GET['feedback_id'] : null;
    
    if ($reactionC->deleteReaction($reactionId)) {
        // Redirect back to feedbacks index
        header('Location: index.php');
        exit();
    } else {
        echo "Error deleting reaction.";
    }
} else {
    header('Location: index.php');
    exit();
}
?>

