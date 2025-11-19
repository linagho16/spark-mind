<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un Don - Dashboard Admin</title>
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

    /* FORM STYLES */
    .form-container {
      background: white;
      padding: 2rem;
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      margin: 1rem 0;
      max-width: 800px;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #333;
    }

    .form-control {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: #4a90e2;
    }

    .form-select {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 1rem;
      background-color: white;
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .btn-submit {
      background: linear-gradient(135deg, #1f8c87, #7eddd5);
      color: white;
      padding: 0.75rem 2rem;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .btn-submit:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(31,140,135,0.3);
    }

    .btn-cancel {
      background: #6c757d;
      color: white;
      padding: 0.75rem 2rem;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .btn-cancel:hover {
      background: #5a6268;
      transform: scale(1.05);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .error-message {
      background: #fee;
      color: #c33;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      border-left: 4px solid #c33;
    }

    .success-message {
      background: #efe;
      color: #363;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      border-left: 4px solid #363;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
      .form-row {
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

      .form-container {
        padding: 1.5rem;
      }

      .form-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <aside class="sidebar">
    <div class="logo">
      <h2>🕊️ Admin</h2>
    </div>
    <nav class="nav-menu">
      <a href="/aide_solitaire/index.php?action=dashboard" class="nav-item">
        <span class="icon">📊</span>
        <span>Dashboard</span>
      </a>
      <a href="/aide_solitaire/index.php?action=dons" class="nav-item">
        <span class="icon">🎁</span>
        <span>Dons</span>
      </a>
      <a href="/aide_solitaire/index.php?action=statistics" class="nav-item">
        <span class="icon">📈</span>
        <span>Statistiques</span>
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="#" class="nav-item">
        <span class="icon">🚪</span>
        <span>Déconnexion</span>
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Top Header -->
    <header class="top-header">
      <div class="header-left">
        <h1>Ajouter un Don</h1>
        <p>Remplissez le formulaire pour ajouter un nouveau don</p>
      </div>
      <div class="header-right">
        <div class="user-profile">
          <span class="notification-badge">3</span>
          <div class="avatar">👤</div>
        </div>
      </div>
    </header>

    <!-- Form Container -->
    <div class="form-container">
      <?php if (isset($error)): ?>
        <div class="error-message">
          ❌ <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['message']) && $_GET['message'] == 'created'): ?>
        <div class="success-message">
          ✅ Don ajouté avec succès!
        </div>
      <?php endif; ?>

      <form action="/aide_solitaire/index.php?action=create_don" method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="type_don">Type de Don *</label>
            <select class="form-select" id="type_don" name="type_don" required>
              <option value="">Sélectionnez un type</option>
              <option value="Vêtements">Vêtements</option>
              <option value="Nourriture">Nourriture</option>
              <option value="Médicaments">Médicaments</option>
              <option value="Équipement">Équipement</option>
              <option value="Argent">Argent</option>
              <option value="Services">Services</option>
              <option value="Autre">Autre</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="quantite">Quantité *</label>
            <input type="text" class="form-control" id="quantite" name="quantite" 
                   placeholder="Ex: 10 articles, 5kg, 150€" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="etat_object">État de l'objet</label>
            <select class="form-select" id="etat_object" name="etat_object">
              <option value="">Sélectionnez l'état</option>
              <option value="Neuf">Neuf</option>
              <option value="Très bon état">Très bon état</option>
              <option value="Bon état">Bon état</option>
              <option value="État moyen">État moyen</option>
              <option value="À réparer">À réparer</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="region">Région *</label>
            <select class="form-select" id="region" name="region" required>
              <option value="">Sélectionnez une région</option>
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
        </div>

        <div class="form-group">
          <label class="form-label" for="photos">Photos</label>
          <input type="file" class="form-control" id="photos" name="photos" 
                 accept="image/*">
          <small style="color: #666; margin-top: 0.5rem; display: block;">
            Formats acceptés: JPG, PNG, GIF. Taille max: 2MB
          </small>
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Description détaillée *</label>
          <textarea class="form-control" id="description" name="description" 
                    placeholder="Décrivez le don en détail..." required></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
          <a href="/aide_solitaire/index.php?action=dons" class="btn-cancel">Annuler</a>
          <button type="submit" class="btn-submit">Ajouter le Don</button>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Dynamic form validation
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const fileInput = document.getElementById('photos');
      
      // File size validation
      fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const fileSize = file.size / 1024 / 1024; // in MB
          if (fileSize > 2) {
            alert('Le fichier est trop volumineux. Taille maximum: 2MB');
            this.value = '';
          }
        }
      });

      // Form submission validation
      form.addEventListener('submit', function(e) {
        const typeDon = document.getElementById('type_don').value;
        const quantite = document.getElementById('quantite').value;
        const region = document.getElementById('region').value;
        const description = document.getElementById('description').value;

        if (!typeDon || !quantite || !region || !description) {
          e.preventDefault();
          alert('Veuillez remplir tous les champs obligatoires (*)');
          return false;
        }
      });
    });
  </script>
</body>
</html>