<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des Dons - Aide Solidaire</title>
  <style>
      body {
          margin: 0;
          min-height: 100vh;
          background:
              radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
              radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
              #FBEDD7;
          font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
          color: #1A464F;
      }

      /* ✅ Layout avec sidebar */
      .layout{
          min-height:100vh;
          display:flex;
      }

      /* ✅ Sidebar (comme la capture) */
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
      }

      .sidebar .logo{
        width:42px;
        height:42px;
        border-radius:50%;
        object-fit:cover;
      }

      .sidebar .brand-name{
        font-family:'Playfair Display', serif;
        font-weight:800;
        font-size:18px;
        color:#1A464F;
        text-transform: lowercase;
      }

      /* ✅ Titres sidebar : MENU PRINCIPAL / ACTIONS RAPIDES */
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
      }

      .sidebar-foot .link:hover{
        background:#f5e2c4ff;
      }

      /* ✅ Main */
      .main{
        flex:1;
        min-width:0;
      }

      /* ✅ Header du contenu : 2 boutons à droite (comme capture) */
     /* ✅ Top Navigation - FIXED ALIGNMENT */
.top-nav {
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(14px);
    background: rgba(251, 237, 215, 0.96);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 10px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

/* Brand section on the left */
.top-nav-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

/* Right section with search and avatar */
.header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-left: auto;
}

/* ✅ Search Box - Improved Alignment */
.search-box {
    position: relative;
    width: 300px;
    min-width: 200px;
}

.search-box input {
    width: 100%;
    padding: 10px 16px;
    padding-left: 40px;
    border: 2px solid rgba(26, 70, 79, 0.1);
    border-radius: 999px;
    font-size: 14px;
    background: white;
    color: #1A464F;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #1A464F;
    box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.15);
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #7a6f66;
    pointer-events: none;
}

/* ✅ User Profile - Perfectly Aligned */
.user-profile {
    display: flex;
    align-items: center;
    position: relative;
}

/* Avatar with perfect centering */
.avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7d5aa6, #b58bf0);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.avatar:hover {
    transform: scale(1.05);
}

