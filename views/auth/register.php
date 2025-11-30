<?php
// Vue inscription (AuthController::register)
?>
<?php if (!empty($errors)): ?>
  <div class="error-box">
    <?php foreach ($errors as $e): ?>
      <p style="color:red;"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Inscription</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Style de la page d'inscription -->
  <link rel="stylesheet" href="inscrit.css">

  <!-- 🔹 Barre en haut IDENTIQUE à la page de login -->
  <style>
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

    .brand-block {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .brand-text {
      display: flex;
      flex-direction: column;
    }

    .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      letter-spacing: 1px;
    }

    .brand-tagline {
      font-size: 12px;
      color: #1A464F;
      opacity: 0.8;
    }

    @keyframes navFade {
      from {
        opacity: 0;
        transform: translateY(-12px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <!-- ✅ BARRE EN HAUT COPIÉE DE LA PAGE LOGIN -->
  <header class="main-header top-nav" aria-label="Logo du site">
    <div class="brand-block">
      <a href="index.php?page=main" class="logo-link" title="Retour à l’accueil">
        <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
      </a>
      <div class="brand-text">
        <span class="brand-name">SPARKMIND</span>
        <span class="brand-tagline">Quand la pensée devient espoir</span>
      </div>
    </div>
  </header>

  <main class="wrap">
    <section class="card">
      <h1 class="title">Créer un compte</h1>
      <p class="subtitle">Rejoignez la communauté ✨</p>

      <?php if (!empty($errors)): ?>
        <div class="error-box" style="color:red; text-align:center;">
          <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form class="form" method="post" action="index.php?page=register" enctype="multipart/form-data">
        <div class="row two">
          <label class="field">
            <span>Nom</span>
            <input type="text" name="nom" placeholder="ex. Gabtni" required autocomplete="family-name">
          </label>

          <label class="field">
            <span>Prénom</span>
            <input type="text" name="prenom" placeholder="ex. Najami" required autocomplete="given-name">
          </label>
        </div>

        <div class="row two">
          <label class="field">
            <span>Date de naissance</span>
            <input type="date" name="naissance" required>
          </label>

          <label class="field">
            <span>N° de téléphone (TN)</span>
            <input type="tel" name="tel" placeholder="+216 2xxxxxxx"
                   pattern="^(?:\+216\s?)?[24579]\d{7}$"
                   title="Numéro tunisien : 8 chiffres (peut commencer par +216)"
                   required>
          </label>
        </div>

        <label class="field">
          <span>Adresse</span>
          <textarea name="adresse" placeholder="Rue, quartier, bâtiment…" rows="3" required></textarea>
        </label>

        <div class="row two">
          <label class="field">
            <span>Ville (Grand Tunis)</span>
            <select name="ville" required>
              <option value="" selected disabled>Choisir une ville…</option>
              <option>Tunis</option>
              <option>Ariana</option>
              <option>Ben Arous</option>
              <option>Manouba</option>
              <option>La Marsa</option>
              <option>Carthage</option>
              <option>La Goulette</option>
              <option>Le Kram</option>
              <option>Bardo</option>
              <option>Sidi Bou Saïd</option>
              <option>El Manar</option>
              <option>El Menzah</option>
              <option>Montplaisir</option>
              <option>Lafayette</option>
              <option>Bizerte</option>
              <option>Nabeul</option>
            </select>
          </label>

          <label class="field">
            <span>Profession</span>
            <select name="profession" required>
              <option value="" selected disabled>Choisir…</option>
              <option>Étudiant(e)</option>
              <option>Ingénieur logiciel embarqué</option>
              <option>Développeur / Développeuse</option>
              <option>Technicien(ne)</option>
              <option>Enseignant(e)</option>
              <option>Santé</option>
              <option>Indépendant(e)</option>
              <option>Sans emploi</option>
              <option>Autre</option>
            </select>
          </label>
        </div>

        <div class="row two">
          <label class="field">
            <span>Adresse e-mail</span>
            <input type="email" name="email" placeholder="ex. nom@exemple.com" required autocomplete="email">
          </label>

          <label class="field">
            <span>Mot de passe</span>
            <input type="password" name="password" placeholder="Minimum 8 caractères" minlength="8" required autocomplete="new-password">
          </label>
        </div>

        <label class="field">
          <span>Photo de profil (optionnel)</span>
          <input type="file" name="photo" accept="image/*">
        </label>

        <label class="check">
          <input type="checkbox" required>
          <span>J’accepte les conditions d’utilisation</span>
        </label>

        <button class="btn-primary" type="submit">Créer mon compte</button>
      </form>

      <div class="divider" role="separator"><span>ou</span></div>

      <div class="actions">
        <a class="btn-secondary" href="index.php?page=login">J’ai déjà un compte</a>
        <a class="btn-ghost" href="index.php?page=main">⬅ Retour à l’accueil</a>
      </div>
    </section>
  </main>

  <a class="help" href="#" title="Besoin d’aide ?">?</a>
</body>
</html>
