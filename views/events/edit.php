<?php
// Vérifier que $event existe avant de l'utiliser
if (!isset($event) || empty($event)) {
    header('Location: index.php?action=events');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Modifier l'événement</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND — IDENTIQUE */
    body{
      margin:0;
      min-height:100vh;
      background:
        radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
        radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
        #FBEDD7;
      font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      color:#1A464F;
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
    .brand-name{
      font-family:'Playfair Display', serif;
      font-size:20px;
      color:#1A464F;
    }
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
      white-space:nowrap;
    }

    .btn-nav.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26, 70, 79, 0.35);
    }

    .btn-nav.danger{ background:#E23B3B; }

    .admin-main{ flex:1; max-width:1100px; margin:32px auto 40px; padding:0 18px 30px; }

    .admin-card{
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 24px 22px 26px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    }

    .admin-card h1{ margin:0 0 6px; font-family:'Playfair Display', serif; font-size:26px; }
    .admin-subtitle{ font-size:13px; margin-bottom:18px; color:#444; }

    /* ✅ Alerts (SPARKMIND) */
    .alert{
      padding: 10px 14px;
      border-radius: 16px;
      background: rgba(255,255,255,0.9);
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      margin-bottom: 12px;
      font-size: 13px;
      color:#444;
      border-left: 4px solid rgba(26,70,79,0.25);
    }
    .alert-success{ border-left-color:#1b6b2a; }
    .alert-danger{ border-left-color:#b02222; }

    /* ✅ Form card */
    .form-card{
      background:#fff7ef;
      border-radius:18px;
      padding:14px 14px 16px;
      box-shadow:0 8px 18px rgba(0,0,0,0.08);
    }

    .form-row{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-bottom:10px;
    }

    .form-field{
      flex: 1 1 220px;
      font-size:13px;
    }

    .form-field label{
      display:block;
      margin-bottom:4px;
      font-size:12px;
      color:#444;
      font-weight:600;
    }

    .form-control{
      width:100%;
      padding:10px 12px;
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

    .actions-row{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      margin-top:12px;
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

    <div style="margin:-6px 12px 6px; color:#6B5F55; font-size:12px;">
      Gestion des événements
    </div>

    <nav class="menu">
      <div class="menu-title">📊 Dashboard admin</div>

      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_dashboard">📊 Tableau de bord</a>
      <a class="menu-item active" href="/sparkmind_mvc_100percent/index.php?page=events_list">📅 Événements</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservations_list">🎫 Réservations</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservation_create">➕ Nouvelle Réservation</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=event_create">✨ Nouvel Événement</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=scanner">📷 Scanner</a>
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
            <span class="brand-tagline">Modifier l’événement</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:flex-start;">
            <div>
              <h1>Modifier l'Événement</h1>
              <p class="admin-subtitle">Mettez à jour les informations de l’événement.</p>
            </div>
            <a href="/sparkmind_mvc_100percent/index.php?page=events_dashboard" class="btn-nav secondary">← Retour à la liste</a>
          </div>

          <?php
          // Messages session
          if (isset($_SESSION['error'])) {
              echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
              unset($_SESSION['error']);
          }
          if (isset($_SESSION['success'])) {
              echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
              unset($_SESSION['success']);
          }
          ?>

          <div class="form-card">
            <form method="POST" action="/sparkmind_mvc_100percent/index.php?page=event_update">
            <input type="hidden" name="id" value="<?php echo (int)$event['id']; ?>">


              <div class="form-row">
                <div class="form-field" style="flex: 1 1 100%;">
                  <label for="titre">Titre *</label>
                  <input
                    type="text"
                    id="titre"
                    name="titre"
                    class="form-control"
                    value="<?php echo htmlspecialchars($event['titre'] ?? ''); ?>"
                    required
                  >
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex: 1 1 100%;">
                  <label for="description">Description *</label>
                  <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="5"
                    required
                  ><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
                </div>
              </div>

              <div class="form-row">
                <div class="form-field">
                  <label for="lieu">Lieu *</label>
                  <input
                    type="text"
                    id="lieu"
                    name="lieu"
                    class="form-control"
                    value="<?php echo htmlspecialchars($event['lieu'] ?? ''); ?>"
                    required
                  >
                </div>

                <div class="form-field">
                  <label for="prix">Prix (€)</label>
                  <input
                    type="number"
                    id="prix"
                    name="prix"
                    class="form-control"
                    step="0.01"
                    min="0"
                    value="<?php echo htmlspecialchars($event['prix'] ?? '0'); ?>"
                  >
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex: 1 1 320px;">
                  <label for="date_event">Date de l'événement *</label>
                  <input
                    type="date"
                    id="date_event"
                    name="date_event"
                    class="form-control"
                    value="<?php echo !empty($event['date_event']) ? date('Y-m-d', strtotime($event['date_event'])) : ''; ?>"
                    required
                  >
                </div>
              </div>

              <div class="actions-row">
                <button type="submit" class="btn-nav">✅ Mettre à jour</button>

                <a href="/sparkmind_mvc_100percent/index.php?page=event_show&id=1" class="btn-nav secondary">↩️ Annuler</a>

                <a href="/sparkmind_mvc_100percent/index.php?page=event_delete&id=<?php echo (int)$event['id']; ?>"
                class="btn-nav danger"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                🗑 Supprimer
                </a>
              </div>

            </form>
          </div>

        </section>
      </main>

    </div>
  </div>
</div>
</body>
</html>
