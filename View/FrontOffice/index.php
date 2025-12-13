<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../Controller/FeedbackController.php';
require_once __DIR__ . '/../../Controller/ReactionController.php';
require_once __DIR__ . '/../../Controller/commentcontroller.php';
require_once __DIR__ . '/../../Model/Feedback.php';


$feedbackC = new FeedbackController();
$reactionC = new ReactionController();
$commentC = new CommentController();

$userEmail = isset($_POST['email']) ? $_POST['email'] : 'user@example.com'; // Default email
$authorId = '1'; // User ID from database (varchar)

if($_SERVER['REQUEST_METHOD']==='POST'){

    if(isset($_POST['description']) && isset($_POST['email'])){

        if(!empty($_POST['description']) && !empty($_POST['email'])){

            $feedback = new Feedback(null, $_POST['email'], $_POST['description'], null);

            if($feedbackC->addFeedback($feedback)){
                // Redirect to prevent form resubmission
                header('Location: index.php');
                exit();
            }

        }

    }

    if(isset($_POST['toggle_reaction']) && isset($_POST['feedback_id']) && isset($_POST['user_id'])){
        $feedback_id = $_POST['feedback_id'];
        $user_id = $_POST['user_id'];
        $reactionC->toggleReaction($feedback_id, $user_id, 'heart');
        // Redirect to prevent form resubmission
        header('Location: index.php');
        exit();
    }

    if(isset($_POST['add_comment']) && isset($_POST['feedback_id']) && !empty($_POST['comment_content'])){
        $feedback_id = $_POST['feedback_id'];
        $commentC->addComment($feedback_id, $authorId, $_POST['comment_content']);
        header('Location: index.php');
        exit();
    }

    if(isset($_POST['update_comment']) && isset($_POST['comment_id']) && !empty($_POST['comment_content'])){
        $comment_id = $_POST['comment_id'];
        $commentC->updateComment($comment_id, $authorId, $_POST['comment_content']);
        header('Location: index.php');
        exit();
    }

    if(isset($_POST['delete_comment']) && isset($_POST['comment_id'])){
        $comment_id = $_POST['comment_id'];
        $commentC->deleteComment($comment_id, $authorId);
        header('Location: index.php');
        exit();
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .feedback-form {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .feedbacks-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feedback-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .feedback-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .user-info h3 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 5px;
        }

        .user-info p {
            font-size: 0.9rem;
            color: #888;
        }

        .feedback-content {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
            white-space: pre-wrap;
        }

        .feedback-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.95rem;
            color: #666;
        }

        .action-btn:hover {
            background: #f5f5f5;
            color: #667eea;
        }

        .heart-btn {
            color: #666;
        }

        .heart-btn.liked {
            color: #e0245e;
        }

        .heart-btn.liked svg {
            fill: #e0245e;
        }

        .heart-btn svg {
            width: 20px;
            height: 20px;
            transition: all 0.2s;
        }

        .btn-group {
            margin-left: auto;
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #667eea;
            color: white;
        }

        .btn-edit:hover {
            background: #5568d3;
        }

        .btn-delete {
            background: #ff4757;
            color: white;
        }

        .btn-delete:hover {
            background: #ee3742;
        }

        .comment-section {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .comment-section.active {
            display: block;
        }

        .comment-form {
            margin-bottom: 20px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: vertical;
            margin-bottom: 10px;
        }

        .comment-form button {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .comment {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .comment-author {
            font-weight: 600;
            color: #333;
        }

        .comment-time {
            font-size: 0.85rem;
            color: #888;
        }

        .comment-content {
            color: #555;
            line-height: 1.5;
        }

        .stats-link {
            text-align: center;
            margin-top: 30px;
        }

        .stats-link a {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .stats-link a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .feedback-form {
                padding: 20px;
            }

            .feedback-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-group {
                margin-left: 0;
                width: 100%;
            }

            .btn-edit, .btn-delete {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Feedback System</h1>
            <p>Share your thoughts and help us improve</p>
        </div>

        <!-- Feedback Form -->
        <div class="feedback-form">
            <form method="POST" onsubmit="return validateFeedbackForm()">
                <div class="form-group">
                    <label for="feedback-email">Your Email</label>
                    <input 
                        type="email"
                        id="feedback-email"
                        name="email"
                        placeholder="Enter your email address"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="feedback-description">Your Feedback</label>
                    <textarea 
                        id="feedback-description"
                        name="description"
                        placeholder="Share your feedback, suggestions, or thoughts..."
                        required
                    ></textarea>
                </div>
                <button type="submit" class="submit-btn">Post Feedback</button>
            </form>
        </div>

        <!-- Feedbacks List -->
        <?php 
        $list = $feedbackC->getAllFeedbacks();
        ?>

        <div class="feedbacks-list">
            <?php foreach($list as $feedback){ 
                $reactionCount = $reactionC->getReactionCount($feedback['id'], 'heart');
                $hasReacted = $reactionC->hasUserReacted($feedback['id'], $authorId, 'heart');
                $commentCount = $commentC->getCommentCount($feedback['id']);
                $comments = $commentC->getCommentsForFeedback($feedback['id']);
            ?>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <div class="user-info">
                            <h3><?php echo htmlspecialchars($feedback['username']); ?></h3>
                            <p><?php echo htmlspecialchars($feedback['email']); ?> · <?php echo date('M d, Y g:i A', strtotime($feedback['created_at'])); ?></p>
                        </div>
                    </div>
                    <div class="feedback-content">
                        <?php echo nl2br(htmlspecialchars($feedback['description'])); ?>
                    </div>
                    <div class="feedback-actions">
                        <form method="POST" action="index.php" style="display: inline;">
                            <input type="hidden" name="toggle_reaction" value="1">
                            <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $authorId; ?>">
                            <button class="action-btn heart-btn <?php echo $hasReacted ? 'liked' : ''; ?>" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5A4.5 4.5 0 016.5 4 4.11 4.11 0 0112 6.09 4.11 4.11 0 0117.5 4 4.5 4.5 0 0122 8.5c0 3.78-3.4 6.86-8.55 11.18z"/>
                                </svg>
                                <span><?php echo $reactionCount; ?></span>
                            </button>
                        </form>
                        <button class="action-btn" type="button" onclick="toggleCommentSection('<?php echo $feedback['id']; ?>')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                                <path d="M10 9V5a7 7 0 017 7h3l-4 4-4-4h3a4 4 0 00-4-4z"/>
                            </svg>
                            <span><?php echo $commentCount; ?> Comments</span>
                        </button>
                        <div class="btn-group">
                            <form method="POST" action="update.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $feedback['id']; ?>">
                                <button type="submit" name="update" class="btn-edit">Update</button>
                            </form>
                            <a href="delete.php?id=<?php echo $feedback['id']; ?>" 
                               class="btn-delete"
                               onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</a>
                        </div>
                    </div>
                    <div class="comment-section" id="comment-section-<?php echo $feedback['id']; ?>">
                        <form method="POST" class="comment-form" onsubmit="return validateCommentForm('<?php echo $feedback['id']; ?>')">
                            <input type="hidden" name="add_comment" value="1">
                            <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                            <textarea id="comment-content-<?php echo $feedback['id']; ?>" name="comment_content" rows="3" placeholder="Write a comment..."></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                        <?php if(count($comments) > 0){ ?>
                            <div>
                                <?php foreach($comments as $comment){ ?>
                                    <div class="comment">
                                        <div class="comment-header">
                                            <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                            <span class="comment-time"><?php echo date('M d, Y g:i A', strtotime($comment['created_at'])); ?></span>
                                        </div>
                                        <div class="comment-content">
                                            <?php echo htmlspecialchars($comment['content']); ?>
                                        </div>
                                        <?php if($comment['user_id'] === $authorId){ ?>
                                            <form method="POST" style="margin-top: 10px; display: flex; gap: 8px;" onsubmit="return validateUpdateCommentForm('<?php echo $comment['id']; ?>')">
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                                <input type="hidden" name="update_comment" value="1">
                                                <input type="text" id="update-comment-<?php echo $comment['id']; ?>" name="comment_content" value="<?php echo htmlspecialchars($comment['content']); ?>" style="flex: 1; padding: 8px; border: 2px solid #e0e0e0; border-radius: 8px;">
                                                <button type="submit" class="btn-edit" style="padding: 8px 16px;">Update</button>
                                            </form>
                                            <form method="POST" style="margin-top: 5px;">
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                                <input type="hidden" name="delete_comment" value="1">
                                                <button type="submit" class="btn-delete" style="padding: 8px 16px;" onclick="return confirm('Delete this comment?')">Delete</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="stats-link">
            <a href="statistics.php">📊 View Statistics & AI Insights</a>
        </div>
    </div>

    <script>
        function toggleCommentSection(feedbackId) {
            const commentSection = document.getElementById('comment-section-' + feedbackId);
            if (commentSection) {
                commentSection.classList.toggle('active');
            }
        }
        
        function validateFeedbackForm() {
            const email = document.getElementById('feedback-email').value.trim();
            const description = document.getElementById('feedback-description').value.trim();
            if (email === '' || description === '') {
                alert('Please fill in both email and description fields');
                return false;
            }
            if (!email.includes('@')) {
                alert('Please enter a valid email address');
                return false;
            }
            return true;
        }
        
        function validateCommentForm(feedbackId) {
            const content = document.getElementById('comment-content-' + feedbackId).value.trim();
            if (content === '') {
                alert('Please fill in the comment field');
                return false;
            }
            return true;
        }
        
        function validateUpdateCommentForm(commentId) {
            const content = document.getElementById('update-comment-' + commentId).value.trim();
            if (content === '') {
                alert('Please fill in the comment field');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
