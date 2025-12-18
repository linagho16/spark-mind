<?php
// Recherche, Tri et Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $perPage;

if ($search) {
    $totalEvents = $eventModel->countAllEvents($search);
    $events = $eventModel->search($search, $perPage, $offset, $sortBy);
} else {
    $totalEvents = $eventModel->countAllEvents();
    $events = $eventModel->getAllEvents($perPage, $offset, $sortBy);
}

$totalPages = (int)ceil($totalEvents / $perPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Liste des événements</title>
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

    .search-info{
      margin: 10px 0 16px;
      padding: 10px 14px;
      border-radius: 16px;
      background: rgba(255,255,255,0.9);
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      font-size: 13px;
      color:#444;
    }
    .btn-clear-search{
      margin-left:10px;
      text-decoration:none;
      font-weight:600;
      color:#1A464F;
      padding:4px 10px;
      border-radius:999px;
      border:1px solid rgba(26,70,79,0.25);
      display:inline-block;
    }
    .btn-clear-search:hover{ background:#f5e2c4ff; }

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
      justify-content:space-between;
    }

    .filters-bar label{
      font-size:12px;
      color:#444;
      display:flex;
      flex-direction:column;
      gap:4px;
      min-width: 240px;
      font-weight:600;
    }

    .filters-bar select{
      padding:7px 10px;
      border-radius:999px;
      border:1px solid #ddd;
      font-size:13px;
      background:#fff;
      font-weight:500;
    }

    .users-table-wrapper{
      overflow-x:auto;
      background:#fff;
      border-radius:18px;
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
    }

    table{
      border-collapse:collapse;
      width:100%;
      border-radius:18px;
      overflow:hidden;
    }
    table td, table th{
      padding:10px;
      border-bottom:1px solid #eee;
      font-size:13px;
      vertical-align:middle;
    }
    table th{ background-color:#f7f1eb; text-align:left; }

    .btn-table{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      padding:4px 10px;
      border-radius:999px;
      font-size:11px;
      border:none;
      cursor:pointer;
      text-decoration:none;
      margin:2px 0;
      background:#1A464F;
      color:#fff;
      white-space:nowrap;
    }
    .btn-table.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26,70,79,0.35);
    }
    .btn-table.warn{ background:#ffb3b3; color:#7a1010; }
    .btn-table.danger{ background:#E23B3B; }

    .tag{
      padding:2px 10px;
      border-radius:999px;
      font-size:11px;
      display:inline-block;
      white-space:nowrap;
      background:#e0f5ff;
      color:#005f8a;
    }

    .empty-state{
      padding:26px 16px;
      text-align:center;
      color:#444;
      background: rgba(255,255,255,0.9);
      border-radius:18px;
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
    }
    .empty-state .emoji{ font-size:40px; margin-bottom:10px; }

    .pagination{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:center;
      margin-top:16px;
    }
    .pagination-btn, .pagination-number{
      text-decoration:none;
      padding:6px 12px;
      border-radius:999px;
      border:1px solid rgba(26,70,79,0.25);
      color:#1A464F;
      background: rgba(255,255,255,0.9);
      font-size: 13px;
      font-weight: 600;
    }
    .pagination-number.active{
      background:#1A464F;
      color:#fff;
      border-color:#1A464F;
    }
    .pagination-pages{ display:flex; gap:6px; flex-wrap:wrap; }

    .pagination-info{
      text-align:center;
      font-size:12px;
      color:#444;
      margin-top:10px;
      opacity:0.9;
    }

    @media (max-width: 900px) { .sidebar{ width:220px; } }
    @media (max-width: 800px) {
      .sidebar{ position:relative; height:auto; }
      .layout{ flex-direction:column; }
      .filters-bar{ flex-direction:column; align-items:stretch; }
      .filters-bar label{ min-width:unset; }
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
            <span class="brand-tagline">Liste des événements</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <h1>📅 Liste des Événements</h1>
          <p class="admin-subtitle">
            <?php if ($search): ?>
              Résultats pour <strong><?= htmlspecialchars($search) ?></strong> (<?= (int)$totalEvents ?>)
            <?php else: ?>
              Consultez, modifiez ou supprimez vos événements.
            <?php endif; ?>
          </p>

          <?php if ($search): ?>
            <div class="search-info">
              <p style="margin:0;">
                🔍 Recherche : <strong><?= htmlspecialchars($search) ?></strong>
                (<?= (int)$totalEvents ?> résultat<?= $totalEvents > 1 ? 's' : '' ?>)
                <a href="?action=events" class="btn-clear-search">✖ Effacer</a>
              </p>
            </div>
          <?php endif; ?>

          <!-- BARRE TRI + ACTION -->
          <div class="filters-bar">
            <label>
              📅 Trier par
              <select id="sortEvents"
                      onchange="window.location.href='?action=events&sort=' + this.value + '<?= $search ? '&search=' . urlencode($search) : '' ?>'">
                <option value="date_desc" <?= $sortBy == 'date_desc' ? 'selected' : '' ?>>Date (récent → ancien)</option>
                <option value="date_asc" <?= $sortBy == 'date_asc' ? 'selected' : '' ?>>Date (ancien → récent)</option>
                <option value="titre_asc" <?= $sortBy == 'titre_asc' ? 'selected' : '' ?>>Titre (A → Z)</option>
                <option value="titre_desc" <?= $sortBy == 'titre_desc' ? 'selected' : '' ?>>Titre (Z → A)</option>
                <option value="prix_desc" <?= $sortBy == 'prix_desc' ? 'selected' : '' ?>>Prix (élevé → faible)</option>
                <option value="prix_asc" <?= $sortBy == 'prix_asc' ? 'selected' : '' ?>>Prix (faible → élevé)</option>
              </select>
            </label>

            <a href="/sparkmind_mvc_100percent/index.php?page=event_create" class="btn-nav">🎭 Nouvel Événement</a>
          </div>

          <?php if (empty($events)): ?>
            <div class="empty-state">
              <div class="emoji">📭</div>
              <h3 style="margin:0 0 6px;">Aucun événement trouvé</h3>
              <p style="margin:0 0 12px;">Commencez par créer votre premier événement</p>
              <a href="?action=create_event" class="btn-nav">✨ Créer un événement</a>
            </div>
          <?php else: ?>
            <div class="users-table-wrapper">
              <table>
                <thead>
                  <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Lieu</th>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Durée</th>
                    <th>Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($events as $event): ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($event['titre']) ?></strong></td>
                      <td><?= htmlspecialchars(substr($event['description'], 0, 50)) ?>...</td>
                      <td><?= htmlspecialchars($event['lieu']) ?></td>
                      <td><?= date('d/m/Y', strtotime($event['date_event'])) ?></td>

                      <td>
                        <?php
                          // tu faisais un getEventById ici : on garde la logique
                          $prix = $eventModel->getEventById($event['id'])['prix'] ?? 0;
                        ?>
                        <span class="tag"><?= number_format((float)$prix, 2) ?> €</span>
                      </td>

                      <td><?= htmlspecialchars($event['duree'] ?? '') ?></td>

                      <td>
                        <a href="/sparkmind_mvc_100percent/index.php?page=event_show&id=<?= (int)$event['id'] ?>"
                           class="btn-table"
                           title="Voir réservations">📋 Réservations</a>

                        <a href="?action=edit_event&id=<?= (int)$event['id'] ?>"
                           class="btn-table secondary"
                           title="Modifier">✏️ Modifier</a>

                        <a href="process_event.php?action=delete&id=<?= (int)$event['id'] ?>"
                           class="btn-table danger"
                           onclick="return confirm('Supprimer cet événement ?')"
                           title="Supprimer">🗑 Supprimer</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>

              </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
              <div class="pagination">
                <?php
                  $base = '?action=events'
                        . ($search ? '&search=' . urlencode($search) : '')
                        . ($sortBy ? '&sort=' . urlencode($sortBy) : '');
                ?>

                <?php if ($page > 1): ?>
                  <a class="pagination-btn" href="<?= $base ?>&page=<?= $page - 1 ?>">← Précédent</a>
                <?php endif; ?>

                <div class="pagination-pages">
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="pagination-number <?= $i == $page ? 'active' : '' ?>"
                       href="<?= $base ?>&page=<?= $i ?>"><?= $i ?></a>
                  <?php endfor; ?>
                </div>

                <?php if ($page < $totalPages): ?>
                  <a class="pagination-btn" href="<?= $base ?>&page=<?= $page + 1 ?>">Suivant →</a>
                <?php endif; ?>
              </div>

              <div class="pagination-info">
                Affichage de <?= min($offset + 1, $totalEvents) ?>
                à <?= min($offset + $perPage, $totalEvents) ?>
                sur <?= (int)$totalEvents ?> événement(s)
              </div>
            <?php endif; ?>
          <?php endif; ?>

        </section>
      </main>

    </div>
  </div>
</div>
</body>
</html>
