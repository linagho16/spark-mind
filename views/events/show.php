<?php
// Page: Détails de l'Événement (style SPARKMIND)
// On suppose que $event est déjà défini (chargé depuis le controller)

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Détails de l'événement</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND — IDENTIQUE */
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
    .btn-nav.warn{ background:#ffb3b3; color:#7a1010; }

    .admin-main{ flex:1; max-width:1100px; margin:32px auto 40px; padding:0 18px 30px; }
    .admin-card{
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 24px 22px 26px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    }

    .admin-card h1{ margin:0 0 6px; font-family:'Playfair Display', serif; font-size:26px; }
    .admin-subtitle{ font-size:13px; margin-bottom:18px; color:#444; }

    /* ✅ Détails événement (compatible SPARKMIND) */
    .detail-hero{
      display:flex;
      gap:12px;
      align-items:flex-start;
      justify-content:space-between;
      flex-wrap:wrap;
      margin-bottom:12px;
    }

    .price-pill{
      padding: 8px 14px;
      border-radius: 999px;
      background: rgba(255,255,255,0.9);
      border: 1px solid rgba(26,70,79,0.18);
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      font-weight: 700;
      color: #1A464F;
      white-space:nowrap;
    }

    .card-soft{
      background:#fff7ef;
      border-radius:18px;
      padding:14px 14px 16px;
      box-shadow:0 8px 18px rgba(0,0,0,0.08);
      margin-top:14px;
    }

    .info-grid{
      display:flex;
      flex-wrap:wrap;
      gap:12px;
      margin-top:12px;
    }

    .info-item{
      flex:1 1 220px;
      background: rgba(255,255,255,0.9);
      border-radius:14px;
      padding:10px 12px;
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
      border-left:4px solid rgba(26,70,79,0.25);
      font-size:13px;
    }

    .info-item strong{ display:block; margin-bottom:4px; }

    .actions-row{
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
            <span class="brand-tagline">Détails de l’événement</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <div class="detail-hero">
            <div>
              <h1 style="margin:0 0 6px;">Détails de l'Événement</h1>
              <p class="admin-subtitle" style="margin:0;">Consultez les informations et gérez cet événement.</p>
            </div>

            <a href="/sparkmind_mvc_100percent/index.php?page=events_dashboard" class="btn-nav secondary">← Retour à la liste</a>
          </div>

          <div class="card-soft">
            <div class="detail-hero" style="margin-bottom:8px;">
              <h2 style="margin:0; font-family:'Playfair Display', serif;">
                <?php echo htmlspecialchars($event['titre']); ?>
              </h2>

              <div class="price-pill">
                💰 <?php echo number_format((float)$event['prix'], 2); ?> €
              </div>
            </div>

            <div style="margin-top:10px;">
              <h3 style="margin:0 0 8px; font-size:16px;">📝 Description</h3>
              <div style="font-size:13px; color:#444; line-height:1.6;">
                <?php echo nl2br(htmlspecialchars($event['description'])); ?>
              </div>
            </div>

            <div class="info-grid">
              <div class="info-item">
                <strong>📍 Lieu</strong>
                <div><?php echo htmlspecialchars($event['lieu']); ?></div>
              </div>

              <div class="info-item">
                <strong>📅 Date</strong>
                <div><?php echo date('d/m/Y', strtotime($event['date_event'])); ?></div>
              </div>

              <div class="info-item">
                <strong>💰 Prix</strong>
                <div><?php echo number_format((float)$event['prix'], 2); ?> €</div>
              </div>
            </div>

            <div class="actions-row">
                <a href="/sparkmind_mvc_100percent/index.php?page=event_edit&id=<?php echo (int)$event['id']; ?>"
                class="btn-nav">
                ✏️ Modifier
                </a>

                <a href="/sparkmind_mvc_100percent/index.php?page=event_delete&id=<?php echo (int)$event['id']; ?>"
                class="btn-nav danger"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                🗑 Supprimer
                </a>

            </div>
          </div>

        </section>
      </main>

    </div>
  </div>
</div>
</body>
</html>
