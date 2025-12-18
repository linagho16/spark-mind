<?php
// Utiliser la constante de configuration si elle existe
$baseUrl = defined('BASE_URL') ? BASE_URL : '/evennement/';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Gestion des Événements'; ?></title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>📅 Gestion des Événements</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="<?php echo $baseUrl; ?>index.php" class="nav-link">Accueil</a></li>
                <li><a href="<?php echo $baseUrl; ?>index.php?action=create" class="nav-link">Nouvel Événement</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php echo $content; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Gestion des Événements. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="<?php echo $baseUrl; ?>assets/js/main.js"></script>
</body>
</html>

