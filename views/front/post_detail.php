<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/**
 * Sécurisation pour éviter:
 * - Undefined variable $comments
 * - count(null)
 */
$comments = $comments ?? [];
?>

<?php // post_detail.php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($post['titre'] ?? 'Post') ?> - SparkMind</title>

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
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    background: var(--glass);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 10px 24px;
    border-bottom:1px solid rgba(0,0,0,0.03);
    animation: navFade 0.7s ease-out;
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

  @keyframes navFade{ from{opacity:0; transform:translateY(-16px);} to{opacity:1; transform:translateY(0);} }
  @keyframes logoPop{ from{transform:scale(.8) translateY(-6px); opacity:0;} to{transform:scale(1) translateY(0); opacity:1;} }
  @keyframes titleGlow{ from{text-shadow:0 0 0 rgba(125,90,166,0);} to{text-shadow:0 4px 16px rgba(125,90,166,.55);} }

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

  /* ===== Layout ===== */
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
  .space-hero::before,
  .space-hero::after{
    content:"";
    position:absolute;
    border-radius:999px;
    filter:blur(18px);
    opacity:.55;
    mix-blend-mode:screen;
    animation:floatBlob 10s ease-in-out infinite alternate;
    pointer-events:none;
  }
  .space-hero::before{
    width:120px; height:120px;
    top:-40px; left:20px;
    background:rgba(127,71,192,0.6);
  }
  .space-hero::after{
    width:160px; height:160px;
    bottom:-50px; right:10px;
    background:rgba(31,140,135,.7);
    animation-delay:-4s;
  }
  @keyframes floatBlob{ from{transform:translate(0,0);} to{transform:translate(-8px,16px);} }

  .space-content{
    position:relative;
    z-index:1;
    display:grid;
    grid-template-columns:1fr;
    gap:18px;
    padding:32px 30px 30px;
    color:#02282f;
  }
  @media (max-width:900px){
    .space-content{ padding:24px 18px 22px; }
    .space-hero{ border-radius:20px; }
    .page-quote{ font-size:1.6rem; }
  }

  /* ===== Buttons ===== */
  .btn-pill{
    text-decoration:none;
    border-radius:999px;
    padding:8px 14px;
    background:rgba(255,255,255,.75);
    border:1px solid rgba(0,0,0,.08);
    color:var(--text);
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    font-size:13px;
    display:inline-flex;
    align-items:center;
    gap:6px;
    width:fit-content;
    font-weight:800;
  }
  .btn-pill:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(0,0,0,.14);
    filter:brightness(1.02);
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
    position:relative;
    overflow:hidden;
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    font-weight:900;
  }
  .btn-submit::before{
    content:"";
    position:absolute;
    inset:0;
    background:radial-gradient(circle at 0 0,rgba(255,255,255,.4),transparent 60%);
    opacity:0;
    transition:opacity .25s ease;
  }
  .btn-submit:hover{
    transform:translateY(-2px) scale(1.02);
    filter:brightness(1.03);
    box-shadow:0 14px 28px rgba(31,140,135,0.6);
  }
  .btn-submit:hover::before{ opacity:1; }

  /* ===== Alert ===== */
  .alert{
    border-radius:16px;
    padding:10px 12px;
    display:flex;
    gap:10px;
    align-items:flex-start;
    box-shadow:0 10px 22px rgba(0,0,0,.10);
    background:#fff;
    max-width:900px;
    margin:0 auto;
    border:1px solid rgba(0,0,0,.06);
  }
  .alert-error{
    background: rgba(239,68,68,.12);
    border:1px solid rgba(239,68,68,.25);
  }

  /* ===== Post card ===== */
  .post-card{
    background: rgba(255,247,239,.85);
    border:1px solid rgba(0,0,0,.04);
    border-radius:24px;
    padding:18px 18px 18px;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    width:100%;
    max-width:900px;
    margin:0 auto;
    overflow:hidden;
    position:relative;
  }
  .post-card::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:radial-gradient(circle at top left,rgba(255,255,255,.45),transparent 60%);
    opacity:0;
    transition:opacity .25s ease;
    pointer-events:none;
  }
  .post-card:hover::before{ opacity:.8; }

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
    white-space:nowrap;
    font-weight:900;
  }

  /* menu ⋮ */
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
  .menu-btn:hover{ transform:translateY(-1px); box-shadow:0 10px 20px rgba(0,0,0,.16); }

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
    font-weight:700;
  }
  .menu-options a:hover,
  .menu-options button.delete-link:hover{ background:rgba(31,140,135,.10); }

  .post-image{
    width:100%;
    border-radius:18px;
    margin:12px 0;
    object-fit:cover;
    box-shadow:0 12px 26px rgba(0,0,0,.14);
  }

  .post-title{
    font-family:'Playfair Display',serif;
    font-size:28px;
    margin: 14px 0 10px;
    color:#02282f;
  }
  .post-content{
    font-size:15px;
    line-height:1.8;
    margin: 14px 0;
    color:#02282f;
  }

  .post-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-top:10px;
    padding-top:10px;
    border-top:1px solid rgba(0,0,0,.06);
  }
  .date{ font-size:12px; color:var(--muted); font-weight:800; white-space:nowrap; }

  /* ===== Reactions ===== */
  .reaction-section{ margin-top:10px; padding-top:10px; border-top:1px solid rgba(0,0,0,.06); }
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
    font-weight:800;
  }
  .reaction-picker{
    margin-top:10px;
    padding:10px;
    border-radius:16px;
    background:#fff;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 12px 26px rgba(0,0,0,.12);
    display:none;
    gap:8px;
    flex-wrap:wrap;
  }
  .reaction-picker button{
    border:none;
    background:rgba(251,237,215,.65);
    border-radius:12px;
    padding:8px 10px;
    cursor:pointer;
    font-size:18px;
  }
  .reaction-display{ margin-top:10px; display:flex; flex-wrap:wrap; gap:8px; }
  .reaction-item{
    background:rgba(255,247,239,.9);
    border:1px solid rgba(0,0,0,.06);
    border-radius:999px;
    padding:6px 10px;
    font-size:12px;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    font-weight:800;
  }

  /* ===== Comments ===== */
  .comments-section{ margin-top:16px; padding-top:12px; border-top:1px solid rgba(0,0,0,.06); }
  .comments-section h2{
    font-family:'Playfair Display',serif;
    font-size:20px;
    margin:0 0 12px;
    color:var(--text);
  }

  .comment-form-wrapper{
    background:rgba(255,255,255,.6);
    border-radius:24px;
    padding:14px;
    box-shadow:0 12px 26px rgba(0,0,0,.12);
    border:1px solid rgba(0,0,0,.04);
    margin-bottom:14px;
  }
  .comment-form .form-group{ display:flex; flex-direction:column; gap:10px; }

  .comment-form textarea{
    width:100%;
    border-radius:14px;
    border:1px solid rgba(0,0,0,0.10);
    padding:10px 12px;
    background:rgba(255,255,255,.85);
    outline:none;
    font-family:inherit;
    font-size:14px;
  }
  .comment-form textarea:focus{
    border-color:rgba(31,140,135,.55);
    box-shadow:0 0 0 4px rgba(31,140,135,.18);
  }

  .sticker-toggle-btn{
    width:fit-content;
    border-radius:999px;
    border:1px solid rgba(0,0,0,.08);
    background:rgba(255,255,255,.85);
    padding:8px 14px;
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    cursor:pointer;
    font-family:inherit;
    font-weight:800;
  }

  .comment{
    background:#f5f5f5;
    border-radius:24px;
    padding:14px 14px 12px;
    box-shadow:0 18px 40px rgba(96,84,84,0.14);
    border:1px solid rgba(0,0,0,.04);
    margin-bottom:12px;
    position:relative;
    overflow:hidden;
  }
  .comment::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:radial-gradient(circle at top left,rgba(255,255,255,.45),transparent 60%);
    opacity:0;
    transition:opacity .25s ease;
    pointer-events:none;
  }
  .comment:hover::before{ opacity:.8; }

  .comment-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:8px;
    flex-wrap:wrap;
  }
  .comment-date{ font-size:12px; color:var(--muted); font-weight:700; white-space:nowrap; }
  .comment p{ margin:0; line-height:1.7; white-space:pre-wrap; color:#02282f; }

  /* ===== Chatbot ===== */
  #chatbotButton{
    position:fixed;
    right:18px;
    bottom:18px;
    width:56px;
    height:56px;
    border-radius:999px;
    display:grid;
    place-items:center;
    background: var(--orange);
    color:#fff;
    box-shadow:0 14px 28px rgba(236,117,70,.45);
    cursor:pointer;
    z-index:999;
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    user-select:none;
  }
  #chatbotButton:hover{
    transform: translateY(-2px) scale(1.03);
    filter: brightness(1.05);
    box-shadow:0 18px 36px rgba(236,117,70,.55);
  }

  #chatbotBox{
    position:fixed;
    right:18px;
    bottom:86px;
    width:min(360px, calc(100vw - 36px));
    background:rgba(255,255,255,.85);
    border-radius:20px;
    box-shadow:0 22px 50px rgba(0,0,0,.25);
    overflow:hidden;
    z-index:999;
    border:1px solid rgba(0,0,0,.06);
  }
  #chatbotBox.hidden{ display:none; }

  .chat-header{
    background:rgba(251,237,215,0.96);
    padding:10px 12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:900;
    color:var(--text);
    border-bottom:1px solid rgba(0,0,0,.06);
  }
  #closeChatbot{
    cursor:pointer;
    font-size:20px;
    line-height:1;
    padding:2px 10px;
    border-radius:999px;
    background:rgba(255,255,255,.7);
    border:1px solid rgba(0,0,0,.06);
  }
  .chat-window{
    height:260px;
    overflow:auto;
    padding:12px;
    background:rgba(255,247,239,.55);
  }
  .chat-input{
    display:flex;
    gap:8px;
    padding:10px;
    background:rgba(255,255,255,.85);
    border-top:1px solid rgba(0,0,0,.06);
  }
  .chat-input input{
    flex:1;
    border-radius:999px;
    border:1px solid rgba(0,0,0,.10);
    padding:10px 12px;
    outline:none;
  }
  .chat-input button{
    border:none;
    border-radius:999px;
    padding:10px 14px;
    background:var(--turquoise);
    color:#fff;
    cursor:pointer;
    font-weight:900;
    box-shadow:0 10px 22px rgba(31,140,135,.35);
  }
