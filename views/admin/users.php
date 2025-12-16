<?php
// Vue listing des utilisateurs (AdminController::users)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SPARKMIND — Administration des utilisateurs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Polices -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">

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

      /* ✅ Sidebar (identique à ton modèle) */
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
        background: #f5e2c4ff; !important;
        color: #0b3936ff;
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

      /* ✅ Main (ton contenu reste pareil) */
      .main{
        flex:1;
        min-width:0;
      }

      /* (tout le reste de ton CSS existant : inchangé) */
      .site { min-height: 100vh; display: flex; flex-direction: column; }

      .top-nav {
          position: sticky;
          top: 0;
          z-index: 100;
          backdrop-filter: blur(14px);
          background: rgba(251, 237, 215, 0.96);
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 10px 24px;
      }


      .menu-title {
        font-size: 10px;      /* ← taille du texte + emoji */
        font-weight: 700;
        letter-spacing: 0.06em;
        color: #1A464F;
        padding: 8px 12px 6px;
        text-transform: uppercase;
        }


      .brand-block { display: flex; align-items: center; gap: 12px; }
      .logo-img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; }
      .brand-text { display: flex; flex-direction: column; line-height: 1.1; }
      .brand-name { font-family: 'Playfair Display', serif; font-size: 20px; color: #1A464F; }
      .brand-tagline { font-size: 11px; color: #1A464F; opacity: 0.8; }

      .header-actions { display: flex; gap: 10px; align-items: center; }
      .btn-nav { border: none; cursor: pointer; padding: 8px 14px; border-radius: 999px; font-size: 13px; font-weight: 500; background: #1A464F; color: #fff; }
      .btn-nav.secondary { background: transparent; color: #1A464F; border: 1px solid rgba(26, 70, 79, 0.35); }

      .admin-main { flex: 1; max-width: 1100px; margin: 32px auto 40px; padding: 0 18px 30px; }
      .admin-card { background: rgba(255, 247, 239, 0.95); border-radius: 24px; padding: 24px 22px 26px; box-shadow: 0 20px 40px rgba(0,0,0,0.18); }
      .admin-card h1 { margin: 0 0 6px; font-family: 'Playfair Display', serif; font-size: 26px; }
      .admin-subtitle { font-size: 13px; margin-bottom: 18px; color: #444; }

      .filters-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; padding: 10px 14px; border-radius: 16px; background: rgba(255,255,255,0.9); box-shadow: 0 8px 18px rgba(0,0,0,0.06); margin-bottom: 18px; }
      .filters-bar .search-wrapper { flex: 1 1 220px; display: flex; align-items: center; gap: 6px; }
      .search-input { flex: 1; padding: 8px 12px; border-radius: 999px; border: 1px solid #ccc; font-size: 13px; outline: none; background: #fff; }
      .search-input:focus { border-color: #1A464F; box-shadow: 0 0 0 2px rgba(26,70,79,0.15); }

      .filters-bar select { padding: 7px 10px; border-radius: 999px; border: 1px solid #ddd; font-size: 13px; background: #fff; min-width: 170px; }
      .filters-bar label { font-size: 12px; color: #444; display: flex; flex-direction: column; gap: 4px; }

      .stats-row { display: flex; flex-wrap: wrap; gap: 12px; margin: 5px 0 14px; }
      .stat-card { flex: 1 1 120px; background: rgba(255,255,255,0.9); padding: 10px 12px; border-radius: 14px; box-shadow: 0 8px 18px rgba(0,0,0,0.08); font-size: 13px; }
      .stat-card strong { font-size: 15px; }

      .admin-form-card { background: #fff7ef; border-radius: 18px; padding: 14px 14px 16px; box-shadow: 0 8px 18px rgba(0,0,0,0.08); margin-bottom: 18px; }
      .admin-form-title { margin: 0 0 6px; font-size: 18px; font-weight: 600; }
      .admin-form-sub { font-size: 12px; margin-bottom: 10px; color: #555; }

      .admin-form-row { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; }
      .admin-form-field { flex: 1 1 150px; font-size: 13px; }
      .admin-form-field label { display: block; margin-bottom: 3px; font-size: 12px; color: #444; }

      .admin-form-field input, .admin-form-field select {
          width: 100%;
          padding: 6px 8px;
          border-radius: 8px;
          border: 1px solid #ccc;
          font-size: 13px;
          outline: none;
          background: #fff;
      }
              /* Titres comme "Menu principal", "Actions rapides" */
    .sidebar .menu-title {
        color: #1A464F !important;
    }

      .users-table-wrapper { overflow-x: auto; background: #fff; border-radius: 18px; box-shadow: 0 8px 18px rgba(0,0,0,0.06); }
      table { border-collapse: collapse; width: 100%; border-radius: 18px; overflow: hidden; }
      table td, table th { padding: 10px; border-bottom: 1px solid #eee; font-size: 13px; vertical-align: middle; }
      table th { background-color: #f7f1eb; }

      .tag { padding: 2px 8px; border-radius: 999px; font-size: 11px; }
      .tag-admin   { background:#ffe0b3; color:#8a4b00; }
      .tag-user    { background:#e0f5ff; color:#005f8a; }
      .tag-active  { background:#e0f9e5; color:#1b6b2a; }
      .tag-blocked { background:#ffe0e0; color:#b02222; }

      .btn-table { display:inline-block; padding:4px 10px; border-radius:999px; font-size:11px; border:none; cursor:pointer; text-decoration:none; margin:2px 0; }
      .btn-table.view { background:#1A464F; color:#fff; }
      .btn-table.delete { background:#E23B3B; color:#fff; }
      .btn-table.block { background:#ffb3b3; color:#7a1010; }
      .btn-table.unblock { background:#b9f4c4; color:#145321; }

      @media (max-width: 900px) {
        .sidebar{ width:220px; }
      }
      @media (max-width: 800px) {
        .filters-bar { flex-direction: column; align-items: stretch; }
        .sidebar{ position:relative; height:auto; }
        .layout{ flex-direction:column; }
      }
    </style>
</head>

<body>

<div class="layout">

  <!-- ✅ SIDEBAR GAUCHE -->
  <aside class="sidebar">
    <a class="brand" >
      <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo" />
      <span class="brand-name">SPARKMIND</span>
    </a>
        <div style="margin: -6px 12px 6px; color:#6B5F55; font-size:12px;">
      Gestion des utilisateurs
    </div>

    <nav class="menu">
     <div class="menu-title">📊 Dashboard admin</div>
      <a class="menu-item active" href="index.php?page=admin_users">👥 Utilisateurs</a>
      <a href="index.php?page=backoffice" class="menu-item">👩‍🦰 Demandeurs</a>
      <a href="index.php?page=admin_forum" class="menu-item">🗣 Expressions</a>
      <a class="menu-item" >📅 Événements</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=backoffice_aide">👥 Groupes / 🎁dons </a>

    </nav>

    <div class="sidebar-foot">
      <a class="link" href="index.php?page=front">← Front Office</a>
    </div>
  </aside>

  <!-- ✅ CONTENU (inchangé) -->
  <div class="main">
    <div class="site">

      <!-- HEADER -->
      <header class="main-header top-nav">
        <div class="brand-block">
          <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
          <div class="brand-text">
            <span class="brand-name">SPARKMIND</span>
            <span class="brand-tagline">Quand la pensée devient espoir</span>
          </div>
        </div>

        <div class="header-actions">
          <button class="btn-nav secondary" onclick="window.location.href='index.php?page=main'">🏠 Espace utilisateur</button>
          <button class="btn-nav" onclick="window.location.href='index.php?page=logout'">🚪 Déconnexion</button>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <h1>Administration des utilisateurs</h1>
          <p class="admin-subtitle">
            Bonjour
            <strong><?= htmlspecialchars($_SESSION['user_prenom'].' '.$_SESSION['user_nom']) ?></strong>
            (<?= htmlspecialchars($_SESSION['user_email']) ?>)
          </p>

          <?php
            $currentSiteRole   = $currentSiteRole   ?? 'all';
            $currentDateFilter = $currentDateFilter ?? 'all';

            $filters = [
                'all'     => 'Tous les rôles',
                'seeker'  => 'Demandeurs',
                'helper'  => 'Donneurs',
                'both'    => 'Les deux',
                'speaker' => 'Expression',
            ];

            $dateFilters = [
                'all'        => 'Toutes les dates',
                'today'      => "Aujourd'hui",
                'yesterday'  => 'Hier',
                'week'       => '7 derniers jours',
                'last_month' => 'Mois dernier',
                'last_year'  => 'Année dernière',
            ];
          ?>

          <!-- BARRE FILTRES + RECHERCHE -->
          <div class="filters-bar">
            <div class="search-wrapper">
              <span>🔍</span>
              <input type="text" id="user-search" class="search-input" placeholder="Rechercher (nom, prénom, email)...">
            </div>

            <label>
              Rôle sur SPARKMIND
              <select id="filter-role">
                <?php foreach ($filters as $value => $label): ?>
                  <option value="<?= $value ?>" <?= $currentSiteRole === $value ? 'selected' : '' ?>>
                    <?= htmlspecialchars($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>

            <label>
              Date d'inscription
              <select id="filter-date">
                <?php foreach ($dateFilters as $value => $label): ?>
                  <option value="<?= $value ?>" <?= $currentDateFilter === $value ? 'selected' : '' ?>>
                    <?= htmlspecialchars($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>

          <!-- STATS -->
          <div class="stats-row">
            <div class="stat-card">
              Comptes affichés<br>
              <strong><?= count($users) ?></strong>
            </div>
            <div class="stat-card">
              Admins<br>
              <strong>
                <?php $admins=0; foreach($users as $u){ if($u['role']==='admin') $admins++; } echo $admins; ?>
              </strong>
            </div>
            <div class="stat-card">
              Utilisateurs<br>
              <strong><?= count($users)-$admins ?></strong>
            </div>
          </div>

          <!-- FORMULAIRE D'AJOUT -->
          <form method="post" action="index.php?page=admin_users" class="admin-form-card">
            <h2 class="admin-form-title">➕ Ajouter un utilisateur</h2>
            <p class="admin-form-sub">Créez rapidement un nouveau compte pour la communauté SPARKMIND.</p>

            <input type="hidden" name="action" value="create_user">

            <div class="admin-form-row">
              <div class="admin-form-field">
                <label>Prénom</label>
                <input type="text" name="prenom" required>
              </div>

              <div class="admin-form-field">
                <label>Nom</label>
                <input type="text" name="nom" required>
              </div>

              <div class="admin-form-field">
                <label>Email</label>
                <input type="email" name="email" required>
              </div>
            </div>

            <div class="admin-form-row">
              <div class="admin-form-field">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
              </div>

              <div class="admin-form-field">
                <label>Rôle technique</label>
                <select name="role" required>
                  <option value="user">Utilisateur</option>
                  <option value="admin">Admin</option>
                </select>
              </div>

              <div class="admin-form-field">
                <label>Rôle SPARKMIND</label>
                <select name="site_role" required>
                  <option value="seeker">Demandeur</option>
                  <option value="helper">Donneur</option>
                  <option value="both">Les deux</option>
                  <option value="speaker">Expression</option>
                </select>
              </div>
            </div>

            <button type="submit" class="btn-nav" style="margin-top:4px;">
              ✅ Créer l’utilisateur
            </button>
          </form>

          <!-- TABLEAU DES UTILISATEURS -->
          <?php if (empty($users)): ?>
            <p>Aucun utilisateur pour ces filtres.</p>
          <?php else: ?>
            <div class="users-table-wrapper">
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nom & Prénom</th>
                    <th>Email</th>
                    <th>Rôle technique</th>
                    <th>Rôle SPARKMIND</th>
                    <th>Statut</th>
                    <th>Date inscription</th>
                    <th>Actions</th>
                  </tr>
                </thead>

                <tbody id="users-table-body">
                  <?php foreach ($users as $u): ?>
                    <?php
                      $status = $u['status'] ?? 'active';
                      $isBlocked = ($status === 'blocked');
                    ?>
                    <tr>
                      <td><?= $u['id'] ?></td>
                      <td><?= htmlspecialchars($u['nom'].' '.$u['prenom']) ?></td>
                      <td><?= htmlspecialchars($u['email']) ?></td>

                      <td>
                        <span class="tag <?= $u['role']==='admin'?'tag-admin':'tag-user' ?>">
                          <?= htmlspecialchars($u['role']) ?>
                        </span>
                      </td>

                      <td>
                        <?php
                          $labels = [
                            'seeker'  => 'Demandeur',
                            'helper'  => 'Donneur',
                            'both'    => 'Les deux',
                            'speaker' => 'Expression'
                          ];
                          echo htmlspecialchars($labels[$u['site_role']] ?? '—');
                        ?>
                      </td>

                      <td>
                        <?php if ($isBlocked): ?>
                          <span class="tag tag-blocked">Bloqué</span>
                        <?php else: ?>
                          <span class="tag tag-active">Actif</span>
                        <?php endif; ?>
                      </td>

                      <td><?= htmlspecialchars($u['created_at']) ?></td>

                      <td>
                        <a href="index.php?page=admin_user_profile&id=<?= $u['id'] ?>" class="btn-table view">👁 Voir</a>

                        <?php if ($isBlocked): ?>
                          <form method="post" action="index.php?page=admin_unblock_user" style="display:inline;"
                                onsubmit="return confirm('Débloquer ce compte et lui permettre de se reconnecter ?');">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-table unblock">✅ Débloquer</button>
                          </form>
                        <?php else: ?>
                          <form method="post" action="index.php?page=admin_block_user" style="display:inline;"
                                onsubmit="return confirm('Bloquer ce compte ? L’utilisateur ne pourra plus se connecter.');">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-table block">🚫 Bloquer</button>
                          </form>
                        <?php endif; ?>

                        <form method="post" action="index.php?page=admin_delete_user" style="display:inline;"
                              onsubmit="return confirm('Supprimer définitivement ce compte ?');">
                          <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                          <button type="submit" class="btn-table delete">🗑 Supprimer</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        </section>
      </main>

    </div>
  </div>
</div>

<!-- 🔍 RECHERCHE LIVE + FILTRES -->
<script>
  (function() {
    const searchInput = document.getElementById('user-search');
    const tableBody   = document.getElementById('users-table-body');
    const filterRole  = document.getElementById('filter-role');
    const filterDate  = document.getElementById('filter-date');

    if (searchInput && tableBody) {
      searchInput.addEventListener('input', function () {
        const filter = this.value.toLowerCase().trim();
        const rows   = tableBody.querySelectorAll('tr');

        rows.forEach(function(row) {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(filter) ? '' : 'none';
        });
      });
    }

    if (filterRole) {
      filterRole.addEventListener('change', function () {
        const role = this.value;
        const date = filterDate ? filterDate.value : 'all';
        window.location.href = 'index.php?page=admin_users&site_role=' + encodeURIComponent(role) + '&date=' + encodeURIComponent(date);
      });
    }

    if (filterDate) {
      filterDate.addEventListener('change', function () {
        const date = this.value;
        const role = filterRole ? filterRole.value : 'all';
        window.location.href = 'index.php?page=admin_users&site_role=' + encodeURIComponent(role) + '&date=' + encodeURIComponent(date);
      });
    }
  })();
</script>

</body>
</html>
