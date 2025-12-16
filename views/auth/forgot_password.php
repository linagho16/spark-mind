<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mot de passe oublié</title>
  <link rel="stylesheet" href="login.css"><!-- ou ton css de connexion -->
</head>
<body>

  <main class="wrap">
    <section class="card">
      <h1 class="title">Mot de passe oublié</h1>
      <p class="subtitle">
        Entrez votre adresse e-mail ou votre numéro de téléphone.  
        Nous allons générer un code de vérification.
      </p>

      <?php if (!empty($errors)): ?>
        <div class="error-box" style="color:red;">
          <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form class="form" method="post" action="index.php?page=forgot_password">
        <label class="field">
          <span>E-mail ou numéro de téléphone</span>
          <input type="text" name="identifier" required>
        </label>

        <button type="submit" class="btn-primary">Recevoir un code</button>
      </form>

      <div class="actions">
        <a href="index.php?page=login" class="btn-ghost">⬅ Retour à la connexion</a>
      </div>
    </section>
  </main>

</body>
</html>
