<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Notifications - SparkMind</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
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

  /* ===== HEADER (ton .toppage) ===== */
  .toppage{
    position:sticky;
    top:0;
    z-index:100;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    background: var(--glass);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 24px;
    border-bottom:1px solid rgba(0,0,0,0.03);
    animation: navFade .7s ease-out;
  }
  .toppage::after{
    content:"";
    position:absolute;
    inset:auto 40px -2px 40px;
    height:2px;
    background:linear-gradient(90deg,var(--violet),var(--orange),var(--turquoise));
    opacity:.35;
    border-radius:999px;
  }

  .logo-title{ display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
  .logo-title img{
    width:40px; height:40px;
    border-radius:50%;
    object-fit:cover;
    box-shadow:0 6px 14px rgba(79,73,73,0.18);
    animation:logoPop .6s ease-out;
  }
  .title-block{ display:flex; flex-direction:column; line-height:1.1; }
  .title-block h1{
    margin:0;
    font-family:'Playfair Display',serif;
    font-size:22px;
    letter-spacing:1px;
    color:var(--text);
    animation:titleGlow 2.8s ease-in-out infinite alternate;
  }
  .subtitle{ margin:2px 0 0; font-size:12px; opacity:.8; color:var(--text); }

  @keyframes navFade{ from{opacity:0; transform:translateY(-16px);} to{opacity:1; transform:translateY(0);} }
  @keyframes logoPop{ from{transform:scale(.8) translateY(-6px); opacity:0;} to{transform:scale(1) translateY(0); opacity:1;} }
  @keyframes titleGlow{ from{text-shadow:0 0 0 rgba(125,90,166,0);} to{text-shadow:0 4px 16px rgba(125,90,166,.55);} }

  /* ===== MAIN (ton .wrap) ===== */
  .wrap{
    padding:10px 20px 60px;
    max-width:900px;
    margin:0 auto;
    display:grid;
    gap:18px;
    position:relative;
  }

  /* fond hero + blobs sans toucher HTML */
  .wrap::before{
    content:"";
    position:absolute;
    left:0; right:0;
    top:10px;
    height:calc(100% - 10px);
    border-radius:24px;
    background:#f5f5f5;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    z-index:-2;
  }

  .wrap::after{
    content:"";
    position:absolute;
    inset:10px 0 auto 0;
    height:280px;
    z-index:-1;
    pointer-events:none;
    background:
      radial-gradient(circle at 10% 10%, rgba(127,71,192,0.45), transparent 55%),
      radial-gradient(circle at 90% 40%, rgba(31,140,135,0.45), transparent 55%);
    filter:blur(10px);
    opacity:.85;
    border-radius:24px;
  }

  /* ===== Boutons pill (ton .btn-view) ===== */
  .btn-view{
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
  .btn-view:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(0,0,0,.14);
    filter:brightness(1.02);
  }

  /* ===== Notifications ===== */
  .notification-item{
    background:rgba(255,247,239,.85);
    border:1px solid rgba(0,0,0,.04);
    padding:16px 16px 14px;
    border-radius:24px;
    margin-bottom:14px;
    box-shadow:0 18px 40px rgba(96,84,84,0.14);
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    display:flex;
    gap:14px;
    align-items:flex-start;
    position:relative;
    overflow:hidden;
  }
  .notification-item::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:radial-gradient(circle at top left,rgba(255,255,255,.45),transparent 60%);
    opacity:0;
    transition:opacity .25s ease;
    pointer-events:none;
  }
  .notification-item:hover{
    transform:translateY(-2px);
    box-shadow:0 22px 46px rgba(0,0,0,.16);
    filter:brightness(1.01);
  }
  .notification-item:hover::before{ opacity:.85; }

  .notification-item.unread{
    border:1px solid rgba(236,117,70,.35);
    box-shadow:0 20px 44px rgba(236,117,70,.10);
  }
  .notification-item.unread::after{
    content:"";
    position:absolute;
    left:14px;
    top:14px;
    width:10px;
    height:10px;
    border-radius:50%;
    background:var(--orange);
    box-shadow:0 0 0 4px rgba(236,117,70,.18);
  }

  .notification-icon{
    font-size:32px;
    flex-shrink:0;
    line-height:1;
    margin-top:2px;
  }

  .notification-content{ flex:1; min-width:0; }

  .notification-message{
    font-weight:900;
    margin-bottom:6px;
    color:#02282f;
  }

  .notification-post{
    font-size:13px;
    color:var(--muted);
    margin-bottom:8px;
  }

  .notification-time{
    font-size:12px;
    color:rgba(26,70,79,.65);
  }

  .notification-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:10px;
  }

  /* boutons actions (remplace .mark-read-btn) */
  .mark-read-btn{
    text-decoration:none;
    border-radius:999px;
    padding:8px 12px;
    font-size:13px;
    font-weight:800;
    border:1px solid rgba(0,0,0,.08);
    background:rgba(255,255,255,.78);
    color:var(--text);
    box-shadow:0 6px 14px rgba(0,0,0,.10);
    transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
    display:inline-flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
  }
  .mark-read-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(0,0,0,.14);
    filter:brightness(1.02);
  }

  /* “Voir le post” plus “turquoise” */
  a.mark-read-btn{
    background:rgba(31,140,135,.10);
    border-color:rgba(31,140,135,.22);
  }

  /* “Marquer comme lu” (ton style inline reste OK, mais on l’améliore si tu enlèves l’inline) */
  form .mark-read-btn{
    background:rgba(236,117,70,.10);
    border-color:rgba(236,117,70,.22);
  }

  /* ===== Empty state ===== */
  .no-notifications{
    text-align:center;
    padding:60px 20px;
    color:rgba(26,70,79,.8);
    background:rgba(255,247,239,.85);
    border:1px solid rgba(0,0,0,.04);
    border-radius:24px;
    box-shadow:0 18px 40px rgba(96,84,84,0.14);
  }
  .no-notifications-icon{
    font-size:64px;
    margin-bottom:14px;
  }
  .no-notifications h2{
    margin:0 0 8px;
    font-family:'Playfair Display',serif;
    color:#02282f;
  }
  .no-notifications p{ margin:6px 0; }

  .mark-all-read{ margin-bottom:6px; }

  @media (max-width:768px){
    .wrap{ padding:10px 14px 50px; }
    .toppage{ padding:10px 14px; }
    .wrap::before, .wrap::after{ border-radius:20px; }
    .notification-item{ border-radius:20px; }
  }
