<?php
// updatedon.php - Add this at the VERY TOP

// Check if don data is passed (from controller)
if (!isset($don) || empty($don)) {
    header('Location: /sparkmind_mvc_100percent/index.php?page=admin_dons&message=not_found');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier le Don - Aide Solidaire</title>
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

      /* ✅ Form Container */
      .form-container {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 32px 40px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.12);
      }

      .form-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 50px;
          margin-bottom: 24px;
      }

      .form-group {
          margin-bottom: 24px;
      }

      .form-group.full-width {
          grid-column: 1 / -1;
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

      .form-label.required-field::after {
          content: " *";
          color: #dc3545;
      }

      .form-control, .form-select {
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

      .form-control:focus, .form-select:focus {
          outline: none;
          border-color: #1A464F;
          box-shadow: 0 0 0 3px rgba(26, 70, 79, 0.1);
      }

      textarea.form-control {
          min-height: 140px;
          resize: vertical;
      }

      /* ✅ Form Help Text */
      .form-help {
          font-size: 12px;
          color: #7a6f66;
          margin-top: 6px;
          font-style: italic;
      }

      /* ✅ Info Section */
      .info-section {
          background: rgba(255, 255, 255, 0.9);
          border-radius: 16px;
          padding: 20px 24px;
          margin: 24px 0;
          border-left: 4px solid #1f8c87;
          box-shadow: 0 8px 20px rgba(0,0,0,0.06);
      }

      .info-section h4 {
          font-family: 'Playfair Display', serif;
          font-size: 18px;
          margin-bottom: 16px;
          color: #1A464F;
      }

      .info-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 16px;
          font-size: 14px;
      }

      .info-grid strong {
          color: #1A464F;
      }

      /* ✅ Badge */
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

      /* ✅ Form Actions */
      .form-actions {
          display: flex;
          gap: 16px;
          margin-top: 32px;
          padding-top: 24px;
          border-top: 2px solid rgba(234, 226, 214, 0.8);
          justify-content: flex-end;
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
          
          .form-grid {
              grid-template-columns: 1fr;
          }
          
          .info-grid {
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
          .form-container {
              padding: 20px;
              border-radius: 18px;
          }
          
          .btn {
              padding: 10px 16px;
              font-size: 13px;
          }
          
          .form-control, .form-select {
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
                        <h1>Modifier le Don #<?php echo $don['id']; ?></h1>
                        <p>Modifiez les informations de ce don</p>
                    </div>
                    <div class="header-right">
                        <a href="/sparkmind_mvc_100percent/controller/donC.php?action=dons" class="btn btn-secondary">
                            ← Retour à la liste
                        </a>
                    </div>
                </header>

                <!-- Success/Error Messages -->
                <?php if (isset($_GET['message'])): ?>
                    <?php
                    $messages = [
                        'updated' => ['type' => 'success', 'text' => 'Don modifié avec succès!'],
                        'error' => ['type' => 'error', 'text' => 'Erreur lors de la modification du don!'],
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

                <?php if (isset($error)): ?>
                    <div class="message-alert message-error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Edit Form -->
                <div class="form-container">
                    <form method="POST"
                        action="/sparkmind_mvc_100percent/controller/donC.php?action=edit_don&id=<?php echo (int)$don['id']; ?>"
                        enctype="multipart/form-data">



                        <div class="form-grid">
                            <!-- Type de Don -->
                            <div class="form-group">
                                <label for="type_don" class="form-label required-field">Type de don</label>
                                <select id="type_don" name="type_don" class="form-select" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="Vêtements" <?php echo $don['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>👕 Vêtements</option>
                                    <option value="Nourriture" <?php echo $don['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>🍞 Nourriture</option>
                                    <option value="Médicaments" <?php echo $don['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>💊 Médicaments</option>
                                    <option value="Équipement" <?php echo $don['type_don'] == 'Équipement' ? 'selected' : ''; ?>>🔧 Équipement</option>
                                    <option value="Argent" <?php echo $don['type_don'] == 'Argent' ? 'selected' : ''; ?>>💰 Argent</option>
                                    <option value="Services" <?php echo $don['type_don'] == 'Services' ? 'selected' : ''; ?>>🤝 Services</option>
                                    <option value="Autre" <?php echo $don['type_don'] == 'Autre' ? 'selected' : ''; ?>>🎁 Autre</option>
                                </select>
                                <div class="form-help">Choisissez le type de don</div>
                            </div>

                            <!-- Quantité -->
                            <div class="form-group">
                                <label for="quantite" class="form-label required-field">Quantité</label>
                                <input type="number" id="quantite" name="quantite" class="form-control" 
                                       value="<?php echo htmlspecialchars($don['quantite']); ?>" 
                                       required min="1" step="1">
                                <div class="form-help">Nombre d'articles ou montant</div>
                            </div>

                            <!-- État de l'objet -->
                            <div class="form-group">
                                <label for="etat_object" class="form-label">État de l'objet</label>
                                <input type="text" id="etat_object" name="etat_object" class="form-control" 
                                       value="<?php echo htmlspecialchars($don['etat_object'] ?? ''); ?>" 
                                       placeholder="Ex: Neuf, Bon état, Usé, Comme neuf...">
                                <div class="form-help">Décrivez l'état des articles (optionnel)</div>
                            </div>

                            <!-- Région -->
                            <div class="form-group">
                                <label for="region" class="form-label required-field">Région</label>
                                <select id="region" name="region" class="form-select" required>
                                    <option value="">Sélectionnez une région</option>
                                    <option value="Tunis" <?php echo $don['region'] == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                                    <option value="Sfax" <?php echo $don['region'] == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                                    <option value="Sousse" <?php echo $don['region'] == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                                    <option value="Kairouan" <?php echo $don['region'] == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                                    <option value="Bizerte" <?php echo $don['region'] == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                                    <option value="Gabès" <?php echo $don['region'] == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                                    <option value="Ariana" <?php echo $don['region'] == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                                    <option value="Gafsa" <?php echo $don['region'] == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                                    <option value="Monastir" <?php echo $don['region'] == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                                    <option value="Autre" <?php echo $don['region'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                                <div class="form-help">Région de disponibilité du don</div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group full-width">
                            <label for="description" class="form-label">Description détaillée</label>
                            <textarea id="description" name="description" class="form-control" rows="4" 
                                      placeholder="Décrivez le don en détail... (matériaux, dimensions, spécificités, etc.)"><?php echo htmlspecialchars($don['description']); ?></textarea>
                            <div class="form-help">Fournissez une description complète du don</div>
                        </div>

                        <!-- Informations de base -->
                        <div class="info-section">
                            <h4>Informations de base</h4>
                            <div class="info-grid">
                                <div>
                                    <strong>ID du don:</strong> #<?php echo $don['id']; ?>
                                </div>
                                <div>
                                    <strong>Date de création:</strong> <?php echo date('d/m/Y à H:i', strtotime($don['date_don'])); ?>
                                </div>
                                <div>
                                    <strong>Statut:</strong> 
                                    <span class="badge badge-active"><?php echo ucfirst($don['statut'] ?? 'actif'); ?></span>
                                </div>
                                <div>
                                    <strong>Dernière modification:</strong> 
                                    <?php echo isset($don['date_modification']) ? date('d/m/Y à H:i', strtotime($don['date_modification'])) : 'Jamais'; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                        <a href="/sparkmind_mvc_100percent/controller/donC.php?action=dons" class="btn btn-secondary">
                            Annuler
                        </a>

                        <button type="submit" class="btn btn-primary">
                            💾 Enregistrer les modifications
                        </button>
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
            
            // Auto-hide messages after 5 seconds
            setTimeout(function() {
                const messages = document.querySelectorAll('.message-alert');
                messages.forEach(message => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => message.remove(), 500);
                });
            }, 5000);

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const quantite = document.getElementById('quantite').value;
                    if (quantite < 1) {
                        alert('La quantité doit être au moins 1');
                        e.preventDefault();
                        return;
                    }
                });
            }
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