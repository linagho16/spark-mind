<?php
// Recherche, Tri et Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $perPage;

if ($search) {
    $totalReservations = $reservation->count($search);
    $reservations = $reservation->search($search, $perPage, $offset, $sortBy);
} else {
    $totalReservations = $reservation->count();
    $reservations = $reservation->getAll($perPage, $offset, $sortBy);
}

$totalPages = (int)ceil($totalReservations / $perPage);

/**
 * IMPORTANT :
 * - Cette page est maintenant une page complète HTML (avec head/style/layout SPARKMIND).
 * - Si tu utilises un layout global (header/sidebar déjà inclus ailleurs), dis-moi et je te fais la version "partial".
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Liste des réservations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND — IDENTIQUE À LA PAGE PRÉCÉDENTE */
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
    .site{ min-height: 100vh; display:flex; flex-direction:column; }

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
    }

    .filters-bar .search-wrapper{
      flex:1 1 260px;
      display:flex;
      align-items:center;
      gap:6px;
    }

    .search-input{
      flex:1;
      padding:8px 12px;
      border-radius:999px;
      border:1px solid #ccc;
      font-size:13px;
      outline:none;
      background:#fff;
    }
    .search-input:focus{
      border-color:#1A464F;
      box-shadow:0 0 0 2px rgba(26,70,79,0.15);
    }

    .filters-bar label{
      font-size:12px;
      color:#444;
      display:flex;
      flex-direction:column;
      gap:4px;
      min-width: 220px;
    }

    .filters-bar select{
      padding:7px 10px;
      border-radius:999px;
      border:1px solid #ddd;
      font-size:13px;
      background:#fff;
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

    .tag{
      padding:2px 8px;
      border-radius:999px;
      font-size:11px;
      display:inline-block;
      white-space:nowrap;
      text-transform: capitalize;
    }

    /* Badges statut réservation */
    .badge{ padding:2px 8px; border-radius:999px; font-size:11px; display:inline-block; white-space:nowrap; }
    .badge-success{ background:#e0f9e5; color:#1b6b2a; }
    .badge-warning{ background:#ffe0b3; color:#8a4b00; }
    .badge-danger{ background:#ffe0e0; color:#b02222; }
    .badge-secondary{ background:#e0f5ff; color:#005f8a; }

    /* Badges ticket */
    .badge-pending{ background:#ffe0b3; color:#8a4b00; }
    .badge-issued{ background:#e0f5ff; color:#005f8a; }
    .badge-used{ background:#e0f9e5; color:#1b6b2a; }
    .badge-cancelled{ background:#ffe0e0; color:#b02222; }

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
    }

    .btn-table.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26,70,79,0.35);
    }

    .btn-table.danger{ background:#E23B3B; }
    .btn-table.warn{ background:#ffb3b3; color:#7a1010; }
    .btn-table.ok{ background:#1A464F; }

    .empty-state{
      padding:26px 16px;
      text-align:center;
      color:#444;
      background: rgba(255,255,255,0.9);
      border-radius:18px;
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
    }
    .empty-state .emoji{ font-size:40px; margin-bottom:10px; }

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

  <!-- ✅ SIDEBAR (identique page précédente) -->
  <aside class="sidebar">
    <a class="brand">
      <span class="brand-name">SPARKMIND</span>
    </a>

    <div style="margin: -6px 12px 6px; color:#6B5F55; font-size:12px;">
      Gestion des réservations
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

      <!-- HEADER (même vibe) -->
      <header class="top-nav">
        <div class="brand-block">
          <div class="brand-text">
            <span class="brand-name">SPARKMIND</span>
            <span class="brand-tagline">Liste des réservations</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <h1>📋 Liste des Réservations</h1>
          <p class="admin-subtitle">
            <?php if ($search): ?>
              Résultats pour <strong><?= htmlspecialchars($search) ?></strong> (<?= (int)$totalReservations ?>)
            <?php else: ?>
              Consultez et gérez toutes les réservations.
            <?php endif; ?>
          </p>

          <?php if ($search): ?>
          <div class="search-info">
              <p style="margin:0;">
                🔍 Recherche : <strong><?= htmlspecialchars($search) ?></strong>
                (<?= (int)$totalReservations ?> résultat<?= $totalReservations > 1 ? 's' : '' ?>)
                <a href="?action=reservations" class="btn-clear-search">✖ Effacer</a>
              </p>
          </div>
          <?php endif; ?>

          <!-- BARRE FILTRES -->
          <div class="filters-bar">
            <div class="search-wrapper">
              <span>🔎</span>
              <input
                class="search-input"
                type="text"
                placeholder="Rechercher par référence, client, email…"
                value="<?= htmlspecialchars($search) ?>"
                onkeydown="if(event.key==='Enter'){ window.location.href='?action=reservations&search='+encodeURIComponent(this.value)+'&sort=<?= htmlspecialchars($sortBy) ?>'; }"
              />
            </div>

            <label>
              Trier par
              <select id="sortReservations"
                      onchange="window.location.href='?action=reservations&sort=' + this.value + '<?= $search ? '&search=' . urlencode($search) : '' ?>'">
                <option value="date_desc" <?= $sortBy == 'date_desc' ? 'selected' : '' ?>>Date réservation (récent → ancien)</option>
                <option value="date_asc" <?= $sortBy == 'date_asc' ? 'selected' : '' ?>>Date réservation (ancien → récent)</option>
                <option value="event_date_asc" <?= $sortBy == 'event_date_asc' ? 'selected' : '' ?>>Date événement (proche → loin)</option>
                <option value="event_date_desc" <?= $sortBy == 'event_date_desc' ? 'selected' : '' ?>>Date événement (loin → proche)</option>
                <option value="client_asc" <?= $sortBy == 'client_asc' ? 'selected' : '' ?>>Client (A → Z)</option>
                <option value="montant_desc" <?= $sortBy == 'montant_desc' ? 'selected' : '' ?>>Montant (élevé → faible)</option>
              </select>
            </label>

            <a class="btn-nav" href="/sparkmind_mvc_100percent/index.php?page=reservation_create">➕ Nouvelle Réservation</a>
          </div>

          <?php if (empty($reservations)): ?>
            <div class="empty-state">
              <div class="emoji">📭</div>
              <h3 style="margin:0 0 6px;">Aucune réservation trouvée</h3>
              <p style="margin:0 0 12px;">Créez votre première réservation</p>
              <a href="?action=create_reservation" class="btn-nav">Créer une réservation</a>
            </div>
          <?php else: ?>
            <div class="users-table-wrapper">
              <table>
                <thead>
                  <tr>
                    <th>Référence</th>
                    <th>Client</th>
                    <th>Événement</th>
                    <th>Places</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Ticket</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reservations as $res): ?>
                    <?php
                      $badgeClass = [
                        'confirmée'  => 'badge-success',
                        'en attente' => 'badge-warning',
                        'annulée'    => 'badge-danger'
                      ][$res['statut']] ?? 'badge-warning';

                      $ticketStatus = $res['ticket_status'] ?? 'pending';
                      $ticketBadgeClass = [
                        'pending'   => 'badge-pending',
                        'issued'    => 'badge-issued',
                        'used'      => 'badge-used',
                        'cancelled' => 'badge-cancelled'
                      ][$ticketStatus] ?? 'badge-secondary';

                      $ticketIcons = [
                        'pending'   => '⏳',
                        'issued'    => '🎫',
                        'used'      => '✅',
                        'cancelled' => '❌'
                      ];
                    ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($res['reference']) ?></strong></td>

                      <td>
                        <strong><?= htmlspecialchars($res['nom_client']) ?></strong><br>
                        <small><?= htmlspecialchars($res['email']) ?></small><br>
                        <small><?= htmlspecialchars($res['telephone']) ?></small>
                      </td>

                      <td>
                        <?= htmlspecialchars($res['event_titre'] ?? 'N/A') ?><br>
                        <small><?= !empty($res['date_event']) ? date('d/m/Y', strtotime($res['date_event'])) : '—' ?></small>
                      </td>

                      <td><?= (int)$res['nombre_places'] ?></td>
                      <td><?= number_format((float)$res['montant_total'], 2) ?> €</td>
                      <td><?= date('d/m/Y H:i', strtotime($res['date_reservation'])) ?></td>

                      <td>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($res['statut']) ?></span>
                      </td>

                      <td style="text-align:center;">
                        <?php if (!empty($res['ticket_code'])): ?>
                          <span class="badge <?= $ticketBadgeClass ?>">
                            <?= ($ticketIcons[$ticketStatus] ?? '🎫') . ' ' . ucfirst($ticketStatus) ?>
                          </span>
                        <?php elseif (($res['statut'] ?? '') === 'confirmée'): ?>
                          <button
                            onclick="issueTicket(<?= (int)$res['id'] ?>)"
                            class="btn-table"
                            title="Émettre un ticket">
                            🎫 Émettre
                          </button>
                        <?php else: ?>
                          <span style="color:#999;">-</span>
                        <?php endif; ?>
                      </td>

                      <td>
                        <?php if (!empty($res['ticket_code'])): ?>
                          <a href="ticket_view.php?id=<?= (int)$res['id'] ?>"
                             class="btn-table"
                             title="Voir le ticket"
                             target="_blank">🎫 Ticket</a>
                        <?php endif; ?>

                        <a href="?action=edit_reservation&id=<?= (int)$res['id'] ?>"
                           class="btn-table secondary"
                           title="Modifier">✏️ Modifier</a>

                        <?php if (($res['statut'] ?? '') === 'en attente'): ?>
                          <a href="process_reservation.php?action=update_status&id=<?= (int)$res['id'] ?>&status=confirmée"
                             class="btn-table"
                             onclick="return confirm('Confirmer cette réservation ?')"
                             title="Confirmer">✅ Confirmer</a>
                        <?php endif; ?>

                        <?php if (($res['statut'] ?? '') !== 'annulée'): ?>
                          <a href="process_reservation.php?action=update_status&id=<?= (int)$res['id'] ?>&status=annulée"
                             class="btn-table warn"
                             onclick="return confirm('Annuler cette réservation ?')"
                             title="Annuler">❌ Annuler</a>
                        <?php endif; ?>

                        <a href="process_reservation.php?action=delete&id=<?= (int)$res['id'] ?>"
                           class="btn-table danger"
                           onclick="return confirm('Supprimer définitivement cette réservation ?')"
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
                  $base = '?action=reservations'
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
                Affichage de <?= min($offset + 1, $totalReservations) ?>
                à <?= min($offset + $perPage, $totalReservations) ?>
                sur <?= (int)$totalReservations ?> réservation(s)
              </div>
            <?php endif; ?>
          <?php endif; ?>

        </section>
      </main>

    </div>
  </div>
</div>

<script>
  async function issueTicket(reservationId) {
    if (!confirm('Émettre un ticket pour cette réservation ?')) return;

    try {
      const formData = new FormData();
      formData.append('reservation_id', reservationId);

      const response = await fetch('api/ticket_operations.php?action=issue', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        alert('✅ Ticket émis avec succès!\nCode: ' + data.data.ticket_code);
        location.reload();
      } else {
        alert('❌ Erreur: ' + (data.error || "Impossible d'émettre le ticket"));
      }
    } catch (error) {
      alert('❌ Erreur: ' + error.message);
    }
  }
</script>

</body>
</html>