/* ✅ Notification Badge - Perfectly Positioned */
.notification-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: linear-gradient(135deg, #ec7546, #ffb38f);
    color: white;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(251, 237, 215, 0.96);
    z-index: 10;
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
      }

      .brand-tagline { 
        font-size: 12px; 
        color: #1A464F; 
        opacity: 0.8; 
      }

      /* ✅ Admin Main Content */
      .admin-main {
          flex: 1;
          max-width: 1100px;
          margin: 32px auto 40px;
          padding: 0 18px 30px;
      }

      /* ✅ Dashboard Header */
      .dashboard-header {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px;
          margin-bottom: 30px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .dashboard-header h1 {
          margin: 0 0 6px;
          font-family: 'Playfair Display', serif;
          font-size: 26px;
          color:#1A464F;
      }

      .dashboard-subtitle {
          font-size: 13px;
          margin-bottom: 18px;
          color: #555;
      }

      /* ✅ Buttons */
      .btn {
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
      }

      .btn-primary {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
          color: white;
      }

      .btn-secondary {
          background: transparent;
          color: #1A464F;
          border: 2px solid rgba(26, 70, 79, 0.35);
      }

      .btn-danger {
          background: linear-gradient(135deg, #dc3545, #c82333);
          color: white;
      }

      .btn-success {
          background: linear-gradient(135deg, #10b981, #059669);
          color: white;
      }

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      }

      /* ✅ Messages */
      .message-alert {
          background: rgba(255, 247, 239, 0.95);
          padding: 16px 20px;
          border-radius: 16px;
          margin-bottom: 24px;
          border-left: 4px solid;
          font-size: 14px;
          font-weight: 500;
          box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      }

      .message-success {
          background: rgba(212, 237, 218, 0.95);
          color: #155724;
          border-left-color: #28a745;
      }

      .message-error {
          background: rgba(248, 215, 218, 0.95);
          color: #721c24;
          border-left-color: #dc3545;
      }

      /* ✅ Filters Section */
      .filters-section {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px;
          margin-bottom: 24px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .filters-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 20px;
          margin-bottom: 20px;
      }

      .filter-group {
          display: flex;
          flex-direction: column;
      }

      .filter-label {
          font-weight: 600;
          color: #1A464F;
          margin-bottom: 8px;
          font-size: 13px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }

      .filter-select {
          padding: 12px 16px;
          border: 2px solid rgba(26, 70, 79, 0.1);
          border-radius: 12px;
          font-size: 14px;
          background: white;
          color: #1A464F;
          font-family: 'Poppins', sans-serif;
          transition: all 0.3s ease;
      }

      .filter-select:focus {
          outline: none;
          border-color: #1A464F;
          box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.1);
      }

      .filters-actions {
          display: flex;
          gap: 12px;
          justify-content: flex-end;
      }

      /* ✅ Stats Bar */
      .stats-bar {
          display: flex;
          justify-content: space-between;
          align-items: center;
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 20px 24px;
          margin-bottom: 24px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .total-count {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
          color: white;
          padding: 10px 20px;
          border-radius: 999px;
          font-weight: 600;
          font-size: 14px;
          display: flex;
          align-items: center;
          gap: 8px;
      }

      .export-buttons {
          display: flex;
          gap: 12px;
      }

      .btn-export {
          padding: 10px 20px;
          border-radius: 999px;
          border: none;
          font-family: 'Poppins', sans-serif;
          font-weight: 600;
          cursor: pointer;
          font-size: 13px;
          transition: all 0.3s ease;
          display: inline-flex;
          align-items: center;
          gap: 6px;
          background: rgba(255, 255, 255, 0.9);
          color: #1A464F;
          border: 2px solid rgba(26, 70, 79, 0.1);
      }

      .btn-export:hover {
          background: white;
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      }

      /* ✅ Table Container */
      .table-container {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          overflow: hidden;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      table {
          width: 100%;
          border-collapse: collapse;
          font-size: 14px;
      }

      th {
          background: rgba(26, 70, 79, 0.95);
          color: white;
          padding: 18px 20px;
          text-align: left;
          font-weight: 600;
          font-size: 13px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }

      td {
          padding: 16px 20px;
          border-bottom: 1px solid rgba(234, 226, 214, 0.8);
          color: #1A464F;
      }

      tr:hover {
          background: rgba(255, 255, 255, 0.8);
      }

      /* ✅ Badges */
      .badge {
          padding: 6px 12px;
          border-radius: 999px;
          font-size: 12px;
          font-weight: 600;
          display: inline-block;
      }

      .badge-active {
          background: rgba(32, 201, 151, 0.15);
          color: #0d805b;
      }

      .badge-pending {
          background: rgba(253, 126, 20, 0.15);
          color: #d35400;
      }

      /* ✅ Table Actions */
      .table-actions {
          display: flex;
          gap: 8px;
      }

      .btn-icon {
          padding: 8px;
          border: none;
          border-radius: 12px;
          cursor: pointer;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          font-size: 16px;
          transition: all 0.3s ease;
          width: 40px;
          height: 40px;
      }

      .btn-view {
          background: rgba(23, 162, 184, 0.1);
          color: #138496;
      }

      .btn-edit {
          background: rgba(255, 193, 7, 0.1);
          color: #d39e00;
      }

      .btn-delete {
          background: rgba(220, 53, 69, 0.1);
          color: #c82333;
      }

      .btn-icon:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      }

      .btn-view:hover {
          background: #138496;
          color: white;
      }

      .btn-edit:hover {
          background: #ffc107;
          color: #212529;
      }

      .btn-delete:hover {
          background: #dc3545;
          color: white;
      }

      /* ✅ Empty State */
      .empty-state {
          text-align: center;
          padding: 60px 20px;
          color: #7a6f66;
          background: white;
          border-radius: 0 0 24px 24px;
      }

      .empty-state .icon {
          font-size: 48px;
          margin-bottom: 16px;
          opacity: 0.7;
      }

      .empty-state h3 {
          font-family: 'Playfair Display', serif;
          font-size: 20px;
          margin-bottom: 12px;
          color: #1A464F;
      }

      .empty-state p {
          font-size: 14px;
          margin-bottom: 24px;
          color: #7a6f66;
          max-width: 400px;
          margin-left: auto;
          margin-right: auto;
      }

      /* ✅ Top Header */
      .top-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 28px;
          background: rgba(255, 247, 239, 0.95);
          padding: 24px 28px;
          border-radius: 24px;
          color: #1A464F;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .header-left h1 {
          font-family: 'Playfair Display', serif;
          font-size: 26px;
          margin-bottom: 6px;
          font-weight: 700;
      }

      .header-left p {
          font-size: 14px;
          color: #7a6f66;
      }

      .header-right {
          display: flex;
          align-items: center;
          gap: 20px;
      }

      /* ✅ Mobile Responsive Design */
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
          
          .sidebar.collapsed {
              display: none;
          }
          
          .top-header {
              flex-direction: column;
              gap: 16px;
              text-align: center;
          }
          
          .header-right {
              flex-direction: column;
              gap: 12px;
          }
          
          .filters-grid {
              grid-template-columns: 1fr;
          }
          
          .filters-actions {
              justify-content: center;
          }
          
          .stats-bar {
              flex-direction: column;
              gap: 16px;
          }
          
          .export-buttons {
              flex-direction: column;
          }
          
          .table-actions {
              flex-direction: column;
          }
          
          .btn-icon {
              width: 35px;
              height: 35px;
          }
          
          table {
              display: block;
              overflow-x: auto;
          }
          
          th, td {
              padding: 12px 16px;
          }
      }

      @media (max-width: 480px) {
          .admin-main {
              padding: 0 15px 20px;
          }
          
          .top-header,
          .filters-section,
          .stats-bar,
          .table-container {
              padding: 20px;
              border-radius: 18px;
          }
          
          .btn {
              padding: 10px 16px;
              font-size: 13px;
          }
      }
  </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()" style="display: none; position: fixed; top: 10px; left: 10px; z-index: 1001; background: #1A464F; color: #fff; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">☰</button>

    <!-- ✅ Layout avec sidebar -->
    <div class="layout">
        <!-- ✅ Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="brand">
                <img src="/aide_solitaire/view/frontoffice/pigeon.png" alt="Logo" class="logo">
                <div class="brand-name">SPARKMIND</div>
            </a>

            <div class="menu-title">MENU PRINCIPAL</div>
            <nav class="menu">
                <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="menu-item">
                    <span class="icon">📊</span>
                    <span>Tableau de bord</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES DONS</div>
            <nav class="menu">
                <a href="/aide_solitaire/controller/donC.php?action=dons" class="menu-item active">
                    <span class="icon">🎁</span>
                    <span>Tous les dons</span>
                </a>
                
                <a href="/aide_solitaire/controller/donC.php?action=create_don" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Ajouter un don</span>
                </a>
                
                <a href="/aide_solitaire/controller/donC.php?action=statistics" class="menu-item">
                    <span class="icon">📈</span>
                    <span>Statistiques dons</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES GROUPES</div>
            <nav class="menu">
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="menu-item">
                    <span class="icon">👥</span>
                    <span>Tous les groupes</span>
                </a>
                
                <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Ajouter un groupe</span>
                </a>
            </nav>

            <div class="sidebar-foot">
                <a href="/aide_solitaire/view/frontoffice/index.php" class="link">
                    <span class="icon">🌐</span>
                    <span>Voir le site public</span>
                </a>
            </div>
        </aside>

        <!-- ✅ Main Content Area -->
        <div class="main">
            <!-- ✅ Top Navigation -->
            <div class="top-nav">
                <div class="top-nav-left">
                    <div class="brand-block">
                        <img src="/aide_solitaire/view/frontoffice/pigeon.png" alt="Logo" class="logo-img">
                        <div class="brand-text">
                            <div class="brand-name">SPARKMIND</div>
                            <div class="brand-tagline">Administration</div>
                        </div>
                    </div>
                </div>
                
                <div class="header-actions">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Rechercher...">
                    </div>
                    
                    <div class="user-profile">
                        <span class="notification-badge">3</span>
                        <div class="avatar">👤</div>
                    </div>
                </div>
            </div>

            <!-- ✅ Main Content -->
            <div class="admin-main">
                <!-- Top Header -->
                <header class="top-header">
                    <div class="header-left">
                        <h1>Gestion des Dons</h1>
                        <p>Gérez tous les dons du système</p>
                    </div>
                    <div class="header-right">
                        <a href="/aide_solitaire/controller/donC.php?action=create_don" class="btn btn-primary">+ Nouveau Don</a>
                        
                    </div>
                </header>

                <!-- Success/Error Messages -->
                <?php if (isset($_GET['message'])): ?>
                    <?php
                    $messages = [
                        'created' => ['type' => 'success', 'text' => 'Don créé avec succès!'],
                        'updated' => ['type' => 'success', 'text' => 'Don modifié avec succès!'],
                        'deleted' => ['type' => 'success', 'text' => 'Don supprimé avec succès!'],
                        'error' => ['type' => 'error', 'text' => 'Une erreur est survenue!'],
                        'not_found' => ['type' => 'error', 'text' => 'Don non trouvé!']
                    ];
                    $message = $messages[$_GET['message']] ?? null;
                    ?>
                    <?php if ($message): ?>
                        <div class="message-alert message-<?php echo $message['type']; ?>">
                            <?php echo $message['text']; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Filters -->
                <form method="GET" action="/aide_solitaire/controller/donC.php">
                    <input type="hidden" name="action" value="dons">
                    <div class="filters-section">
                        <div class="filters-grid">
                            <div class="filter-group">
                                <label class="filter-label">Type de don</label>
                                <select class="filter-select" name="type_don">
                                    <option value="">Tous les types</option>
                                    <option value="Vêtements" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>Vêtements</option>
                                    <option value="Nourriture" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>Nourriture</option>
                                    <option value="Médicaments" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>Médicaments</option>
                                    <option value="Équipement" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Équipement' ? 'selected' : ''; ?>>Équipement</option>
                                    <option value="Argent" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Argent' ? 'selected' : ''; ?>>Argent</option>
                                    <option value="Services" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Services' ? 'selected' : ''; ?>>Services</option>
                                    <option value="Autre" <?php echo isset($_GET['type_don']) && $_GET['type_don'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label class="filter-label">Région</label>
                                <select class="filter-select" name="region">
                                    <option value="">Toutes les régions</option>
                                    <option value="Tunis" <?php echo isset($_GET['region']) && $_GET['region'] == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                                    <option value="Sfax" <?php echo isset($_GET['region']) && $_GET['region'] == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                                    <option value="Sousse" <?php echo isset($_GET['region']) && $_GET['region'] == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                                    <option value="Kairouan" <?php echo isset($_GET['region']) && $_GET['region'] == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                                    <option value="Bizerte" <?php echo isset($_GET['region']) && $_GET['region'] == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                                    <option value="Gabès" <?php echo isset($_GET['region']) && $_GET['region'] == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                                    <option value="Ariana" <?php echo isset($_GET['region']) && $_GET['region'] == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                                    <option value="Gafsa" <?php echo isset($_GET['region']) && $_GET['region'] == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                                    <option value="Monastir" <?php echo isset($_GET['region']) && $_GET['region'] == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                                    <option value="Autre" <?php echo isset($_GET['region']) && $_GET['region'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="filters-actions">
                            <button type="submit" class="btn btn-success">🔍 Appliquer</button>
                            <a href="/aide_solitaire/controller/donC.php?action=dons" class="btn btn-secondary">🔄 Réinitialiser</a>
                        </div>
                    </div>
                </form>

                <!-- Stats and Export -->
                <div class="stats-bar">
                    <div class="total-count">
                        📊 Total: <?php echo isset($dons) ? count($dons) : 0; ?> don(s)
                    </div>
                    <div class="export-buttons">
                        <button class="btn-export" onclick="printTable()">🖨️ Imprimer</button>
                        <button class="btn-export" onclick="exportToCSV()">📥 CSV</button>
                    </div>
                </div>

                <!-- Dons Table -->
                <div class="table-container">
                    <?php if (!isset($dons) || empty($dons)): ?>
                        <div class="empty-state">
                            <div class="icon">📭</div>
                            <h3>Aucun don trouvé</h3>
                            <p>Aucun don ne correspond à vos critères de recherche.</p>
                            <a href="/aide_solitaire/controller/donC.php?action=create_don" class="btn btn-primary" style="margin-top: 1rem;">➕ Ajouter le premier don</a>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>État</th>
                                    <th>Région</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dons as $don): ?>
                                <tr>
                                    <td><strong>#<?php echo $don['id']; ?></strong></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="font-size: 1.2rem;">
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
                                            </span>
                                            <?php echo htmlspecialchars($don['type_don']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($don['quantite']); ?></td>
                                    <td>
                                        <?php if (!empty($don['etat_object'])): ?>
                                            <span class="badge badge-active"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-pending">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-active"><?php echo htmlspecialchars($don['region']); ?></span>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y', strtotime($don['date_don'])); ?></small>
                                        <br>
                                        <small style="color: #666;"><?php echo date('H:i', strtotime($don['date_don'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="/aide_solitaire/controller/donC.php?action=view_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-view" title="Voir">
                                                👁️
                                            </a>
                                            <a href="/aide_solitaire/controller/donC.php?action=edit_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-edit" title="Modifier">
                                                ✏️
                                            </a>
                                            <a href="/aide_solitaire/controller/donC.php?action=delete_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-delete" title="Supprimer" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')">
                                                🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Mobile responsive behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Show mobile toggle on small screens
            if (window.innerWidth <= 768) {
                document.querySelector('.mobile-toggle').style.display = 'block';
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

        function printTable() {
            window.print();
        }

        function exportToCSV() {
            <?php if (isset($dons) && !empty($dons)): ?>
            const rows = [
                ['ID', 'Type', 'Quantité', 'État', 'Région', 'Date', 'Description']
            ];
            
            <?php foreach ($dons as $don): ?>
            rows.push([
                '<?php echo $don['id']; ?>',
                '<?php echo $don['type_don']; ?>',
                '<?php echo $don['quantite']; ?>',
                '<?php echo $don['etat_object']; ?>',
                '<?php echo $don['region']; ?>',
                '<?php echo date('d/m/Y H:i', strtotime($don['date_don'])); ?>',
                '<?php echo isset($don['description']) ? addslashes($don['description']) : ''; ?>'
            ]);
            <?php endforeach; ?>

            let csvContent = "data:text/csv;charset=utf-8,";
            rows.forEach(function(rowArray) {
                let row = rowArray.map(field => `"${field}"`).join(",");
                csvContent += row + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "dons_<?php echo date('Y-m-d'); ?>.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            <?php else: ?>
            alert('Aucun don à exporter!');
            <?php endif; ?>
        }

        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.message-alert');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>