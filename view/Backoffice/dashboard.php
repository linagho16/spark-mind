<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Aide Solidaire</title>
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

/* ✅ Mobile Responsive Fixes */
@media (max-width: 768px) {
    .top-nav {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
        padding: 15px;
    }
    
    .top-nav-left {
        width: 100%;
        justify-content: space-between;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
        margin-left: 0;
    }
    
    .search-box {
        width: calc(100% - 60px);
    }
}

@media (max-width: 480px) {
    .search-box {
        width: calc(100% - 50px);
        min-width: 0;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .notification-badge {
        top: -2px;
        right: -2px;
        font-size: 10px;
        min-width: 16px;
        height: 16px;
    }
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

      /* ✅ Search Box */
      .search-box {
          position: relative;
          flex: 1;
          max-width: 300px;
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
      }

      /* ✅ Stats Grid */
      .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
      }

      .stat-card {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
          position: relative;
          overflow: hidden;
      }

      .stat-card::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 4px;
      }

      .stat-card.stat-teal::before {
          background: linear-gradient(90deg, #1f8c87, #7eddd5);
      }

      .stat-card.stat-purple::before {
          background: linear-gradient(90deg, #7d5aa6, #b58bf0);
      }

      .stat-card.stat-orange::before {
          background: linear-gradient(90deg, #ec7546, #ffb38f);
      }

      .stat-card.stat-coral::before {
          background: linear-gradient(90deg, #ec9d78, #fbdcc1);
      }

      .stat-content {
          display: flex;
          align-items: center;
          gap: 15px;
      }

      .stat-icon {
          font-size: 32px;
          width: 60px;
          height: 60px;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 12px;
          background: rgba(255, 255, 255, 0.9);
      }

      .stat-info {
          flex: 1;
      }

      .stat-info h3 {
          font-size: 24px;
          color: #1A464F;
          margin: 0 0 5px;
          font-weight: 600;
      }

      .stat-info p {
          font-size: 13px;
          color: #7a6f66;
          margin: 0 0 8px;
      }

      .stat-trend {
          font-size: 11px;
          font-weight: 600;
          padding: 4px 8px;
          border-radius: 12px;
          display: inline-block;
      }

      .stat-trend.up {
          background: rgba(212, 237, 218, 0.3);
          color: #155724;
      }

      /* ✅ Content Grid */
      .content-grid {
          display: grid;
          grid-template-columns: 2fr 1fr;
          gap: 20px;
          margin-bottom: 30px;
      }

      @media (max-width: 900px) {
          .content-grid {
              grid-template-columns: 1fr;
          }
      }

      /* ✅ Content Card */
      .content-card {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .card-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
          padding-bottom: 10px;
          border-bottom: 1px solid rgba(0,0,0,0.08);
      }

      .card-header h2 {
          font-family: 'Playfair Display', serif;
          font-size: 22px;
          color: #1A464F;
          margin: 0;
      }

      .btn {
          padding: 8px 16px;
          border-radius: 999px;
          border: none;
          font-family: 'Poppins', sans-serif;
          font-weight: 600;
          cursor: pointer;
          text-decoration: none;
          font-size: 13px;
          transition: all 0.3s ease;
          display: inline-flex;
          align-items: center;
          gap: 6px;
      }

      .btn-primary {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
          color: white;
      }

      .btn-secondary {
          background: transparent;
          color: #1A464F;
          border: 1px solid rgba(26, 70, 79, 0.35);
      }

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      }

      /* ✅ Donation List */
      .donation-list {
          display: flex;
          flex-direction: column;
          gap: 12px;
      }

      .donation-item {
          display: flex;
          align-items: center;
          gap: 15px;
          padding: 15px;
          background: white;
          border-radius: 18px;
          box-shadow: 0 8px 18px rgba(0,0,0,0.08);
          transition: all 0.3s ease;
      }

      .donation-item:hover {
          transform: translateY(-2px);
          box-shadow: 0 12px 24px rgba(0,0,0,0.12);
      }

      .donation-icon {
          width: 50px;
          height: 50px;
          border-radius: 12px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 24px;
          background: rgba(255, 255, 255, 0.9);
      }

      .donation-details {
          flex: 1;
      }

      .donation-details h4 {
          font-size: 16px;
          color: #1A464F;
          margin: 0 0 4px;
          font-weight: 600;
      }

      .donation-details p {
          font-size: 13px;
          color: #7a6f66;
          margin: 0;
      }

      .donation-time {
          font-size: 12px;
          color: #7a6f66;
          white-space: nowrap;
      }

      /* ✅ Quick Actions */
      .quick-actions {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .quick-actions h2 {
          font-family: 'Playfair Display', serif;
          font-size: 22px;
          color: #1A464F;
          margin: 0 0 20px;
      }

      .action-buttons {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 15px;
      }

      .action-btn {
          display: flex;
          align-items: center;
          gap: 12px;
          padding: 15px;
          border: none;
          border-radius: 18px;
          font-size: 14px;
          font-weight: 600;
          cursor: pointer;
          color: white;
          text-decoration: none;
          transition: all 0.3s ease;
      }

      .action-btn.teal {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
      }

      .action-btn.purple {
          background: linear-gradient(135deg, #7d5aa6, #b58bf0);
      }

      .action-btn.orange {
          background: linear-gradient(135deg, #ec7546, #ffb38f);
      }

      .action-btn.coral {
          background: linear-gradient(135deg, #ec9d78, #fbdcc1);
      }

      .action-btn:hover {
          transform: translateY(-3px);
          box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      }

      /* ✅ User Profile */
      .user-profile {
          display: flex;
          align-items: center;
          gap: 12px;
          position: relative;
      }

      .notification-badge {
          position: absolute;
          top: -5px;
          right: -5px;
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
      }

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
      }

      .avatar:hover {
          transform: scale(1.1);
      }

      /* ✅ Responsive Design */
      @media (max-width: 900px) {
          .sidebar {
              width: 220px;
          }
          
          .stats-grid {
              grid-template-columns: repeat(2, 1fr);
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
          
          .sidebar.collapsed {
              display: none;
          }
          
          .stats-grid {
              grid-template-columns: 1fr;
          }
          
          .content-grid {
              grid-template-columns: 1fr;
          }
          
          .top-nav {
              flex-direction: column;
              align-items: flex-start;
              gap: 10px;
              padding: 15px;
          }
          
          .search-box {
              max-width: 100%;
          }
          
          .action-buttons {
              grid-template-columns: 1fr;
          }
      }

      @media (max-width: 480px) {
          .admin-main {
              padding: 0 15px 20px;
          }
          
          .dashboard-header,
          .content-card,
          .quick-actions,
          .stat-card {
              padding: 20px;
              border-radius: 18px;
          }
          
          .donation-item {
              padding: 12px;
          }
          
          .btn {
              padding: 8px 12px;
              font-size: 12px;
          }
          
          .action-btn {
              padding: 12px;
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
                <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="menu-item active">
                    <span class="icon">📊</span>
                    <span>Tableau de bord</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES DONS</div>
            <nav class="menu">
                <a href="/aide_solitaire/controller/donC.php?action=dons" class="menu-item">
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
           <!-- ✅ Top Navigation - FIXED ALIGNMENT -->
<div class="top-nav">
    <!-- Brand section -->
    <div class="top-nav-left">
        <div class="brand-block">
            <img src="/aide_solitaire/view/frontoffice/pigeon.png" alt="Logo" class="logo-img">
            <div class="brand-text">
                <div class="brand-name">SPARKMIND</div>
                <div class="brand-tagline">Administration</div>
            </div>
        </div>
    </div>
    
    <!-- Right section - Perfectly aligned -->
    <div class="header-actions">
        <!-- Search Box -->
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Rechercher...">
        </div>
        
        <!-- User Profile with Notification Badge -->
        <div class="user-profile">
            <span class="notification-badge">3</span>
            <div class="avatar">👤</div>
        </div>
    </div>
</div>

            <!-- ✅ Main Content -->
            <div class="admin-main">
                <!-- Dashboard Header -->
                <div class="dashboard-header">
                    <h1>Tableau de bord</h1>
                    <p class="dashboard-subtitle">Bienvenue sur votre espace d'administration</p>
                </div>

                <!-- Stats Cards - Now Dynamic -->
                <div class="stats-grid">
                    <div class="stat-card stat-teal">
                        <div class="stat-content">
                            <div class="stat-icon">🎁</div>
                            <div class="stat-info">
                                <h3><?php echo $stats['total_dons'] ?? 0; ?></h3>
                                <p>Total des dons</p>
                                <span class="stat-trend up">+<?php echo $stats['recent_dons'] ?? 0; ?> cette semaine</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card stat-purple">
                        <div class="stat-content">
                            <div class="stat-icon">📊</div>
                            <div class="stat-info">
                                <h3>
                                    <?php 
                                    if (isset($stats['dons_by_type']) && count($stats['dons_by_type']) > 0) {
                                      echo count($stats['dons_by_type']);
                                    } else {
                                      echo '0';
                                    }
                                    ?>
                                </h3>
                                <p>Types de dons</p>
                                <span class="stat-trend up">Variétés</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card stat-coral">
                        <div class="stat-content">
                            <div class="stat-icon">📍</div>
                            <div class="stat-info">
                                <h3>
                                    <?php 
                                    if (isset($stats['dons_by_region']) && count($stats['dons_by_region']) > 0) {
                                      echo count($stats['dons_by_region']);
                                    } else {
                                      echo '0';
                                    }
                                    ?>
                                </h3>
                                <p>Régions actives</p>
                                <span class="stat-trend up">Couverture</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card stat-orange">
                        <div class="stat-content">
                            <div class="stat-icon">📈</div>
                            <div class="stat-info">
                                <h3><?php echo isset($stats['avg_daily']) ? $stats['avg_daily'] : '0'; ?></h3>
                                <p>Moyenne journalière</p>
                                <span class="stat-trend up">Croissance</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <section class="content-grid">
                    <!-- Recent Donations - Now Dynamic -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2>🎁 Dons récents</h2>
                            <a href="/aide_solitaire/controller/donC.php?action=dons" class="btn btn-secondary">
                                <span>📋</span>
                                <span>Voir tout</span>
                            </a>
                        </div>
                        <div class="donation-list">
                            <?php if (!empty($recent_dons)): ?>
                                <?php foreach (array_slice($recent_dons, 0, 4) as $don): ?>
                                <div class="donation-item">
                                    <div class="donation-icon">
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
                                    <div class="donation-details">
                                        <h4><?php echo htmlspecialchars($don['type_don']); ?></h4>
                                        <p><?php echo htmlspecialchars($don['quantite']); ?> - <?php echo htmlspecialchars($don['region']); ?></p>
                                    </div>
                                    <div class="donation-time">
                                        <?php 
                                        $date = new DateTime($don['date_don']);
                                        $now = new DateTime();
                                        $diff = $now->diff($date);
                                        
                                        if ($diff->days == 0) {
                                          if ($diff->h == 0) {
                                            echo 'Il y a ' . $diff->i . 'min';
                                          } else {
                                            echo 'Il y a ' . $diff->h . 'h';
                                          }
                                        } elseif ($diff->days == 1) {
                                          echo 'Il y a 1j';
                                        } else {
                                          echo 'Il y a ' . $diff->days . 'j';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="text-align: center; padding: 2rem; color: #666;">
                                    Aucun don récent
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- Quick Actions -->
                <section class="quick-actions">
                    <h2>⚡ Actions rapides</h2>
                    <div class="action-buttons">
                        <a href="/aide_solitaire/controller/donC.php?action=create_don" class="action-btn teal">
                            <span class="action-icon">➕</span>
                            <span>Ajouter un don</span>
                        </a>
                        <a href="/aide_solitaire/controller/donC.php?action=dons" class="action-btn purple">
                            <span class="action-icon">📋</span>
                            <span>Gérer les dons</span>
                        </a>
                        <a href="/aide_solitaire/controller/donC.php?action=statistics" class="action-btn coral">
                            <span class="action-icon">📊</span>
                            <span>Voir les stats</span>
                        </a>
                        <button class="action-btn orange">
                            <span class="action-icon">📧</span>
                            <span>Envoyer newsletter</span>
                        </button>
                    </div>
                </section>
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
    </script>
</body>
</html>