<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réinitialiser le mot de passe</title>
  <link rel="stylesheet" href="login.css"><!-- ou ton css de connexion -->
</head>
<body>

  <main class="wrap">
    <section class="card">
      <h1 class="title">Réinitialiser le mot de passe</h1>

      <?php if (!empty($info)): ?>
        <p style="color:#555; font-size: 14px; margin-bottom:10px;">
          <?= htmlspecialchars($info) ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="error-box" style="color:red;">
          <?php foreach ($errors as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-box" style="color:green;">
          <p><?= htmlspecialchars($success) ?></p>
        </div>

        <div class="actions">
          <a href="index.php?page=login" class="btn-primary">Aller à la connexion</a>
        </div>
      <?php else: ?>

        <form class="form" method="post" action="index.php?page=reset_password">
          <label class="field">
            <span>Code de vérification</span>
            <input type="text" name="code" required>
          </label>

          <label class="field">
            <span>Nouveau mot de passe</span>
            <input type="password" name="password" minlength="8" required>
          </label>

          <label class="field">
            <span>Confirmer le mot de passe</span>
            <input type="password" name="password_confirm" minlength="8" required>
          </label>

          <button type="submit" class="btn-primary">Changer le mot de passe</button>
        </form>

        <div class="actions">
          <a href="index.php?page=login" class="btn-ghost">⬅ Retour à la connexion</a>
        </div>

      <?php endif; ?>

    </section>
  </main>

</body>
</html>
