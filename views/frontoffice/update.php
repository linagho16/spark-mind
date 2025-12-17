<?php

require_once __DIR__ . '/../../controllers/feedbackcontroller.php';
require_once __DIR__ . '/../../models/feedback.php';


$feedbackController = new FeedbackController();

$error = '';

$feedback = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo "No ID provided.";
    exit();
}

$feedback = $feedbackController->showFeedback($id);

if (!$feedback) {
    echo "Feedback not found.";
    exit();
}

if (isset($_POST['description'])) {
    if (!empty($_POST['description'])) {
        $f = new Feedback(
            $id,
            $feedback['email'],
            $_POST['description'],
            $feedback['created_at']
        );

        if($feedbackController->updateFeedback($f, $id)){
            header('Location: index.php');
            exit();
        } else {
            $error = 'Error updating feedback';
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
    <title>Update Feedback</title>
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
            border-radius: 20px;
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
            color: #555;
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
            min-height: 150px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .info-box p {
            margin: 5px 0;
            color: #666;
            font-size: 0.9rem;
        }

        .info-box strong {
            color: #333;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
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
            <h1>✏️ Update Feedback</h1>
            <p>Edit your feedback</p>
        </div>

        <div class="form-card">
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($feedback['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($feedback['email']); ?></p>
                <p><strong>Created:</strong> <?php echo date('M d, Y g:i A', strtotime($feedback['created_at'])); ?></p>
            </div>

            <form action="" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">
                    <label for="description">Feedback Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Enter your feedback description..."
                    ><?php echo htmlspecialchars($feedback['description']); ?></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Update Feedback</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const description = document.getElementById('description').value.trim();
            if (description === '') {
                alert('Please fill in the description field');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
