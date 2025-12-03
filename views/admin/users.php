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
      }

      .site {
          min-height: 100vh;
          display: flex;
          flex-direction: column;
      }

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

      .brand-block {
          display: flex;
          align-items: center;
          gap: 12px;
      }

      .logo-img {
          width: 44px;
          height: 44px;
          border-radius: 50%;
          object-fit: cover;
      }

      .header-actions {
          display: flex;
          gap: 10px;
          align-items: center;
      }

      .btn-nav {
          border: none;
          cursor: pointer;
          padding: 8px 14px;
          border-radius: 999px;
          font-size: 13px;
          font-weight: 500;
          background: #1A464F;
          color: #fff;
      }

      .btn-nav.secondary {
          background: transparent;
          color: #1A464F;
          border: 1px solid rgba(26, 70, 79, 0.35);
      }

      .admin-main {
          flex: 1;
          max-width: 1100px;
          margin: 32px auto 40px;
          padding: 0 18px 30px;
      }

      .admin-card {
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px 26px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.18);
      }

      .admin-card h1 {
          margin: 0 0 6px;
          font-family: 'Playfair Display', serif;
          font-size: 26px;
      }

      .stats-row {
          display: flex;
          flex-wrap: wrap;
          gap: 12px;
          margin: 12px 0 16px;
      }

      .stat-card {
          flex: 1 1 120px;
          background: rgba(255,255,255,0.9);
          padding: 10px 12px;
          border-radius: 14px;
          box-shadow: 0 8px 18px rgba(0,0,0,0.08);
      }

      table {
          border-collapse: collapse;
          width: 100%;
      }

      table td, table th {
          padding: 10px;
          border-bottom: 1px solid #eee;
          font-size: 13px;
          vertical-align: middle;
      }

      table th {
          background-color: #f7f1eb;
      }

      .tag {
          padding: 2px 8px;
          border-radius: 999px;
          font-size: 11px;
      }

      .tag-admin { background:#ffe0b3; color:#8a4b00; }
      .tag-user { background:#e0f5ff; color:#005f8a; }

      .users-table-wrapper {
          overflow-x: auto;
      }

      .search-wrapper {
          margin: 10px 0 15px;
          display: flex;
          align-items: center;
          gap: 8px;
      }

      .search-input {
          flex: 1;
          padding: 8px 12px;
          border-radius: 999px;
          border: 1px solid #ccc;
          font-size: 13px;
          outline: none;
      }

      .search-input:focus {
          border-color: #1A464F;
          box-shadow: 0 0 0 2px rgba(26,70,79,0.15);
      }

      .btn-table {
          display: inline-block;
          padding: 4px 10px;
          border-radius: 999px;
          font-size: 11px;
          border: none;
          cursor: pointer;
          text-decoration: none;
          margin: 2px 0;
      }

      .btn-table.view {
          background: #1A464F;
          color: #fff;
      }

      .btn-table.delete {
          background: #E23B3B;
          color: #fff;
      }

      .btn-table.delete:hover {
          opacity: 0.9;
      }
    </style>
</head>

<body>
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
        <button class="btn-nav" onclick="window.location.href='index.php?page=admin_home'">🔙 Retour</button>
      </div>
    </header>

    <main class="admin-main">
        <section class="admin-card">

            <h1>Administration des utilisateurs</h1>
            <p>
                Bonjour <strong><?= htmlspecialchars($_SESSION['user_prenom'].' '.$_SESSION['user_nom']) ?></strong>
                (<?= htmlspecialchars($_SESSION['user_email']) ?>)
            </p>

            <!-- 🔍 BARRE DE RECHERCHE LIVE -->
            <div class="search-wrapper">
                <span>🔍</span>
                <input
                    type="text"
                    id="user-search"
                    class="search-input"
                    placeholder="Rechercher un utilisateur (nom, prénom, email)...">
            </div>

            <!-- FORMULAIRE D'AJOUT D'UN UTILISATEUR -->
            <form method="post" action="index.php?page=admin_users" style="margin:20px 0; padding:15px; border-radius:16px; background:#fff7ef; box-shadow:0 8px 18px rgba(0,0,0,0.08);">
                <h2 style="margin-top:0; font-size:18px;">➕ Ajouter un utilisateur</h2>

                <input type="hidden" name="action" value="create_user">

                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                    <div style="flex:1 1 160px;">
                        <label>Prénom</label><br>
                        <input type="text" name="prenom" required style="width:100%; padding:6px 8px; border-radius:8px; border:1px solid #ccc;">
                    </div>

                    <div style="flex:1 1 160px;">
                        <label>Nom</label><br>
                        <input type="text" name="nom" required style="width:100%; padding:6px 8px; border-radius:8px; border:1px solid #ccc;">
                    </div>

                    <div style="flex:1 1 220px;">
                        <label>Email</label><br>
                        <input type="email" name="email" required style="width:100%; padding:6px 8px; border-radius:8px; border:1px solid #ccc;">
                    </div>
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                    <div style="flex:1 1 200px;">
                        <label>Mot de passe</label><br>
                        <input type="password" name="password" required style="width:100%; padding:6px 8px; border-radius:8px; border:1px solid #ccc;">
                    </div>

                    <div style="flex:1 1 160px;">
                        <label>Rôle technique</label><br>
                        <select name="role" required style="width:100%; padding:6px 8px; border-radius:8px; border:1px solid:#ccc;">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div style="flex:1 1 180px;">
                        <label>Rôle SPARKMIND</label><br>
                        <select name="site_role" required style="width:100%; padding:6px 8px; border-radius:8px; border:1px solid:#ccc;">
                            <option value="seeker">Demandeur</option>
                            <option value="helper">Donneur</option>
                            <option value="both">Les deux</option>
                            <option value="speaker">Expression</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-nav" style="margin-top:5px;">
                    ✅ Créer l’utilisateur
                </button>
            </form>

            <?php
              $currentSiteRole   = $currentSiteRole   ?? 'all';
              $currentDateFilter = $currentDateFilter ?? 'all';

              $filters = [
                  'all'     => 'Tous',
                  'seeker'  => 'Demandeurs',
                  'helper'  => 'Donneurs',
                  'both'    => 'Les deux',
                  'speaker' => 'Expression',
              ];

              $dateFilters = [
                  'all'       => 'Toutes les dates',
                  'today'     => "Aujourd'hui",
                  'yesterday' => 'Hier',
                  'week'      => '7 derniers jours',
                  'last_month'=> 'Mois dernier',
                  'last_year' => 'Année dernière',
              ];
            ?>

            <!-- Filtres rôle SPARKMIND -->
            <div style="margin:15px 0; display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach ($filters as $value => $label):
                    $active = ($currentSiteRole === $value);
                ?>
                    <a href="index.php?page=admin_users&site_role=<?= $value ?>&date=<?= htmlspecialchars($currentDateFilter) ?>"
                       style="
                         padding:8px 14px;
                         border-radius:999px;
                         font-size:13px;
                         text-decoration:none;
                         border:1px solid #1A464F;
                         background:<?= $active ? '#1A464F' : 'transparent' ?>;
                         color:<?= $active ? '#fff' : '#1A464F' ?>;">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Filtres date d'inscription -->
            <div style="margin:5px 0 15px; display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach ($dateFilters as $value => $label):
                    $active = ($currentDateFilter === $value);
                ?>
                    <a href="index.php?page=admin_users&site_role=<?= htmlspecialchars($currentSiteRole) ?>&date=<?= $value ?>"
                       style="
                         padding:8px 14px;
                         border-radius:999px;
                         font-size:13px;
                         text-decoration:none;
                         border:1px solid #EC7546;
                         background:<?= $active ? '#EC7546' : 'transparent' ?>;
                         color:<?= $active ? '#fff' : '#EC7546' ?>;">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- STATS -->
            <div class="stats-row">
                <div class="stat-card">Comptes affichés <strong><?= count($users) ?></strong></div>
                <div class="stat-card">
                    Admins <strong>
                    <?php $admins=0; foreach($users as $u){ if($u['role']==='admin') $admins++; } echo $admins; ?>
                    </strong>
                </div>
                <div class="stat-card">Utilisateurs <strong><?= count($users)-$admins ?></strong></div>
            </div>

            <!-- TABLE -->
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
                                <th>Date inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="users-table-body">
                        <?php foreach ($users as $u): ?>
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

                                <td><?= htmlspecialchars($u['created_at']) ?></td>

                                <td>
                                    <!-- Voir profil -->
                                    <a href="index.php?page=admin_user_profile&id=<?= $u['id'] ?>"
                                       class="btn-table view">
                                        👁 Voir profil
                                    </a>

                                    <!-- Supprimer compte -->
                                    <form method="post"
                                          action="index.php?page=admin_delete_user"
                                          style="display:inline;"
                                          onsubmit="return confirm('Supprimer définitivement ce compte ?');">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn-table delete">
                                            🗑 Supprimer
                                        </button>
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

<!-- 🔍 SCRIPT DE RECHERCHE LIVE -->
<script>
  (function() {
    const searchInput = document.getElementById('user-search');
    const tableBody   = document.getElementById('users-table-body');

    if (!searchInput || !tableBody) return;

    searchInput.addEventListener('input', function () {
      const filter = this.value.toLowerCase().trim();
      const rows   = tableBody.querySelectorAll('tr');

      rows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  })();
</script>

</body>
</html>
