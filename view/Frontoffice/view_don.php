<?php
// view/frontoffice/view_don.php - View single donation
session_start();
require_once __DIR__ . '/../../model/donmodel.php';


// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /sparkmind_mvc_100percent/index.php?page=browse_dons');

    exit;
}

$donId = (int)$_GET['id'];

try {
    $model = new DonModel();
    $don = $model->getDonById($donId);
    
    // Check if donation exists (NO STATUS CHECK - show all)
    if (!$don) {
    header('Location: /sparkmind_mvc_100percent/index.php?page=browse_dons');

    exit;
}
    
} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    $don = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Don - Aide Solidaire</title>
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

    /* ✅ Main Content */
    .space-main { 
      padding: 10px 20px 60px; 
      max-width: 1100px;
      margin: 0 auto;
    }

    /* ✅ Back Button */
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      color: #1A464F;
      background: rgba(255, 247, 239, 0.9);
      border: 1px solid rgba(26, 70, 79, 0.15);
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    .back-link:hover {
      background: rgba(26, 70, 79, 0.1);
      transform: translateX(-5px);
      border-color: rgba(26, 70, 79, 0.3);
    }

    /* ✅ Donation Details Card */
    .details-card {
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 30px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      margin-bottom: 30px;
      animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .don-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid rgba(26, 70, 79, 0.1);
    }

    .don-icon {
      font-size: 48px;
      width: 80px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .don-title h2 {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #1A464F;
      margin: 0 0 10px;
    }

    .don-meta {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    /* ✅ ID Badge */
    .id-badge {
      background: linear-gradient(135deg, var(--orange), #ffb38f);
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    /* ✅ Status Badge */
    .status-badge {
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-active {
      background: rgba(212, 237, 218, 0.3);
      color: #155724;
      border: 1px solid rgba(21, 87, 36, 0.2);
    }

    .status-pending {
      background: rgba(255, 243, 205, 0.3);
      color: #856404;
      border: 1px solid rgba(133, 100, 4, 0.2);
    }

    .status-inactif {
      background: rgba(248, 215, 218, 0.3);
      color: #721c24;
      border: 1px solid rgba(114, 28, 36, 0.2);
    }

    /* ✅ Details Grid */
    .details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 25px;
      margin-bottom: 30px;
    }

    .detail-section {
      background: white;
      border-radius: 20px;
      padding: 22px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .detail-section h3 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: #1A464F;
      margin: 0 0 18px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(26, 70, 79, 0.1);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .detail-item:last-child {
      border-bottom: none;
    }

    .detail-label {
      font-weight: 600;
      color: #1A464F;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-value {
      color: #555;
      text-align: right;
      font-size: 14px;
      font-weight: 500;
    }

    /* ✅ Description Box */
    .description-box {
      background: white;
      border-radius: 20px;
      padding: 22px;
      margin: 25px 0;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      border-left: 4px solid var(--turquoise);
    }

    .description-box h3 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: #1A464F;
      margin: 0 0 18px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(26, 70, 79, 0.1);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .description-content {
      line-height: 1.7;
      color: #555;
      font-size: 15px;
      padding: 5px 0;
    }

    /* ✅ Contact Box */
    .contact-box {
      background: rgba(232, 244, 248, 0.6);
      border-radius: 20px;
      padding: 22px;
      margin: 25px 0;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      border-left: 4px solid #17a2b8;
    }

    .contact-box h3 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: #1A464F;
      margin: 0 0 18px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(26, 70, 79, 0.1);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .contact-box p {
      color: #555;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    /* ✅ Action Buttons */
    .action-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 30px;
      padding-top: 25px;
      border-top: 1px solid rgba(26, 70, 79, 0.1);
      flex-wrap: wrap;
    }

    .action-btn {
      padding: 12px 24px;
      border-radius: 999px;
      border: none;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      font-size: 14px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      min-width: 200px;
      justify-content: center;
    }

    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .action-primary {
      background: linear-gradient(135deg, var(--turquoise), #7eddd5);
      color: white;
    }

    .action-secondary {
      background: linear-gradient(135deg, var(--violet), #b58bf0);
      color: white;
    }

    .action-tertiary {
      background: linear-gradient(135deg, var(--orange), #ffb38f);
      color: white;
    }

    /* ✅ Related Section */
    .related-section {
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 25px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      margin-top: 30px;
    }

    .related-section h3 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      color: #1A464F;
      margin: 0 0 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .related-section p {
      color: #7a6f66;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .related-links {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .related-link {
      padding: 10px 20px;
      border-radius: 999px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .related-link:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* ✅ Error State */
    .error-state {
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 40px 30px;
      text-align: center;
      box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .error-state h2 {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      color: #1A464F;
      margin: 0 0 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .error-state p {
      color: #7a6f66;
      margin-bottom: 30px;
      font-size: 16px;
      line-height: 1.6;
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
        
        .details-grid {
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
        
        .don-header {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .don-icon {
            width: 60px;
            height: 60px;
            font-size: 36px;
        }
        
        .don-title h2 {
            font-size: 22px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-btn {
            width: 100%;
        }
        
        .related-links {
            flex-direction: column;
        }
        
        .related-link {
            width: 100%;
            justify-content: center;
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
    }

    @media (max-width: 480px) {
        .space-main {
            padding: 10px 12px 30px;
        }
        
        .details-card {
            padding: 20px;
        }
        
        .detail-section {
            padding: 18px;
        }
        
        .description-box, .contact-box {
            padding: 18px;
        }
        
        .don-meta {
            flex-direction: column;
            gap: 8px;
        }
        
        .detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .detail-value {
            text-align: left;
            width: 100%;
        }
        
        .page-quote {
            font-size: 18px;
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
                <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="menu-item">
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
                Détails du don - Solidarité en action
            </div>

            <!-- Main Content -->
            <div class="space-main">
                <?php if ($don): ?>
                    <!-- Back Button -->
                    <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="back-link">
                        <span>←</span>
                        <span>Retour aux dons</span>
                    </a>
                    
                    <!-- Donation Details -->
                    <div class="details-card">
                        <!-- Header -->
                        <div class="don-header">
                            <div class="don-icon">
                                <?php 
                                $icons = [
                                    'Vêtements' => '👕',
                                    'Nourriture' => '🍞',
                                    'Médicaments' => '💊',
                                    'Équipement' => '🔧',
                                    'Argent' => '💰',
                                    'Services' => '🤝',
                                    'Autre' => '🎁'
                                ];
                                echo $icons[$don['type_don']] ?? '🎁';
                                ?>
                            </div>
                            <div class="don-title">
                                <h2><?php echo htmlspecialchars($don['type_don']); ?></h2>
                                <div class="don-meta">
                                    <span class="id-badge">#<?php echo $don['id']; ?></span>
                                    <span class="status-badge status-<?php echo $don['statut']; ?>">
                                        <?php 
                                        $statusText = [
                                            'actif' => 'Actif',
                                            'en_attente' => 'En attente',
                                            'inactif' => 'Inactif'
                                        ];
                                        echo $statusText[$don['statut']] ?? $don['statut'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Details Grid -->
                        <div class="details-grid">
                            <div class="detail-section">
                                <h3><span>📋</span> Informations de base</h3>
                                <div class="detail-item">
                                    <span class="detail-label"><span>🏷️</span> Type:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($don['type_don']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label"><span>📦</span> Quantité:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($don['quantite']); ?> unités</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label"><span>📍</span> Région:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($don['region']); ?></span>
                                </div>
                                <?php if (!empty($don['etat_object'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label"><span>⭐</span> État:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="detail-section">
                                <h3><span>📅</span> Informations temporelles</h3>
                                <div class="detail-item">
                                    <span class="detail-label"><span>📅</span> Date de création:</span>
                                    <span class="detail-value"><?php echo date('d/m/Y à H:i', strtotime($don['date_don'])); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label"><span>🔄</span> Statut:</span>
                                    <span class="detail-value">
                                        <span class="status-badge status-<?php echo $don['statut']; ?>">
                                            <?php echo $statusText[$don['statut']] ?? $don['statut']; ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label"><span>👁️</span> Visibilité:</span>
                                    <span class="detail-value">
                                        <?php echo $don['statut'] == 'actif' ? 'Public' : 'En attente de validation'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <?php if (!empty($don['description'])): ?>
                        <div class="description-box">
                            <h3><span>📝</span> Description</h3>
                            <div class="description-content">
                                <?php echo nl2br(htmlspecialchars($don['description'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Contact Information -->
                        <div class="contact-box">
                            <h3><span>📞</span> Comment contacter ?</h3>
                            <p>Les informations de contact sont protégées pour garantir la confidentialité des donateurs.</p>
                            <p>Notre équipe de coordination sert d'intermédiaire pour mettre en relation les donateurs et les bénéficiaires.</p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons&type_don=<?php echo urlencode($don['type_don']); ?>" class="action-btn action-primary">

                                <span>🔍</span>
                                <span>Voir d'autres dons similaires</span>
                            </a>
                            <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="action-btn action-secondary">
                                <span>🎁</span>
                                <span>Faire un don similaire</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Related Section -->
                    <div class="related-section">
                        <h3><span>🔗</span> Liens utiles</h3>
                        <p>Explorez d'autres dons ou découvrez notre plateforme</p>
                        <div class="related-links">
                            <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="action-btn action-tertiary">

                                <span>🎁</span>
                                <span>Parcourir tous les dons</span>
                            </a>
                            <a href="/sparkmind_mvc_100percent/index.php?page=browse_groupes" class="action-btn action-secondary">

                                <span>👥</span>
                                <span>Voir les groupes</span>
                            </a>
                            <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="action-btn action-primary">

                                <span>➕</span>
                                <span>Faire un don</span>
                            </a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Error State -->
                    <div class="error-state">
                        <h2><span>❌</span> Don non trouvé</h2>
                        <p>Le don que vous recherchez n'existe pas ou a été retiré.</p>
                        <div class="action-buttons">
                            <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="action-btn action-primary">

                                <span>🔍</span>
                                <span>Parcourir les dons</span>
                            </a>
                            <a href="/sparkmind_mvc_100percent/index.php?page=frontoffice" class="action-btn action-tertiary">

                                <span>🏠</span>
                                <span>Retour à l'accueil</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Footer -->
                <footer class="footer">
                    <p>© 2025 Aide Solidaire - Merci pour votre intérêt pour la solidarité ❤️</p>
                </footer>
            </div>
        </div>
    </div>

    <script>
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

        <?php if (isset($error)): ?>
            alert('Erreur: <?php echo $error; ?>');
        <?php endif; ?>
    </script>
</body>
</html>