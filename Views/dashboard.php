<?php
// Récupérer les vraies données de la base de données

// EventModel
if (!isset($eventModel)) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../models/EventModel.php';
    $eventModel = new EventModel($pdo);
}

// Reservation
if (!isset($reservation)) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../models/Reservation.php';
    $reservation = new Reservation($pdo);
}

$eventsCount = $eventModel->countEvents();

$stats = $reservation->getStats();
$reservationsCount = $stats['total'] ?? 0;
$confirmedCount    = $stats['confirmées'] ?? 0;
$totalRevenue      = $stats['revenu_total'] ?? 0.00;

$upcomingEvents = $eventModel->getUpcomingEvents(5);

// Calculer le taux de remplissage
$totalPlaces     = $eventsCount * 100; // 100 places par événement
$placesReservees = $reservationsCount;
$tauxRemplissage = $totalPlaces > 0 ? round(($placesReservees / $totalPlaces) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Dashboard Événements</title>
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
      letter-spacing:0.06em;
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

    .menu-item.active{
      background:#f5e2c4ff;
      color:#0b3936ff;
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
    .brand-tagline{ font-size:11px; color:#1A464F; opacity:0.8; }

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
    }

    .btn-nav.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26, 70, 79, 0.35);
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

    .stats-row{ display:flex; flex-wrap:wrap; gap:12px; margin:14px 0 18px; }
    .stat-card{
      flex: 1 1 160px;
      background: rgba(255,255,255,0.9);
      padding: 10px 12px;
      border-radius: 14px;
      box-shadow: 0 8px 18px rgba(0,0,0,0.08);
      font-size: 13px;
      opacity: 0;
      transform: translateY(12px);
      transition: all .45s ease;
    }
    .stat-card strong{ font-size: 15px; }

    .filters-bar{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      align-items:center;
      padding: 10px 14px;
      border-radius: 16px;
      background: rgba(255,255,255,0.9);
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      margin-bottom: 18px;
      justify-content: space-between;
    }

    .users-table-wrapper{
      overflow-x:auto;
      background:#fff;
      border-radius:18px;
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
    }
    table{ border-collapse:collapse; width:100%; border-radius:18px; overflow:hidden; }
    table td, table th{ padding:10px; border-bottom:1px solid #eee; font-size:13px; vertical-align:middle; }
    table th{ background-color:#f7f1eb; text-align:left; }

    .tag{
      padding:2px 10px;
      border-radius:999px;
      font-size:11px;
      display:inline-block;
      white-space:nowrap;
    }
    .tag-green{ background:#e0f9e5; color:#1b6b2a; }

    .btn-table{
      display:inline-block;
      padding:4px 10px;
      border-radius:999px;
      font-size:11px;
      border:none;
      cursor:pointer;
      text-decoration:none;
      margin:2px 0;
      background:#1A464F;
      color:#fff;
    }

    .empty-state{
      padding: 26px 16px;
      text-align:center;
      color:#444;
    }
    .empty-state .emoji{ font-size:40px; margin-bottom:10px; }

    @media (max-width: 900px) { .sidebar{ width:220px; } }
    @media (max-width: 800px) {
      .sidebar{ position:relative; height:auto; }
      .layout{ flex-direction:column; }
      .filters-bar{ flex-direction:column; align-items:stretch; }
      .stats-row{ flex-direction:column; }
    }
  </style>
</head>

<body>

<div class="layout">

  <!-- SIDEBAR -->
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

  <!-- MAIN -->
  <div class="main">
    <div class="site">

      <!-- HEADER -->
      <header class="top-nav">
        <div class="brand-block">
          <div class="brand-text">
            <span class="brand-name">SPARKMIND</span>
            <span class="brand-tagline">Tableau de bord Événements</span>
          </div>
        </div>

        <div class="header-actions">
          <button class="btn-nav secondary" onclick="window.location.href='?action=main'">🏠 Espace utilisateur</button>
          <button class="btn-nav" onclick="window.location.href='?action=logout'">🚪 Déconnexion</button>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <!-- ✅ AJOUT DU "CONTENU PRINCIPAL" DEMANDÉ SANS CHANGER LE CSS -->
          <div style="margin-bottom: 10px;">
            <h1 style="margin:0 0 6px;">Tableau de bord</h1>
            <p class="admin-subtitle" style="margin-bottom:0;">Vue d'ensemble de votre activité</p>
          </div>

          <!-- Statistiques (même contenu, rendu SPARKMIND) -->
          <div class="stats-row">
            <div class="stat-card">
              Événements<br>
              <strong><?php echo (int)$eventsCount; ?></strong>
            </div>

            <div class="stat-card">
              Réservations<br>
              <strong><?php echo (int)$reservationsCount; ?></strong>
            </div>

            <div class="stat-card">
              Confirmées<br>
              <strong><?php echo (int)$confirmedCount; ?></strong>
            </div>

            <div class="stat-card">
              Revenu Total<br>
              <strong><?php echo number_format((float)$totalRevenue, 2, ',', ' '); ?> €</strong>
            </div>

            <div class="stat-card">
              Taux de remplissage<br>
              <strong><?php echo (int)$tauxRemplissage; ?> %</strong>
            </div>
          </div>

          <!-- Événements à venir + actions (même contenu) -->
          <div class="filters-bar">
            <div style="font-weight:600;">📅 Événements à venir</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
              <button class="btn-nav" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=events_list'">✨ Nouvel Événement</button>
              <button class="btn-nav secondary" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=reservations_list'">🎫 Nouvelle Réservation</button>
            </div>
          </div>

          <div class="users-table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Titre</th>
                  <th>Date</th>
                  <th>Lieu</th>
                  <th>Prix</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody>
                <?php if (empty($upcomingEvents)): ?>
                  <tr>
                    <td colspan="5">
                      <div class="empty-state">
                        <div class="emoji">📭</div>
                        <strong>Aucun événement à venir</strong>
                        <div style="margin-top:8px;">Créez votre premier événement pour commencer</div>
                        <button class="btn-nav" style="margin-top:12px;" onclick="window.location.href='?action=create_event'">
                          ✨ Créer un événement
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($upcomingEvents as $event): ?>
                    <tr>
                      <td><strong><?php echo htmlspecialchars($event['titre']); ?></strong></td>
                      <td><?php echo date('d/m/Y', strtotime($event['date_event'])); ?></td>
                      <td><?php echo htmlspecialchars($event['lieu']); ?></td>
                      <td>
                        <span class="tag tag-green">
                          <?php echo number_format((float)$event['prix'], 2, ',', ' '); ?> €
                        </span>
                      </td>
                      <td>
                        <a class="btn-table"href="/sparkmind_mvc_100percent/index.php?page=event_show&id=<?php echo (int)$event['id']; ?>">👁 Voir</a>

                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Revenu total (même contenu, rendu SPARKMIND) -->
          <div style="margin-top:18px;">
            <div class="stat-card" style="opacity:1; transform:none; max-width:520px;">
              💰 Revenu Total Généré<br>
              <strong><?php echo number_format((float)$totalRevenue, 2, ',', ' '); ?> €</strong><br>
              <span style="font-size:12px; color:#555;">Sur l'ensemble des réservations confirmées</span>
            </div>
          </div>

        </section>
      </main>

    </div>
  </div>
</div>

<script>
  // ✅ Animation des cartes (même effet, sans casser le thème)
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, index * 90);
    });
  });
</script>

</body>
</html>
