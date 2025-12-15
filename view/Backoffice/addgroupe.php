<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Groupe - Dashboard Admin</title>
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
      .top-nav {
          position: sticky;
          top: 0;
          z-index: 100;
          backdrop-filter: blur(14px);
          background: rgba(251, 237, 215, 0.96);
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 10px;
          padding: 10px 24px;
          border-bottom: 1px solid rgba(0,0,0,0.05);
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

      .btn-nav {
          border: none;
          cursor: pointer;
          padding: 8px 18px;
          border-radius: 999px;
          font-size: 13px;
          font-weight: 600;
          background: linear-gradient(135deg, #7d5aa6, #b58bf0);
          color: white;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 6px;
      }

      .btn-nav.secondary {
          background: transparent;
          color: #1A464F;
          border: 1px solid rgba(26, 70, 79, 0.35);
      }

      /* ✅ Admin Main Content */
      .admin-main {
          flex: 1;
          max-width: 1100px;
          margin: 32px auto 40px;
          padding: 0 18px 30px;
      }

      /* ✅ Page Header */
      .page-header {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px;
          margin-bottom: 30px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .page-header h1 {
          margin: 0 0 6px;
          font-family: 'Playfair Display', serif;
          font-size: 26px;
          color:#1A464F;
      }

      .page-subtitle {
          font-size: 13px;
          margin-bottom: 18px;
          color: #555;
      }

      /* ✅ Form Container */
      .form-container {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px 26px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      /* ✅ Form Elements */
      .form-group {
          margin-bottom: 20px;
      }

      .form-label {
          display: block;
          margin-bottom: 8px;
          font-weight: 600;
          color: #1A464F;
          font-size: 14px;
      }

      .form-label.required::after {
          content: " *";
          color: #dc3545;
      }

      .form-input, .form-select, .form-textarea {
          width: 100%;
          padding: 12px 16px;
          border: 2px solid rgba(26, 70, 79, 0.1);
          border-radius: 12px;
          font-size: 14px;
          transition: all 0.3s ease;
          box-sizing: border-box;
          background: white;
          color: #1A464F;
          font-family: 'Poppins', sans-serif;
      }

      .form-input:focus, .form-select:focus, .form-textarea:focus {
          outline: none;
          border-color: #1A464F;
          box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.15);
      }

      .form-textarea {
          min-height: 120px;
          resize: vertical;
          line-height: 1.5;
      }

      .form-row {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 20px;
      }

      /* ✅ Error Message */
      .error-message {
          background: rgba(248, 215, 218, 0.2);
          color: #721c24;
          padding: 16px 20px;
          border-radius: 12px;
          margin-bottom: 25px;
          border-left: 4px solid #dc3545;
          display: flex;
          align-items: center;
          gap: 12px;
          animation: slideIn 0.5s ease;
      }

      @keyframes slideIn {
          from { transform: translateY(-20px); opacity: 0; }
          to { transform: translateY(0); opacity: 1; }
      }

      /* ✅ Form Actions */
      .form-actions {
          display: flex;
          gap: 12px;
          margin-top: 25px;
          padding-top: 25px;
          border-top: 1px solid rgba(0,0,0,0.08);
      }

      .btn {
          padding: 12px 24px;
          border-radius: 999px;
          border: none;
          font-family: 'Poppins', sans-serif;
          font-weight: 600;
          cursor: pointer;
          text-decoration: none;
          text-align: center;
          display: inline-flex;
          align-items: center;
          gap: 8px;
          transition: all 0.3s ease;
      }

      .btn-primary {
          background: linear-gradient(135deg, #7d5aa6, #b58bf0);
          color: white;
      }

      .btn-secondary {
          background: linear-gradient(135deg, #1f8c87, #7eddd5);
          color: white;
      }

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      }

      /* ✅ Select Styling */
      .form-select {
          appearance: none;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237d5aa6' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
          background-repeat: no-repeat;
          background-position: right 1rem center;
          background-size: 12px;
          padding-right: 3rem;
      }

      /* ✅ Responsive Design */
      @media (max-width: 900px) {
        .sidebar{ width:220px; }
      }

      @media (max-width: 768px) {
        .layout{ flex-direction:column; }
        .sidebar{ 
          position:relative; 
          height:auto; 
          width:100%;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .form-container {
            padding: 20px;
        }
        
        .page-header {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .top-nav {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 15px;
        }
      }

      @media (max-width: 480px) {
        .admin-main {
            padding: 0 15px 20px;
        }
        
        .form-container {
            padding: 18px;
            border-radius: 18px;
        }
        
        .form-input, .form-select, .form-textarea {
            padding: 10px 14px;
            font-size: 13px;
        }
        
        .btn {
            padding: 10px 20px;
            font-size: 14px;
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
                <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php' || (!isset($_GET['action']) && isset($_GET['action']) == 'dashboard')) ? 'active' : ''; ?>">
                    <span class="icon">📊</span>
                    <span>Tableau de bord</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES DONS</div>
            <nav class="menu">
                <a href="/aide_solitaire/controller/donC.php?action=dons" class="menu-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'dons') ? '' : ''; ?>">
                    <span class="icon">🎁</span>
                    <span>Tous les dons</span>
                </a>
                
                <a href="/aide_solitaire/controller/donC.php?action=create_don" class="menu-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'create_don') ? 'active' : ''; ?>">
                    <span class="icon">➕</span>
                    <span>Ajouter un don</span>
                </a>
                
                <a href="/aide_solitaire/controller/donC.php?action=statistics" class="menu-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'statistics') ? 'active' : ''; ?>">
                    <span class="icon">📈</span>
                    <span>Statistiques dons</span>
                </a>
            </nav>

            <div class="menu-title">GESTION DES GROUPES</div>
            <nav class="menu">
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="menu-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'groupes') ? '' : ''; ?>">
                    <span class="icon">👥</span>
                    <span>Tous les groupes</span>
                </a>
                
                <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="menu-item active">
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
                    <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-nav">
                        <span>←</span>
                        <span>Retour</span>
                    </a>
                </div>
            </div>

            <!-- ✅ Main Content -->
            <div class="admin-main">
                <!-- Page Header -->
                <div class="page-header">
                    <h1>Créer un Nouveau Groupe</h1>
                    <p class="page-subtitle">Ajoutez un nouveau groupe de solidarité</p>
                </div>

                <!-- Error Message -->
                <?php if (isset($error) && $error): ?>
                    <div class="error-message">
                        <span>⚠️</span>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <!-- Creation Form -->
                <div class="form-container">
                    <form method="POST" action="/aide_solitaire/controller/groupeC.php?action=create_groupe">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nom du groupe</label>
                                <input type="text" name="nom" class="form-input" required 
                                       placeholder="Ex: Association Solidarité Tunis">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Type de groupe</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="Santé">🏥 Santé</option>
                                    <option value="Éducation">📚 Éducation</option>
                                    <option value="Seniors">👵 Seniors</option>
                                    <option value="Jeunesse">👦 Jeunesse</option>
                                    <option value="Culture">🎨 Culture</option>
                                    <option value="Urgence">🚨 Urgence</option>
                                    <option value="Animaux">🐾 Animaux</option>
                                    <option value="Environnement">🌿 Environnement</option>
                                    <option value="Religieux">🌙 Religieux</option>
                                    <option value="Social">🤝 Social</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Région</label>
                                <select name="region" class="form-select" required>
                                    <option value="">Sélectionner une région</option>
                                    <option value="Tunis">Tunis</option>
                                    <option value="Sfax">Sfax</option>
                                    <option value="Sousse">Sousse</option>
                                    <option value="Kairouan">Kairouan</option>
                                    <option value="Bizerte">Bizerte</option>
                                    <option value="Gabès">Gabès</option>
                                    <option value="Ariana">Ariana</option>
                                    <option value="Gafsa">Gafsa</option>
                                    <option value="Monastir">Monastir</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Responsable</label>
                                <input type="text" name="responsable" class="form-input" required 
                                       placeholder="Ex: Mohamed Ali">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email" class="form-input" required 
                                       placeholder="exemple@association.tn">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Téléphone</label>
                                <input type="tel" name="telephone" class="form-input" required 
                                       placeholder="Ex: +216 12 345 678">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description du groupe</label>
                            <textarea name="description" class="form-textarea" 
                                      placeholder="Décrivez les activités et objectifs du groupe..."></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <span>➕</span>
                                <span>Créer le groupe</span>
                            </button>
                            <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn btn-secondary">
                                <span>✕</span>
                                <span>Annuler</span>
                            </a>
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