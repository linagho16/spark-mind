<?php
// frontoffice/browse_dons.php - Browse all donations
session_start();
require_once __DIR__ . '/../../Model/donmodel.php';
require_once __DIR__ . '/../../Model/groupemodel.php';

try {
    $model = new DonModel();

    // Get filters from URL
    $filters = [];

    if (isset($_GET['type_don']) && !empty($_GET['type_don'])) {
        $filters['type_don'] = $_GET['type_don'];
    }

    if (isset($_GET['region']) && !empty($_GET['region'])) {
        $filters['region'] = $_GET['region'];
    }

    if (isset($_GET['groupe_id']) && !empty($_GET['groupe_id'])) {
        $filters['groupe_id'] = $_GET['groupe_id'];
    }

    // MODIFICATION IMPORTANTE ICI :
    // Pour frontoffice, on veut afficher les dons 'actif', 'en_attente' et 'payé'
    $filters['statut'] = 'frontoffice'; // Ceci utilise un filtre spécial

    // Get donations with filters
    $dons = $model->getDonsWithFiltersAndGroupes($filters);

    // Debug: Vérifier ce qui est récupéré
    error_log("Nombre de dons récupérés: " . count($dons));
    if (!empty($dons)) {
        error_log("Premier don récupéré: " . print_r($dons[0], true));
    }

    // Get unique types and regions for filters
    $allDons = $model->getAllDons();
    $types = array_unique(array_column($allDons, 'type_don'));
    $regions = array_unique(array_column($allDons, 'region'));

    // Vérifier les messages de succès
    if (isset($_GET['message'])) {
        if ($_GET['message'] == 'don_created') {
            $success_message = "✅ Votre don a été créé avec succès !";
        } elseif ($_GET['message'] == 'paiement_success') {
            $success_message = "✅ Paiement effectué avec succès ! Votre don financier est maintenant disponible.";
        }
    }

} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    error_log("Erreur dans browse_dons.php: " . $e->getMessage());
    $dons = [];
    $types = [];
    $regions = [];
}

