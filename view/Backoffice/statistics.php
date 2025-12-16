<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistiques des Dons - Aide Solidaire</title>
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

      .btn-purple {
          background: linear-gradient(135deg, #7d5aa6, #b58bf0);
          color: white;
      }

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
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

      /* ✅ Time Filter */
      .time-filter {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 20px 24px;
          margin-bottom: 28px;
          display: flex;
          align-items: center;
          gap: 16px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .time-filter label {
          font-weight: 600;
          color: #1A464F;
          font-size: 14px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }

      .time-select {
          padding: 12px 20px;
          border: 2px solid rgba(26, 70, 79, 0.1);
          border-radius: 12px;
          font-size: 14px;
          background: white;
          color: #1A464F;
          font-family: 'Poppins', sans-serif;
          transition: all 0.3s ease;
          cursor: pointer;
      }

      .time-select:focus {
          outline: none;
          border-color: #1A464F;
          box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.1);
      }

      /* ✅ Stats Summary */
      .stats-summary {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
          gap: 20px;
          margin-bottom: 28px;
      }

      .stat-card {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px;
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
          margin: 0;
      }

      /* ✅ Charts Container */
      .charts-container {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
          gap: 24px;
          margin-bottom: 28px;
      }

      @media (max-width: 900px) {
          .charts-container {
              grid-template-columns: 1fr;
          }
      }

      /* ✅ Chart Card */
      .chart-card {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .chart-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
          padding-bottom: 12px;
          border-bottom: 2px solid rgba(234, 226, 214, 0.8);
      }

      .chart-header h2 {
          font-family: 'Playfair Display', serif;
          font-size: 20px;
          color: #1A464F;
          margin: 0;
      }

      .chart-controls {
          display: flex;
          gap: 10px;
      }

      .chart-select {
          padding: 8px 16px;
          border: 2px solid rgba(26, 70, 79, 0.1);
          border-radius: 12px;
          font-size: 13px;
          background: white;
          color: #1A464F;
          font-family: 'Poppins', sans-serif;
          cursor: pointer;
          transition: all 0.3s ease;
      }

      .chart-select:focus {
          outline: none;
          border-color: #1A464F;
          box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.1);
      }

      .chart-canvas {
          width: 100%;
          height: 300px;
          position: relative;
      }

      /* ✅ Detailed Statistics */
      .detailed-stats {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px;
          margin-bottom: 28px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .detailed-stats h2 {
          font-family: 'Playfair Display', serif;
          font-size: 20px;
          color: #1A464F;
          margin: 0 0 20px;
          padding-bottom: 12px;
          border-bottom: 2px solid rgba(234, 226, 214, 0.8);
      }

      .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
          gap: 16px;
      }

      .stat-item {
          text-align: center;
          padding: 20px;
          background: white;
          border-radius: 16px;
          box-shadow: 0 8px 20px rgba(0,0,0,0.06);
      }

      .stat-item h4 {
          font-size: 28px;
          margin: 0 0 8px;
          color: #1f8c87;
          font-weight: 700;
      }

      .stat-item p {
          font-size: 14px;
          color: #7a6f66;
          margin: 0;
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
          
          .time-filter {
              flex-direction: column;
              align-items: stretch;
              gap: 12px;
          }
          
          .stats-summary {
              grid-template-columns: 1fr;
          }
          
          .charts-container {
              grid-template-columns: 1fr;
          }
          
          .stats-grid {
              grid-template-columns: repeat(2, 1fr);
          }
          
          .chart-canvas {
              height: 250px;
          }
      }

      @media (max-width: 480px) {
          .admin-main {
              padding: 0 15px 20px;
          }
          
          .top-header,
          .time-filter,
          .stat-card,
          .chart-card,
          .detailed-stats {
              padding: 20px;
              border-radius: 18px;
          }
          
          .btn {
              padding: 10px 16px;
              font-size: 13px;
          }
          
          .stats-grid {
              grid-template-columns: 1fr;
          }
      }
  </style>
  <!-- Include Chart.js for charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()" style="display: none; position: fixed; top: 10px; left: 10px; z-index: 1001; background: #1A464F; color: #fff; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">☰</button>

    <!-- ✅ Layout avec sidebar -->
    <div class="layout">
        <!-- ✅ Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <a href="/sparkmind_mvc_100percent/controller/donC.php?action=dashboard" class="brand">
                <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo">
                <div class="brand-name">SPARKMIND</div>
            </a>

            <div class="menu-title">MENU PRINCIPAL</div>
            <nav class="menu">
                <a href="/sparkmind_mvc_100percent/index.php?page=backoffice_aide" class="menu-item">
                    <span class="icon">📊</span>
                    <span>Tableau de bord</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES DONS</div>
            <nav class="menu">
                <a href="/sparkmind_mvc_100percent/controller/donC.php?action=dons" class="menu-item">
                    <span class="icon">🎁</span>
                    <span>Tous les dons</span>
                </a>

                <a href="/sparkmind_mvc_100percent/controller/donC.php?action=create_don" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Ajouter un don</span>
                </a>

                <a href="/sparkmind_mvc_100percent/controller/donC.php?action=statistics" class="menu-item active">
                    <span class="icon">📈</span>
                    <span>Statistiques dons</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES GROUPES</div>
            <nav class="menu">
                <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="menu-item">
                    <span class="icon">👥</span>
                    <span>Tous les groupes</span>
                </a>

                <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=create_groupe" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Ajouter un groupe</span>
                </a>
            </nav>

            <div class="sidebar-foot">
                <a href="/sparkmind_mvc_100percent/view/frontoffice/index.php" class="link">
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
                        <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo-img">
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
                        <h1>📈 Statistiques des Dons</h1>
                        <p>Analyse et visualisation des données des donations</p>
                    </div>
                    <div class="header-right">
                        <button class="btn btn-purple" onclick="exportStatistics()">
                            📥 Exporter les statistiques
                        </button>
                        
                    </div>
                </header>

                <!-- Time Period Filter -->
                <div class="time-filter">
                    <label for="timePeriod">Période d'analyse :</label>
                    <select id="timePeriod" class="time-select" onchange="updateStatistics()">
                        <option value="7days">7 derniers jours</option>
                        <option value="30days">30 derniers jours</option>
                        <option value="3months">3 derniers mois</option>
                        <option value="6months">6 derniers mois</option>
                        <option value="1year" selected>1 année</option>
                        <option value="all">Toutes les données</option>
                    </select>
                </div>

                <!-- Stats Summary -->
                <div class="stats-summary">
                    <div class="stat-card stat-teal">
                        <div class="stat-content">
                            <div class="stat-icon">🎁</div>
                            <div class="stat-info">
                                <h3><?php echo $stats['total_dons'] ?? 0; ?></h3>
                                <p>Total des dons</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card stat-purple">
                        <div class="stat-content">
                            <div class="stat-icon">📅</div>
                            <div class="stat-info">
                                <h3><?php echo $stats['recent_dons'] ?? 0; ?></h3>
                                <p>Dons récents (7 jours)</p>
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
                            </div>
                        </div>
                    </div>

                    <div class="stat-card stat-orange">
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Container -->
                <div class="charts-container">
                    <!-- Dons by Type Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h2>📦 Dons par type</h2>
                            <div class="chart-controls">
                                <select class="chart-select" onchange="updateTypeChart(this.value)">
                                    <option value="bar">Barres</option>
                                    <option value="pie">Camembert</option>
                                    <option value="doughnut">Anneau</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-canvas">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>

                    <!-- Dons by Region Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h2>📍 Dons par région</h2>
                            <div class="chart-controls">
                                <select class="chart-select" onchange="updateRegionChart(this.value)">
                                    <option value="bar">Barres</option>
                                    <option value="pie">Camembert</option>
                                    <option value="doughnut">Anneau</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-canvas">
                            <canvas id="regionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Dons by Month Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h2>📅 Évolution mensuelle des dons</h2>
                        <div class="chart-controls">
                            <select class="chart-select" onchange="updateMonthlyChart(this.value)">
                                <option value="line">Ligne</option>
                                <option value="bar">Barres</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- Detailed Statistics -->
                <div class="detailed-stats">
                    <h2>📋 Statistiques détaillées</h2>
                    <div class="stats-grid">
                        <!-- Type Breakdown -->
                        <?php if (isset($stats['dons_by_type']) && !empty($stats['dons_by_type'])): ?>
                            <?php foreach ($stats['dons_by_type'] as $type): ?>
                                <div class="stat-item">
                                    <h4><?php echo $type['count']; ?></h4>
                                    <p><?php echo htmlspecialchars($type['type_don']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="stat-item">
                                <h4>0</h4>
                                <p>Aucun type de don</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Region Breakdown -->
                <div class="detailed-stats">
                    <h2>🌍 Répartition par région</h2>
                    <div class="stats-grid">
                        <?php if (isset($stats['dons_by_region']) && !empty($stats['dons_by_region'])): ?>
                            <?php foreach ($stats['dons_by_region'] as $region): ?>
                                <div class="stat-item">
                                    <h4><?php echo $region['count']; ?></h4>
                                    <p><?php echo htmlspecialchars($region['region']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="stat-item">
                                <h4>0</h4>
                                <p>Aucune région</p>
                            </div>
                        <?php endif; ?>
                    </div>
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
            
            // Initialize charts
            initializeCharts();
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

        // Charts data from PHP
        const typeData = <?php echo json_encode($stats['dons_by_type'] ?? []); ?>;
        const regionData = <?php echo json_encode($stats['dons_by_region'] ?? []); ?>;

        let typeChart, regionChart, monthlyChart;

        function initializeCharts() {
            // Dons by Type Chart
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            typeChart = new Chart(typeCtx, {
                type: 'bar',
                data: {
                    labels: typeData.map(item => item.type_don),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: typeData.map(item => item.count),
                        backgroundColor: [
                            '#1f8c87', '#7d5aa6', '#ec9d78', '#ec7546',
                            '#10b981', '#3b82f6', '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Dons by Region Chart
            const regionCtx = document.getElementById('regionChart').getContext('2d');
            regionChart = new Chart(regionCtx, {
                type: 'bar',
                data: {
                    labels: regionData.map(item => item.region),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: regionData.map(item => item.count),
                        backgroundColor: [
                            '#f59e0b', '#84cc16', '#06b6d4', '#8b5cf6',
                            '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Monthly Evolution Chart (dummy data - you need to implement this in your model)
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Dons mensuels',
                        data: [12, 19, 8, 15, 22, 18, 25, 12, 19, 21, 16, 24],
                        borderColor: '#1f8c87',
                        backgroundColor: 'rgba(31, 140, 135, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de dons'
                            }
                        }
                    }
                }
            });
        }

        function updateTypeChart(chartType) {
            typeChart.destroy();
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            typeChart = new Chart(typeCtx, {
                type: chartType,
                data: {
                    labels: typeData.map(item => item.type_don),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: typeData.map(item => item.count),
                        backgroundColor: [
                            '#1f8c87', '#7d5aa6', '#ec9d78', '#ec7546',
                            '#10b981', '#3b82f6', '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: chartType !== 'bar'
                        }
                    }
                }
            });
        }

        function updateRegionChart(chartType) {
            regionChart.destroy();
            const regionCtx = document.getElementById('regionChart').getContext('2d');
            regionChart = new Chart(regionCtx, {
                type: chartType,
                data: {
                    labels: regionData.map(item => item.region),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: regionData.map(item => item.count),
                        backgroundColor: [
                            '#f59e0b', '#84cc16', '#06b6d4', '#8b5cf6',
                            '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: chartType !== 'bar'
                        }
                    }
                }
            });
        }

        function updateMonthlyChart(chartType) {
            monthlyChart.destroy();
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            monthlyChart = new Chart(monthlyCtx, {
                type: chartType,
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Dons mensuels',
                        data: [12, 19, 8, 15, 22, 18, 25, 12, 19, 21, 16, 24],
                        borderColor: '#1f8c87',
                        backgroundColor: chartType === 'line' 
                            ? 'rgba(31, 140, 135, 0.1)' 
                            : '#1f8c87',
                        borderWidth: 3,
                        fill: chartType === 'line',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de dons'
                            }
                        }
                    }
                }
            });
        }

        function updateStatistics() {
            const timePeriod = document.getElementById('timePeriod').value;
            // Here you would typically make an AJAX call to update statistics
            // For now, we'll just show a message
            alert(`Mise à jour des statistiques pour la période: ${timePeriod}`);
            // In a real implementation, you would fetch new data and update charts
        }

        function exportStatistics() {
            // Create CSV content
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Add header
            csvContent += "Type,Région,Compte\n";
            
            // Add type data
            if (typeData.length > 0) {
                typeData.forEach(item => {
                    csvContent += `"${item.type_don}",,"${item.count}"\n`;
                });
            }
            
            // Add region data
            if (regionData.length > 0) {
                regionData.forEach(item => {
                    csvContent += `,"${item.region}","${item.count}"\n`;
                });
            }
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `statistiques_dons_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>