<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Gestion des Commentaires - BackOffice</title>
  

  <!-- ✅ Même style CSS SparkMind (comme la dernière) - sans modifier ton contenu -->
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}

    body{
      min-height:100vh;
      font-family:'Poppins',system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
      color:#1A464F;
      background:
        radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
        radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
        #FBEDD7;
    }

    /* header sticky + blur */
    .toppage{
      position: sticky;
      top: 0;
      z-index: 90;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96);
      border-bottom: 1px solid rgba(0,0,0,.06);
      padding: 10px 24px;
    }

    .logo-title{
      display:flex;
      align-items:center;
      gap:12px;
    }

    .logo-title img{
      width:44px;
      height:44px;
      border-radius:50%;
      object-fit:cover;
    }

    .title-block h1{
      font-family:'Playfair Display',serif;
      font-size:20px;
      margin:0;
      color:#1A464F;
    }



    .subtitle{
      margin:0;
      font-size:12px;
      color:#1A464F;
      opacity:.8;
    }

    /* wrapper */
    .wrap{
      max-width:1100px;
      margin: 28px auto 40px;
      padding: 0 18px 30px;
    }

    .stat-card-link {
    text-decoration: none;
    color: inherit;
}


    /* btn-view (même style que le dernier) */
    .btn-view{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      border:none;
      cursor:pointer;
      padding: 8px 14px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
      background:#1A464F;
      color:#fff;
      text-decoration:none;
      box-shadow: 0 8px 18px rgba(0,0,0,0.12);
      transition: transform .15s ease, filter .15s ease, box-shadow .15s ease;
    }
    .btn-view:hover{
      transform: translateY(-1px);
      filter: brightness(1.02);
    }
    .btn-view:active{ transform: translateY(0); }

    /* post-item card */
    .post-item{
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 18px 16px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.18);
      border: 1px solid rgba(0,0,0,.06);
    }

    .post-item h2{
      margin:0 0 12px;
      font-family:'Playfair Display', serif;
      color:#1A464F;
    }

    /* table : harmonisation sans toucher HTML */
    table{
      width:100%;
      border-collapse: collapse;
      border-radius: 18px;
      overflow:hidden;
      background:#fff;
      border: 1px solid rgba(0,0,0,.06);
    }

    thead tr{
      background: rgba(245,226,196,.55) !important;
      border-bottom: 1px solid rgba(0,0,0,.06) !important;
    }

    th, td{
      padding: 12px !important;
      font-size: 13px;
      border-bottom: 1px solid rgba(0,0,0,.06);
      vertical-align: top;
      color:#1A464F;
    }

    tbody tr:hover{
      background: rgba(245,226,196,.30);
    }

    /* liens */
    a{
      color:#1A464F;
    }

    /* bouton delete (sans changer ton contenu) */
    button{
      border:none;
      cursor:pointer;
      border-radius: 999px;
      font-weight:700;
      color:#fff;
      transition: transform .15s ease, filter .15s ease;
    }
    button:hover{
      transform: translateY(-1px);
      filter: brightness(1.02);
    }

    /* responsive */
    @media (max-width: 768px){
      .toppage{ padding: 10px 14px; }
      .wrap{ margin: 18px auto 30px; padding: 0 14px 20px; }
      .logo-title img{ width:40px;height:40px; }
      .title-block h1{ font-size:18px; }
    }
  </style>
</head>

<body>
  <header class="toppage">
    <div class="logo-title">
      <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" />

      <div class="title-block">
        <h1>Gestion des Commentaires</h1>
        <p class="subtitle">BackOffice - Administration</p>
      </div>
    </div>
  </header>

  <main class="wrap" style="grid-template-columns: 1fr;">
    <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
      ← Retour au dashboard
    </a>

    <div class="post-item">
      <h2>💬 Liste des commentaires (<?= count($comments) ?>)</h2>

      <?php if (empty($comments)): ?>
        <p style="text-align: center; color: #718096; padding: 30px;">Aucun commentaire pour le moment.</p>
      <?php else: ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: #fef8f3; border-bottom: 2px solid #e8d5c4;">
                <th style="padding: 12px; text-align: left;">ID</th>
                <th style="padding: 12px; text-align: left;">Post</th>
                <th style="padding: 12px; text-align: left;">Contenu</th>
                <th style="padding: 12px; text-align: left;">Date</th>
                <th style="padding: 12px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($comments as $comment): ?>
                <tr style="border-bottom: 1px solid #e8d5c4;">
                  <td style="padding: 12px;"><?= $comment['id'] ?></td>
                  <td style="padding: 12px;">
                    <a href="index.php?action=show&id=<?= $comment['post_id'] ?>" style="color: #2c5f5d; text-decoration: underline;">
                      <?= htmlspecialchars($comment['post_titre'] ?: 'Post #' . $comment['post_id']) ?>
                    </a>
                  </td>
                  <td style="padding: 12px; max-width: 400px;">
                    <?= htmlspecialchars($comment['content']) ?>
                  </td>
                  <td style="padding: 12px; font-size: 12px; color: #718096;">
                    <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                  </td>
                  <td style="padding: 12px; text-align: center;">
                    <form method="post" action="index.php?action=admin_delete_comment" onsubmit="return confirm('Supprimer ce commentaire ?');" style="margin: 0;">
                      <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                      <button type="submit" style="background: #e53e3e; padding: 6px 12px; font-size: 12px; width: auto;">🗑️ Supprimer</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
