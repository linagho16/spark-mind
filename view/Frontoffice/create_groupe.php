<?php
// view/frontoffice/create_groupe.php - Create group from frontoffice
session_start();
require_once __DIR__ . '/../../model/groupemodel.php';
require_once __DIR__ . '/../../model/validation.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $model = new GroupeModel();
        $errors = [];
        
        // Validate inputs
        $nomValidation = Validation::validateText($_POST['nom'] ?? '', 'Nom du groupe', 3, 100);
        if ($nomValidation !== true) $errors[] = $nomValidation;
        
        $typeValidation = Validation::validateSelection($_POST['type'] ?? '', 'Type de groupe', [
            'Santé', 'Éducation', 'Seniors', 'Jeunesse', 'Culture', 
            'Urgence', 'Animaux', 'Environnement', 'Religieux', 'Social'
        ]);
        if ($typeValidation !== true) $errors[] = $typeValidation;
        
        $regionValidation = Validation::validateSelection($_POST['region'] ?? '', 'Région', [
            'Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 
            'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'
        ]);
        if ($regionValidation !== true) $errors[] = $regionValidation;
        
        $responsableValidation = Validation::validateText($_POST['responsable'] ?? '', 'Nom du responsable', 2, 100);
        if ($responsableValidation !== true) $errors[] = $responsableValidation;
        
        $emailValidation = Validation::validateEmail($_POST['email'] ?? '');
        if ($emailValidation !== true) $errors[] = $emailValidation;
        
        $telephoneValidation = Validation::validatePhone($_POST['telephone'] ?? '');
        if ($telephoneValidation !== true) $errors[] = $telephoneValidation;
        
        // Description is optional, but validate if provided
        if (!empty($_POST['description'])) {
            $descriptionValidation = Validation::validateText($_POST['description'], 'Description', 0, 1000);
            if ($descriptionValidation !== true) $errors[] = $descriptionValidation;
        }
        
        if (empty($errors)) {
            // CHANGED: FrontOffice groups are now 'actif' immediately
            $data = [
                'nom' => Validation::sanitize(trim($_POST['nom'])),
                'description' => isset($_POST['description']) ? Validation::sanitize(trim($_POST['description'])) : '',
                'type' => Validation::sanitize(trim($_POST['type'])),
                'region' => Validation::sanitize(trim($_POST['region'])),
                'responsable' => Validation::sanitize(trim($_POST['responsable'])),
                'email' => Validation::sanitize(trim($_POST['email'])),
                'telephone' => Validation::sanitize(trim($_POST['telephone'])),
                'statut' => 'actif' // CHANGED: Now immediately active
            ];
            
            // Save group
            if ($model->createGroupe($data)) {
                $success = "✅ Votre groupe a été créé avec succès ! Il est maintenant visible sur le site.";
                $_POST = []; // Clear form
            } else {
                $error = "❌ Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";
            }
        } else {
            $error = "❌ " . implode("<br>❌ ", $errors);
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
    <title>Créer un Groupe - Aide Solidaire</title>
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

        /* Main Container */
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
            color: #7d5aa6;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .back-link:hover {
            background: rgba(125, 90, 166, 0.1);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
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
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
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
            border-color: #7d5aa6;
            box-shadow: 0 0 0 3px rgba(125, 90, 166, 0.15);
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
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
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
            box-shadow: 0 8px 25px rgba(125, 90, 166, 0.3);
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
            background: linear-gradient(135deg, rgba(232, 244, 253, 0.3), rgba(125, 90, 166, 0.1));
            border-radius: 15px;
            padding: 2rem;
            margin-top: 3rem;
            border-left: 5px solid #7d5aa6;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .info-box h4 {
            color: #7d5aa6;
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
            color: #7d5aa6;
            font-weight: bold;
        }

        /* Select Styling */
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237d5aa6' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 3rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
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
            color: #7d5aa6;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>👥 Créer un Groupe</h1>
        <p>Lancez votre propre initiative solidaire et rassemblez votre communauté</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <!-- Form Container -->
    <main class="container">
        <div class="form-container">
            <!-- Back button -->
            <a href="/aide_solitaire/view/frontoffice/index.php" class="back-link">
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
            
            <h2 class="form-title">Créer votre groupe</h2>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Nom du groupe</label>
                        <input type="text" name="nom" class="form-input" required 
                               placeholder="Ex: Solidarité Tunis Nord"
                               value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Type de groupe</label>
                        <select name="type" class="form-select" required>
                            <option value="">Choisissez un type</option>
                            <option value="Santé" <?php echo isset($_POST['type']) && $_POST['type'] == 'Santé' ? 'selected' : ''; ?>>🏥 Santé</option>
                            <option value="Éducation" <?php echo isset($_POST['type']) && $_POST['type'] == 'Éducation' ? 'selected' : ''; ?>>📚 Éducation</option>
                            <option value="Seniors" <?php echo isset($_POST['type']) && $_POST['type'] == 'Seniors' ? 'selected' : ''; ?>>👵 Seniors</option>
                            <option value="Jeunesse" <?php echo isset($_POST['type']) && $_POST['type'] == 'Jeunesse' ? 'selected' : ''; ?>>👦 Jeunesse</option>
                            <option value="Culture" <?php echo isset($_POST['type']) && $_POST['type'] == 'Culture' ? 'selected' : ''; ?>>🎨 Culture</option>
                            <option value="Urgence" <?php echo isset($_POST['type']) && $_POST['type'] == 'Urgence' ? 'selected' : ''; ?>>🚨 Urgence</option>
                            <option value="Animaux" <?php echo isset($_POST['type']) && $_POST['type'] == 'Animaux' ? 'selected' : ''; ?>>🐾 Animaux</option>
                            <option value="Environnement" <?php echo isset($_POST['type']) && $_POST['type'] == 'Environnement' ? 'selected' : ''; ?>>🌿 Environnement</option>
                            <option value="Religieux" <?php echo isset($_POST['type']) && $_POST['type'] == 'Religieux' ? 'selected' : ''; ?>>🌙 Religieux</option>
                            <option value="Social" <?php echo isset($_POST['type']) && $_POST['type'] == 'Social' ? 'selected' : ''; ?>>🤝 Social</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
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
                    
                    <div class="form-group">
                        <label class="form-label required">Responsable</label>
                        <input type="text" name="responsable" class="form-input" required 
                               placeholder="Votre nom complet"
                               value="<?php echo isset($_POST['responsable']) ? htmlspecialchars($_POST['responsable']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-input" required 
                               placeholder="exemple@email.tn"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Téléphone</label>
                        <input type="tel" name="telephone" class="form-input" required 
                               placeholder="+216 XX XXX XXX"
                               value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description du groupe</label>
                    <textarea name="description" class="form-textarea" 
                              placeholder="Décrivez les objectifs, activités, et valeurs de votre groupe..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="form-submit">
                    <span style="font-size: 1.2rem; margin-right: 0.5rem;">✅</span>
                    <span>Créer le groupe</span>
                </button>
            </form>
            
            <div class="info-box">
                <h4><span>📝</span> Comment ça marche ?</h4>
                <p>1. Vous remplissez ce formulaire</p>
                <p>2. Votre groupe est immédiatement visible sur le site</p>
                <p>3. Les personnes intéressées peuvent vous contacter</p>
                <p>4. Vous gérez les inscriptions et activités de votre groupe</p>
                <p>5. Vous pouvez éditer vos informations à tout moment</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Aide Solidaire - Merci pour votre engagement communautaire ❤️</p>
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
            // Show loading state
            const submitBtn = document.querySelector('.form-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span>⏳</span> Création en cours...';
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