<?php
// Vue login (AuthController::login)
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
  <title>SPARKMIND — Connexion</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  
  <link rel="stylesheet" href="login.css">

  
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
      <h1 class="title">Connexion</h1>
      <p class="subtitle">Ravi de vous revoir ✨</p>

      <?php if (!empty($errors)): ?>
        <div class="error-box" style="color:red;text-align:center;margin-bottom:10px;">
          <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form class="form" method="post" action="index.php?page=login">

        <label class="field">
          <span>Adresse e-mail</span>
          <input
            type="email"
            name="email"
            placeholder="ex. nom@exemple.com"
            required
            autofocus
            value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"

          />
        </label>

        <label class="field">
          <a href="index.php?page=forgot_password" class="link-forgot">Mot de passe oublié ?</a>
          <div class="password-wrapper">
            <input
              type="password"
              name="password"
              placeholder="Votre mot de passe"
              required
              minlength="8"
              id="passwordInput"
            />
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
          </div>
        </label>

        <script>
          function togglePassword() {
            const input = document.getElementById("passwordInput");
            const icon = document.querySelector(".toggle-password");

            if (input.type === "password") {
              input.type = "text";
              icon.textContent = "🙈";
            } else {
              input.type = "password";
              icon.textContent = "👁️";
            }
          }
        </script>

        <div class="row between">
          <label class="check">
            <input type="checkbox" name="remember">
            <span>Se souvenir de moi</span>
          </label>
          <a class="link" href="#" title="Récupérer le mot de passe">Mot de passe oublié&nbsp;?</a>
        </div>

        <button class="btn-primary" type="submit">Se connecter</button>
      </form>

      <div class="divider" role="separator"><span>ou</span></div>

      <div class="actions">
        <a class="btn-secondary" href="index.php?page=register">Créer un compte</a>
        <a class="btn-ghost" href="index.php?page=main">⬅ Retour à l’accueil</a>
      </div>
    </section>
  </main>

  <a class="help" href="#" title="Besoin d’aide ?">?</a>
</body>
</html>