</style>

</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" />
            <div class="title-block">
                <h1>🔔 Notifications</h1>
                <p class="subtitle">Vos dernières notifications</p>
            </div>
        </div>
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    </header>

    <main class="wrap" style="grid-template-columns: 1fr; max-width: 900px; margin: 0 auto;">
        <a href="/sparkmind_mvc_100percent/index.php?page=post_list" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au forum
        </a>

        <?php if (!empty($notifications)): ?>
            <div class="mark-all-read">
                <a href="index.php?action=mark_all_read" class="btn-view">
                    ✅ Tout marquer comme lu
                </a>
            </div>

            <?php foreach ($notifications as $notif): ?>
                <div class="notification-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                    <div class="notification-icon">
                        <?php if ($notif['type'] === 'like'): ?>
                            ❤️
                        <?php elseif ($notif['type'] === 'comment'): ?>
                            💬
                        <?php else: ?>
                            📢
                        <?php endif; ?>
                    </div>
                    
                    <div class="notification-content">
                        <div class="notification-message">
                            <?= htmlspecialchars($notif['message']) ?>
                        </div>
                        
                        <?php if ($notif['post_titre']): ?>
                            <div class="notification-post">
                                Post: <strong><?= htmlspecialchars($notif['post_titre']) ?></strong>
                            </div>
                        <?php elseif ($notif['post_contenu']): ?>
                            <div class="notification-post">
                                Post: <?= htmlspecialchars(substr($notif['post_contenu'], 0, 60)) ?>...
                            </div>
                        <?php endif; ?>
                        
                        <div class="notification-time">
                            <?= date('d/m/Y à H:i', strtotime($notif['created_at'])) ?>
                        </div>
                        
                        <div class="notification-actions" style="margin-top: 10px;">
                            <a href="index.php?action=show&id=<?= $notif['post_id'] ?>" class="mark-read-btn">
                                👁️ Voir le post
                            </a>
                            
                            <?php if (!$notif['is_read']): ?>
                                <form method="post" action="index.php?action=mark_notification_read" style="margin: 0;">
                                    <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                                    <input type="hidden" name="redirect" value="index.php?action=notifications">
                                    <button type="submit" class="mark-read-btn" style="background: #e8d5c4; color: #2d3748;">
                                        ✓ Marquer comme lu
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        <?php else: ?>
            <div class="no-notifications">
                <div class="no-notifications-icon">🔔</div>
                <h2>Aucune notification</h2>
                <p>Vous n'avez pas encore de notifications.</p>
                <p>Lorsque quelqu'un commente ou aime vos posts, vous serez notifié ici!</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
