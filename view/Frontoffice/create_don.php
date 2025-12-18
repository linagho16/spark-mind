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
                        header('Location: /sparkmind_mvc_100percent/index.php?page=browse_dons&message=don_created');

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
            <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="back-link">

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
                    const paymentResponse = await fetch("/sparkmind_mvc_100percent/controller/donC.php?action=create_payment_intent", {
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
                        const donResponse = await fetch("/sparkmind_mvc_100percent/controller/donC.php?action=save_don_after_payment", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
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
                                groupe_id: <?php echo isset($tempDonData['groupe_id']) && $tempDonData['groupe_id'] !== null ? $tempDonData['groupe_id'] : 'null'; ?>,
                                payment_intent_id: result.paymentIntent.id,
                                statut: "payé"
                            })
                        });
                        
                        const donResult = await donResponse.json();
                        
                        if (donResult.success) {
                            // Rediriger directement vers browse_dons
                            window.location.href = "/sparkmind_mvc_100percent/index.php?page=browse_dons&message=paiement_success&paiement_id=" + result.paymentIntent.id;

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
    <style>
    :root{
      --orange:#ec7546;
      --turquoise:#1f8c87;
      --violet:#7d5aa6;
      --bg:#FBEDD7;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
            radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
            var(--bg);
        font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        color: #1A464F;
    }

    /* ✅ Layout avec sidebar */
    .layout{
        min-height:100vh;
        display:flex;
    }

    /* ✅ Sidebar */
    .sidebar{
      width:260px;
      background:linear-gradient(#ede8deff 50%, #f7f1eb 100%);
      border-right:1px solid rgba(0,0,0,.06);
      padding:18px 14px;
      display:flex;
      flex-direction:column;
      gap:12px;
      position:sticky;
      top:0;
      height:100vh;
    }

    .sidebar .brand{
      display:flex;
      align-items:center;
      gap:10px;
      text-decoration:none;
      padding:10px 10px;
      border-radius:14px;
      color:#1A464F;
      margin-bottom: 10px;
    }

    .sidebar .logo-img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
    }

    .sidebar .brand-name{
      font-family:'Playfair Display', serif;
      font-weight:800;
      font-size:18px;
      color:#1A464F;
      text-transform: lowercase;
    }

    /* ✅ Titres sidebar : MENU PRINCIPAL */
    .menu-title {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.08em;
      color: #7a6f66;
      padding: 10px 12px 4px;
      text-transform: uppercase;
      margin-top: 8px;
    }

    .menu{
      display:flex;
      flex-direction:column;
      gap:6px;
      margin-top:6px;
    }

    .menu-item{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius:12px;
      text-decoration:none;
      color:#1A464F;
      font-weight:600;
      font-size: 14px;
    }

    .menu-item:hover{
      background:#f5e2c4ff;
    }

    .menu-item.active{
      background:#1A464F !important;
      color:#ddad56ff !important;
    }

    .sidebar-foot{
      margin-top:auto;
      padding-top:10px;
      border-top:1px solid rgba(0,0,0,.06);
    }

    .sidebar-foot .link{
      display:block;
      padding:10px 12px;
      border-radius:12px;
      text-decoration:none;
      color:#1A464F;
      font-weight:600;
      font-size: 14px;
    }

    .sidebar-foot .link:hover{
      background:#f5e2c4ff;
    }

    /* ✅ Main */
    .main{
      flex:1;
      min-width:0;
      padding: 0;
      overflow-y: auto;
    }

    /* ✅ Top Navigation */
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 24px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.03);
      animation: navFade 0.7s ease-out;
    }

    .top-nav::after{
      content:"";
      position:absolute;
      inset:auto 40px -2px 40px;
      height:2px;
      background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87);
      opacity:.35;
      border-radius:999px;
    }

    .brand-block { 
      display:flex; 
      align-items:center; 
      gap:10px; 
    }

    .logo-img {
      width: 40px; 
      height: 40px; 
      border-radius: 50%;
      object-fit: cover;
      box-shadow:0 6px 14px rgba(79, 73, 73, 0.18);
      animation: logoPop 0.6s ease-out;
    }

    .brand-text { 
      display:flex; 
      flex-direction:column; 
    }

    .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      letter-spacing: 1px;
      text-transform:uppercase;
      animation: titleGlow 2.8s ease-in-out infinite alternate;
    }

    .brand-tagline { 
      font-size: 12px; 
      color: #1A464F; 
      opacity: 0.8; 
    }

    .header-actions { 
      display:flex; 
      align-items:center; 
      gap:10px; 
    }

    @keyframes navFade { 
      from {opacity:0; transform:translateY(-16px);} 
      to {opacity:1; transform:translateY(0);} 
    }

    @keyframes logoPop{ 
      from{transform:scale(0.8) translateY(-6px); opacity:0;} 
      to{transform:scale(1) translateY(0); opacity:1;} 
    }

    @keyframes titleGlow{ 
      from{text-shadow:0 0 0 rgba(125,90,166,0.0);} 
      to{text-shadow:0 4px 16px rgba(125,90,166,0.55);} 
    }

    .btn-orange {
      background: var(--orange);
      color: #ffffff;
      border: none;
      border-radius: 999px;
      padding: 8px 18px;
      font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      position:relative;
      overflow:hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
      text-decoration: none;
    }

    .btn-orange::before{
      content:"";
      position:absolute;
      inset:0;
      background:linear-gradient(120deg,rgba(255,255,255,.35),transparent 60%);
      transform:translateX(-120%);
      transition:transform .4s ease;
    }

    .btn-orange:hover::before{ 
      transform:translateX(20%); 
    }

    .btn-orange:hover {
      transform: translateY(-2px) scale(1.03);
      filter: brightness(1.05);
      box-shadow: 0 10px 24px rgba(236, 117, 70, 0.55);
    }

    .page-quote {
      text-align: center;
      margin: 22px auto 14px auto;
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      opacity: 0.95;
      position:relative;
      animation: quoteFade 1s ease-out;
    }

    .page-quote::after{
      content:"";
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      bottom:-8px;
      width:90px;
      height:3px;
      border-radius:999px;
      background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87);
      opacity:.6;
    }

    @keyframes quoteFade{ 
      from{opacity:0; transform:translateY(-8px);} 
      to{opacity:1; transform:translateY(0);} 
    }

    /* ✅ Form Container */
    .space-main { 
      padding: 10px 20px 60px; 
      max-width: 1100px;
      margin: 0 auto;
    }

    .form-container {
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 30px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      margin-bottom: 30px;
    }

    .form-title {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #1A464F;
      margin: 0 0 25px;
      text-align: center;
      position: relative;
    }

    .form-title::after {
      content: '';
      display: block;
      width: 80px;
      height: 4px;
      background: linear-gradient(90deg, #7d5aa6, #ec7546, #1f8c87);
      margin: 10px auto;
      border-radius: 2px;
      opacity: 0.6;
    }

    /* ✅ Form Styles */
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #1A464F;
      font-size: 14px;
    }

    .form-label.required::after {
      content: " *";
      color: #ec7546;
    }

    .form-input, .form-select, .form-textarea, .form-file {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid rgba(26, 70, 79, 0.1);
      border-radius: 12px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      background: white;
      color: #1A464F;
      transition: all 0.3s ease;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus, .form-file:focus {
      outline: none;
      border-color: var(--turquoise);
      box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.15);
    }

    .form-textarea {
      min-height: 120px;
      resize: vertical;
    }

    .form-submit {
      background: linear-gradient(135deg, var(--turquoise), #7eddd5);
      color: white;
      border: none;
      padding: 14px 30px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      width: 100%;
      margin-top: 20px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
    }

    .form-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(31, 140, 135, 0.3);
    }

    /* ✅ Alert Messages */
    .alert {
      padding: 16px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      border-left: 4px solid;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .alert-error {
      background: rgba(248, 215, 218, 0.2);
      color: #721c24;
      border-left-color: #dc3545;
    }

    .alert-success {
      background: rgba(212, 237, 218, 0.2);
      color: #155724;
      border-left-color: #28a745;
    }

    /* ✅ Info Box */
    .info-box {
      background: rgba(232, 244, 253, 0.3);
      border-radius: 18px;
      padding: 25px;
      margin-top: 30px;
      border-left: 5px solid var(--turquoise);
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .info-box h4 {
      color: var(--turquoise);
      margin-bottom: 15px;
      font-size: 18px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-box p {
      margin-bottom: 8px;
      padding-left: 15px;
      position: relative;
      color: #555;
    }

    .info-box p::before {
      content: "•";
      position: absolute;
      left: 0;
      color: var(--turquoise);
      font-weight: bold;
    }

    /* ✅ Payment Section */
    .payment-section {
      background: rgba(248, 249, 250, 0.8);
      border-radius: 16px;
      padding: 20px;
      margin: 25px 0;
      border-left: 5px solid var(--turquoise);
    }

    .payment-section h3 {
      color: var(--turquoise);
      margin-bottom: 15px;
      font-size: 18px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .payment-info {
      background: rgba(232, 245, 233, 0.6);
      border-radius: 12px;
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

    

    /* ✅ Groupe Help */
    .groupe-help {
      background: rgba(232, 244, 248, 0.6);
      border-radius: 12px;
      padding: 15px;
      margin-top: 10px;
      font-size: 14px;
      color: #17a2b8;
      border-left: 4px solid #17a2b8;
    }

    /* ✅ Image Preview */
    .image-preview {
      margin-top: 10px;
      display: none;
    }

    .image-preview img {
      max-width: 200px;
      max-height: 200px;
      border-radius: 12px;
      border: 2px solid rgba(26, 70, 79, 0.1);
    }

    /* ✅ Footer */
    .footer {
      background: rgba(255, 247, 239, 0.95);
      border-top: 1px solid rgba(0,0,0,0.06);
      padding: 25px;
      margin-top: 30px;
      text-align: center;
      border-radius: 18px;
    }

    .footer p {
      margin-bottom: 15px;
      color: #1A464F;
      font-size: 14px;
    }

    /* ✅ Mobile Toggle */
    .mobile-toggle {
        display: none;
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1001;
        background: #1A464F;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
    }

    /* ✅ Responsive Design */
    @media (max-width: 900px) {
        .sidebar {
            width: 220px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .layout {
            flex-direction: column;
        }
        
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            padding: 15px;
        }
        
        .main {
            padding: 0;
        }
        
        .mobile-toggle {
            display: block;
        }
        
        .sidebar.collapsed {
            display: none;
        }
        
        .space-main {
            padding: 10px 15px 40px;
        }
        
        .form-container {
            padding: 20px;
        }
        
        .top-nav {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 15px;
        }
        
        .top-nav::after {
            inset: auto 20px -2px 20px;
        }
        
        .form-title {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .form-container {
            padding: 18px;
        }
        
        .form-title {
            font-size: 22px;
        }
        
        .form-input, .form-select, .form-textarea, .form-file {
            padding: 10px 14px;
        }
        
        .form-submit {
            padding: 12px 20px;
            font-size: 14px;
        }
        
        .page-quote {
            font-size: 18px;
        }
        
        .info-box, .payment-section {
            padding: 18px;
        }
    }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Layout Container -->
    <div class="layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <a href="/sparkmind_mvc_100percent/index.php?page=frontoffice" class="brand">

                <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo-img">
                <div class="brand-name">SPARKMIND</div>
            </a>

            <div class="menu-title">MENU PRINCIPAL</div>
            <nav class="menu">
                <a href="/sparkmind_mvc_100percent/index.php?page=frontoffice" class="menu-item">
                    <span class="icon">🏠</span>
                    <span>Accueil</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="menu-item">
                    <span class="icon">🎁</span>
                    <span>Parcourir les Dons</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=browse_groupes" class="menu-item">
                    <span class="icon">👥</span>
                    <span>Parcourir les Groupes</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="menu-item active">
                    <span class="icon">➕</span>
                    <span>Faire un Don</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=create_groupe" class="menu-item">
                    <span class="icon">✨</span>
                    <span>Créer un Groupe</span>
                </a>
            </nav>

            <div class="sidebar-foot">
                <a href="/sparkmind_mvc_100percent/index.php?page=offer_support" class="link">
                    <span class="icon"></span>
                    <span>Retour</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="top-nav-left">
                    <div class="brand-block">
                        <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo-img">
                        <div class="brand-text">
                            <div class="brand-name">SPARKMIND</div>
                            <div class="brand-tagline">Plateforme de solidarité</div>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="btn-orange">

                        <span>➕</span>
                        <span>Créer un don</span>
                    </a>
                </div>
            </div>

            <!-- Page Quote -->
            <div class="page-quote">
                Partagez votre générosité, faites la différence
            </div>

            <!-- Main Content -->
            <div class="space-main">
                <!-- Form Container -->
                <div class="form-container">
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <span style="font-size: 1.2rem;">❌</span>
                            <span><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <span style="font-size: 1.2rem;">✅</span>
                            <span><?php echo $success; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="form-title">🎁 Proposer votre don</h2>
                    
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
                        
                        <!-- Image Upload -->
                        <div class="form-group">
                            <label class="form-label">Image du don (optionnel)</label>
                            <input type="file" name="photo" id="photoInput" class="form-file" accept="image/*" onchange="previewImage(event)">
                            <small style="color: #7a6f66; display: block; margin-top: 5px;">
                                Formats acceptés: JPG, PNG, GIF, WEBP (max 5MB)
                            </small>
                            <div class="image-preview" id="imagePreview">
                                <img id="previewImage" src="#" alt="Aperçu de l'image">
                            </div>
                        </div>
                        
                        <!-- Payment Section (visible when Argent is selected) -->
                        <div class="payment-section" id="paymentSection" style="display: none;">
                            <h3>💳 Paiement en ligne</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Montant (TND)</label>
                                    <div style="position: relative;">
                                        <input type="number" name="montant" id="montantInput" class="form-input" 
                                               min="1" max="10000" step="0.01" placeholder="50.00"
                                               value="<?php echo isset($_POST['montant']) ? htmlspecialchars($_POST['montant']) : ''; ?>">
                                        <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #7a6f66; font-weight: 600;">TND</span>
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
                                <h5>🛡️ Paiement 100% sécurisé</h5>
                                <ul>
                                    <li>Processus sécurisé via Stripe (leader mondial)</li>
                                    <li>Aucune donnée bancaire stockée sur nos serveurs</li>
                                    <li>Reçu disponible par email</li>
                                </ul>
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
                        
                        <!-- Groupe Association -->
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
                            <small style="color: #7a6f66; display: block; margin-top: 5px;">
                                Vous pouvez associer ce don à un groupe existant pour une meilleure gestion
                            </small>
                            <div class="groupe-help">
                                <p><strong>💡 Pourquoi associer à un groupe ?</strong></p>
                                <p>• Le don apparaîtra dans la liste des dons du groupe</p>
                                <p>• Le groupe sera notifié de votre don</p>
                                <p>• Meilleure coordination pour la distribution</p>
                            </div>
                            <div style="margin-top: 15px; text-align: center;">
                                <a href="/sparkmind_mvc_100percent/index.php?page=create_groupe" class="btn-orange" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.2rem; background: linear-gradient(135deg, var(--violet), #b58ce0);">
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
                        <h4>📝 Comment ça marche ?</h4>
                        <p>1. Vous remplissez ce formulaire</p>
                        <p>2. Pour les dons financiers: redirection vers paiement sécurisé</p>
                        <p>3. Votre don est enregistré après confirmation</p>
                        <p>4. Votre don apparaîtra dans la liste des dons disponibles</p>
                        <p>5. Si associé à un groupe, il sera visible dans les dons du groupe</p>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="footer">
                    <p>© 2025 Aide Solidaire - Merci pour votre générosité ❤️</p>
                </footer>
            </div>
        </div>
    </div>

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
        
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
        
        // Auto-close sidebar on mobile when clicking a link
        document.querySelectorAll('.menu-item, .link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.add('collapsed');
                }
            });
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
            }
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize payment section visibility
            togglePaymentSection();
            
            // Auto-hide alerts after 8 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 1000);
                });
            }, 8000);
            
            // Show mobile toggle on small screens
            if (window.innerWidth <= 768) {
                document.querySelector('.mobile-toggle').style.display = 'block';
            }
        });
        
        // Window resize handler
        window.addEventListener('resize', function() {
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768) {
                toggle.style.display = 'block';
            } else {
                toggle.style.display = 'none';
                document.getElementById('sidebar').classList.remove('collapsed');
            }
        });
    </script>
</body>
</html>