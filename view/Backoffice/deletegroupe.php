<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supprimer Groupe - Aide Solidaire</title>
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

      /* ✅ Confirmation Container */
      .confirmation-container {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 32px 28px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
          text-align: center;
      }

      .warning-icon {
          font-size: 60px;
          margin-bottom: 20px;
          color: #dc3545;
      }

      .confirmation-title {
          font-family: 'Playfair Display', serif;
          font-size: 28px;
          color: #1A464F;
          margin-bottom: 16px;
          font-weight: 700;
      }

      .confirmation-message {
          font-size: 15px;
          color: #7a6f66;
          margin-bottom: 28px;
          line-height: 1.6;
          max-width: 700px;
          margin-left: auto;
          margin-right: auto;
      }

      /* ✅ Group Details */
      .groupe-details {
          background: white;
          padding: 24px;
          border-radius: 18px;
          margin-bottom: 28px;
          text-align: left;
          box-shadow: 0 8px 24px rgba(0,0,0,0.06);
      }

      .groupe-details h3 {
          font-family: 'Playfair Display', serif;
          font-size: 20px;
          color: #1A464F;
          margin-bottom: 20px;
          padding-bottom: 12px;
          border-bottom: 2px solid rgba(26, 70, 79, 0.1);
      }

      .detail-row {
          display: flex;
          margin-bottom: 16px;
          padding-bottom: 16px;
          border-bottom: 1px solid rgba(234, 226, 214, 0.8);
      }

      .detail-row:last-child {
          border-bottom: none;
          margin-bottom: 0;
          padding-bottom: 0;
      }

      .detail-label {
          font-weight: 600;
          color: #1A464F;
          width: 150px;
          font-size: 14px;
      }

      .detail-value {
          color: #7a6f66;
          flex: 1;
          font-size: 14px;
      }

      .detail-value strong {
          color: #1A464F;
      }

      /* ✅ Status Badge */
      .status-badge {
          display: inline-block;
          padding: 4px 12px;
          border-radius: 999px;
          font-size: 12px;
          font-weight: 600;
          margin-right: 8px;
      }

      .status-active {
          background: rgba(32, 201, 151, 0.15);
          color: #0d805b;
      }

      .status-inactive {
          background: rgba(108, 117, 125, 0.15);
          color: #495057;
      }

      .status-pending {
          background: rgba(253, 126, 20, 0.15);
          color: #d35400;
      }

      /* ✅ Confirmation Actions */
      .confirmation-actions {
          display: flex;
          gap: 16px;
          justify-content: center;
          margin-top: 28px;
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
          
          .confirmation-container {
              padding: 24px 20px;
          }
          
          .detail-row {
              flex-direction: column;
              gap: 8px;
          }
          
          .detail-label {
              width: 100%;
          }
          
          .confirmation-actions {
              flex-direction: column;
          }
          
          .btn {
              width: 100%;
              justify-content: center;
          }
      }

      @media (max-width: 480px) {
          .admin-main {
              padding: 0 15px 20px;
          }
          
          .top-header,
          .confirmation-container,
          .groupe-details {
              padding: 20px;
              border-radius: 18px;
          }
          
          .confirmation-title {
              font-size: 22px;
          }
          
          .warning-icon {
              font-size: 48px;
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
                <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="menu-item">
                    <span class="icon">👥</span>
                    <span>Tous les groupes</span>
                </a>

                <a href="/sparkmind_mvc_100percent/sparkmind_mvc_100percent/controller/donC.php?action=dons/controller/groupeC.php?action=create_groupe" class="menu-item">
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
                        <h1>Supprimer un Groupe</h1>
                        <p>Confirmez la suppression du groupe #<?php echo htmlspecialchars($groupe['id'] ?? ''); ?></p>
                    </div>
                    <div class="header-right">
                        <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="btn btn-secondary">← Retour à la liste</a>
                    </div>
                </header>

                <!-- Confirmation Box -->
                <div class="confirmation-container">
                    <div class="warning-icon">⚠️</div>
                    
                    <h2 class="confirmation-title">Êtes-vous sûr de vouloir supprimer ce groupe ?</h2>
                    
                    <p class="confirmation-message">
                        Cette action est irréversible. Toutes les informations relatives à ce groupe seront définitivement supprimées.
                    </p>

                    <!-- Group Details -->
                    <div class="groupe-details">
                        <h3>Détails du groupe à supprimer</h3>
                        
                        <div class="detail-row">
                            <div class="detail-label">ID :</div>
                            <div class="detail-value"><strong>#<?php echo htmlspecialchars($groupe['id'] ?? ''); ?></strong></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Nom :</div>
                            <div class="detail-value"><?php echo htmlspecialchars($groupe['nom'] ?? ''); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Type :</div>
                            <div class="detail-value">
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
                                echo ($icons[$groupe['type']] ?? '👥') . ' ' . htmlspecialchars($groupe['type'] ?? '');
                                ?>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Région :</div>
                            <div class="detail-value"><?php echo htmlspecialchars($groupe['region'] ?? ''); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Responsable :</div>
                            <div class="detail-value"><?php echo htmlspecialchars($groupe['responsable'] ?? ''); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Email :</div>
                            <div class="detail-value"><?php echo htmlspecialchars($groupe['email'] ?? ''); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Téléphone :</div>
                            <div class="detail-value"><?php echo htmlspecialchars($groupe['telephone'] ?? ''); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Statut :</div>
                            <div class="detail-value">
                                <?php if (($groupe['statut'] ?? '') == 'actif'): ?>
                                    <span class="status-badge status-active">Actif</span>
                                <?php elseif (($groupe['statut'] ?? '') == 'inactif'): ?>
                                    <span class="status-badge status-inactive">Inactif</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">En attente</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($groupe['description'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Description :</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($groupe['description'] ?? '')); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="detail-row">
                            <div class="detail-label">Créé le :</div>
                            <div class="detail-value"><?php echo isset($groupe['created_at']) ? date('d/m/Y à H:i', strtotime($groupe['created_at'])) : 'Date non disponible'; ?></div>
                        </div>
                    </div>

                    <!-- Confirmation Actions -->
                    <form method="POST" action="/sparkmind_mvc_100percent/controller/groupeC.php?action=delete_groupe&id=<?php echo $groupe['id'] ?? ''; ?>">
                        <div class="confirmation-actions">
                            <button type="submit" class="btn btn-danger">🗑️ Oui, supprimer définitivement</button>
                            <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="btn btn-secondary">Non, annuler</a>
                        </div>
                    </form>
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