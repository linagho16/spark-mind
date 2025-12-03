<?php
// frontoffice/create_don.php - Create donation from frontoffice
session_start();
require_once __DIR__ . '/../../Model/donmodel.php';
require_once __DIR__ . '/../../Model/Validation.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $model = new DonModel();
        
        // Validate inputs
        $validationErrors = [];
        
        // Validate type
        $allowedTypes = ['Vêtements', 'Nourriture', 'Médicaments', 'Équipement', 'Argent', 'Services', 'Autre'];
        if (empty($_POST['type_don']) || !in_array($_POST['type_don'], $allowedTypes)) {
            $validationErrors[] = "Type de don invalide";
        }
        
        // Validate quantity
        if (empty($_POST['quantite']) || !is_numeric($_POST['quantite']) || $_POST['quantite'] <= 0) {
            $validationErrors[] = "Quantité invalide (doit être un nombre positif)";
        }
        
        // Validate region
        $allowedRegions = ['Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'];
        if (empty($_POST['region']) || !in_array($_POST['region'], $allowedRegions)) {
            $validationErrors[] = "Région invalide";
        }
        
        // Validate contact info
        if (empty($_POST['contact_name']) || strlen($_POST['contact_name']) < 2) {
            $validationErrors[] = "Nom de contact requis (minimum 2 caractères)";
        }
        
        if (empty($_POST['contact_email']) || !filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $validationErrors[] = "Email de contact invalide";
        }
        
        // If validation passed
        if (empty($validationErrors)) {
            // Prepare data - FrontOffice donations are 'en_attente' until admin approves
            $data = [
                'type_don' => htmlspecialchars(trim($_POST['type_don'])),
                'quantite' => (int)$_POST['quantite'],
                'etat_object' => isset($_POST['etat_object']) ? htmlspecialchars(trim($_POST['etat_object'])) : '',
                'photos' => '', // FrontOffice doesn't handle file uploads
                'region' => htmlspecialchars(trim($_POST['region'])),
                'description' => isset($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : '',
                'statut' => 'actif'
            ];
            
            // Try to save
            if ($model->createDon($data)) {
                $success = "✅ Votre don a été ajouté avec succès ! Il est maintenant visible sur le site.";
                $_POST = []; // Clear form
            } else {
                $error = "❌ Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";
            }
        } else {
            $error = "❌ " . implode("<br>❌ ", $validationErrors);
        }
        
    } catch (Exception $e) {
        $error = "❌ Erreur système: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire un Don - Aide Solidaire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #FBEDD7;
            color: #333;
            line-height: 1.6;
        }

        /* Header - Dashboard Style */
        .header {
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            color: white;
            padding: 3rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            border-radius: 0 0 20px 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .pigeon-bg {
            position: absolute;
            bottom: 20px;
            right: 5%;
            font-size: 8rem;
            opacity: 0.15;
            z-index: 1;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

        /* Form Container */
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            color: #1f8c87;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: rgba(31, 140, 135, 0.1);
            transform: translateX(-5px);
        }

        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 2.5rem;
            font-size: 2rem;
            font-weight: 700;
            position: relative;
        }

        .form-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            margin: 0.5rem auto;
            border-radius: 2px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .form-label.required::after {
            content: " *";
            color: #dc3545;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #fff;
            color: #333;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #1f8c87;
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.15);
            transform: translateY(-1px);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
            line-height: 1.5;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .form-submit {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            border: none;
            padding: 1.2rem 2.5rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 2rem;
            letter-spacing: 0.5px;
        }

        .form-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(31, 140, 135, 0.3);
        }

        /* Alerts - Dashboard Style */
        .alert {
            padding: 1.5rem 1.8rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border-left: 6px solid;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(212, 237, 218, 0.2), rgba(40, 167, 69, 0.1));
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(248, 215, 218, 0.2), rgba(220, 53, 69, 0.1));
            color: #721c24;
            border-left-color: #dc3545;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, rgba(232, 244, 253, 0.3), rgba(31, 140, 135, 0.1));
            border-radius: 15px;
            padding: 2rem;
            margin-top: 3rem;
            border-left: 5px solid #1f8c87;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .info-box h4 {
            color: #1f8c87;
            margin-bottom: 1.2rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box p {
            margin-bottom: 0.8rem;
            padding-left: 1rem;
            position: relative;
            color: #555;
        }

        .info-box p::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #1f8c87;
            font-weight: bold;
        }

        /* Select Styling */
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231f8c87' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 3rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 3rem;
        }

        .footer p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                max-width: 100%;
                padding: 0 1.5rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 2rem 1rem 3rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .form-container {
                padding: 1.8rem;
                margin: 1rem auto;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .form-title {
                font-size: 1.6rem;
            }
            
            .form-submit {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
            
            .info-box {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 1.5rem;
                margin: 0.5rem;
            }
            
            .form-input, .form-select, .form-textarea {
                padding: 0.9rem;
                font-size: 0.95rem;
            }
            
            .alert {
                padding: 1.2rem;
                font-size: 0.95rem;
            }
            
            .footer {
                padding: 2rem 1rem;
            }
        }

        /* Animation for form elements */
        .form-group {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }

        @keyframes fadeIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Input focus label effect */
        .form-group:focus-within .form-label {
            color: #1f8c87;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        /* Custom select styling */
        select option {
            padding: 1rem;
            font-size: 1rem;
        }

        /* Disabled state styling */
        .form-input:disabled,
        .form-select:disabled,
        .form-textarea:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>🎁 Faire un Don</h1>
        <p>Partagez ce que vous pouvez offrir pour aider les autres</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <!-- Form Container -->
    <main class="container">
        <div class="form-container">
            <!-- Back button -->
            <a href="index.php" class="back-link">
                <span>←</span>
                <span>Retour à l'accueil</span>
            </a>
            
            <!-- Messages -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span style="font-size: 1.5rem;">✅</span>
                    <span><?php echo $success; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <span style="font-size: 1.5rem;">❌</span>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <h2 class="form-title">Proposer votre don</h2>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Type de don</label>
                        <select name="type_don" class="form-select" required>
                            <option value="">Choisissez un type</option>
                            <option value="Vêtements" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>👕 Vêtements</option>
                            <option value="Nourriture" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>🍞 Nourriture</option>
                            <option value="Médicaments" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>💊 Médicaments</option>
                            <option value="Équipement" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Équipement' ? 'selected' : ''; ?>>🔧 Équipement</option>
                            <option value="Argent" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Argent' ? 'selected' : ''; ?>>💰 Argent</option>
                            <option value="Services" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Services' ? 'selected' : ''; ?>>🤝 Services</option>
                            <option value="Autre" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Autre' ? 'selected' : ''; ?>>🎁 Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Quantité</label>
                        <input type="number" name="quantite" class="form-input" required 
                               min="1" placeholder="Ex: 5"
                               value="<?php echo isset($_POST['quantite']) ? htmlspecialchars($_POST['quantite']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">État (si applicable)</label>
                        <select name="etat_object" class="form-select">
                            <option value="">Ne s'applique pas</option>
                            <option value="Neuf" <?php echo isset($_POST['etat_object']) && $_POST['etat_object'] == 'Neuf' ? 'selected' : ''; ?>>Neuf</option>
                            <option value="Bon état" <?php echo isset($_POST['etat_object']) && $_POST['etat_object'] == 'Bon état' ? 'selected' : ''; ?>>Bon état</option>
                            <option value="Usagé" <?php echo isset($_POST['etat_object']) && $_POST['etat_object'] == 'Usagé' ? 'selected' : ''; ?>>Usagé</option>
                            <option value="À réparer" <?php echo isset($_POST['etat_object']) && $_POST['etat_object'] == 'À réparer' ? 'selected' : ''; ?>>À réparer</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Région</label>
                        <select name="region" class="form-select" required>
                            <option value="">Sélectionnez votre région</option>
                            <option value="Tunis" <?php echo isset($_POST['region']) && $_POST['region'] == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                            <option value="Sfax" <?php echo isset($_POST['region']) && $_POST['region'] == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                            <option value="Sousse" <?php echo isset($_POST['region']) && $_POST['region'] == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                            <option value="Kairouan" <?php echo isset($_POST['region']) && $_POST['region'] == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                            <option value="Bizerte" <?php echo isset($_POST['region']) && $_POST['region'] == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                            <option value="Gabès" <?php echo isset($_POST['region']) && $_POST['region'] == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                            <option value="Ariana" <?php echo isset($_POST['region']) && $_POST['region'] == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                            <option value="Gafsa" <?php echo isset($_POST['region']) && $_POST['region'] == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                            <option value="Monastir" <?php echo isset($_POST['region']) && $_POST['region'] == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                            <option value="Autre" <?php echo isset($_POST['region']) && $_POST['region'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                        </select>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Votre nom</label>
                        <input type="text" name="contact_name" class="form-input" required 
                               placeholder="Ex: Mohamed Ali"
                               value="<?php echo isset($_POST['contact_name']) ? htmlspecialchars($_POST['contact_name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Votre email</label>
                        <input type="email" name="contact_email" class="form-input" required 
                               placeholder="exemple@email.tn"
                               value="<?php echo isset($_POST['contact_email']) ? htmlspecialchars($_POST['contact_email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description détaillée</label>
                    <textarea name="description" class="form-textarea" 
                              placeholder="Décrivez votre don, comment il peut aider, conditions de récupération..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="form-submit">
                    <span style="font-size: 1.2rem; margin-right: 0.5rem;">✅</span>
                    <span>Soumettre mon don</span>
                </button>
            </form>
            
            <div class="info-box">
    <h4><span>📝</span> Comment ça marche ?</h4>
    <p>1. Vous remplissez ce formulaire</p>
    <p>2. Votre don est immédiatement visible sur le site</p> <!-- CHANGED -->
    <p>3. Les personnes intéressées peuvent vous contacter</p>
    <p>4. Vous organisez ensemble la remise du don</p>
</div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Aide Solidaire - Merci pour votre générosité ❤️</p>
    </footer>

    <script>
        // Auto-hide messages after 10 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 1s ease';
                setTimeout(() => alert.remove(), 1000);
            });
        }, 10000);
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const quantite = document.querySelector('input[name="quantite"]').value;
            if (quantite <= 0) {
                e.preventDefault();
                alert('⚠️ La quantité doit être supérieure à 0');
                return false;
            }
            
            // Show loading state
            const submitBtn = document.querySelector('.form-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span>⏳</span> Traitement en cours...';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds if submission fails
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Add animation to form elements on load
        document.addEventListener('DOMContentLoaded', function() {
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.animationDelay = `${0.1 + (index * 0.1)}s`;
            });
        });
    </script>
</body>
</html>