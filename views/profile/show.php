<?php
// Vue profil (ProfileController::show)
// variable disponible : $user
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Profil — SPARKMIND</title>

  <!-- Polices -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="profil.css">

  <style>
    /* 🔹 Barre du haut identique aux autres pages */
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96); /* ton bg */
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

    .logout-btn {
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
    }

    .logout-btn:hover {
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
  </style>

</head>

<body>

  <!-- 🔹 BARRE DU HAUT (identique aux autres pages) -->
  <header class="main-header top-nav">
    <div class="brand-block">
      <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
      <div class="brand-text">
        <span class="brand-name">SPARKMIND</span>
        <span class="brand-tagline">Quand la pensée devient espoir</span>
      </div>
    </div>

    <div class="header-actions">
      <a href="index.php?page=front_step" class="logout-btn">
        🔓 Déconnexion
      </a>
    </div>
  </header>

  <!-- 🔹 Contenu principal -->
  <main class="profile-container">

    <section class="profile-card">

      <div class="photo-section">
        <!-- Pour l’instant on garde une image fixe -->
       
        <img src="<?= !empty($user['photo']) ? $user['photo'] : 'profil.jpg' ?>" 
        class="profile-img" 
        alt="Photo de profil">

      </div>

      <!-- 🔹 Nom + rôle dynamiques -->
      <h1 class="name">
        <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
      </h1>
      <p class="role">
        <?= htmlspecialchars($user['profession']) ?>
      </p>

      <div class="info-section">
        <p><strong>📧 Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>📞 Téléphone :</strong> <?= htmlspecialchars($user['tel']) ?></p>
        <p><strong>📍 Ville :</strong> <?= htmlspecialchars($user['ville']) ?></p>
        <p><strong>💼 Profession :</strong> <?= htmlspecialchars($user['profession']) ?></p>
        <p><strong>🎂 Date de naissance :</strong> <?= htmlspecialchars($user['naissance']) ?></p>
        <p><strong>🏠 Adresse :</strong> <?= htmlspecialchars($user['adresse']) ?></p>
      </div>

      <div class="actions">
        <a href="index.php?page=main" class="btn-ghost">⬅ Retour</a>
        <a href="index.php?page=profile_edit" class="btn-secondary">Modifier</a>

      

        <form action="index.php?page=delete_account" method="post" 
              onsubmit="return confirm('Voulez-vous vraiment supprimer définitivement votre compte ?');"
              style="display:inline-block; margin-left: 1rem;">
          <button type="submit" class="logout-btn" style="background-color:#c0392b;">
            🗑 Supprimer mon compte
          </button>
        </form>
      </div>


    </section>

  </main>

  <a class="help" href="#" title="Aide">?</a>

</body>
</html>
