<?php
require_once __DIR__ . '/../../controllers/feedbackcontroller.php';
require_once __DIR__ . '/../../models/feedback.php';


$feedbackC = new FeedbackController();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['description']) && isset($_POST['email'])){
        if(!empty($_POST['description']) && !empty($_POST['email'])){
            $feedback = new Feedback(null, $_POST['email'], $_POST['description'], null);
            
            if($feedbackC->addFeedback($feedback)){
                header('Location: index.php');
                exit();
            } else {
                $error = 'Error creating feedback. Please try again.';
            }
        } else {
            $error = 'Description and email cannot be empty.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feedback - Admin Panel</title>
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

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }

        .success-message {
            background: #efe;
            color: #3c3;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #3c3;
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

        .info-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
            font-style: italic;
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
            <h1>➕ Add New Feedback</h1>
            <p>Create a new feedback entry</p>
        </div>

        <div class="form-card">
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email"
                        id="email" 
                        name="email" 
                        placeholder="Enter email address (e.g., user@example.com)"
                        required
                    />
                    <p class="info-text">The email address of the feedback author</p>
                </div>

                <div class="form-group">
                    <label for="description">Feedback Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Enter feedback description here..."
                        required
                    ></textarea>
                    <p class="info-text">Provide detailed feedback or comments</p>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Create Feedback</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const description = document.getElementById('description').value.trim();
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
    </script>
</body>
</html>
