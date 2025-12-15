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

    /* ✅ Page Title */
    .page-title {
        text-align: center;
        margin: 22px auto 14px auto;
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #1A464F;
        position:relative;
        animation: titleFade 1s ease-out;
    }

    .page-title::after{
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

    @keyframes titleFade{ 
      from{opacity:0; transform:translateY(-8px);} 
      to{opacity:1; transform:translateY(0);} 
    }

    /* ✅ Main Content */
    .space-main { 
      padding: 10px 20px 60px; 
    }

    /* ✅ Form Container */
    .form-container {
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 30px;
        margin: 30px auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        max-width: 800px;
        position: relative;
        overflow: hidden;
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--violet), var(--orange), var(--turquoise));
        opacity: 0.6;
    }

    /* ✅ Alerts */
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

    .alert-success {
        background: rgba(212, 237, 218, 0.2);
        color: #155724;
        border-left-color: #28a745;
    }

    .alert-error {
        background: rgba(248, 215, 218, 0.2);
        color: #721c24;
        border-left-color: #dc3545;
    }

    /* ✅ Form Title */
    .form-title {
        text-align: center;
        color: #1A464F;
        margin-bottom: 30px;
        font-size: 24px;
        font-weight: 600;
        font-family: 'Playfair Display', serif;
        position: relative;
    }

    .form-title::after {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: -10px;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--violet), var(--orange), var(--turquoise));
        opacity: 0.6;
        border-radius: 2px;
    }

    /* ✅ Form Elements */
    .form-group {
        margin-bottom: 20px;
        animation: fadeIn 0.5s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    .form-group:nth-child(6) { animation-delay: 0.6s; }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1A464F;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group:focus-within .form-label {
        color: var(--violet);
        transform: translateX(5px);
    }

    .form-label.required::after {
        content: " *";
        color: #dc3545;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid rgba(26, 70, 79, 0.1);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        background: white;
        color: #1A464F;
        font-family: 'Poppins', sans-serif;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--violet);
        box-shadow: 0 0 0 3px rgba(125, 90, 166, 0.15);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
        line-height: 1.5;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* ✅ Select Styling */
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237d5aa6' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px;
        padding-right: 3rem;
    }

    /* ✅ Submit Button */
    .form-submit {
        background: linear-gradient(135deg, var(--violet), #b58bf0);
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 999px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 25px;
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .form-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(125, 90, 166, 0.3);
    }

    .form-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* ✅ Info Box */
    .info-box {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 18px;
        padding: 25px;
        margin-top: 30px;
        border-left: 4px solid var(--violet);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .info-box h4 {
        color: var(--violet);
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
        font-size: 14px;
    }

    .info-box p::before {
        content: "•";
        position: absolute;
        left: 0;
        color: var(--violet);
        font-weight: bold;
    }

    /* ✅ Footer */
    .footer {
        background: rgba(255, 247, 239, 0.95);
        border-top: 1px solid rgba(0,0,0,0.06);
        padding: 30px 24px;
        margin-top: 30px;
        text-align: center;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 24px;
    }

    .footer p {
        margin-bottom: 20px;
        color: #1A464F;
        font-size: 16px;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 30px;
    }

    .footer-links a {
        color: #1A464F;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }

    /* ✅ Mobile Toggle Button */
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
        
        .form-container {
            padding: 25px;
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
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .form-container {
            padding: 20px;
            margin: 20px auto;
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
        
        .info-box {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .space-main {
            padding: 10px 15px 40px;
        }
        
        .form-container {
            padding: 18px;
            border-radius: 18px;
        }
        
        .form-title {
            font-size: 20px;
        }
        
        .page-title {
            font-size: 22px;
        }
        
        .form-submit {
            padding: 12px 20px;
            font-size: 14px;
        }
        
        .form-input, .form-select, .form-textarea {
            padding: 10px 14px;
            font-size: 13px;
        }
        
        .footer {
            padding: 20px;
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
            <a href="index.php" class="brand">
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
                <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Faire un Don</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=create_groupe" class="menu-item active">
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
                    <a href="browse_groupes.php" class="btn" style="background: linear-gradient(135deg, var(--violet), #b58bf0); color: white; text-decoration: none; padding: 8px 18px; border-radius: 999px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        <span>👥</span>
                        <span>Voir les groupes</span>
                    </a>
                </div>
            </div>

            <!-- Page Title -->
            <div class="page-title">
                Créer un Groupe Solidaire
            </div>

            <!-- Main Content -->
            <div class="space-main">
                <div class="form-container">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <span style="font-size: 20px;">✅</span>
                            <span><?php echo $success; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <span style="font-size: 20px;">❌</span>
                            <span><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="form-title">Créer votre groupe</h2>
                    
                    <form method="POST" action="" id="groupForm">
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
                        
                        <button type="submit" class="form-submit" id="submitBtn">
                            <span>✅</span>
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

                <!-- Footer -->
                <footer class="footer">
                    <p>© 2025 Aide Solidaire - Merci pour votre engagement communautaire ❤️</p>
                    <div class="footer-links">
                        <a href="index.php">🏠 Accueil</a>
                        <a href="../Backoffice/dashboard.php">🔒 Espace Admin</a>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

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

        // Auto-close sidebar on mobile when clicking a link
        document.querySelectorAll('.menu-item, .link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.add('collapsed');
                }
            });
        });

        // Form submission handling
        document.getElementById('groupForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span>⏳</span> Création en cours...';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds if submission fails
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Auto-remove alerts after 8 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 1s ease';
                setTimeout(() => alert.remove(), 1000);
            });
        }, 8000);

        // Animate form elements on load
        document.addEventListener('DOMContentLoaded', function() {
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.animationDelay = `${0.1 + (index * 0.1)}s`;
                group.style.opacity = '0';
                group.style.transform = 'translateY(20px)';
                group.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    group.style.opacity = '1';
                    group.style.transform = 'translateY(0)';
                }, 300 + (index * 100));
            });
        });
    </script>
</body>
</html>