// Définir les icônes pour les types de dons
$icons = [
    'Alimentaire' => '🍎',
    'Vêtements' => '👕',
    'Médicaments' => '💊',
    'Fournitures scolaires' => '📚',
    'Matériel médical' => '🏥',
    'Équipements sportifs' => '⚽',
    'Produits d\'hygiène' => '🚿',
    'Meubles' => '🛋️',
    'Électronique' => '💻',
    'Financier' => '💰',
    'Autre' => '🎁'
];
?>
<?php $BASE = '/sparkmind_mvc_100percent/index.php?page='; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcourir les Dons - Aide Solidaire</title>
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

    /* ✅ Hero Section pour Parcourir les Dons */
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

    /* ✅ Results Count */
    .results-count {
      text-align: center;
      margin: 20px auto 30px;
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      max-width: 1100px;
    }

    .results-count strong {
      font-size: 32px;
      color: var(--turquoise);
    }

    /* ✅ Filters Container */
    .filters-container {
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 24px 22px 26px;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .filters-title {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        color: #1A464F;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filters-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }

    .filter-select {
        padding: 10px 12px;
        border: 2px solid rgba(0,0,0,0.1);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #1A464F;
        box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .filter-btn {
        background: var(--turquoise);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 999px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-btn:hover {
        background: #2a5663;
        transform: translateY(-2px);
    }

    .reset-btn {
        background: transparent;
        color: #1A464F;
        border: 1px solid rgba(26, 70, 79, 0.35);
        padding: 10px 20px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .reset-btn:hover {
        background: rgba(26, 70, 79, 0.1);
        transform: translateY(-2px);
    }

    /* ✅ Active Filters */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: var(--turquoise);
        color: #fff;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .filter-tag button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        font-size: 16px;
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

    .card-image-container {
        height: 150px;
        overflow: hidden;
        border-radius: 12px;
        margin-bottom: 15px;
        background: rgba(26, 70, 79, 0.05);
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #1A464F;
        opacity: 0.3;
    }

    .card-header {
        margin-bottom: 15px;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        color: #1A464F;
        margin: 0 0 5px 0;
    }

    .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 13px;
        color: #7a6f66;
    }

    .card-meta span {
        display: flex;
        align-items: center;
        gap: 3px;
        padding: 4px 8px;
        background: rgba(0,0,0,0.03);
        border-radius: 12px;
    }

    .card-description {
        font-size: 14px;
        color: #555;
        line-height: 1.5;
        margin-bottom: 15px;
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .card-etat {
        background: rgba(255, 193, 7, 0.1);
        padding: 8px 12px;
        border-radius: 10px;
        margin-bottom: 15px;
        border-left: 3px solid #ffc107;
    }

    .card-etat strong {
        color: #856404;
        margin-right: 5px;
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

    /* ✅ Alert Messages */
    .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid;
        animation: slideIn 0.3s ease;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .alert-success {
        background: rgba(212, 237, 218, 0.3);
        color: #155724;
        border-left-color: #28a745;
    }

    .alert-error {
        background: rgba(248, 215, 218, 0.3);
        color: #721c24;
        border-left-color: #dc3545;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

        .filters-form {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            flex-direction: column;
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

        .filters-container,
        .recent-section {
            padding: 20px;
        }

        .btn {
            padding: 8px 12px;
            font-size: 12px;
        }

        .filter-btn,
        .reset-btn {
            padding: 8px 16px;
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
                <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="menu-item active">
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
                            <div class="brand-tagline">Parcourir les dons</div>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-orange" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=create_don'">
                        <span>➕</span>
                        <span>Créer un don</span>
                    </button>
                </div>
            </div>

            <!-- Page Quote -->
            <div class="page-quote">
                Trouvez ce dont vous avez besoin parmi nos dons disponibles
            </div>

            <!-- Main Content -->
            <div class="space-main">
                <!-- Hero Section -->
                <div class="space-hero">
                    <div class="space-content">
                        <h2 class="space-title">🔍 Explorez nos dons</h2>
                        <p class="space-text">
                            Découvrez une variété de dons généreusement offerts par notre communauté.
                            Utilisez les filtres pour trouver exactement ce que vous cherchez.
                        </p>
                    </div>
                </div>

                <!-- Messages d'alerte -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success">
                        <span style="font-size: 1.5rem;">✅</span>
                        <span><?php echo $success_message; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <span style="font-size: 1.5rem;">❌</span>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <!-- Active Filters -->
                <?php if (isset($_GET['type_don']) || isset($_GET['region']) || isset($_GET['groupe_id'])): ?>
                <div class="active-filters">
                    <?php if (isset($_GET['type_don']) && !empty($_GET['type_don'])): ?>
                    <span class="filter-tag">
                        <span>🏷️ Type: <?php echo htmlspecialchars($_GET['type_don']); ?></span>
                        <button onclick="removeFilter('type_don')">×</button>
                    </span>
                    <?php endif; ?>

                    <?php if (isset($_GET['region']) && !empty($_GET['region'])): ?>
                    <span class="filter-tag">
                        <span>📍 Région: <?php echo htmlspecialchars($_GET['region']); ?></span>
                        <button onclick="removeFilter('region')">×</button>
                    </span>
                    <?php endif; ?>

                    <?php if (isset($_GET['groupe_id']) && !empty($_GET['groupe_id'])):
                        $groupeModel = new GroupeModel();
                        $groupe = $groupeModel->getGroupeById($_GET['groupe_id']);
                    ?>
                    <span class="filter-tag">
                        <span>👥 Groupe: <?php echo htmlspecialchars($groupe['nom'] ?? 'Inconnu'); ?></span>
                        <button onclick="removeFilter('groupe_id')">×</button>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Filters -->
                <div class="filters-container">
                    <div class="filters-title">
                        <span>🔍</span>
                        <span>Filtres de recherche</span>
                    </div>

                    <form method="GET" action="/sparkmind_mvc_100percent/index.php" class="filters-form">
                        <input type="hidden" name="page" value="browse_dons">

                        <div class="filter-group">
                            <label class="filter-label">Type de don</label>
                            <select name="type_don" class="filter-select">
                                <option value="">Tous les types</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>"
                                        <?php echo isset($_GET['type_don']) && $_GET['type_don'] == $type ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Région</label>
                            <select name="region" class="filter-select">
                                <option value="">Toutes les régions</option>
                                <?php foreach ($regions as $region): ?>
                                    <option value="<?php echo htmlspecialchars($region); ?>"
                                        <?php echo isset($_GET['region']) && $_GET['region'] == $region ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($region); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Groupe</label>
                            <select name="groupe_id" class="filter-select">
                                <option value="">Tous les groupes</option>
                                <?php
                                $groupeModel = new GroupeModel();
                                $activeGroupes = $groupeModel->getGroupesWithFilters(['statut' => 'actif']);
                                foreach ($activeGroupes as $groupe): ?>
                                    <option value="<?php echo $groupe['id']; ?>"
                                        <?php echo isset($_GET['groupe_id']) && $_GET['groupe_id'] == $groupe['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($groupe['nom']); ?> (<?php echo htmlspecialchars($groupe['region']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <span>🔍</span>
                                <span>Appliquer les filtres</span>
                            </button>
                            <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="reset-btn">
                                <span>Réinitialiser</span>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Results Count -->
                <div class="results-count">
                    <strong><?php echo count($dons); ?></strong> dons trouvés
                    <?php if (isset($_GET['groupe_id']) && !empty($_GET['groupe_id'])): ?>
                        <br><small>Filtrés par groupe</small>
                    <?php endif; ?>
                </div>

                <!-- Donations Grid -->
                <section class="recent-section">
                    <div class="section-header">
                        <h2>🎁 Dons disponibles</h2>
                    </div>

                    <?php if (!empty($dons)): ?>
                        <div class="grid">
                            <?php foreach ($dons as $don): ?>
                            <div class="content-card">
                                <div class="card-image-container">

 <?php if (!empty($don['photos'])): ?>
    <?php
        // Si plusieurs images sont stockées (ex: "img1.jpg,img2.jpg"), on prend la 1ère
        $raw = trim($don['photos']);
        $raw = explode(',', $raw)[0];
        $raw = str_replace('\\', '/', trim($raw));

        // 1) Si c'est une URL complète
        if (preg_match('#^https?://#i', $raw)) {
            $photoUrl = $raw;
            $diskPath = $_SERVER['DOCUMENT_ROOT'] . parse_url($raw, PHP_URL_PATH);
        } else {
            // 2) Chemin local (relatif)
            $webPath = ($raw[0] === '/') ? $raw : '/' . $raw;

            // Si ce n'est pas déjà sous /sparkmind_mvc_100percent/
            if (!preg_match('#^/sparkmind_mvc_100percent/#', $webPath)) {

                // Cas: DB stocke "uploads/xxx.jpg" ou "/uploads/xxx.jpg"
                if (preg_match('#^/uploads/#', $webPath)) {
                    $webPath = '/sparkmind_mvc_100percent' . $webPath;

                // Cas: DB stocke juste "xxx.jpg"
                } else {
                    $webPath = '/sparkmind_mvc_100percent/uploads/' . ltrim($webPath, '/');
                }
            }

            $photoUrl = $webPath;
            $diskPath = $_SERVER['DOCUMENT_ROOT'] . $photoUrl;
        }
    ?>

    <?php if (file_exists($diskPath)): ?>
        <img
            src="<?= htmlspecialchars($photoUrl) ?>"
            alt="Image de <?= htmlspecialchars($don['type_don']) ?>"
            class="card-image"
            onerror="this.style.display='none';"
        >
    <?php else: ?>
        <div class="card-image-placeholder">
            <?php echo $icons[$don['type_don']] ?? '🎁'; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="card-image-placeholder">
        <?php echo $icons[$don['type_don']] ?? '🎁'; ?>
    </div>
<?php endif; ?>


                                </div>

                                <div class="card-header">
                                    <h3 class="card-title"><?php echo htmlspecialchars($don['type_don']); ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-meta">
                                        <span>📦 <?php echo htmlspecialchars($don['quantite']); ?> unités</span>
                                        <span>📍 <?php echo htmlspecialchars($don['region']); ?></span>
                                        <span>📅 <?php echo date('d/m/Y', strtotime($don['date_don'])); ?></span>
                                    </div>

                                    <?php if (!empty($don['groupe_nom'])): ?>
                                    <div class="card-meta" style="margin-top: 10px; margin-bottom: 10px;">
                                        <a href="/sparkmind_mvc_100percent/index.php?page=view_groupe&id=<?php echo $don['groupe_id']; ?>"
                                        style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:var(--turquoise);color:#fff;border-radius:20px;font-size:13px;font-weight:500;text-decoration:none;">
                                            <span>👥</span>
                                            <span><?php echo htmlspecialchars($don['groupe_nom']); ?></span>
                                        </a>

                                        <?php if (!empty($don['groupe_type'])): ?>
                                            <span style="background: rgba(125, 90, 166, 0.2); color: #7d5aa6; padding: 4px 8px; border-radius: 15px; font-size: 12px;">
                                                <?php echo htmlspecialchars($don['groupe_type']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($don['description'])): ?>
                                        <p class="card-description"><?php echo nl2br(htmlspecialchars($don['description'])); ?></p>
                                    <?php else: ?>
                                        <p class="card-description" style="color: #999; font-style: italic;">Aucune description fournie</p>
                                    <?php endif; ?>

                                    <?php if (!empty($don['etat_object'])): ?>
                                        <div class="card-etat">
                                            <strong>⭐ État:</strong> <?php echo htmlspecialchars($don['etat_object']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card-actions">
                                        <a href="/sparkmind_mvc_100percent/index.php?page=view_don&id=<?php echo $don['id']; ?>" class="btn btn-primary">
                                            <span>🔍</span>
                                            <span>Voir détails</span>
                                        </a>
                                        <?php if (!empty($don['groupe_id'])): ?>
                                        <a href="/sparkmind_mvc_100percent/index.php?page=view_groupe&id=<?php echo $don['groupe_id']; ?>" class="btn btn-secondary">
                                            <span>👥</span>
                                            <span>Voir le groupe</span>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>📭 Aucun don ne correspond à vos critères.</p>
                            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
                                <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="btn btn-primary" style="display: inline-flex; width: auto;">
                                    <span>🔍</span>
                                    <span>Voir tous les dons</span>
                                </a>
                                <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="btn btn-secondary" style="display: inline-flex; width: auto;">
                                    <span>➕</span>
                                    <span>Proposer un don</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Footer -->
                <footer class="footer">
                    <p>© 2025 Aide Solidaire - La solidarité en action ❤️</p>
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

            // Animate cards
            const cards = document.querySelectorAll('.content-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 800 + (index * 100));
            });

            // Auto-hide success messages
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 1000);
                });
            }, 5000);
        });

        // Remove filter functionality
        function removeFilter(filterName) {
            const url = new URL(window.location);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        }
    </script>

    <?php if (isset($error)): ?>
    <script>
        alert('Erreur: <?php echo $error; ?>');
    </script>
    <?php endif; ?>
</body>
</html>
