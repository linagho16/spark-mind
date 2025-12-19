<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// éviter warnings si le contrôleur n'a pas injecté les variables
$post = $post ?? [];
$donation_types = $donation_types ?? [];

// flashes (optionnel)
$flash_error = $_SESSION['flash_error'] ?? '';
$flash_success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Modifier le post - SparkMind</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
  :root{
    --orange:#ec7546;
    --turquoise:#1f8c87;
    --violet:#7d5aa6;
    --text:#1A464F;
    --bg:#fbead7;
    --glass: rgba(251, 237, 215, 0.96);
  }
  *{ box-sizing:border-box; }
  body{
    margin:0;
    background:var(--bg);
    color:var(--text);
    font-family:'Poppins',system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
  }
  .top-nav{
    position:sticky; top:0; z-index:100;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    background: var(--glass);
    display:flex; align-items:center; justify-content:space-between;
    padding: 10px 24px;
    border-bottom:1px solid rgba(0,0,0,0.03);
  }
  .top-nav::after{
    content:"";
    position:absolute;
    inset:auto 40px -2px 40px;
    height:2px;
    background:linear-gradient(90deg,var(--violet),var(--orange),var(--turquoise));
    opacity:.35;
    border-radius:999px;
  }
  .brand-block{ display:flex; align-items:center; gap:10px; }
  .logo-img{
    width:40px; height:40px;
    border-radius:50%;
    object-fit:cover;
    box-shadow:0 6px 14px rgba(79,73,73,0.18);
  }
  .brand-text{ display:flex; flex-direction:column; line-height:1.1; }
  .brand-name{
    font-family:'Playfair Display',serif;
    font-size:22px;
    letter-spacing:1px;
    text-transform:uppercase;
  }
  .brand-tagline{ font-size:12px; opacity:.8; }

  .page-quote{
    text-align:center;
    margin:22px auto 14px auto;
    font-family:'Playfair Display',serif;
    font-size:22px;
    opacity:.95;
    position:relative;
  }
  .page-quote::after{
    content:"";
    position:absolute;
    left:50%;
    transform:translateX(-50%);
    bottom:-8px;
    width:90px;
    height:3px;
    border-radius:999px;
    background:linear-gradient(90deg,var(--violet),var(--orange),var(--turquoise));
    opacity:.6;
  }

  .space-main{ padding:10px 20px 60px; }
  .space-hero{
    border-radius:24px;
    max-width:1100px;
    margin:10px auto 40px auto;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    background:#f5f5f5;
    overflow:hidden;
  }
  .space-content{
    padding:32px 30px 30px;
    max-width:1100px;
    margin:0 auto;
  }

  .btn-pill{
    text-decoration:none;
    border-radius:999px;
    padding:8px 14px;
    background:rgba(255,255,255,.75);
    border:1px solid rgba(0,0,0,.08);
    color:var(--text);
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    font-size:13px;
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-weight:800;
  }

  .edit-card{
    background: rgba(255,247,239,.85);
    border:1px solid rgba(0,0,0,.04);
    border-radius:24px;
    padding:18px;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    max-width:780px;
    margin:16px auto 0;
  }
  .edit-card h2{
    font-family:'Playfair Display',serif;
    font-size:22px;
    margin:0 0 12px;
    color:#02282f;
  }

  .alert{
    border-radius:16px;
    padding:10px 12px;
    display:flex;
    gap:10px;
    align-items:flex-start;
    box-shadow:0 10px 22px rgba(0,0,0,.12);
    background:#fff;
    max-width:780px;
    margin:12px auto;
    border:1px solid rgba(0,0,0,.06);
  }
  .alert-error{ background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.25); }
  .alert-success{ background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.25); }

  .form-group{ display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
  .form-group label{ font-size:13px; font-weight:800; }

  input[type="text"], select, textarea{
    width:100%;
    border-radius:14px;
    border:1px solid rgba(0,0,0,0.10);
    padding:10px 12px;
    background:rgba(255,255,255,.85);
    outline:none;
    font-family:inherit;
    font-size:14px;
  }

  .current-img{
    width:100%;
    max-width:380px;
    border-radius:18px;
    margin-top:10px;
    object-fit:cover;
    box-shadow:0 12px 26px rgba(0,0,0,.14);
    border:1px solid rgba(0,0,0,.06);
  }

  .file-input-wrapper input[type="file"]{ display:none; }
  .file-input-label{
    display:inline-flex;
    align-items:center;
    gap:8px;
    border-radius:999px;
    padding:8px 14px;
    background:rgba(255,255,255,.85);
    border:1px solid rgba(0,0,0,.10);
    cursor:pointer;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    width:fit-content;
    user-select:none;
    font-weight:800;
  }

  .btn-submit{
    border-radius:999px;
    border:none;
    padding:10px 20px;
    font-size:15px;
    cursor:pointer;
    font-family:inherit;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    background:var(--turquoise);
    color:#fff;
    box-shadow:0 10px 22px rgba(31,140,135,0.5);
    width:100%;
    margin-top:10px;
    font-weight:900;
  }
  </style>
</head>

<body>
  <div class="site">

    <header class="main-header top-nav">
      <div class="brand-block">
        <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" class="logo-img">
        <div class="brand-text">
          <span class="brand-name">SPARKMIND</span>
          <span class="brand-tagline">Forum de messageries</span>
        </div>
      </div>
    </header>

    <h2 class="page-quote">« Quand la pensée devient espoir. »</h2>

    <?php if (!empty($flash_error)): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($flash_error) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_success)): ?>
      <div class="alert alert-success">✅ <?= htmlspecialchars($flash_success) ?></div>
    <?php endif; ?>

    <main class="space-main">
      <section class="space-hero">
        <div class="space-content">

          <a href="index.php?page=post_detail&id=<?= (int)($post['id'] ?? 0) ?>" class="btn-pill">
            ← Retour au post
          </a>

          <div class="edit-card">
            <h2>✏️ Modifier le post</h2>

            <form method="post" action="index.php?page=post_update" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= (int)($post['id'] ?? 0) ?>">

                <input type="hidden" name="donation_type_id" value="<?= (int)($post['donation_type_id'] ?? 1) ?>">


              <div class="form-group">
                <label>Titre (optionnel)</label>
                <input type="text" name="titre"
                       value="<?= htmlspecialchars($post['titre'] ?? '') ?>"
                       placeholder="Ex: Don de vêtements d'hiver" />
              </div>

              <div class="form-group">
                <label>Message *</label>
                <textarea name="contenu" rows="6" required><?= htmlspecialchars($post['contenu'] ?? '') ?></textarea>
              </div>

              <?php if (!empty($post['image'])): ?>
                <div class="form-group">
                  <label>Image actuelle</label>
                  <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image actuelle" class="current-img">
                </div>
              <?php endif; ?>

              <div class="form-group">
                <label><?= !empty($post['image']) ? 'Changer l\'image' : 'Ajouter une image' ?></label>
                <div class="file-input-wrapper">
                  <input type="file" name="image" id="imageInput" accept="image/*">
                  <label for="imageInput" class="file-input-label">
                    📷 <?= !empty($post['image']) ? 'Changer l\'image' : 'Ajouter une image' ?>
                  </label>
                </div>
              </div>

              <button type="submit" class="btn-submit">💾 Enregistrer les modifications</button>
            </form>
          </div>

        </div>
      </section>
    </main>

  </div>

  <script>
    const imgInput = document.getElementById('imageInput');
    if (imgInput) {
      imgInput.addEventListener('change', function() {
        const label = this.nextElementSibling;
        if (this.files && this.files[0]) label.textContent = '✅ ' + this.files[0].name;
      });
    }
  </script>
</body>
</html>
