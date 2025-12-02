<?php
// Vue admin pour la gestion des notifications
// Variable disponible : $notifications (vient de AdminController::notifications)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SPARKMIND — Notifications (Admin)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- même style que users.php -->
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
          -webkit-backdrop-filter: blur(14px);
          background: rgba(251, 237, 215, 0.96);
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 10px 24px;
          border-bottom: 1px solid rgba(0, 0, 0, 0.04);
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

      .brand-text {
          display: flex;
          flex-direction: column;
          line-height: 1.1;
      }

      .brand-name {
          font-family: 'Playfair Display', serif;
          font-size: 22px;
          letter-spacing: 1px;
          color: #1A464F;
      }

      .brand-tagline {
          font-size: 12px;
          color: #1A464F;
          opacity: 0.85;
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
          color: #02161a;
      }

      .admin-card p.subtitle {
          margin: 0 0 14px;
          font-size: 14px;
          color: #1A464F;
      }

      .notifications-table-wrapper {
          margin-top: 12px;
          border-radius: 18px;
          overflow: hidden;
          background: rgba(255,255,255,0.96);
          box-shadow: 0 12px 26px rgba(0,0,0,0.10);
      }

      table {
          border-collapse: collapse;
          width: 100%;
          font-size: 13px;
      }

      th, td {
          padding: 8px 10px;
          border-bottom: 1px solid #eee;
          text-align: left;
      }

      th {
          background: #f7f1eb;
          font-weight: 600;
          color: #1A464F;
      }

      tr:last-child td {
          border-bottom: none;
      }

      .tag-count {
          font-size: 11px;
          padding: 2px 6px;
          border-radius: 999px;
          background: #eef7ff;
          color: #145;
      }
    </style>
</head>
<body>
<div class="site">

    <header class="main-header top-nav">
      <div class="brand-block">
        <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
        <div class="brand-text">
          <span class="brand-name">SPARKMIND</span>
          <span class="brand-tagline">Quand la pensée devient espoir</span>
        </div>
      </div>

      <div class="header-actions">
        <button class="btn-nav secondary" onclick="window.location.href='index.php?page=admin_home'">
          ⬅ Tableau de bord
        </button>
        <button class="btn-nav" onclick="window.location.href='index.php?page=logout'">
          🚪 Déconnexion
        </button>
      </div>
    </header>

    <main class="admin-main">
      <section class="admin-card">
        <h1>Notifications</h1>
        <p class="subtitle">
          Vue d’ensemble des notifications publiées et de leurs réponses (acceptées / refusées / en attente).
        </p>

        <?php if (empty($notifications)): ?>
          <p>Aucune notification pour le moment.</p>
        <?php else: ?>
          <div class="notifications-table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Titre</th>
                  <th>Date de publication</th>
                  <th>Acceptées</th>
                  <th>Refusées</th>
                  <th>En attente</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($notifications as $n): ?>
                <tr>
                  <td><?= htmlspecialchars($n['id']) ?></td>
                  <td><?= htmlspecialchars($n['titre']) ?></td>
                  <td><?= htmlspecialchars($n['date_publication']) ?></td>
                  <td><span class="tag-count"><?= (int)$n['nb_accepted'] ?></span></td>
                  <td><span class="tag-count"><?= (int)$n['nb_rejected'] ?></span></td>
                  <td><span class="tag-count"><?= (int)$n['nb_pending'] ?></span></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </section>
    </main>

</div>
</body>
</html>
