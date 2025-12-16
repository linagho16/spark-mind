<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Groupe - Aide Solidaire</title>
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
          gap: 16px;
      }

      /* ✅ Error Message */
      .error-message {
          background: rgba(248, 215, 218, 0.95);
          border-radius: 16px;
          padding: 16px 20px;
          margin-bottom: 24px;
          border-left: 4px solid #dc3545;
          font-size: 14px;
          font-weight: 500;
          color: #721c24;
          box-shadow: 0 8px 20px rgba(0,0,0,0.08);
          display: flex;
          align-items: center;
          gap: 10px;
      }

      /* ✅ Group Info */
      .groupe-info {
          background: rgba(212, 237, 218, 0.95);
          border-radius: 16px;
          padding: 20px 24px;
          margin-bottom: 28px;
          border-left: 4px solid #28a745;
          box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      }

      .groupe-info h3 {
          font-family: 'Playfair Display', serif;
          font-size: 18px;
          margin-bottom: 8px;
          color: #1A464F;
      }

      .groupe-info p {
          font-size: 14px;
          color: #7a6f66;
          margin: 0;
      }

      /* ✅ Form Container */
      .form-container {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 32px 28px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .form-group {
          margin-bottom: 24px;
      }

      .form-label {
          display: block;
          margin-bottom: 10px;
          font-weight: 600;
          color: #1A464F;
          font-size: 14px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }

      .form-control {
          width: 100%;
          padding: 14px 18px;
          border: 2px solid rgba(26, 70, 79, 0.1);
          border-radius: 12px;
          font-size: 14px;
          background: white;
          color: #1A464F;
          font-family: 'Poppins', sans-serif;
          transition: all 0.3s ease;
      }

      .form-control:focus {
          outline: none;
          border-color: #1A464F;
          box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.1);
      }

      textarea.form-control {
          min-height: 140px;
          resize: vertical;
      }

      /* ✅ Form Layout */
      .form-row {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 20px;
      }

      /* ✅ Form Actions */
      .form-actions {
          display: flex;
          gap: 16px;
          margin-top: 32px;
          padding-top: 24px;
          border-top: 2px solid rgba(234, 226, 214, 0.8);
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
          
          .form-row {
              grid-template-columns: 1fr;
          }
          
          .form-actions {
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
          .form-container,
          .groupe-info {
              padding: 20px;
              border-radius: 18px;
          }
          
          .btn {
              padding: 10px 16px;
              font-size: 13px;
          }
          
          .form-control {
              padding: 12px 16px;
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
                        <h1>Modifier le Groupe</h1>
                        <p>Modifiez les informations du groupe #<?php echo htmlspecialchars($groupe['id'] ?? ''); ?></p>
                    </div>
                    <div class="header-right">
                        <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="btn btn-secondary">← Retour à la liste</a>
                        <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=view_groupe&id=<?php echo $groupe['id'] ?? ''; ?>" class="btn btn-primary">👁️ Voir</a>
                    </div>
                </header>

                <!-- Error Message -->
                <?php if (isset($error) && $error): ?>
                    <div class="error-message">
                        ⚠️ <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Group Info -->
                <div class="groupe-info">
                    <h3>Groupe #<?php echo htmlspecialchars($groupe['id'] ?? ''); ?> - <?php echo htmlspecialchars($groupe['nom'] ?? ''); ?></h3>
                    <p>Créé le: <?php echo isset($groupe['created_at']) ? date('d/m/Y', strtotime($groupe['created_at'])) : 'Date non disponible'; ?></p>
                </div>

                <!-- Edit Form -->
                <div class="form-container">
                    <form method="POST" action="/sparkmind_mvc_100percent/controller/groupeC.php?action=edit_groupe&id=<?php echo $groupe['id'] ?? ''; ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nom du groupe *</label>
                                <input type="text" name="nom" class="form-control" required 
                                       value="<?php echo htmlspecialchars($groupe['nom'] ?? ''); ?>"
                                       placeholder="Ex: Association Solidarité Tunis">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Type de groupe *</label>
                                <select name="type" class="form-control" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="Santé" <?php echo ($groupe['type'] ?? '') == 'Santé' ? 'selected' : ''; ?>>🏥 Santé</option>
                                    <option value="Éducation" <?php echo ($groupe['type'] ?? '') == 'Éducation' ? 'selected' : ''; ?>>📚 Éducation</option>
                                    <option value="Seniors" <?php echo ($groupe['type'] ?? '') == 'Seniors' ? 'selected' : ''; ?>>👵 Seniors</option>
                                    <option value="Jeunesse" <?php echo ($groupe['type'] ?? '') == 'Jeunesse' ? 'selected' : ''; ?>>👦 Jeunesse</option>
                                    <option value="Culture" <?php echo ($groupe['type'] ?? '') == 'Culture' ? 'selected' : ''; ?>>🎨 Culture</option>
                                    <option value="Urgence" <?php echo ($groupe['type'] ?? '') == 'Urgence' ? 'selected' : ''; ?>>🚨 Urgence</option>
                                    <option value="Animaux" <?php echo ($groupe['type'] ?? '') == 'Animaux' ? 'selected' : ''; ?>>🐾 Animaux</option>
                                    <option value="Environnement" <?php echo ($groupe['type'] ?? '') == 'Environnement' ? 'selected' : ''; ?>>🌿 Environnement</option>
                                    <option value="Religieux" <?php echo ($groupe['type'] ?? '') == 'Religieux' ? 'selected' : ''; ?>>🌙 Religieux</option>
                                    <option value="Social" <?php echo ($groupe['type'] ?? '') == 'Social' ? 'selected' : ''; ?>>🤝 Social</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Région *</label>
                                <select name="region" class="form-control" required>
                                    <option value="">Sélectionner une région</option>
                                    <option value="Tunis" <?php echo ($groupe['region'] ?? '') == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                                    <option value="Sfax" <?php echo ($groupe['region'] ?? '') == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                                    <option value="Sousse" <?php echo ($groupe['region'] ?? '') == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                                    <option value="Kairouan" <?php echo ($groupe['region'] ?? '') == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                                    <option value="Bizerte" <?php echo ($groupe['region'] ?? '') == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                                    <option value="Gabès" <?php echo ($groupe['region'] ?? '') == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                                    <option value="Ariana" <?php echo ($groupe['region'] ?? '') == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                                    <option value="Gafsa" <?php echo ($groupe['region'] ?? '') == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                                    <option value="Monastir" <?php echo ($groupe['region'] ?? '') == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                                    <option value="Autre" <?php echo ($groupe['region'] ?? '') == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Responsable *</label>
                                <input type="text" name="responsable" class="form-control" required 
                                       value="<?php echo htmlspecialchars($groupe['responsable'] ?? ''); ?>"
                                       placeholder="Ex: Mohamed Ali">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required 
                                       value="<?php echo htmlspecialchars($groupe['email'] ?? ''); ?>"
                                       placeholder="exemple@association.tn">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Téléphone *</label>
                                <input type="tel" name="telephone" class="form-control" required 
                                       value="<?php echo htmlspecialchars($groupe['telephone'] ?? ''); ?>"
                                       placeholder="Ex: +216 12 345 678">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Statut *</label>
                                <select name="statut" class="form-control" required>
                                    <option value="actif" <?php echo ($groupe['statut'] ?? '') == 'actif' ? 'selected' : ''; ?>>Actif</option>
                                    <option value="inactif" <?php echo ($groupe['statut'] ?? '') == 'inactif' ? 'selected' : ''; ?>>Inactif</option>
                                    <option value="en_attente" <?php echo ($groupe['statut'] ?? '') == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description du groupe</label>
                            <textarea name="description" class="form-control" 
                                      placeholder="Décrivez les activités et objectifs du groupe..."><?php echo htmlspecialchars($groupe['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                            <a href="/sparkmind_mvc_100percent/controller/groupeC.php?action=groupes" class="btn btn-secondary">Annuler</a>
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