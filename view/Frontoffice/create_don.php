<?php
// view/frontoffice/create_don.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Model/donmodel.php';
require_once __DIR__ . '/../../Model/groupemodel.php';
require_once __DIR__ . '/../../Model/Validation.php';

$error = '';
$success = '';
$showStripeForm = false;
$tempDonData = [];

// Charger les groupes actifs pour la liste déroulante
$groupeModel = new GroupeModel();
$groupes = $groupeModel->getGroupesWithFilters(['statut' => 'actif']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $model = new DonModel();
        
        // Validation
        $validationErrors = [];
        
        // Type
        $allowedTypes = ['Vêtements', 'Nourriture', 'Médicaments', 'Équipement', 'Argent', 'Services', 'Autre'];
        if (empty($_POST['type_don']) || !in_array($_POST['type_don'], $allowedTypes)) {
            $validationErrors[] = "Type de don invalide";
        }
        
        // Quantité (sauf Argent)
        if ($_POST['type_don'] !== 'Argent') {
            if (empty($_POST['quantite']) || !is_numeric($_POST['quantite']) || $_POST['quantite'] <= 0) {
                $validationErrors[] = "Quantité invalide (doit être un nombre positif)";
            }
        }
        
        // Région
        $allowedRegions = ['Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Autre'];
        if (empty($_POST['region']) || !in_array($_POST['region'], $allowedRegions)) {
            $validationErrors[] = "Région invalide";
        }
        
        // Contact
        if (empty($_POST['contact_name']) || strlen($_POST['contact_name']) < 2) {
            $validationErrors[] = "Nom de contact requis (minimum 2 caractères)";
        }
        
        if (empty($_POST['contact_email']) || !filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $validationErrors[] = "Email de contact invalide";
        }
        
        // Validation paiement pour Argent
        if ($_POST['type_don'] === 'Argent') {
            if (empty($_POST['montant']) || !is_numeric($_POST['montant']) || $_POST['montant'] <= 0) {
                $validationErrors[] = "Montant invalide (minimum 1 TND)";
            }
            if ($_POST['montant'] > 10000) {
                $validationErrors[] = "Montant maximum: 10,000 TND";
            }
        }
        
        // Validation image
        $photos = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                // Vérifier le type de fichier
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['photo']['tmp_name']);
                
                if (!in_array($fileType, $allowedTypes)) {
                    $validationErrors[] = "Format d'image non supporté. Utilisez JPG, PNG ou GIF.";
                }
                
                // Vérifier la taille (max 5MB)
                if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                    $validationErrors[] = "L'image ne doit pas dépasser 5MB";
                }
            } else {
                $validationErrors[] = "Erreur lors du téléchargement de l'image. Code d'erreur: " . $_FILES['photo']['error'];
            }
        }
        
        if (empty($validationErrors)) {
            // Traitement de l'upload de l'image
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../uploads/dons/';
                
                // Créer le dossier s'il n'existe pas
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Générer un nom de fichier unique
                $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('don_') . '_' . time() . '.' . $extension;
                $uploadFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                    $photos = 'uploads/dons/' . $fileName;
                    error_log("Image téléchargée avec succès: " . $photos);
                } else {
                    $validationErrors[] = "Erreur lors du téléchargement de l'image";
                    error_log("Erreur move_uploaded_file: " . print_r($_FILES['photo'], true));
                }
            }
            
            if (empty($validationErrors)) {
                // Récupérer l'ID du groupe CORRECTEMENT
                $groupe_id = isset($_POST['groupe_id']) && $_POST['groupe_id'] !== '' ? (int)$_POST['groupe_id'] : null;
                
                // DEBUG LOG
                error_log("=== DEBUG Groupe ID ===");
                error_log("POST groupe_id: " . ($_POST['groupe_id'] ?? 'non défini'));
                error_log("Valeur finale: " . ($groupe_id ?? 'NULL'));
                
                if ($_POST['type_don'] === 'Argent') {
                    // Stocker les données pour afficher le formulaire Stripe
                    $tempDonData = [
                        'montant' => $_POST['montant'],
                        'contact_name' => $_POST['contact_name'],
                        'contact_email' => $_POST['contact_email'],
                        'contact_phone' => $_POST['contact_phone'] ?? '',
                        'region' => $_POST['region'],
                        'description' => $_POST['description'] ?? '',
                        'type_don' => 'Argent',
                        'etat_object' => $_POST['etat_object'] ?? '',
                        'quantite' => 1,
                        'photos' => $photos,
                        'groupe_id' => $groupe_id  // CORRECTEMENT ASSIGNÉ
                    ];
                    
                    $_SESSION['temp_don_data'] = $tempDonData;
                    $showStripeForm = true;
                    
                } else {
                    // Pour les autres types de dons
                    $data = [
                        'type_don' => $_POST['type_don'],
                        'quantite' => $_POST['quantite'],
                        'etat_object' => $_POST['etat_object'] ?? '',
                        'photos' => $photos,
                        'region' => $_POST['region'],
                        'description' => $_POST['description'] ?? '',
                        'contact_name' => $_POST['contact_name'],
                        'contact_email' => $_POST['contact_email'],
                        'groupe_id' => $groupe_id,  // CORRECTEMENT ASSIGNÉ
                        'statut' => 'actif'
                    ];
                    
                    // DEBUG: Afficher les données avant création
                    error_log("Données à enregistrer: " . print_r($data, true));
                    
                    if ($model->createDon($data)) {
                        header('Location: /aide_solitaire/view/frontoffice/browse_dons.php?message=don_created');
                        exit;
                    } else {
                        $error = "❌ Erreur lors de l'enregistrement du don";
                    }
                }
            }
        } else {
            $error = "❌ " . implode("<br>❌ ", $validationErrors);
        }
        
    } catch (Exception $e) {
        $error = "❌ Erreur système: " . $e->getMessage();
        error_log("Erreur dans create_don.php: " . $e->getMessage());
    }
}

