<?php
require_once __DIR__ . '/../../models/Reaction.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$errors = [];
$success = '';

if (!empty($_SESSION['flash_error'])) {
  $errors[] = $_SESSION['flash_error'];
  unset($_SESSION['flash_error']);
}
if (!empty($_SESSION['flash_success'])) {
  $success = $_SESSION['flash_success'];
  unset($_SESSION['flash_success']);
}

// éviter warnings si le contrôleur n'a pas injecté les variables
$donation_types = $donation_types ?? [];
$posts = $posts ?? [];
$errors = $errors ?? [];
$success = $success ?? '';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>SparkMind - forum de Donations</title>

  <style>
  :root{
    --orange:#ec7546;
    --turquoise:#1f8c87;
    --violet:#7d5aa6;

    --text:#1A464F;
    --muted:rgba(26,70,79,.75);

    --bg:#fbead7;
    --soft:#FFF7EF;
    --glass: rgba(251, 237, 215, 0.96);
  }

  *{ box-sizing:border-box; }
  body{
    margin:0;
    background:var(--bg);
    color:var(--text);
    font-family:'Poppins',system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
  }

  /* ===== TOP NAV ===== */
  .top-nav{
    position:sticky;
    top:0;
    z-index:100;
    backdrop-filter:blur(14px);
    -webkit-backdrop-filter:blur(14px);
    background:var(--glass);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 24px;
    border-bottom:1px solid rgba(0,0,0,0.03);
    animation:navFade .7s ease-out;
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
    width:40px; height:40px; border-radius:50%;
    object-fit:cover;
    box-shadow:0 6px 14px rgba(79,73,73,0.18);
    animation:logoPop .6s ease-out;
  }
  .brand-text{ display:flex; flex-direction:column; line-height:1.1; }
  .brand-name{
    font-family:'Playfair Display',serif;
    font-size:22px;
    color:var(--text);
    letter-spacing:1px;
    text-transform:uppercase;
    animation:titleGlow 2.8s ease-in-out infinite alternate;
  }
  .brand-tagline{ font-size:12px; color:var(--text); opacity:.8; }

  .header-actions{ display:flex; align-items:center; gap:10px; }

  @keyframes navFade{ from{opacity:0; transform:translateY(-16px);} to{opacity:1; transform:translateY(0);} }
  @keyframes logoPop{ from{transform:scale(.8) translateY(-6px); opacity:0;} to{transform:scale(1) translateY(0); opacity:1;} }
  @keyframes titleGlow{ from{text-shadow:0 0 0 rgba(125,90,166,0.0);} to{text-shadow:0 4px 16px rgba(125,90,166,0.55);} }

  /* ===== BTN ORANGE ===== */
  .btn-orange{
    background:var(--orange);
    color:#fff;
    border:none;
    border-radius:999px;
    padding:8px 18px;
    font-size:14px;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(236,117,70,0.45);
    display:inline-flex;
    align-items:center;
    gap:6px;
    position:relative;
    overflow:hidden;
    transition:transform .2s ease, box-shadow .2s ease, filter .2s ease;
    text-decoration:none;
    user-select:none;
  }
  .btn-orange::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,rgba(255,255,255,.35),transparent 60%);
    transform:translateX(-120%);
    transition:transform .4s ease;
  }
  .btn-orange:hover::before{ transform:translateX(20%); }
  .btn-orange:hover{
    transform:translateY(-2px) scale(1.03);
    filter:brightness(1.05);
    box-shadow:0 10px 24px rgba(236,117,70,0.55);
  }

  .notification-badge{
    position:absolute;
    top:-8px; right:-8px;
    background:#ef4444;
    color:#fff;
    border-radius:50%;
    width:22px; height:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:800;
    animation:pulse 2s infinite;
  }
  @keyframes pulse{ 0%,100%{transform:scale(1);} 50%{transform:scale(1.1);} }

  /* ===== Quote ===== */
  .page-quote{
    text-align:center;
    margin:22px auto 14px auto;
    font-family:'Playfair Display',serif;
    font-size:22px;
    color:var(--text);
    opacity:.95;
    position:relative;
    animation:quoteFade 1s ease-out;
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
  @keyframes quoteFade{ from{opacity:0; transform:translateY(-8px);} to{opacity:1; transform:translateY(0);} }

  /* ===== Main ===== */
  .space-main{ padding:10px 20px 60px; }
  .space-hero{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    max-width:1100px;
    margin:10px auto 40px auto;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    background:#f5f5f5;
  }
  .space-content{
    position:relative;
    z-index:1;
    display:grid;
    grid-template-columns:minmax(0, 360px) minmax(0, 1fr);
    gap:26px;
    padding:32px 30px 30px;
    color:#02282f;
  }

  @media (max-width: 900px){
    .space-content{ grid-template-columns:1fr; padding:24px 18px 22px; }
    .page-quote{ font-size:1.6rem; }
    .space-hero{ border-radius:20px; }
  }

  /* ===== Left column ===== */
  .side-card{
    background: rgba(255,247,239,.85);
    border-radius:24px;
    padding:18px;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    border:1px solid rgba(0,0,0,.04);
    display:flex;
    flex-direction:column;
    gap:14px;
    opacity:0;
    transform:translateY(26px);
    transition:opacity .7s ease, transform .7s ease;
  }

  .space-title{
    font-family:'Playfair Display',serif;
    font-size:20px;
    margin:0;
    color:var(--text);
    opacity:0;
    transform:translateY(18px);
  }

  .filters{ display:flex; flex-wrap:wrap; gap:10px; }

  .filter-btn{
    text-decoration:none;
    border-radius:999px;
    border:1px solid rgba(0,0,0,0.06);
    padding:8px 14px;
    background:var(--soft);
    color:var(--text);
    box-shadow:0 6px 14px rgba(0,0,0,0.12);
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    font-size:13px;
    display:inline-flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
  }
  .filter-btn:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 20px rgba(0,0,0,0.18);
    filter:brightness(1.02);
  }
  .filter-btn.active{
    background:linear-gradient(135deg,var(--violet),#b58bf0);
    border-color:transparent !important;
    color:#fff !important;
  }

  .post-box{
    background: rgba(255,255,255,.6);
    border-radius:24px;
    padding:16px;
    box-shadow:0 12px 26px rgba(0,0,0,0.14);
    border:1px solid rgba(0,0,0,.04);
  }
  .post-box h2{
    font-family:'Playfair Display',serif;
    font-size:18px;
    margin:0 0 12px;
  }

  .form-group{ display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
  .form-group label{ font-size:13px; font-weight:700; color:var(--text); }

  input[type="text"], select, textarea{
    width:100%;
    border-radius:14px;
    border:1px solid rgba(0,0,0,0.08);
    padding:10px 12px;
    background:rgba(255,255,255,.8);
    outline:none;
    transition:box-shadow .18s ease, border-color .18s ease;
    font-family:inherit;
    font-size:14px;
  }
  input[type="text"]:focus, select:focus, textarea:focus{
    border-color:rgba(31,140,135,.55);
    box-shadow:0 0 0 4px rgba(31,140,135,.18);
  }

  .error-msg{ color:#b91c1c; font-size:12px; min-height:14px; }
  .charCount{ font-size:12px; opacity:.75; align-self:flex-end; }

  .file-input-wrapper{ display:flex; flex-direction:column; gap:8px; }
  .file-input-wrapper input[type="file"]{ display:none; }

  .file-input-label{
    display:inline-flex;
    align-items:center;
    gap:8px;
    border-radius:999px;
    padding:8px 14px;
    background:rgba(255,255,255,.85);
    border:1px solid rgba(0,0,0,.08);
    cursor:pointer;
    box-shadow:0 6px 14px rgba(0,0,0,.12);
    transition:transform .18s ease, box-shadow .18s ease;
    width:fit-content;
    user-select:none;
  }
  .file-input-label:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 20px rgba(0,0,0,0.18);
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
    position:relative;
    overflow:hidden;
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    width:100%;
  }
  .btn-submit:hover{
    transform:translateY(-2px) scale(1.02);
    filter:brightness(1.03);
    box-shadow:0 14px 28px rgba(31,140,135,0.6);
  }

  /* Alerts */
  .alert{
    border-radius:16px;
    padding:10px 12px;
    display:flex;
    gap:10px;
    align-items:flex-start;
    margin:10px 0 12px;
    box-shadow:0 10px 22px rgba(0,0,0,.10);
    background:#fff;
  }
  .alert p{ margin:0; font-size:13px; }
  .alert-error{ background: rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.25); }
  .alert-success{ background: rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.25); }

  /* ===== Right column ===== */
  .post-list{
    opacity:0;
    transform:translateY(26px);
    transition:opacity .7s ease, transform .7s ease;
  }
  .post-list h2{
    font-family:'Playfair Display',serif;
    font-size:22px;
    margin:0 0 14px;
    position:relative;
    color:var(--text);
  }
  .post-list h2::after{
    content:"";
    position:absolute;
    left:0;
    bottom:-8px;
    width:90px;
    height:3px;
    border-radius:999px;
    background:linear-gradient(90deg,var(--violet),var(--orange),var(--turquoise));
    opacity:.6;
  }

  .post-item{
    background:#f5f5f5;
    border-radius:24px;
    padding:16px 16px 14px;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    margin-bottom:16px;
    overflow:hidden;
    position:relative;
    border:1px solid rgba(0,0,0,.04);
  }

  .post-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
  }

  .donation-badge{
    border-radius:999px;
    padding:6px 12px;
    font-size:12px;
    color:#fff;
    box-shadow:0 10px 22px rgba(0,0,0,.14);
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-weight:800;
  }

  .menu-container{ position:relative; }
  .menu-btn{
    border:none;
    background:rgba(255,255,255,.75);
    border-radius:999px;
    padding:6px 10px;
    cursor:pointer;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    transition:transform .18s ease, box-shadow .18s ease;
    font-size:18px;
    line-height:1;
  }
  .menu-btn:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 20px rgba(0,0,0,.16);
  }

  .menu-options{
    position:absolute;
    right:0;
    top:38px;
    background:#fff;
    border-radius:14px;
    box-shadow:0 18px 40px rgba(0,0,0,.18);
    padding:8px;
    min-width:160px;
    display:none;
    border:1px solid rgba(0,0,0,.06);
    z-index:20;
  }
  .menu-options.show{ display:block; }

  .menu-options a,
  .menu-options button.delete-link{
    display:block;
    width:100%;
    text-align:left;
    padding:8px 10px;
    border-radius:10px;
    text-decoration:none;
    color:var(--text);
    background:transparent;
    border:none;
    cursor:pointer;
    font-size:13px;
    font-family:inherit;
  }
  .menu-options a:hover,
  .menu-options button.delete-link:hover{
    background:rgba(31,140,135,.10);
  }

  .post-item img{
    width:100%;
    border-radius:18px;
    margin:12px 0;
    object-fit:cover;
    box-shadow:0 12px 26px rgba(0,0,0,.14);
  }

  .post-item h3{
    margin:6px 0 8px;
    font-size:18px;
    color:#02282f;
  }

  .post-item p{
    margin:0 0 10px;
    line-height:1.7;
    color:#02282f;
    white-space:pre-wrap;
  }

  .post-footer{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    flex-wrap:wrap;
    padding-top:10px;
    border-top:1px solid rgba(0,0,0,.06);
    margin-top:12px;
  }
  .post-footer .date{
    font-size:12px;
    color:var(--muted);
    font-weight:700;
  }

  .post-actions{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
  }

  .btn-pill{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 12px;
    border-radius:999px;
    text-decoration:none;
    font-weight:800;
    font-size:13px;
    color:var(--text);
    background:rgba(255,255,255,.75);
    border:1px solid rgba(0,0,0,.08);
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    cursor:pointer;
  }
  .btn-pill:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(0,0,0,.14);
    filter:brightness(1.02);
  }

  /* ===== REACTIONS (même style) ===== */
  .reaction-section{
    margin-top:10px;
    padding-top:10px;
    border-top:1px solid rgba(0,0,0,.06);
  }
  .reaction-btn{
    border-radius:999px;
    border:1px solid rgba(0,0,0,.08);
    background:rgba(255,255,255,.75);
    padding:8px 12px;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-family:inherit;
    font-weight:900;
    font-size:13px;
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
  }
  .reaction-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(0,0,0,.14);
    filter:brightness(1.02);
  }
  .reaction-btn .emoji{ font-size:16px; }

  .reaction-picker{
    margin-top:10px;
    padding:10px;
    border-radius:18px;
    background: rgba(255,255,255,.75);
    border:1px solid rgba(0,0,0,.08);
    box-shadow:0 12px 26px rgba(0,0,0,.12);
    display:none;
    gap:8px;
    flex-wrap:wrap;
  }

  .reaction-chip{
    border:none;
    background: rgba(255,247,239,.95);
    border:1px solid rgba(0,0,0,.08);
    border-radius:999px;
    padding:8px 12px;
    cursor:pointer;
    font-size:16px;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
  }
  .reaction-chip:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(0,0,0,.14);
    filter:brightness(1.02);
  }

  .reaction-display{
    margin-top:10px;
    display:flex;
    flex-wrap:wrap;
    gap:8px;
  }
  .reaction-item{
    background: rgba(255,255,255,.75);
    border:1px solid rgba(0,0,0,.08);
    border-radius:999px;
    padding:6px 10px;
    font-size:12px;
    font-weight:900;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
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

      <div class="header-actions">
        <?php
          require_once __DIR__ . '/../../models/Notification.php';
          $notifModel = new Notification();
          $currentUserId = (int)($_SESSION['user_id'] ?? 1);
          $unreadCount = $notifModel->getUnreadCount($currentUserId);
        ?>
        <a href="index.php?page=notifications" class="btn-orange" style="position:relative;">
          <span class="icon">🔔</span>
          <span>Notifications</span>
          <?php if ($unreadCount > 0): ?>
            <span class="notification-badge"><?= (int)$unreadCount ?></span>
          <?php endif; ?>
        </a>
      </div>
    </header>

    <h2 class="page-quote">« Quand la pensée devient espoir. »</h2>

    <main class="space-main">
      <section class="space-hero">
        <div class="space-content">

          <!-- COLONNE GAUCHE -->
          <aside class="side-card">
            <h2 class="space-title">📋 Sujet</h2>

            <div class="filters">
              <a href="index.php?page=post_list" class="filter-btn <?= !isset($_GET['type']) ? 'active' : '' ?>">
                🌟 Tous
              </a>

              <?php foreach($donation_types as $type): ?>
                <a href="index.php?page=post_list&type=<?= (int)$type['id'] ?>"
                   class="filter-btn <?= (isset($_GET['type']) && (int)$_GET['type'] === (int)$type['id']) ? 'active' : '' ?>"
                   style="<?= (isset($_GET['type']) && (int)$_GET['type'] === (int)$type['id']) ? '' : 'border-color: '.$type['color'].'; color: '.$type['color'] ?>">
                  <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
                </a>
              <?php endforeach; ?>
            </div>

            <div class="post-box">
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

              <form id="postFormFront" method="post" enctype="multipart/form-data" action="index.php?page=post_store">
                <div class="form-group">
                  <label>Type de sujet *</label>
                  <select name="donation_type_id" id="donationType">
                    <option value="">sélectionner un type</option>
                    <?php foreach($donation_types as $type): ?>
                      <option value="<?= (int)$type['id'] ?>">
                        <?= $type['icon'] ?> <?= htmlspecialchars($type['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <div id="errorType" class="error-msg"></div>
                </div>

                <div class="form-group">
                  <label>Titre (optionnel)</label>
                  <input type="text" name="titre" placeholder="Ex: titre..." />
                </div>

                <div class="form-group">
                  <label>Message *</label>
                  <textarea name="contenu" id="contenuFront" rows="4" placeholder="écrivez votre message..."></textarea>
                  <div id="errorMessage" class="error-msg"></div>
                  <small class="charCount" data-max="280">0 / 280</small>
                </div>

                <div class="form-group">
                  <div class="file-input-wrapper">
                    <input type="file" name="image" id="imageInput" accept="image/*">
                    <label for="imageInput" class="file-input-label">📷 Ajouter une image</label>
                  </div>
                </div>

                <button type="submit" class="btn-submit">Publier</button>
              </form>
            </div>
          </aside>

          <!-- COLONNE DROITE -->
          <section class="post-list">
            <h2>📢 Derniers posts</h2>

            <?php if (empty($posts)): ?>
              <article class="post-item">
                <p style="text-align:center; opacity:.8;">Aucun post pour le moment. Soyez le premier à publier ! 🎉</p>
              </article>
            <?php else: ?>
              <?php
                // Tu peux garder Like.php si tu l’utilises ailleurs
                $reactionModel = new Reaction();
              ?>

              <?php foreach ($posts as $p): ?>
                <?php
                  $postId = (int)$p['id'];
                  $userReaction = $reactionModel->getUserReaction($currentUserId, $postId, null);
                  $reactionCounts = $reactionModel->getReactionCounts($postId, null);
                ?>

                <article class="post-item">
                  <div class="post-header">
                    <span class="donation-badge" style="background-color: <?= $p['color'] ?? '#667eea' ?>">
                      <?= $p['icon'] ?? '🎁' ?> <?= htmlspecialchars($p['type_name'] ?? 'Autre') ?>
                    </span>

                    <div class="menu-container">
                      <button class="menu-btn" onclick="toggleMenu(this)">⋮</button>
                      <div class="menu-options">
                        <a href="index.php?page=post_edit&id=<?= $postId ?>">✏️ Modifier</a>

                        <form method="post" action="index.php?page=post_delete" onsubmit="return confirm('Supprimer ce post ?');">
                          <input type="hidden" name="id" value="<?= $postId ?>">
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
                      <a href="index.php?page=post_detail&id=<?= $postId ?>" class="btn-pill">💬 Commentaires</a>
                      <a href="index.php?page=post_detail&id=<?= $postId ?>" class="btn-pill">👁️ Voir</a>
                    </div>
                  </div>

                  <!-- ✅ REACTIONS -->
                  <div class="reaction-section" data-post-id="<?= $postId ?>">
                    <button type="button" class="reaction-btn" onclick="toggleReactionPicker(this)">
                      <span class="emoji"><?= $userReaction ? (Reaction::REACTIONS[$userReaction] ?? '😊') : '😊' ?></span>
                      <span>Réagir</span>
                    </button>

                    <div class="reaction-picker">
                      <?php foreach (Reaction::REACTIONS as $type => $emoji): ?>
                        <button type="button"
                                class="reaction-chip"
                                onclick="addReaction('<?= $type ?>', <?= $postId ?>)">
                          <?= $emoji ?>
                        </button>

                      <?php endforeach; ?>
                    </div>

                    <div class="reaction-display" style="<?= empty($reactionCounts) ? 'display:none;' : '' ?>">
                      <?php if (!empty($reactionCounts)):
                        arsort($reactionCounts);
                        foreach ($reactionCounts as $type => $count):
                          $emoji = Reaction::REACTIONS[$type] ?? '👍';
                      ?>
                        <span class="reaction-item"><?= $emoji ?> <?= (int)$count ?></span>
                      <?php endforeach; endif; ?>
                    </div>
                  </div>

                </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </section>
          <a class="btn-orange" href = "/sparkmind_mvc_100percent/index.php?page=main" style="position:fixed; bottom:20px; right:20px; z-index:1000;">
            Retour a l'accueil
          </a>

        </div>
      </section>
    </main>

  </div>

  <!-- Scripts -->
  <script src="assets/js/validationPost.js"></script>
  <script src="assets/js/chatbot.js"></script>

  <script>
    // Menu 3 points
    function toggleMenu(button) {
      const menu = button.nextElementSibling;
      const allMenus = document.querySelectorAll('.menu-options');
      allMenus.forEach(m => { if (m !== menu) m.classList.remove('show'); });
      menu.classList.toggle('show');
    }
    document.addEventListener('click', (e) => {
      if (!e.target.classList.contains('menu-btn')) {
        document.querySelectorAll('.menu-options').forEach(m => m.classList.remove('show'));
      }
    });

    // Nom de fichier choisi
    const imgInput = document.getElementById('imageInput');
    if (imgInput) {
      imgInput.addEventListener('change', function() {
        const label = this.nextElementSibling;
        if (this.files && this.files[0]) label.textContent = '✅ ' + this.files[0].name;
      });
    }

    // Animations
    document.addEventListener("DOMContentLoaded", () => {
      const sideCard = document.querySelector(".side-card");
      const postList = document.querySelector(".post-list");
      const title = document.querySelector(".space-title");

      setTimeout(() => {
        if (title) {
          title.style.opacity = "1";
          title.style.transform = "translateY(0)";
          title.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        }
      }, 150);

      setTimeout(() => {
        if (sideCard) {
          sideCard.style.opacity = "1";
          sideCard.style.transform = "translateY(0)";
        }
      }, 220);

      setTimeout(() => {
        if (postList) {
          postList.style.opacity = "1";
          postList.style.transform = "translateY(0)";
        }
      }, 320);
    });

    /* ===== REACTIONS JS ===== */
    function toggleReactionPicker(btn){
      const section = btn.closest('.reaction-section');
      const picker = section.querySelector('.reaction-picker');
      document.querySelectorAll('.reaction-picker').forEach(p => {
        if (p !== picker) p.style.display = 'none';
      });
      picker.style.display = (picker.style.display === 'flex') ? 'none' : 'flex';
    }

    document.addEventListener('click', (e) => {
      if (!e.target.closest('.reaction-section')) {
        document.querySelectorAll('.reaction-picker').forEach(p => p.style.display = 'none');
      }
    });

    function addReaction(type, postId, commentId, button){
      const formData = new FormData();
      formData.append('reaction_type', type);
      if (postId) formData.append('post_id', postId);
      if (commentId) formData.append('comment_id', commentId);

      fetch('index.php?page=add_reaction_ajax', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (!data.ok) {
          alert(data.error || "Erreur");
          return;
        }

        const section = button.closest('.reaction-section');

        // emoji du bouton principal
        const emojiSpan = section.querySelector('.reaction-btn .emoji');
        if (emojiSpan) emojiSpan.textContent = data.userEmoji || '😊';

        // affichage des compteurs
        const display = section.querySelector('.reaction-display');
        if (!display) return;

        display.innerHTML = '';
        const counts = data.counts || {};
        const entries = Object.entries(counts);

        if (entries.length === 0) {
          display.style.display = 'none';
        } else {
          display.style.display = 'flex';
          entries.sort((a,b)=> (b[1]||0) - (a[1]||0)).forEach(([t,c])=>{
            const span = document.createElement('span');
            span.className = 'reaction-item';
            const emoji = (data.emojis && data.emojis[t]) ? data.emojis[t] : '👍';
            span.textContent = `${emoji} ${c}`;
            display.appendChild(span);
          });
        }

        // fermer le picker
        const picker = section.querySelector('.reaction-picker');
        if (picker) picker.style.display = 'none';
      })
      .catch(err => {
        console.error(err);
        alert("Erreur réseau");
      });
    }
  </script>
</body>
</html>
