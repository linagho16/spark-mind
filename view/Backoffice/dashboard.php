<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Aide Solidaire</title>
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
      display: flex;
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      width: 260px;
      background: linear-gradient(135deg, #1f8c87, #7eddd5);
      color: white;
      padding: 2rem 0;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 15px rgba(0,0,0,0.1);
      z-index: 100;
    }

    .logo {
      text-align: center;
      padding: 0 1.5rem 2rem;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      margin-bottom: 2rem;
    }

    .logo h2 {
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: 1px;
    }

    .nav-menu {
      flex: 1;
      padding: 0 1rem;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 0.9rem 1.2rem;
      color: rgba(255,255,255,0.85);
      text-decoration: none;
      border-radius: 12px;
      margin-bottom: 0.5rem;
      transition: all 0.3s ease;
      font-size: 1rem;
    }

    .nav-item:hover {
      background-color: rgba(255,255,255,0.15);
      color: white;
      transform: translateX(5px);
    }

    .nav-item.active {
      background-color: rgba(255,255,255,0.25);
      color: white;
      font-weight: 600;
    }

    .nav-item .icon {
      font-size: 1.3rem;
    }

    .sidebar-footer {
      padding: 1rem;
      border-top: 1px solid rgba(255,255,255,0.2);
      margin-top: auto;
    }

    /* MAIN CONTENT */
    .main-content {
      margin-left: 260px;
      flex: 1;
      padding: 2rem;
      width: calc(100% - 260px);
    }

    /* TOP HEADER */
    .top-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2.5rem;
      background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
      padding: 2rem;
      border-radius: 20px;
      color: white;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .header-left h1 {
      font-size: 2rem;
      margin-bottom: 0.3rem;
      font-weight: 700;
      text-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .header-left p {
      opacity: 0.95;
      font-size: 1rem;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      padding: 0.7rem 2.5rem 0.7rem 1rem;
      border: none;
      border-radius: 25px;
      background-color: rgba(255,255,255,0.95);
      color: #333;
      font-size: 0.95rem;
      min-width: 250px;
      outline: none;
      transition: all 0.3s ease;
    }

    .search-box input:focus {
      box-shadow: 0 3px 12px rgba(0,0,0,0.15);
      background-color: white;
    }

    .search-icon {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0.6;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      position: relative;
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: #ec7546;
      color: white;
      font-size: 0.7rem;
      padding: 0.2rem 0.5rem;
      border-radius: 50%;
      font-weight: 600;
      z-index: 10;
    }

    .avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background-color: rgba(255,255,255,0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      border: 2px solid rgba(255,255,255,0.6);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .avatar:hover {
      transform: scale(1.1);
      background-color: rgba(255,255,255,0.4);
    }

    /* STATS GRID */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .stat-card {
      background: white;
      padding: 1.8rem;
      border-radius: 20px;
      display: flex;
      align-items: center;
      gap: 1.2rem;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 5px;
      height: 100%;
    }

    .stat-card.stat-teal::before {
      background: linear-gradient(135deg, #1f8c87, #7eddd5);
    }

    .stat-card.stat-purple::before {
      background: linear-gradient(135deg, #7d5aa6, #b58ce0);
    }

    .stat-card.stat-coral::before {
      background: linear-gradient(135deg, #ec9d78, #fbdcc1);
    }

    .stat-card.stat-orange::before {
      background: linear-gradient(135deg, #ec7546, #f4a261);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-icon {
      font-size: 2.5rem;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 15px;
      background-color: #f8f9fa;
    }

    .stat-card.stat-teal .stat-icon {
      background: linear-gradient(135deg, rgba(31,140,135,0.1), rgba(126,221,213,0.15));
    }

    .stat-card.stat-purple .stat-icon {
      background: linear-gradient(135deg, rgba(125,90,166,0.1), rgba(181,140,224,0.15));
    }

    .stat-card.stat-coral .stat-icon {
      background: linear-gradient(135deg, rgba(236,157,120,0.1), rgba(251,220,193,0.15));
    }

    .stat-card.stat-orange .stat-icon {
      background: linear-gradient(135deg, rgba(236,117,70,0.1), rgba(244,162,97,0.15));
    }

    .stat-info {
      flex: 1;
    }

    .stat-info h3 {
      font-size: 1.8rem;
      margin-bottom: 0.3rem;
      color: #333;
      font-weight: 700;
    }

    .stat-info p {
      color: #666;
      font-size: 0.95rem;
    }

    .stat-trend {
      font-size: 0.85rem;
      font-weight: 600;
      padding: 0.3rem 0.7rem;
      border-radius: 12px;
    }

    .stat-trend.up {
      background-color: #d4edda;
      color: #155724;
    }

    /* CONTENT GRID */
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 2rem;
      margin-bottom: 2.5rem;
    }

    .content-card {
      background: white;
      border-radius: 20px;
      padding: 1.8rem;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .card-header h2 {
      font-size: 1.4rem;
      color: #333;
      font-weight: 600;
    }

    .btn-primary {
      background: linear-gradient(135deg, #1f8c87, #7eddd5);
      color: white;
      border: none;
      padding: 0.6rem 1.5rem;
      border-radius: 25px;
      font-size: 0.9rem;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(31,140,135,0.3);
    }

    .btn-secondary {
      background-color: transparent;
      color: #7d5aa6;
      border: 2px solid #b58ce0;
      padding: 0.5rem 1.2rem;
      border-radius: 25px;
      font-size: 0.9rem;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-secondary:hover {
      background: linear-gradient(135deg, #7d5aa6, #b58ce0);
      color: white;
      border-color: transparent;
    }

    /* TABLE */
    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background-color: #f8f9fa;
    }

    th {
      text-align: left;
      padding: 1rem;
      font-weight: 600;
      color: #555;
      font-size: 0.9rem;
      border-bottom: 2px solid #e9ecef;
    }

    td {
      padding: 1rem;
      border-bottom: 1px solid #f1f3f5;
      color: #333;
      font-size: 0.95rem;
    }

    tr:hover {
      background-color: #f8f9fa;
    }

    .badge {
      padding: 0.4rem 0.9rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      display: inline-block;
    }

    .badge-active {
      background-color: #d4edda;
      color: #155724;
    }

    .badge-pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .btn-icon {
      background: none;
      border: none;
      font-size: 1.1rem;
      cursor: pointer;
      padding: 0.3rem 0.5rem;
      margin: 0 0.2rem;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .btn-icon:hover {
      background-color: #f1f3f5;
      transform: scale(1.1);
    }

    /* DONATION LIST */
    .donation-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .donation-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      border-radius: 12px;
      background-color: #f8f9fa;
      transition: all 0.3s ease;
    }

    .donation-item:hover {
      background-color: #e9ecef;
      transform: translateX(5px);
    }

    .donation-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    .donation-icon.teal {
      background: linear-gradient(135deg, rgba(31,140,135,0.15), rgba(126,221,213,0.2));
    }

    .donation-icon.purple {
      background: linear-gradient(135deg, rgba(125,90,166,0.15), rgba(181,140,224,0.2));
    }

    .donation-icon.coral {
      background: linear-gradient(135deg, rgba(236,157,120,0.15), rgba(251,220,193,0.2));
    }

    .donation-icon.orange {
      background: linear-gradient(135deg, rgba(236,117,70,0.15), rgba(244,162,97,0.2));
    }

    .donation-details {
      flex: 1;
    }

    .donation-details h4 {
      font-size: 1rem;
      margin-bottom: 0.3rem;
      color: #333;
    }

    .donation-details p {
      font-size: 0.85rem;
      color: #666;
    }

    .donation-time {
      font-size: 0.8rem;
      color: #999;
    }

    /* QUICK ACTIONS */
    .quick-actions {
      background: white;
      padding: 2rem;
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .quick-actions h2 {
      font-size: 1.4rem;
      margin-bottom: 1.5rem;
      color: #333;
      font-weight: 600;
    }

    .action-buttons {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .action-btn {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      padding: 1rem 1.5rem;
      border: none;
      border-radius: 15px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      color: white;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .action-btn.teal {
      background: linear-gradient(135deg, #1f8c87, #7eddd5);
    }

    .action-btn.purple {
      background: linear-gradient(135deg, #7d5aa6, #b58ce0);
    }

    .action-btn.coral {
      background: linear-gradient(135deg, #ec9d78, #fbdcc1);
    }

    .action-btn.orange {
      background: linear-gradient(135deg, #ec7546, #f4a261);
    }

    .action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .action-icon {
      font-size: 1.3rem;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
      .content-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
        padding: 1.5rem 0;
      }

      .logo h2 {
        font-size: 1.5rem;
      }

      .nav-item span:not(.icon) {
        display: none;
      }

      .nav-item {
        justify-content: center;
        padding: 0.9rem;
      }

      .main-content {
        margin-left: 70px;
        width: calc(100% - 70px);
        padding: 1rem;
      }

      .top-header {
        flex-direction: column;
        gap: 1.5rem;
      }

      .search-box input {
        min-width: 200px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .action-buttons {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
 <!-- Sidebar Navigation -->


  <!-- Main Content -->
  <main class="main-content">
    <!-- Top Header -->
    <header class="top-header">
      <div class="header-left">
        <h1>Tableau de bord</h1>
        <p>Bienvenue sur votre espace d'administration</p>
      </div>
      <div class="header-right">
        <div class="search-box">
          <input type="text" placeholder="Rechercher...">
          <span class="search-icon">🔍</span>
        </div>
        <div class="user-profile">
          <span class="notification-badge">3</span>
          <div class="avatar">👤</div>
        </div>
      </div>
    </header>
    <!-- Stats Cards - Now Dynamic -->
    <div class="stats-grid">
      <div class="stat-card stat-teal">
        <div class="stat-icon">🎁</div>
        <div class="stat-info">
          <h3><?php echo $stats['total_dons'] ?? 0; ?></h3>
          <p>Total des dons</p>
          <span class="stat-trend up">+<?php echo $stats['recent_dons'] ?? 0; ?> cette semaine</span>
        </div>
      </div>

      <div class="stat-card stat-purple">
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

      <div class="stat-card stat-coral">
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

      <div class="stat-card stat-orange">
        <div class="stat-icon">📈</div>
        <div class="stat-info">
          <h3><?php echo isset($stats['avg_daily']) ? $stats['avg_daily'] : '0'; ?></h3>
          <p>Moyenne journalière</p>
          <span class="stat-trend up">Croissance</span>
        </div>
      </div>
    </div>
     
  <!--  Sidebar Navigation -->
  <div class="sidebar">
    <!-- Logo -->
    <div class="logo">
      <h2>🤝 Aide Solidaire</h2>
      <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">Administration</p>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="nav-menu">
      <!-- Dashboard -->
      <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="nav-item active">
        <span class="icon">📊</span>
        <span>Tableau de bord</span>
      </a>
      
      <!-- Donations Section -->
      <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600;">
        GESTION DES DONS
      </div>
      
      <a href="/aide_solitaire/controller/donC.php?action=dons" class="nav-item">
        <span class="icon">🎁</span>
        <span>Tous les dons</span>
      </a>
      
      <a href="/aide_solitaire/controller/donC.php?action=create_don" class="nav-item">
        <span class="icon">➕</span>
        <span>Ajouter un don</span>
      </a>
      
      <a href="/aide_solitaire/controller/donC.php?action=statistics" class="nav-item">
        <span class="icon">📈</span>
        <span>Statistiques dons</span>
      </a>
      
      <!-- Groups Section -->
      <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; margin-top: 1rem;">
        GESTION DES GROUPES
      </div>
      
      <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="nav-item">
        <span class="icon">👥</span>
        <span>Tous les groupes</span>
      </a>
      
      <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="nav-item">
        <span class="icon">➕</span>
        <span>Ajouter un groupe</span>
      </a>
    </nav>  
      <!-- FrontOffice Link -->
      <a href="/aide_solitaire/view/frontoffice/index.php" style="display: block; text-align: center; margin-top: 0.5rem; padding: 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; text-decoration: none; color: white; font-size: 0.9rem; transition: all 0.3s ease;">
        <span>🌐</span>
        <span>Voir le site public</span>
      </a>
    </div>
  </div>
    
    <section class="content-grid">
      <!-- Recent Groups -->

      <!-- Recent Donations - Now Dynamic -->
      <div class="content-card">
        <div class="card-header">
          <h2>🎁 Dons récents</h2>
          <a href="/aide_solitaire/controller/donC.php?action=dons" class="btn-secondary">Voir tout</a>
        </div>
        <div class="donation-list">
          <?php if (!empty($recent_dons)): ?>
            <?php foreach (array_slice($recent_dons, 0, 4) as $don): ?>
            <div class="donation-item">
              <div class="donation-icon teal">
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
  </main>
</body>
</html>