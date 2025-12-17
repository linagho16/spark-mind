<?php
require_once __DIR__ . '/../../controllers/commentcontroller.php';

$commentC = new CommentController();

if (isset($_GET['id'])) {
    $commentId = $_GET['id'];
    $feedbackId = isset($_GET['feedback_id']) ? $_GET['feedback_id'] : null;
    
    if ($commentC->deleteComment($commentId)) {
        // Redirect back to feedbacks index
        header('Location: index.php');
        exit();
    } else {
        echo "Error deleting comment.";
    }
} else {
    header('Location: index.php');
    exit();
}
?>