</style>

</head>

<body>
  <div class="site">

    <header class="main-header top-nav">
      <div class="brand-block">
        <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" class="logo-img" />
        <div class="brand-text">
          <span class="brand-name">SPARKMIND</span>
          <span class="brand-tagline">Forum de messageries</span>
        </div>
      </div>
      <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    </header>

    <h2 class="page-quote">« Quand la pensée devient espoir. »</h2>

    <main class="space-main">
      <section class="space-hero">
        <div class="space-content">

          <?php if (isset($_SESSION['comment_error'])): ?>
            <div class="alert alert-error">
              <?= htmlspecialchars($_SESSION['comment_error']) ?>
            </div>
            <?php unset($_SESSION['comment_error']); ?>
          <?php endif; ?>

          <a href="index.php?page=post_list" class="btn-pill">← Retour à la liste</a>

          <div class="post-card">
            <div class="post-header">
              <span class="donation-badge" style="background-color: <?= $post['color'] ?? '#667eea' ?>">
                <?= $post['icon'] ?? '🎁' ?> <?= htmlspecialchars($post['type_name'] ?? 'Autre') ?>
              </span>

              <div class="menu-container">
                <button class="menu-btn" onclick="toggleMenu(this)">⋮</button>
                <div class="menu-options">
                  <a href="index.php?page=post_edit&id=<?= (int)$post['id'] ?>">✏️ Modifier</a>

                  <form method="post" action="index.php?page=post_delete" onsubmit="return confirm('Supprimer ce post ?');">
                    <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
                    <button type="submit" class="delete-link">🗑️ Supprimer</button>
                  </form>
                </div>
              </div>
            </div>

            <?php if (!empty($post['image'])): ?>
              <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image du post" class="post-image" />
            <?php endif; ?>

            <?php if (!empty($post['titre'])): ?>
              <h1 class="post-title"><?= htmlspecialchars($post['titre']) ?></h1>
            <?php endif; ?>

            <div class="post-content">
              <?= nl2br(htmlspecialchars($post['contenu'])) ?>
            </div>

            <div class="post-footer">
              <span class="date">📅 Publié le <?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?></span>
            </div>

            <!-- REACTIONS POST -->
            <?php
              require_once __DIR__ . '/../../models/Reaction.php';
              $reactionModel = new Reaction();
              $currentUserId = $_SESSION['user_id'] ?? 1;
              $userReaction = $reactionModel->getUserReaction($currentUserId, (int)$post['id']);
              $reactionCounts = $reactionModel->getReactionCounts((int)$post['id']);
            ?>
            <div class="reaction-section" data-post-id="<?= (int)$post['id'] ?>">
              <button type="button" class="reaction-btn" onclick="toggleReactionPicker(this)">
                <span class="emoji"><?= $userReaction ? (Reaction::REACTIONS[$userReaction] ?? '😊') : '😊' ?></span>
                <span>Réagir</span>
              </button>

              <div class="reaction-picker">
                <?php foreach(Reaction::REACTIONS as $type => $emoji): ?>
                  <button type="button" onclick="addReaction('<?= $type ?>', <?= (int)$post['id'] ?>, null, this)" title="<?= ucfirst($type) ?>">
                    <?= $emoji ?>
                  </button>
                <?php endforeach; ?>
              </div>

              <div class="reaction-display" style="<?= empty($reactionCounts) ? 'display:none;' : '' ?>">
                <?php if (!empty($reactionCounts)):
                  arsort($reactionCounts);
                  foreach($reactionCounts as $type => $count):
                    $emoji = Reaction::REACTIONS[$type] ?? '👍';
                ?>
                  <span class="reaction-item"><?= $emoji ?> <?= (int)$count ?></span>
                <?php endforeach; endif; ?>
              </div>
            </div>

            <!-- COMMENTAIRES -->
            <div class="comments-section">
              <h2>💬 Commentaires (<?= count($comments) ?>)</h2>

              <div class="comment-form-wrapper">
                <form method="post" action="index.php?page=comment_add" class="comment-form">
                  <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                  <div class="form-group">
                    <textarea name="content" id="commentTextarea" rows="3" placeholder="Écrire un commentaire..."></textarea>
                    <button type="button" class="sticker-toggle-btn"
                            onclick="toggleStickerPicker(document.getElementById('commentTextarea'))">
                    😊 Stickers
                    </button>

                    <div id="stickerPicker" style="display:none; margin-top:10px; padding:10px; border-radius:16px; background:#fff; border:1px solid rgba(0,0,0,.06); box-shadow:0 12px 26px rgba(0,0,0,.12); flex-wrap:wrap; gap:8px;">
                    <button type="button" onclick="insertSticker('😀')">😀</button>
                    <button type="button" onclick="insertSticker('😍')">😍</button>
                    <button type="button" onclick="insertSticker('😂')">😂</button>
                    <button type="button" onclick="insertSticker('🥳')">🥳</button>
                    <button type="button" onclick="insertSticker('😡')">😡</button>
                    <button type="button" onclick="insertSticker('🙏')">🙏</button>
                    <button type="button" onclick="insertSticker('❤️')">❤️</button>
                    <button type="button" onclick="insertSticker('👍')">👍</button>
                    <button type="button" onclick="insertSticker('🎁')">🎁</button>
                    </div>

                    <style>
                    #stickerPicker button{
                        border:none;
                        background:rgba(251,237,215,.65);
                        border-radius:12px;
                        padding:8px 10px;
                        cursor:pointer;
                        font-size:18px;
                        transition:transform .15s ease;
                    }
                    #stickerPicker button:hover{ transform:scale(1.12); }
                    </style>

                  </div>
                  <button type="submit" class="btn-submit">💬 Commenter</button>
                </form>
              </div>

              <div id="commentsList">
                <?php if (empty($comments)): ?>
                  <p style="text-align:center; opacity:.8; padding:18px 0;">
                    Aucun commentaire pour le moment. Soyez le premier à commenter ! ✨
                  </p>
                <?php else: ?>
                  <?php foreach($comments as $comment): ?>
                    <div class="comment">
                      <div class="comment-header">
                        <strong>😊 <?= htmlspecialchars($comment['username']) ?></strong>

                        <span class="comment-date">
                          <?= date('d/m/Y à H:i', strtotime($comment['created_at'])) ?>
                        </span>

                        <div class="menu-container">
                          <button class="menu-btn" onclick="toggleMenu(this)" style="padding:2px 8px; font-size:16px;">⋮</button>
                          <div class="menu-options">
                            <a href="index.php?page=comment_edit&id=<?= (int)$comment['id'] ?>&post_id=<?= (int)$post['id'] ?>">✏️ Modifier</a>

                            <form method="post" action="index.php?page=comment_delete" onsubmit="return confirm('Supprimer ce commentaire ?');">
                              <input type="hidden" name="comment_id" value="<?= (int)$comment['id'] ?>">
                              <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                              <button type="submit" class="delete-link">🗑️ Supprimer</button>
                            </form>
                          </div>
                        </div>
                      </div>

                      <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>

                      <?php
                        $commentReaction = $reactionModel->getUserReaction($currentUserId, null, (int)$comment['id']);
                        $commentReactionCounts = $reactionModel->getReactionCounts(null, (int)$comment['id']);
                      ?>

                      <div class="reaction-section" data-comment-id="<?= (int)$comment['id'] ?>">
                        <button type="button" class="reaction-btn" onclick="toggleReactionPicker(this)">
                          <span class="emoji"><?= $commentReaction ? (Reaction::REACTIONS[$commentReaction] ?? '😊') : '😊' ?></span>
                          <span>Réagir</span>
                        </button>

                        <div class="reaction-picker">
                          <?php foreach(Reaction::REACTIONS as $type => $emoji): ?>
                            <button type="button" onclick="addReaction('<?= $type ?>', null, <?= (int)$comment['id'] ?>, this)" title="<?= ucfirst($type) ?>">
                              <?= $emoji ?>
                            </button>
                          <?php endforeach; ?>
                        </div>

                        <div class="reaction-display" style="<?= empty($commentReactionCounts) ? 'display:none;' : '' ?>">
                          <?php if (!empty($commentReactionCounts)):
                            arsort($commentReactionCounts);
                            foreach($commentReactionCounts as $type => $count):
                              $emoji = Reaction::REACTIONS[$type] ?? '👍';
                          ?>
                            <span class="reaction-item"><?= $emoji ?> <?= (int)$count ?></span>
                          <?php endforeach; endif; ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>

          </div><!-- /post-card -->

        </div><!-- /space-content -->
      </section>
    </main>

    <!-- Chatbot -->
    <div id="chatbotButton">💬</div>
    <div id="chatbotBox" class="hidden">
      <div class="chat-header">
        Assistant IA
        <span id="closeChatbot">×</span>
      </div>
      <div id="chatWindow" class="chat-window"></div>
      <div class="chat-input">
        <input type="text" id="userMessage" placeholder="Écris un message…">
        <button onclick="sendMessage()">Envoyer</button>
      </div>
    </div>

  </div>

  <script>
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
  </script>

  <script src="assets/js/chatbot.js"></script>
  <script src="assets/js/reactions.js"></script>
  <script src="assets/js/validationComment.js"></script>
  <script>
  function toggleStickerPicker(textarea) {
    const picker = document.getElementById('stickerPicker');
    if (!picker) return;

    // toggle display
    const isHidden = (picker.style.display === '' || picker.style.display === 'none');
    picker.style.display = isHidden ? 'flex' : 'none';

    // garder le focus sur textarea
    if (textarea) textarea.focus();
  }

  function insertSticker(emoji) {
    const textarea = document.getElementById('commentTextarea');
    if (!textarea) return;

    const start = textarea.selectionStart ?? textarea.value.length;
    const end = textarea.selectionEnd ?? textarea.value.length;

    const before = textarea.value.substring(0, start);
    const after  = textarea.value.substring(end);

    textarea.value = before + emoji + after;

    const newPos = start + emoji.length;
    textarea.selectionStart = textarea.selectionEnd = newPos;

    textarea.focus();
  }
</script>

</body>
</html>
