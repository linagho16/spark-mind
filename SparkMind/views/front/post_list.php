<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SparkMind - poster & discuter anonymement</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
  </head>
  <body>
    <header class="toppage">
      <div class="logo-title">
        <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
        <div class="title-block">
          <h1>SparkMind</h1>
          <p class="subtitle">poster & discuter anonymement</p>
        </div>
      </div>
    </header>

    <main class="wrap">
      <section class="post">
        <h2>Nouveau post (anonyme)</h2>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error">
            <?php foreach ($errors as $e): ?>
              <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="alert alert-success">
            <p><?= htmlspecialchars($success) ?></p>
          </div>
        <?php endif; ?>

        <form class="post-form" id="postFormFront"
              method="post"
              action="index.php?action=store_front">
          <input type="text" name="titre" placeholder="Titre (optionnel)" />
          <textarea
            name="contenu"
            id="contenuFront"
            rows="4"
            placeholder="Écrire ton message ici..."
          ></textarea>
          <small class="charCount" data-max="280">0 / 280</small>
          <button type="submit">Publier</button>
        </form>
      </section>

      <section class="post-list">
        <h2>Derniers posts</h2>
        <?php if (empty($posts)): ?>
          <p>Aucun post pour le moment.</p>
        <?php else: ?>
          <?php foreach ($posts as $p): ?>
            <article class="post-item">
              <?php if (!empty($p['titre'])): ?>
                <h3><?= htmlspecialchars($p['titre']) ?></h3>
              <?php endif; ?>
              <p><?= nl2br(htmlspecialchars($p['contenu'])) ?></p>
              <span class="date">
                Publié le <?= htmlspecialchars($p['created_at']) ?>
              </span>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>

    <script src="assets/js/validationPost.js"></script>
  </body>
</html>
