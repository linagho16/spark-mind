<?php
// Vue listing des utilisateurs (AdminController::users)
// Variable disponible : $users (tableau)
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

    <!-- CSS global si tu en as un -->
    <link rel="stylesheet" href="style.css">

    <style>
        /* ---- Fond général proche de la première page ---- */
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

        /* ---- Barre du haut façon verre flou (même style que page front) ---- */
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
            animation: navFade 0.7s ease-out;
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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
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
            align-items: center;
            gap: 10px;
        }

        .btn-nav {
            border: none;
            cursor: pointer;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            background: #1A464F;
            color: #fff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn-nav.secondary {
            background: transparent;
            color: #1A464F;
            border: 1px solid rgba(26, 70, 79, 0.35);
            box-shadow: none;
        }

        .btn-nav:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
            filter: brightness(1.03);
        }

        @keyframes navFade {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ---- Contenu principal ---- */
        .admin-main {
            flex: 1;
            max-width: 1100px;
            margin: 32px auto 40px auto;
            padding: 0 18px 30px;
        }

        .admin-card {
            background: rgba(255, 247, 239, 0.95);
            border-radius: 24px;
            padding: 24px 22px 26px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
            position: relative;
            overflow: hidden;
        }

        .admin-card::before,
        .admin-card::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(28px);
            opacity: 0.7;
            z-index: -1;
        }

        .admin-card::before {
            width: 180px;
            height: 180px;
            background: rgba(125, 90, 166, 0.30);
            top: -50px;
            left: -50px;
        }

        .admin-card::after {
            width: 220px;
            height: 220px;
            background: rgba(31, 140, 135, 0.28);
            bottom: -70px;
            right: -40px;
        }

        .admin-header-text h1 {
            margin: 0 0 4px;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #02161a;
        }

        .admin-header-text p {
            margin: 0;
            font-size: 14px;
            color: #1A464F;
        }

        .admin-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 18px;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(125, 90, 166, 0.12);
            color: #1A464F;
            margin-top: 6px;
        }

        .badge-role span.icon {
            font-size: 14px;
        }

        /* ---- Petites cartes de stats ---- */
        .stats-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 12px 0 16px;
        }

        .stat-card {
            flex: 1 1 120px;
            min-width: 140px;
            background: rgba(255, 255, 255, 0.90);
            border-radius: 14px;
            padding: 10px 12px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            font-size: 13px;
            color: #1A464F;
        }

        .stat-card strong {
            display: block;
            font-size: 18px;
            margin-top: 2px;
            font-family: 'Playfair Display', serif;
        }

        /* ---- Tableau utilisateurs ---- */
        .users-table-wrapper {
            margin-top: 10px;
            border-radius: 18px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 12px 26px rgba(0,0,0,0.10);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }

        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f7f1eb;
            font-weight: 600;
            color: #1A464F;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .tag {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .tag-admin {
            background:#ffe0b3;
            color:#8a4b00;
        }

        .tag-user  {
            background:#e0f5ff;
            color:#005f8a;
        }

        @media (max-width: 780px) {
            .admin-header-row {
                flex-direction: column;
            }
            .admin-main {
                margin: 20px auto 30px;
            }
            .admin-card {
                padding: 20px 16px 22px;
            }
            th:nth-child(1),
            td:nth-child(1) {
                display:none; /* masque l'ID sur petit écran */
            }
        }
    </style>
</head>
<body>
<div class="site">

    <!-- HEADER STYLE FRONT OFFICE -->
    <header class="main-header top-nav">
      <div class="brand-block">
        <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
        <div class="brand-text">
          <span class="brand-name">SPARKMIND</span>
          <span class="brand-tagline">Quand la pensée devient espoir</span>
        </div>
      </div>

      <div class="header-actions">
        <button class="btn-nav secondary" onclick="window.location.href='index.php?page=main'">
          🏠 Espace utilisateur
        </button>
        <button class="btn-nav" onclick="window.location.href='index.php?page=logout'">
          🚪 Déconnexion
        </button>
      </div>
    </header>

    <!-- CONTENU ADMIN -->
    <main class="admin-main">
        <section class="admin-card">
            <div class="admin-header-row">
                <div class="admin-header-text">
                    <h1>Administration des utilisateurs</h1>
                    <p>
                        Bonjour
                        <strong>
                            <?php echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']); ?>
                        </strong>
                        (<?php echo htmlspecialchars($_SESSION['user_email']); ?>)
                    </p>
                    <div class="badge-role">
                        <span class="icon">⭐</span>
                        <span>Rôle : <?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                    </div>
                </div>
            </div>

            <!-- petites stats -->
            <div class="stats-row">
                <div class="stat-card">
                    Total comptes
                    <strong><?php echo count($users); ?></strong>
                </div>
                <div class="stat-card">
                    Nombre d'admins
                    <strong>
                        <?php
                        $admins = 0;
                        foreach ($users as $u) {
                            if ($u['role'] === 'admin') $admins++;
                        }
                        echo $admins;
                        ?>
                    </strong>
                </div>
                <div class="stat-card">
                    Nombre d'utilisateurs
                    <strong><?php echo count($users) - $admins; ?></strong>
                </div>
            </div>

            <h2 style="margin-top: 8px; font-size:18px; color:#02161a; font-family:'Playfair Display',serif;">
                Comptes inscrits
            </h2>

            <?php if (empty($users)): ?>
                <p>Aucun utilisateur enregistré pour le moment.</p>
            <?php else: ?>
                <div class="users-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom &amp; Prénom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date d'inscription</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['id']); ?></td>
                                <td><?php echo htmlspecialchars($u['nom'] . ' ' . $u['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td>
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="tag tag-admin">admin</span>
                                    <?php else: ?>
                                        <span class="tag tag-user">user</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($u['created_at']); ?></td>
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
