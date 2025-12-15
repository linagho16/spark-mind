<?php
// frontoffice/index.php - FrontOffice Homepage with Sidebar
session_start();
require_once __DIR__ . '/../../model/donmodel.php';
require_once __DIR__ . '/../../model/groupemodel.php';

try {
    $donModel = new DonModel();
    $groupeModel = new GroupeModel();
    
    // Get donations with 'frontoffice' status filter
    $activeDons = $donModel->getDonsWithFilters(['statut' => 'frontoffice']);
    $activeGroupes = $groupeModel->getGroupesWithFilters(['statut' => 'frontoffice']);
    
    // Limit to 6 each for homepage
    $recentDons = array_slice($activeDons, 0, 6);
    $recentGroupes = array_slice($activeGroupes, 0, 6);
    
} catch (Exception $e) {
    $error = "Erreur de connexion: " . $e->getMessage();
    $recentDons = [];
    $recentGroupes = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide Solidaire - Plateforme de Don et Solidarité</title>
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

    .space-main { 
      padding: 10px 20px 60px; 
    }

    .space-hero {
      position: relative;
      overflow: hidden;
      border-radius: 24px;
      max-width: 1100px;
      margin: 10px auto 40px auto;
      box-shadow: 0 18px 40px rgba(96, 84, 84, 0.18);
      background: #f5f5f5;
    }

    .space-hero::before,
    .space-hero::after{
      content:"";
      position:absolute;
      border-radius:999px;
      filter:blur(18px);
      opacity:.55;
      mix-blend-mode:screen;
      animation: floatBlob 10s ease-in-out infinite alternate;
    }

    .space-hero::before{
      width:120px; height:120px; top:-40px; left:20px;
      background:rgba(127, 71, 192, 0.6);
    }

    .space-hero::after{
      width:160px; height:160px; bottom:-50px; right:10px;
      background:rgba(31,140,135,.7);
      animation-delay:-4s;
    }

    @keyframes floatBlob{ 
      from{transform:translateY(0) translateX(0);} 
      to{transform:translateY(16px) translateX(-8px);} 
    }

    .space-content {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: 1fr;
      gap: 18px;
      padding: 32px 30px 30px;
    }

    .space-title{
      font-family:'Playfair Display', serif;
      font-size: 30px;
      margin: 0;
      opacity: 0;
      transform: translateY(18px);
      color:#02282f !important;
    }

    .space-text{
      font-size: 17px;
      line-height: 1.7;
      margin: 0;
      max-width: 820px;
      opacity: 0;
      transform: translateY(18px);
      color:#020202 !important;
    }

    .card-row {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      margin-top: 10px;
    }

    .space-card {
      position: relative;
      flex: 1 1 260px;
      min-width: 240px;
      border-radius: 18px;
      padding: 16px 16px 14px;
      text-decoration: none;
      color: #1A464F;
      font-size: 16px;
      background: #FFF7EF;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25);
      display: flex;
      flex-direction: column;
      gap: 6px;
      overflow: hidden;
      opacity: 0;
      transform: translateY(24px) scale(0.97);
      transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, opacity 0.6s ease, transform 0.6s ease;
    }

    .space-card::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
    }

    .space-card:hover { 
      transform: translateY(-4px) scale(1.02); 
      box-shadow: 0 16px 34px rgba(0, 0, 0, 0.35); 
    }

    .space-card:hover::before{ 
      opacity:1; 
    }

    .space-card.dons { 
      background:linear-gradient(135deg,#1f8c87,#56c7c2); 
      color:#072828; 
    }

    .space-card.groupes { 
      background:linear-gradient(135deg,#7d5aa6,#b58bf0); 
      color:#1a0f22; 
    }

    .space-card.opportunities { 
      background:linear-gradient(135deg,#ec7546,#ffb38f); 
      color:#2c130d; 
    }

    .space-card strong {
      font-size: 32px;
      font-weight: 700;
    }

    .space-card span {
      font-size: 14px;
    }

    .space-card .bubble { 
      position: absolute; 
      right: 12px; 
      bottom: 10px; 
      font-size: 26px; 
      opacity: 0.85; 
    }

    /* ✅ Recent Content Sections */
    .recent-section {
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 24px 22px 26px;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        color: #1A464F;
        margin: 0;
    }

    .view-all {
        background: var(--violet);
        color: #fff;
        padding: 8px 16px;
        border-radius: 999px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .view-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(125, 90, 166, 0.45);
    }

    /* ✅ Grid Layout */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    /* ✅ Content Cards */
    .content-card {
        background: rgba(255,255,255,0.95);
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .content-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .card-icon {
        font-size: 32px;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        color: #1A464F;
        margin: 0;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #7a6f66;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }

    .card-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .card-description {
        font-size: 14px;
        color: #555;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .card-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 999px;
        border: none;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        flex: 1;
    }

    .btn-primary {
        background: var(--turquoise);
        color: #fff;
    }

    .btn-secondary {
        background: var(--orange);
        color: #fff;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* ✅ Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #7a6f66;
        background: rgba(255,255,255,0.8);
        border-radius: 18px;
        border: 2px dashed rgba(122, 111, 102, 0.3);
    }

    .empty-state p {
        font-size: 16px;
        margin-bottom: 20px;
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
        
        .grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
        
        .card-row {
            flex-direction: column;
        }
        
        .space-card {
            flex: 1 1 100%;
        }
        
        .grid {
            grid-template-columns: 1fr;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .card-actions {
            flex-direction: column;
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
        
        .footer-links {
            flex-direction: column;
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .space-main {
            padding: 10px 15px 40px;
        }
        
        .space-hero {
            border-radius: 18px;
        }
        
        .space-content {
            padding: 24px 20px 22px;
        }
        
        .space-title {
            font-size: 24px;
        }
        
        .space-text {
            font-size: 15px;
        }
        
        .recent-section {
            padding: 20px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 12px;
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
            <a href="index.php" class="brand">
                <img src="/aide_solitaire/view/frontoffice/pigeon.png" alt="Logo" class="logo-img">
                <div class="brand-name">SPARKMIND</div>
            </a>

            <div class="menu-title">MENU PRINCIPAL</div>
            <nav class="menu">
                <a href="index.php" class="menu-item active">
                    <span class="icon">🏠</span>
                    <span>Accueil</span>
                </a>
                <a href="browse_dons.php" class="menu-item">
                    <span class="icon">🎁</span>
                    <span>Parcourir les Dons</span>
                </a>
                <a href="browse_groupes.php" class="menu-item">
                    <span class="icon">👥</span>
                    <span>Parcourir les Groupes</span>
                </a>
                <a href="create_don.php" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Faire un Don</span>
                </a>
                <a href="create_groupe.php" class="menu-item">
                    <span class="icon">✨</span>
                    <span>Créer un Groupe</span>
                </a>
            </nav>

            <div class="sidebar-foot">
                <a href="../Backoffice/dashboard.php" class="link">
                    <span class="icon">🔒</span>
                    <span>Espace Admin</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="top-nav-left">
                    <div class="brand-block">
                        <img src="/aide_solitaire/view/frontoffice/pigeon.png" alt="Logo" class="logo-img">
                        <div class="brand-text">
                            <div class="brand-name">SPARKMIND</div>
                            <div class="brand-tagline">Plateforme de solidarité</div>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-orange" onclick="window.location.href='create_don.php'">
                        <span>➕</span>
                        <span>Créer un don</span>
                    </button>
                </div>
            </div>

            <!-- Page Quote -->
            <div class="page-quote">
                Partagez, donnez, et rejoignez des initiatives qui changent des vies
            </div>

            <!-- Main Content -->
            <div class="space-main">
                <!-- Hero Section -->
                <div class="space-hero">
                    <div class="space-content">
                        <h2 class="space-title">🤝 Aide Solidaire</h2>
                        <p class="space-text">
                            Ensemble, faisons la différence. Notre plateforme connecte ceux qui veulent donner 
                            avec ceux qui en ont besoin, créant ainsi une chaîne de solidarité sans frontières.
                        </p>
                        
                        <!-- Stats Cards -->
                        <div class="card-row">
                            <a href="browse_dons.php" class="space-card dons" style="animation-delay: 0.2s">
                                <strong><?php echo count($activeDons ?? []); ?></strong>
                                <span>Dons actifs</span>
                                <div class="bubble">🎁</div>
                            </a>
                            
                            <a href="browse_groupes.php" class="space-card groupes" style="animation-delay: 0.4s">
                                <strong><?php echo count($activeGroupes ?? []); ?></strong>
                                <span>Groupes actifs</span>
                                <div class="bubble">👥</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Donations -->
                <section class="recent-section">
                    <div class="section-header">
                        <h2>🎁 Dons récents</h2>
                        <a href="browse_dons.php" class="view-all">Voir tous →</a>
                    </div>
                    
                    <?php if (!empty($recentDons)): ?>
                        <div class="grid">
                            <?php foreach ($recentDons as $don): ?>
                            <div class="content-card">
                                <div class="card-header">
                                    <div class="card-icon">
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
                                    <h3 class="card-title"><?php echo htmlspecialchars($don['type_don']); ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-meta">
                                        <span>📦 <?php echo htmlspecialchars($don['quantite']); ?> unités</span>
                                        <span>📍 <?php echo htmlspecialchars($don['region']); ?></span>
                                    </div>
                                    <p class="card-description"><?php echo substr(htmlspecialchars($don['description'] ?? 'Pas de description'), 0, 100); ?>...</p>
                                    <div class="card-actions">
                                        <a href="view_don.php?id=<?php echo $don['id']; ?>" class="btn btn-primary">Voir détails</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>📭 Aucun don disponible pour le moment.</p>
                            <a href="create_don.php" class="btn btn-primary">Soyez le premier à donner</a>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Recent Groups -->
                <section class="recent-section">
                    <div class="section-header">
                        <h2>👥 Groupes récents</h2>
                        <a href="browse_groupes.php" class="view-all">Voir tous →</a>
                    </div>
                    
                    <?php if (!empty($recentGroupes)): ?>
                        <div class="grid">
                            <?php foreach ($recentGroupes as $groupe): ?>
                            <div class="content-card">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <?php 
                                        $icons = [
                                            'Santé' => '🏥',
                                            'Éducation' => '📚',
                                            'Seniors' => '👵',
                                            'Jeunesse' => '👦',
                                            'Culture' => '🎨',
                                            'Urgence' => '🚨',
                                            'Animaux' => '🐾',
                                            'Environnement' => '🌿'
                                        ];
                                        echo $icons[$groupe['type']] ?? '👥';
                                        ?>
                                    </div>
                                    <h3 class="card-title"><?php echo htmlspecialchars($groupe['nom']); ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-meta">
                                        <span>📍 <?php echo htmlspecialchars($groupe['region']); ?></span>
                                        <span>👤 <?php echo htmlspecialchars($groupe['responsable']); ?></span>
                                    </div>
                                    <p class="card-description"><?php echo substr(htmlspecialchars($groupe['description'] ?? 'Pas de description'), 0, 100); ?>...</p>
                                    <div class="card-actions">
                                        <a href="view_groupe.php?id=<?php echo $groupe['id']; ?>" class="btn btn-primary">Voir groupe</a>
                                        <a href="mailto:<?php echo htmlspecialchars($groupe['email']); ?>" class="btn btn-secondary">Contacter</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>👥 Aucun groupe disponible pour le moment.</p>
                            <a href="create_groupe.php" class="btn btn-primary">Créer le premier groupe</a>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Footer -->
                <footer class="footer">
                    <p>© 2025 Aide Solidaire - Ensemble, faisons la différence ❤️</p>
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

        // Animate elements on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate hero elements
            const spaceTitle = document.querySelector('.space-title');
            const spaceText = document.querySelector('.space-text');
            const spaceCards = document.querySelectorAll('.space-card');
            
            setTimeout(() => {
                if (spaceTitle) {
                    spaceTitle.style.opacity = '1';
                    spaceTitle.style.transform = 'translateY(0)';
                }
            }, 300);
            
            setTimeout(() => {
                if (spaceText) {
                    spaceText.style.opacity = '1';
                    spaceText.style.transform = 'translateY(0)';
                }
            }, 600);
            
            spaceCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                }, 800 + (index * 200));
            });
        });
    </script>

    <?php if (isset($error)): ?>
    <script>
        alert('Erreur: <?php echo $error; ?>');
    </script>
    <?php endif; ?>
</body>
</html>