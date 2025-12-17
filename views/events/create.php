<?php
// (optionnel) session_start(); si ce n'est pas déjà fait dans ton bootstrap
// session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Créer un événement</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND — IDENTIQUE AUX PAGES PRÉCÉDENTES */
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

    .layout{ min-height:100vh; display:flex; }

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

    .sidebar .brand-name{
      font-family:'Playfair Display', serif;
      font-weight:800;
      font-size:18px;
      color:#1A464F;
    }

    .menu{ display:flex; flex-direction:column; gap:6px; margin-top:6px; }

    .menu-title{
      font-size:10px;
      font-weight:700;
      letter-spacing:.06em;
      color:#1A464F;
      padding:8px 12px 6px;
      text-transform:uppercase;
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

    .menu-item:hover{ background:#f5e2c4ff; }
    .menu-item.active{ background:#f5e2c4ff; color:#0b3936ff; }

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

    .sidebar-foot .link:hover{ background:#f5e2c4ff; }

    .main{ flex:1; min-width:0; }
    .site{ min-height:100vh; display:flex; flex-direction:column; }

    .top-nav{
      position:sticky;
      top:0;
      z-index:100;
      backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96);
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:10px 24px;
    }

    .brand-block{ display:flex; align-items:center; gap:12px; }
    .brand-text{ display:flex; flex-direction:column; line-height:1.1; }
    .brand-name{ font-family:'Playfair Display', serif; font-size:20px; color:#1A464F; }
    .brand-tagline{ font-size:11px; color:#1A464F; opacity:.8; }

    .header-actions{ display:flex; gap:10px; align-items:center; }

    .btn-nav{
      border:none;
      cursor:pointer;
      padding:8px 14px;
      border-radius:999px;
      font-size:13px;
      font-weight:500;
      background:#1A464F;
      color:#fff;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }

    .btn-nav.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26,70,79,0.35);
    }

    .admin-main{ flex:1; max-width:1100px; margin:32px auto 40px; padding:0 18px 30px; }
    .admin-card{
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 24px 22px 26px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    }
    .admin-card h1{ margin:0 0 6px; font-family:'Playfair Display', serif; font-size:26px; }
    .admin-subtitle{ font-size:13px; margin-bottom:18px; color:#444; }

    /* Alerts (compatible avec ton echo alert alert-success/warning/danger) */
    .alert{
      padding: 10px 14px;
      border-radius: 16px;
      background: rgba(255,255,255,0.9);
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      margin-bottom: 14px;
      font-size: 13px;
      color: #444;
    }
    .alert-success{ border-left: 4px solid #1b6b2a; }
    .alert-warning{ border-left: 4px solid #8a4b00; }
    .alert-danger{ border-left: 4px solid #b02222; }

    /* ✅ Form cards (comme admin-form-card) */
    .form-card{
      background:#fff7ef;
      border-radius:18px;
      padding:14px 14px 16px;
      box-shadow:0 8px 18px rgba(0,0,0,0.08);
      margin-bottom:14px;
    }

    .form-card h2{
      margin:0 0 10px;
      font-size:18px;
      font-weight:600;
      color:#1A464F;
    }

    .form-row{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-bottom:10px;
    }

    .form-field{
      flex: 1 1 260px;
      font-size:13px;
    }

    .form-field label{
      display:block;
      margin-bottom:3px;
      font-size:12px;
      color:#444;
      font-weight:600;
    }

    .form-control{
      width:100%;
      padding:8px 10px;
      border-radius:12px;
      border:1px solid #ccc;
      font-size:13px;
      outline:none;
      background:#fff;
    }

    .form-control:focus{
      border-color:#1A464F;
      box-shadow:0 0 0 2px rgba(26,70,79,0.15);
    }

    .form-actions{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      margin-top:14px;
    }

    @media (max-width: 900px) { .sidebar{ width:220px; } }
    @media (max-width: 800px) {
      .sidebar{ position:relative; height:auto; }
      .layout{ flex-direction:column; }
    }
  </style>
</head>

<body>

<div class="layout">

  <!-- ✅ SIDEBAR -->
  <aside class="sidebar">
    <a class="brand">
      <span class="brand-name">SPARKMIND</span>
    </a>

    <div style="margin: -6px 12px 6px; color:#6B5F55; font-size:12px;">
      Gestion des événements
    </div>

    <nav class="menu">
      <div class="menu-title">📊 Dashboard admin</div>
      <a class="menu-item active" href="/sparkmind_mvc_100percent/index.php?page=events_dashboard">📊 Tableau de bord</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_list">📅 Événements</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservations_list">🎫 Réservations</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservation_create">➕ Nouvelle Réservation</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=event_create">✨ Nouvel Événement</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_scan">📷 Scanner</a>

    </nav>

    <div class="sidebar-foot">
      <a class="link" href="?action=front">← Front Office</a>
    </div>
  </aside>

  <!-- ✅ MAIN -->
  <div class="main">
    <div class="site">

      <header class="top-nav">
        <div class="brand-block">
          <div class="brand-text">
            <span class="brand-name">SPARKMIND</span>
            <span class="brand-tagline">Créer un nouvel événement</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <?php
          // ✅ Messages session
          if (isset($_SESSION['message'])) {
              $type = $_SESSION['message_type'] ?? 'success';
              echo '<div class="alert alert-' . htmlspecialchars($type) . '">';
              echo htmlspecialchars($_SESSION['message']);
              echo '</div>';
              unset($_SESSION['message'], $_SESSION['message_type']);
          }
          ?>

          <h1>🎭 Créer un Nouvel Événement</h1>
          <p class="admin-subtitle">Remplissez le formulaire pour créer un nouvel événement</p>

          <form action="process_event.php?action=create" method="POST" class="event-form">

            <div class="form-card">
              <h2>📝 Informations de base</h2>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 520px;">
                  <label for="titre">Titre de l'événement *</label>
                  <input type="text" id="titre" name="titre" class="form-control"
                         required
                         placeholder="Ex: Concert de Jazz"
                         value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 520px;">
                  <label for="description">Description *</label>
                  <textarea id="description" name="description" class="form-control" rows="5"
                            required
                            placeholder="Décrivez votre événement..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
              </div>
            </div>

            <div class="form-card">
              <h2>📍 Détails pratiques</h2>

              <div class="form-row">
                <div class="form-field">
                  <label for="lieu">Lieu *</label>
                  <input type="text" id="lieu" name="lieu" class="form-control"
                         required
                         placeholder="Ex: Salle de concert municipale"
                         value="<?php echo htmlspecialchars($_POST['lieu'] ?? ''); ?>">
                </div>

                <div class="form-field">
                  <label for="prix">Prix (€)</label>
                  <input type="number" id="prix" name="prix" class="form-control"
                         step="0.01" min="0"
                         placeholder="0.00"
                         value="<?php echo htmlspecialchars($_POST['prix'] ?? '0'); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 320px;">
                  <label for="date_event">Date de l'événement *</label>
                  <input type="date" id="date_event" name="date_event" class="form-control"
                         required
                         value="<?php echo htmlspecialchars($_POST['date_event'] ?? ''); ?>">
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-nav" name="submit">✅ Créer l'Événement</button>
              <a href="/sparkmind_mvc_100percent/index.php?page=events_dashboard" class="btn-nav secondary">↩️ Annuler</a>
            </div>

          </form>

        </section>
      </main>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const today = new Date().toISOString().split('T')[0];
  const dateInput = document.getElementById('date_event');
  if (dateInput) dateInput.min = today;

  const form = document.querySelector('.event-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value || !field.value.toString().trim()) {
          field.style.borderColor = '#f56565';
          isValid = false;
        } else {
          field.style.borderColor = '';
        }
      });

      const dateField = document.getElementById('date_event');
      if (dateField && dateField.value && new Date(dateField.value) < new Date(today)) {
        alert("La date de l'événement ne peut pas être dans le passé.");
        dateField.style.borderColor = '#f56565';
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires (*) avec des valeurs valides.');
      }
    });
  }
});
</script>

</body>
</html>