// Si on doit afficher le formulaire Stripe
if ($showStripeForm && !empty($tempDonData)) {
    require_once __DIR__ . '/../../config/stripe_config.php';
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Paiement Stripe - Aide Solidaire</title>
        <script src="https://js.stripe.com/v3/"></script>
        <style>
            body { 
                font-family: "Poppins", sans-serif; 
                padding: 20px; 
                background: #FBEDD7; 
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .stripe-container { 
                max-width: 500px; 
                width: 100%;
                background: white; 
                padding: 40px; 
                border-radius: 20px; 
                box-shadow: 0 8px 30px rgba(0,0,0,0.12); 
            }
            .stripe-title { 
                text-align: center; 
                color: #1f8c87; 
                margin-bottom: 30px;
                font-size: 28px;
            }
            .amount-display { 
                text-align: center; 
                font-size: 32px; 
                color: #1f8c87; 
                margin: 20px 0; 
                font-weight: bold; 
            }
            .stripe-button { 
                background: linear-gradient(135deg, #1f8c87, #7eddd5); 
                color: white; 
                border: none; 
                padding: 16px 30px; 
                border-radius: 12px; 
                font-size: 18px; 
                cursor: pointer; 
                width: 100%; 
                margin-top: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .stripe-button:hover { 
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(31, 140, 135, 0.3);
            }
            .stripe-button:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            #card-element {
                border: 2px solid #e1e5e9;
                border-radius: 12px;
                padding: 16px;
                margin: 20px 0;
                transition: all 0.3s ease;
            }
            #card-element.StripeElement--focus {
                border-color: #1f8c87;
                box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.15);
            }
            #error-message {
                color: #dc3545;
                margin: 15px 0;
                padding: 12px;
                background: #f8d7da;
                border-radius: 8px;
                border-left: 4px solid #dc3545;
                display: none;
            }
            .loading {
                text-align: center;
                margin: 20px 0;
                color: #1f8c87;
                display: none;
            }
            .payment-details {
                background: #f8f9fa;
                border-radius: 12px;
                padding: 20px;
                margin: 20px 0;
                border-left: 4px solid #1f8c87;
            }
            .test-card {
                background: #fff3cd;
                border-radius: 10px;
                padding: 15px;
                margin-top: 20px;
                border-left: 4px solid #ffc107;
            }
            .test-card h5 {
                color: #856404;
                margin-bottom: 10px;
            }
            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                color: #1f8c87;
                text-decoration: none;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 12px;
                transition: all 0.3s ease;
                margin-bottom: 20px;
            }
            .back-link:hover {
                background: rgba(31, 140, 135, 0.1);
            }
            .payment-status {
                padding: 15px;
                border-radius: 10px;
                margin: 20px 0;
                display: none;
            }
            .payment-success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .payment-error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            .groupe-info {
                background: #e8f4f8;
                border-radius: 10px;
                padding: 15px;
                margin: 15px 0;
                border-left: 4px solid #17a2b8;
            }
        </style>
    </head>
    <body>
        <div class="stripe-container">
            <a href="create_don.php" class="back-link">
                <span>←</span>
                <span>Retour au formulaire</span>
            </a>
            
            <h2 class="stripe-title">Paiement sécurisé Stripe</h2>
            
            <div class="payment-details">
                <div class="amount-display"><?php echo number_format($tempDonData['montant'], 2); ?> TND</div>
                <p style="text-align: center; color: #666; margin: 10px 0;">
                    Pour: <?php echo htmlspecialchars($tempDonData['description'] ?: 'Don solidaire'); ?>
                </p>
                <p style="text-align: center; color: #666;">
                    Donateur: <?php echo htmlspecialchars($tempDonData['contact_name']); ?>
                </p>
                <p style="text-align: center; color: #666; font-size: 14px;">
                    Email: <?php echo htmlspecialchars($tempDonData['contact_email']); ?>
                </p>
                <p style="text-align: center; color: #666; font-size: 14px;">
                    Région: <?php echo htmlspecialchars($tempDonData['region']); ?>
                </p>
                
                <?php if (!empty($tempDonData['groupe_id'])): 
                    $groupe = $groupeModel->getGroupeById($tempDonData['groupe_id']);
                    if ($groupe): ?>
                        <div class="groupe-info">
                            <p style="text-align: center; color: #17a2b8; font-weight: 600; margin-bottom: 5px;">
                                👥 Ce don sera associé au groupe:
                            </p>
                            <p style="text-align: center; color: #333;">
                                <?php echo htmlspecialchars($groupe['nom']); ?>
                            </p>
                            <p style="text-align: center; color: #666; font-size: 13px;">
                                Type: <?php echo htmlspecialchars($groupe['type']); ?> • 
                                Région: <?php echo htmlspecialchars($groupe['region']); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <?php if ($error): ?>
                <div id="error-message" style="display: block;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div id="payment-status" class="payment-status"></div>
            
            <div id="stripe-error-message" style="display: none;"></div>
            
            <form id="payment-form">
                <div id="card-element">
                    <!-- Stripe Card Element will be inserted here -->
                </div>
                
                <button type="submit" class="stripe-button" id="submit-button">
                    Payer <?php echo number_format($tempDonData['montant'], 2); ?> TND
                </button>
                
                <div class="loading" id="loading">
                    <p>⚡ Traitement en cours...</p>
                </div>
            </form>
            
            <div class="test-card">
                <h5>💳 Carte de test Stripe (pour développement)</h5>
                <p><strong>Numéro:</strong> 4242 4242 4242 4242</p>
                <p><strong>Date d'expiration:</strong> 12/34</p>
                <p><strong>CVC:</strong> 123</p>
                <p><strong>Code postal:</strong> 12345</p>
            </div>
            
            <p style="text-align: center; margin-top: 25px; color: #888; font-size: 14px;">
                🔒 Paiement 100% sécurisé par Stripe • Aucune donnée bancaire stockée
            </p>
        </div>
        
        <script>
            const stripe = Stripe("<?php echo StripeConfig::getPublicKey(); ?>");
            const elements = stripe.elements();

            const cardElement = elements.create("card", {
                style: {
                    base: {
                        fontSize: "16px",
                        color: "#32325d",
                        fontFamily: '"Poppins", sans-serif',
                        "::placeholder": {
                            color: "#aab7c4"
                        }
                    },
                    invalid: {
                        color: "#dc3545",
                        iconColor: "#dc3545"
                    }
                },
                hidePostalCode: true,
                iconStyle: 'solid'
            });

            cardElement.mount("#card-element");

            cardElement.on('change', function(event) {
                const displayError = document.getElementById('stripe-error-message');
                if (event.error) {
                    displayError.textContent = event.error.message;
                    displayError.style.display = 'block';
                } else {
                    displayError.style.display = 'none';
                }
            });

            const form = document.getElementById("payment-form");
            const submitButton = document.getElementById("submit-button");
            const loading = document.getElementById("loading");
            const errorMessage = document.getElementById("stripe-error-message");
            const paymentStatus = document.getElementById("payment-status");
            
            form.addEventListener("submit", async (event) => {
                event.preventDefault();
                
                submitButton.disabled = true;
                loading.style.display = "block";
                errorMessage.style.display = "none";
                paymentStatus.style.display = "none";
                
                try {
                    // 1. Créer le paiement Stripe
                    const paymentResponse = await fetch("/aide_solitaire/controller/donC.php?action=create_payment_intent", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            amount: <?php echo intval($tempDonData['montant'] * 100); ?>,
                            currency: "eur",
                            description: "Don solidaire",
                            email: "<?php echo addslashes($tempDonData['contact_email']); ?>",
                            name: "<?php echo addslashes($tempDonData['contact_name']); ?>"
                        })
                    });
                    
                    const paymentData = await paymentResponse.json();
                    
                    if (paymentData.error) {
                        throw new Error(paymentData.error);
                    }
                    
                    if (!paymentData.client_secret) {
                        throw new Error("Clé secrète manquante dans la réponse");
                    }
                    
                    // 2. Confirmer le paiement avec Stripe
                    const result = await stripe.confirmCardPayment(paymentData.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: "<?php echo addslashes($tempDonData['contact_name']); ?>",
                                email: "<?php echo addslashes($tempDonData['contact_email']); ?>",
                                phone: "<?php echo addslashes($tempDonData['contact_phone']); ?>"
                            }
                        }
                    });
                    
                    if (result.error) {
                        throw new Error(result.error.message);
                    }
                    
                    if (result.paymentIntent.status === "succeeded") {
                        // 3. Enregistrer le don dans la base de données
                        const donResponse = await fetch("/aide_solitaire/controller/donC.php?action=save_don_after_payment", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                           // Dans la partie save_don_after_payment du script Stripe, assurez-vous que groupe_id est envoyé :
body: JSON.stringify({
    type_don: "Argent",
    quantite: 1,
    region: "<?php echo addslashes($tempDonData['region']); ?>",
    description: "<?php echo addslashes($tempDonData['description'] ?: ''); ?>",
    contact_name: "<?php echo addslashes($tempDonData['contact_name']); ?>",
    contact_email: "<?php echo addslashes($tempDonData['contact_email']); ?>",
    contact_phone: "<?php echo addslashes($tempDonData['contact_phone']); ?>",
    montant: <?php echo $tempDonData['montant']; ?>,
    etat_object: "<?php echo addslashes($tempDonData['etat_object']); ?>",
    photos: "<?php echo addslashes($tempDonData['photos']); ?>",
    groupe_id: <?php echo isset($tempDonData['groupe_id']) && $tempDonData['groupe_id'] !== null ? $tempDonData['groupe_id'] : 'null'; ?>, // CORRECTION ICI
    payment_intent_id: result.paymentIntent.id,
    statut: "payé"
})
                        });
                        
                        const donResult = await donResponse.json();
                        
                        if (donResult.success) {
                            // Rediriger directement vers browse_dons
                            window.location.href = "/aide_solitaire/view/frontoffice/browse_dons.php?message=paiement_success&paiement_id=" + result.paymentIntent.id;
                        } else {
                            throw new Error("Erreur lors de l'enregistrement du don: " + (donResult.error || ""));
                        }
                    }
                    
                } catch (error) {
                    errorMessage.textContent = "Erreur: " + error.message;
                    errorMessage.style.display = "block";
                    submitButton.disabled = false;
                    loading.style.display = "none";
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire un Don - Aide Solidaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

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

        .form-input, .form-select, .form-textarea, .form-file {
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

        .form-file {
            padding: 0.8rem 1.2rem;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus, .form-file:focus {
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

        .payment-section {
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 5px solid #1f8c87;
        }

        .payment-info {
            background: #e8f5e9;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #4caf50;
        }

        .payment-info h5 {
            color: #2e7d32;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .payment-info ul {
            list-style: none;
            padding-left: 0;
        }

        .payment-info li {
            margin-bottom: 5px;
            color: #555;
        }

        .payment-info li:before {
            content: "✓";
            color: #4caf50;
            font-weight: bold;
            margin-right: 10px;
        }

        .footer {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 3rem;
        }

        .test-card-info {
            background: #fff3cd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #ffc107;
        }

        .test-card-info h6 {
            color: #856404;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .image-preview {
            margin-top: 10px;
            display: none;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            border: 2px solid #e1e5e9;
        }

        .groupe-help {
            background: #e8f4f8;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #17a2b8;
            border-left: 4px solid #17a2b8;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .form-container {
                padding: 1.8rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>🎁 Faire un Don</h1>
        <p>Partagez ce que vous pouvez offrir pour aider les autres</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <main class="container">
        <div class="form-container">
            <a href="index.php" class="back-link">
                <span>←</span>
                <span>Retour à l'accueil</span>
            </a>
            
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
            
            <form method="POST" action="" id="donForm" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Type de don</label>
                        <select name="type_don" id="typeDonSelect" class="form-select" required onchange="togglePaymentSection()">
                            <option value="">Choisissez un type</option>
                            <option value="Vêtements" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>👕 Vêtements</option>
                            <option value="Nourriture" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>🍞 Nourriture</option>
                            <option value="Médicaments" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>💊 Médicaments</option>
                            <option value="Équipement" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Équipement' ? 'selected' : ''; ?>>🔧 Équipement</option>
                            <option value="Argent" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Argent' ? 'selected' : ''; ?>>💰 Argent (Paiement en ligne)</option>
                            <option value="Services" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Services' ? 'selected' : ''; ?>>🤝 Services</option>
                            <option value="Autre" <?php echo isset($_POST['type_don']) && $_POST['type_don'] == 'Autre' ? 'selected' : ''; ?>>🎁 Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="quantityField">
                        <label class="form-label required">Quantité</label>
                        <input type="number" name="quantite" class="form-input" 
                               min="1" max="1000" placeholder="Ex: 5"
                               value="<?php echo isset($_POST['quantite']) ? htmlspecialchars($_POST['quantite']) : ''; ?>">
                    </div>
                </div>
                
                <!-- NOUVEAU CHAMP : Image -->
                <div class="form-group">
                    <label class="form-label">Image du don (optionnel)</label>
                    <input type="file" name="photo" id="photoInput" class="form-file" accept="image/*" onchange="previewImage(event)">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Formats acceptés: JPG, PNG, GIF, WEBP (max 5MB)
                    </small>
                    <div class="image-preview" id="imagePreview">
                        <img id="previewImage" src="#" alt="Aperçu de l'image">
                    </div>
                </div>
                
                <!-- Section Paiement (visible quand Argent est sélectionné) -->
                <div class="payment-section" id="paymentSection" style="display: none;">
                    <h3 style="color: #1f8c87; margin-bottom: 20px;">
                        <i class="fas fa-credit-card"></i> Paiement en ligne
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Montant (TND)</label>
                            <div style="position: relative;">
                                <input type="number" name="montant" id="montantInput" class="form-input" 
                                       min="1" max="10000" step="0.01" placeholder="50.00"
                                       value="<?php echo isset($_POST['montant']) ? htmlspecialchars($_POST['montant']) : ''; ?>">
                                <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #666; font-weight: 600;">TND</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Téléphone (optionnel)</label>
                            <input type="tel" name="contact_phone" class="form-input" 
                                   placeholder="+216 12 345 678"
                                   value="<?php echo isset($_POST['contact_phone']) ? htmlspecialchars($_POST['contact_phone']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="payment-info">
                        <h5><i class="fas fa-shield-alt"></i> Paiement 100% sécurisé</h5>
                        <ul>
                            <li>Processus sécurisé via Stripe (leader mondial)</li>
                            <li>Aucune donnée bancaire stockée sur nos serveurs</li>
                            <li>Reçu disponible par email</li>
                        </ul>
                    </div>
                    
                    <div class="test-card-info">
                        <h6><i class="fas fa-vial"></i> Carte de test Stripe</h6>
                        <p><strong>Carte:</strong> 4242 4242 4242 4242</p>
                        <p><strong>Date:</strong> 12/34</p>
                        <p><strong>CVC:</strong> 123</p>
                        <p><strong>Code postal:</strong> 12345</p>
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
                
                <!-- NOUVEAU CHAMP : Groupe -->
                <div class="form-group">
                    <label class="form-label">Associer à un groupe (optionnel)</label>
                    <select name="groupe_id" id="groupeSelect" class="form-select">
                        <option value="">Aucun groupe (don indépendant)</option>
                        <?php foreach ($groupes as $groupe): ?>
                            <option value="<?php echo $groupe['id']; ?>" 
                                <?php echo isset($_POST['groupe_id']) && $_POST['groupe_id'] == $groupe['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($groupe['nom']); ?> 
                                (<?php echo htmlspecialchars($groupe['type']); ?> - <?php echo htmlspecialchars($groupe['region']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Vous pouvez associer ce don à un groupe existant pour une meilleure gestion
                    </small>
                    <div class="groupe-help">
                        <p><strong>💡 Pourquoi associer à un groupe ?</strong></p>
                        <p>• Le don apparaîtra dans la liste des dons du groupe</p>
                        <p>• Le groupe sera notifié de votre don</p>
                        <p>• Meilleure coordination pour la distribution</p>
                    </div>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="create_groupe.php" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.2rem; background: linear-gradient(135deg, #7d5aa6, #b58ce0); color: white; text-decoration: none; border-radius: 10px; font-size: 0.95rem; transition: all 0.3s ease;">
                            <span>👥</span>
                            <span>Créer un nouveau groupe</span>
                        </a>
                    </div>
                </div>
                
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
                
                <button type="submit" class="form-submit" id="submitBtn">
                    <span style="font-size: 1.2rem; margin-right: 0.5rem;">✅</span>
                    <span id="submitText">Soumettre mon don</span>
                </button>
            </form>
            
            <div class="info-box">
                <h4><span>📝</span> Comment ça marche ?</h4>
                <p>1. Vous remplissez ce formulaire</p>
                <p>2. Pour les dons financiers: redirection vers paiement sécurisé</p>
                <p>3. Votre don est enregistré après confirmation</p>
                <p>4. Votre don apparaîtra dans la liste des dons disponibles</p>
                <p>5. Si associé à un groupe, il sera visible dans les dons du groupe</p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>© 2025 Aide Solidaire - Merci pour votre générosité ❤️</p>
    </footer>

    <script>
        function togglePaymentSection() {
            const typeDon = document.getElementById('typeDonSelect').value;
            const paymentSection = document.getElementById('paymentSection');
            const quantityField = document.getElementById('quantityField');
            const montantInput = document.getElementById('montantInput');
            const submitText = document.getElementById('submitText');
            
            if (typeDon === 'Argent') {
                paymentSection.style.display = 'block';
                quantityField.style.display = 'none';
                
                if (montantInput) {
                    montantInput.required = true;
                }
                
                submitText.textContent = 'Procéder au paiement sécurisé';
            } else {
                paymentSection.style.display = 'none';
                quantityField.style.display = 'block';
                
                if (montantInput) {
                    montantInput.required = false;
                }
                
                submitText.textContent = 'Soumettre mon don';
            }
        }
        
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('previewImage');
            const previewContainer = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                previewContainer.style.display = 'none';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentSection();
            
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 1000);
                });
            }, 8000);
        });
    </script>
</body>
</html>