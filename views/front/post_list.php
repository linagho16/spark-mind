<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SparkMind - forum de Donations</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
  </head>
  <body>
    <header class="toppage">
      <div class="logo-title">
        <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
        <div class="title-block">
          <h1>SparkMind</h1>
          <p class="subtitle">Forum de messageries</p>
        </div>
      </div>
    </header>

    <main class="wrap">
      <!--sidebar filtres -->
      <aside class="filters-section">
        <h2>📋 Sujet</h2>
        <div class="filters">
          <a href="index.php" class="filter-btn <?= !isset($_GET['type']) ? 'active' : '' ?>">
            🌟 Tous
          </a>
          <?php foreach($donation_types as $type): ?>
            <a href="index.php?type=<?= $type['id'] ?>"
            class="filter-btn <?= (isset($_GET['type']) && $_GET['type'] == $type['id']) ? 'active' : '' ?>"
            style="<?= (isset($_GET['type']) && $_GET['type'] == $type['id']) ? '' : 'border-color: '.$type['color'].'; color: '.$type['color'] ?>">
            <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
          </a>
          <?php endforeach; ?>
          </div>
          <div class="post" style="margin-top: 30px;">
            <h2>✨ Nouveau post</h2>
            <?php if (!empty($errors)): ?>
              <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
              <span>⚠️</span>
              <p><?= htmlspecialchars($e) ?></p>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
          <div class="alert alert-success">
              <span>✅</span>
              <p><?= htmlspecialchars($success) ?></p>
          </div>
          <?php endif; ?>
            <form id="postFormFront" method="post" enctype="multipart/form-data" action="index.php?action=store_front">
              <div class="form-group">
                <label>Type de donation *</label>
                <select name="donation_type_id" required>
                  <option value="">sélectionner un type</option>
                  <?php foreach($donation_types as $type): ?>
                    <option value="<?= $type['id'] ?>">
                      <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
                  </option>
                  <?php endforeach; ?>
                  </select>
                  </div>

                  <div class="form-group">
                    <label>Titre (optionnel)</label>
                    <input type="text" name="titre" placeholder="Ex: titre..." />
                  </div>
                  <div class="form-group">
                    <label>Message *</label>
                    <textarea name="contenu" id="contenuFront" rows="4" placeholder="écrivez votre message..." required></textarea>
                    <small class="charCount" data-max="280">0 / 280</small>
                  </div>
                  <div class="form-group">
                    <div class="file-input-wrapper">
                      <input type="file" name="image" id ="imageInput" accept="image/*">
                      <label for="imageInput" class="file-input-label">
                        📷 Ajouter une image
                  </label>
                  </div>
                  </div>

                  <button type="submit">Publier</button>
                  </form>
                  </div>
                  </aside>
                  <!-- liste des posts -->
                   <section class="post-list">
                    <h2>📢 Derniers posts</h2>
                    <?php if (empty($posts)): ?>
                      <div class="post-item">
                        <p style="text-align: center; color: #718096;">Aucun post pour le moment. Soyer le premier à publier ! 🎉</p>
                    </div>
                    <?php else: ?>
                      <?php foreach ($posts as $p): ?>
                        <article class="post-item">
                          <div class="post-header">
                            <span class="donation-badge" style="background-color: <?= $p['color'] ?? '#667eea' ?>">
                              <?= $p['icon'] ?? '🎁' ?> <?= htmlspecialchars($p['type_name'] ?? 'Autre') ?>
                            </span>
                            <div class="menu-container">
                              <button class="menu-btn" onclick="toggleMenu(this)">⋮</button>
                              <div class="menu-options">
                                <a href="index.php?action=edit&id=<?= $p['id'] ?>">✏️ Modifier</a>
                                <form method="post" action="index.php?action=delete_front" onsubmit="return confirm('Supprimer ce post ?');"> 
                                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                  <button type="submit" class="delete-link">🗑️ Supprimer</button>
                      </form>
                      </div>
                      </div>
                      </div>
                      <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" alt="Image du post" />
                        <?php endif; ?>
                        <?php if (!empty($p['titre'])): ?>
                          <h3><?= htmlspecialchars($p['titre']) ?></h3>
                          <?php endif; ?>
                          <p><?= nl2br(htmlspecialchars($p['contenu'])) ?></p>
                          <div class="post-footer">
                          <span class="date">📅 <?= date('d/m/Y à H:i', strtotime($p['created_at'])) ?></span>
                          <div class="post-actions">
                            <a href="index.php?action=show&id=<?= $p['id'] ?>" class="btn-comment">
                              💬 Commentaires
                                </a>
                                <a href="index.php?action=show&id=<?= $p['id'] ?>" class="btn-view">
                                    👁️ Voir
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
      </main>
      <script src="assets/js/validationPost.js"></script>
      <script>
        function toggleMenu(button) {
          const menu = button.nextElementSibling;
          const allMenus = document.querySelectorAll('.menu-options');

          //fermer tous les autres menus
          allMenus.forEach(m =>{
            if (m !== menu) m.classList.remove('show');
          });
          menu.classList.toggle('show');
        }
        //fermer les menus si on clique ailleurs
        document.addEventListener('click', (e) => {
          if (!e.target.classList.contains('menu-btn')) {
            document.querySelectorAll('.menu-options').forEach(m =>{
              m.classList.remove('show');
            });
          }
        });
        document.getElementById('imageInput').addEventListener('change', function(e) {
          const label = this.nextElementSibling;
          if (this.files && this.files[0]) {
            label.textContent = '✅ ' + this.files[0].name;
          }
        });
        </script>
        </body>
        </html>
