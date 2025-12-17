<?php

require_once __DIR__ . '/../../controllers/commentcontroller.php';

$commentController = new CommentController();

$error = '';

$comment = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo "No ID provided.";
    exit();
}

$comment = $commentController->getCommentById($id);

if (!$comment) {
    echo "Comment not found.";
    exit();
}

if (isset($_POST['content'])) {
    if (!empty($_POST['content'])) {
        // Admin update - no user_id check needed
        if ($commentController->updateComment($id, null, $_POST['content'])) {
            header('Location: index.php');
            exit();
        } else {
            $error = 'Error updating comment';
        }
    } else {
        $error = 'Please fill in all fields';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Comment - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 700px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1e3c72;
            font-size: 1rem;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #2a5298;
        }

        .info-box p {
            margin: 5px 0;
            color: #666;
            font-size: 0.9rem;
        }

        .info-box strong {
            color: #1e3c72;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(30, 60, 114, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 25px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Update Comment</h1>
            <p>Edit comment content</p>
        </div>

        <div class="form-card">
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($comment['username']); ?></p>
                <p><strong>Created:</strong> <?php echo date('M d, Y g:i A', strtotime($comment['created_at'])); ?></p>
                <p><strong>Comment ID:</strong> <code style="font-size: 0.85rem;"><?php echo htmlspecialchars($comment['id']); ?></code></p>
                <p><strong>Feedback ID:</strong> <code style="font-size: 0.85rem;"><?php echo htmlspecialchars($comment['feedback_id']); ?></code></p>
            </div>

            <form action="" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">
                    <label for="content">Comment Content</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        placeholder="Enter comment content..."
                    ><?php echo htmlspecialchars($comment['content']); ?></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Update Comment</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const content = document.getElementById('content').value.trim();
            if (content === '') {
                alert('Please fill in the content field');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
