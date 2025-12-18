<?php
// Vue profil utilisateur (AdminController::userProfile)
// variable disponible : $user (profil de l'utilisateur choisi par l'admin)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil utilisateur — SPARKMIND</title>

  <!-- Polices -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles spécifiques du profil (même fichier que le front) -->
  <link rel="stylesheet" href="profil.css">

  <style>
    /* 🔹 Barre du haut identique aux autres pages */
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
      border-bottom: 1px solid rgba(0, 0, 0, 0.03);
      animation: navFade 0.6s ease-out;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logout-btn,
    .header-btn {
      background: #ec7546;
      color: #ffffff;
      border: none;
      padding: 8px 18px;
      font-family: 'Poppins', sans-serif;
      border-radius: 999px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 8px 18px rgba(236,117,70,0.45);
      transition: 0.25s ease;
      cursor: pointer;
      display: inline-block;
    }

    .logout-btn:hover,
    .header-btn:hover {
      transform: translateY(-2px) scale(1.03);
      filter: brightness(1.07);
      box-shadow: 0 10px 24px rgba(236,117,70,0.6);
    }

    .brand-block {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-img {
      width: 45px;
      height: 45px;
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
      font-size: 20px;
      color: #1A464F;
      letter-spacing: 0.5px;
    }

    .brand-tagline {
      font-size: 11px;
      color: #1A464F;
      opacity: 0.8;
    }

    @keyframes navFade {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Petits ajustements pour le mode admin */
    .profile-container {
      padding-top: 30px;
    }
  </style>

</head>

<body>

  <!-- 🔹 BARRE DU HAUT (version admin) -->
  <header class="main-header top-nav">
    <div class="brand-block">
      <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
      <div class="brand-text">
        <span class="brand-name">SPARKMIND</span>
        <span class="brand-tagline">Quand la pensée devient espoir</span>
      </div>
    </div>

    <div class="header-actions">
      <!-- Retour à la liste des utilisateurs -->
      <a href="index.php?page=admin_users" class="header-btn" style="background:#1A464F;">
        ⬅ Liste utilisateurs
      </a>

      <!-- Retour au backoffice -->
      <a href="index.php?page=admin_home" class="header-btn">
        🏠 Backoffice
      </a>
    </div>
  </header>

  <!-- 🔹 Contenu principal -->
  <main class="profile-container">

    <section class="profile-card">

      <div class="photo-section">
        <img src="<?= !empty($user['photo']) ? htmlspecialchars($user['photo']) : 'profil.jpg' ?>" 
             class="profile-img" 
             alt="Photo de profil">
      </div>

      <!-- 🔹 Nom + rôle dynamiques -->
      <h1 class="name">
        <?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>
      </h1>

      <p class="role">
        <?= htmlspecialchars($user['profession'] ?? 'Utilisateur SPARKMIND') ?>
      </p>

      <div class="info-section">
        <p><strong>📧 Email :</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
        <p><strong>📞 Téléphone :</strong> <?= htmlspecialchars($user['tel'] ?? '—') ?></p>
        <p><strong>📍 Ville :</strong> <?= htmlspecialchars($user['ville'] ?? '—') ?></p>
        <p><strong>💼 Profession :</strong> <?= htmlspecialchars($user['profession'] ?? '—') ?></p>
        <p><strong>🎂 Date de naissance :</strong> <?= htmlspecialchars($user['naissance'] ?? '—') ?></p>
        <p><strong>🏠 Adresse :</strong> <?= htmlspecialchars($user['adresse'] ?? '—') ?></p>

        <?php
          $roleTech = $user['role'] ?? 'user';
          $siteRole = $user['site_role'] ?? null;
          $status   = $user['status'] ?? 'active';

          $siteRoleLabels = [
              'seeker'  => 'Demandeur',
              'helper'  => 'Donneur',
              'both'    => 'Les deux',
              'speaker' => 'Expression',
          ];
        ?>
        <p><strong>⚙ Rôle technique :</strong> <?= htmlspecialchars($roleTech) ?></p>
        <p><strong>🌟 Rôle SPARKMIND :</strong>
          <?= htmlspecialchars($siteRoleLabels[$siteRole] ?? ($siteRole ?: '—')) ?>
        </p>
        <p><strong>✅ Statut :</strong> <?= htmlspecialchars($status) ?></p>
        <p><strong>🕒 Créé le :</strong> <?= htmlspecialchars($user['created_at'] ?? '—') ?></p>
      </div>

      <div class="actions">
        <!-- Retour liste admin -->
        <a href="index.php?page=admin_users" class="btn-ghost">⬅ Retour à la liste</a>

        <!-- (Optionnel) Bouton pour supprimer le compte depuis la fiche -->
        <form action="index.php?page=admin_delete_user" method="post"
              onsubmit="return confirm('Voulez-vous vraiment supprimer définitivement ce compte utilisateur ?');"
              style="display:inline-block; margin-left: 1rem;">
          <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
          <button type="submit" class="logout-btn" style="background-color:#c0392b;">
            🗑 Supprimer ce compte
          </button>
        </form>
      </div>

    </section>

  </main>

  <a class="help" href="#" title="Aide">?</a>

</body>
</html>
