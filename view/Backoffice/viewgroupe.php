<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails du Groupe - Dashboard Admin</title>
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

      /* ✅ Details Container */
      .details-container {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 30px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
          margin-bottom: 30px;
      }

      .details-header {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 20px;
          margin-bottom: 30px;
          padding-bottom: 20px;
          border-bottom: 2px solid rgba(26, 70, 79, 0.1);
      }

      .groupe-info {
          display: flex;
          align-items: center;
          gap: 20px;
      }

      .groupe-icon {
          font-size: 48px;
          width: 80px;
          height: 80px;
          display: flex;
          align-items: center;
          justify-content: center;
          background: white;
          border-radius: 20px;
          box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      }

      .groupe-title h2 {
          font-family: 'Playfair Display', serif;
          font-size: 28px;
          color: #1A464F;
          margin: 0 0 5px;
      }

      .groupe-title .groupe-id {
          font-size: 14px;
          color: #7a6f66;
          font-weight: 500;
      }

      /* ✅ Status Badge */
      .groupe-status {
          padding: 8px 20px;
          border-radius: 20px;
          font-size: 13px;
          font-weight: 600;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }

      .status-active {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
          color: white;
      }

      .status-inactive {
          background: linear-gradient(135deg, #7d5aa6, #b58bf0);
          color: white;
      }

      .status-pending {
          background: linear-gradient(135deg, #ec7546, #ffb38f);
          color: white;
      }

      /* ✅ Details Grid */
      .details-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
      }

      .detail-value {
          color: #555;
          text-align: right;
          font-size: 14px;
          font-weight: 500;
      }

      .contact-info {
          display: flex;
          align-items: center;
          gap: 8px;
          justify-content: flex-end;
      }

      .contact-icon {
          font-size: 16px;
      }

      /* ✅ Description Section */
      .description-section {
          background: white;
          border-radius: 20px;
          padding: 22px;
          margin: 25px 0;
          box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      }

      .description-section h3 {
          font-family: 'Playfair Display', serif;
          font-size: 18px;
          color: #1A464F;
          margin: 0 0 18px;
          padding-bottom: 10px;
          border-bottom: 1px solid rgba(26, 70, 79, 0.1);
      }

      .description-content {
          line-height: 1.7;
          color: #555;
          font-size: 15px;
          padding: 5px 0;
      }

      .no-description {
          color: #7a6f66;
          font-style: italic;
          text-align: center;
          padding: 30px 20px;
          background: rgba(26, 70, 79, 0.03);
          border-radius: 12px;
          border: 2px dashed rgba(26, 70, 79, 0.15);
      }

      /* ✅ Action Buttons */
      .action-buttons {
          display: flex;
          gap: 15px;
          justify-content: flex-end;
          margin-top: 30px;
          padding-top: 25px;
          border-top: 1px solid rgba(26, 70, 79, 0.1);
      }

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

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      }

      .btn-back {
          background: transparent;
          color: #1A464F;
          border: 1px solid rgba(26, 70, 79, 0.35);
      }

      .btn-edit {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
          color: white;
      }

      .btn-delete {
          background: linear-gradient(135deg, #ec7546, #ffb38f);
          color: white;
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
          
          .action-buttons {
              flex-direction: column;
          }
          
          .btn {
              width: 100%;
              justify-content: center;
          }
          
          .details-grid {
              grid-template-columns: 1fr;
          }
          
          .details-header {
              flex-direction: column;
              align-items: flex-start;
              gap: 15px;
          }
          
          .groupe-info {
              width: 100%;
              justify-content: center;
              flex-direction: column;
              text-align: center;
          }
          
          .groupe-status {
              align-self: flex-start;
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
          
          .admin-main {
              padding: 0 15px 20px;
          }
          
          .details-container {
              padding: 20px;
          }
          
          .groupe-icon {
              width: 60px;
              height: 60px;
              font-size: 36px;
          }
          
          .groupe-title h2 {
              font-size: 22px;
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

                <a href="/sparkmind_mvc_100percent/controller/donC.php?action=statistics" class="menu-item">
                    <span class="icon">📈</span>
                    <span>Statistiques dons</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES GROUPES</div>
            <nav class="menu">
                <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="menu-item active">
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
                <!-- Brand section -->
                <div class="top-nav-left">
                    <div class="brand-block">
                        <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo-img">
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
                    <h1>Détails du Groupe</h1>
                    <p class="dashboard-subtitle">Informations complètes sur ce groupe de solidarité</p>
                </div>

                <!-- Details Container -->
                <div class="details-container">
                    <!-- Groupe Header -->
                    <div class="details-header">
                        <div class="groupe-info">
                            <div class="groupe-icon">
                                <?php 
                                $icons = [
                                    'Santé' => '🏥',
                                    'Éducation' => '📚',
                                    'Seniors' => '👵',
                                    'Jeunesse' => '👦',
                                    'Culture' => '🎨',
                                    'Urgence' => '🚨',
                                    'Animaux' => '🐾',
                                    'Environnement' => '🌿',
                                    'Religieux' => '🌙',
                                    'Social' => '🤝'
                                ];
                                echo $icons[$groupe['type']] ?? '👥';
                                ?>
                            </div>
                            <div class="groupe-title">
                                <h2><?php echo htmlspecialchars($groupe['nom']); ?></h2>
                                <div class="groupe-id">Groupe #<?php echo $groupe['id']; ?></div>
                            </div>
                        </div>
                        <div class="groupe-status status-<?php echo $groupe['statut'] ?? 'actif'; ?>">
                            <?php 
                            $statusText = [
                                'actif' => 'Actif',
                                'inactif' => 'Inactif', 
                                'en_attente' => 'En attente'
                            ];
                            echo $statusText[$groupe['statut']] ?? 'Actif';
                            ?>
                        </div>
                    </div>

                    <!-- Main Details Grid -->
                    <div class="details-grid">
                        <!-- Groupe Information -->
                        <div class="detail-section">
                            <h3>Informations du groupe</h3>
                            <div class="detail-item">
                                <span class="detail-label">ID:</span>
                                <span class="detail-value">#<?php echo $groupe['id']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Nom:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($groupe['nom']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Type:</span>
                                <span class="detail-value">
                                    <?php 
                                    $typeIcon = $icons[$groupe['type']] ?? '👥';
                                    echo $typeIcon . ' ' . htmlspecialchars($groupe['type']);
                                    ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Région:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($groupe['region']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Statut:</span>
                                <span class="detail-value">
                                    <span class="groupe-status status-<?php echo $groupe['statut'] ?? 'actif'; ?>" style="font-size: 12px; padding: 4px 12px;">
                                        <?php echo $statusText[$groupe['statut']] ?? 'Actif'; ?>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="detail-section">
                            <h3>Contact et responsable</h3>
                            <div class="detail-item">
                                <span class="detail-label">Responsable:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($groupe['responsable']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">
                                    <div class="contact-info">
                                        <span class="contact-icon">📧</span>
                                        <?php echo htmlspecialchars($groupe['email']); ?>
                                    </div>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Téléphone:</span>
                                <span class="detail-value">
                                    <div class="contact-info">
                                        <span class="contact-icon">📞</span>
                                        <?php echo htmlspecialchars($groupe['telephone']); ?>
                                    </div>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date de création:</span>
                                <span class="detail-value"><?php echo isset($groupe['created_at']) ? date('d/m/Y à H:i', strtotime($groupe['created_at'])) : 'Non disponible'; ?></span>
                            </div>
                            <?php if (isset($groupe['membres_count'])): ?>
                            <div class="detail-item">
                                <span class="detail-label">Nombre de membres:</span>
                                <span class="detail-value"><?php echo $groupe['membres_count']; ?> membres</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description-section">
                        <h3>Description du groupe</h3>
                        <div class="description-content">
                            <?php if (!empty($groupe['description'])): ?>
                                <?php echo nl2br(htmlspecialchars($groupe['description'])); ?>
                            <?php else: ?>
                                <div class="no-description">
                                    📝 Aucune description disponible pour ce groupe
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="btn btn-back">
                            ← Retour à la liste
                        </a>
                        <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=edit_groupe&id=<?php echo $groupe['id']; ?>" class="btn btn-edit">
                            ✏️ Modifier ce groupe
                        </a>
                        <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=delete_groupe&id=<?php echo $groupe['id']; ?>" class="btn btn-delete"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')">
                            🗑️ Supprimer ce groupe
                        </a>
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