<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Modifier le commentaire - SparkMind</title>

  <!-- ✅ CSS intégré : on garde EXACTEMENT ton contenu/HTML, on change فقط l'apparence -->
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
    text-transform:uppercase;
    color:var(--text);
    animation:titleGlow 2.8s ease-in-out infinite alternate;
  }
  .subtitle{ margin:2px 0 0; font-size:12px; opacity:.8; color:var(--text); }

  @keyframes navFade{ from{opacity:0; transform:translateY(-16px);} to{opacity:1; transform:translateY(0);} }
  @keyframes logoPop{ from{transform:scale(.8) translateY(-6px); opacity:0;} to{transform:scale(1) translateY(0); opacity:1;} }
  @keyframes titleGlow{ from{text-shadow:0 0 0 rgba(125,90,166,0);} to{text-shadow:0 4px 16px rgba(125,90,166,.55);} }

  /* ===== MAIN (ton .wrap) -> style "space-hero" ===== */
  .wrap{
    padding:10px 20px 60px;
    max-width:1100px;
    margin:0 auto;
    display:grid;
    gap:18px;
    position:relative;
  }

  /* Ajoute un "bloc hero" derrière ton contenu sans toucher HTML */
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

  /* ===== Bouton retour (ton .btn-view) ===== */
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

  /* ===== Card (ton .post) ===== */
  .post{
    background: rgba(255,247,239,.85);
    border:1px solid rgba(0,0,0,.04);
    border-radius:24px;
    padding:18px 18px 18px;
    box-shadow:0 18px 40px rgba(96,84,84,0.18);
    width:100%;
    overflow:hidden;
    position:relative;
    opacity:0;
    transform:translateY(18px);
    animation:cardIn .55s ease-out forwards;
  }
  .post::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:radial-gradient(circle at top left,rgba(255,255,255,.45),transparent 60%);
    opacity:0;
    transition:opacity .25s ease;
    pointer-events:none;
  }
  .post:hover::before{ opacity:.8; }

  @keyframes cardIn{ to{opacity:1; transform:translateY(0);} }

  .post h2{
    font-family:'Playfair Display',serif;
    font-size:22px;
    margin:0 0 12px;
    color:#02282f;
  }

  /* ===== Form ===== */
  .form-group{ display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
  .form-group label{ font-size:13px; font-weight:800; color:var(--text); }

  textarea{
    width:100%;
    border-radius:14px;
    border:1px solid rgba(0,0,0,0.10);
    padding:10px 12px;
    background:rgba(255,255,255,.85);
    outline:none;
    transition:box-shadow .18s ease, border-color .18s ease;
    font-family:inherit;
    font-size:14px;
    resize:vertical;
  }
  textarea:focus{
    border-color:rgba(31,140,135,.55);
    box-shadow:0 0 0 4px rgba(31,140,135,.18);
  }

  /* ===== Submit button ===== */
  button[type="submit"]{
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
    font-weight:900;
  }
  button[type="submit"]::before{
    content:"";
    position:absolute;
    inset:0;
    background:radial-gradient(circle at 0 0,rgba(255,255,255,.4),transparent 60%);
    opacity:0;
    transition:opacity .25s ease;
  }
  button[type="submit"]:hover{
    transform:translateY(-2px) scale(1.02);
    filter:brightness(1.03);
    box-shadow:0 14px 28px rgba(31,140,135,0.6);
  }
  button[type="submit"]:hover::before{ opacity:1; }

  @media (max-width:768px){
    .wrap{ padding:10px 14px 50px; }
    .toppage{ padding:10px 14px; }
    .post{ border-radius:20px; }
    .wrap::before, .wrap::after{ border-radius:20px; }
  }
</style>

</head>

<body>
  <header class="toppage">
    <div class="logo-title">
      <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" />
      <div class="title-block">
        <h1>SparkMind</h1>
        <p class="subtitle">Forum de donations</p>
      </div>
    </div>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  </header>

  <main class="wrap" style="grid-template-columns: 1fr;">
    <a href="index.php?action=show&id=<?= $comment['post_id'] ?>" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
      ← Retour au post
    </a>

    <div class="post" style="max-width: 700px; margin: 0 auto; width: 100%;">
      <h2>✏️ Modifier le commentaire</h2>

      <form method="post" action="index.php?action=update_comment">
        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
        <input type="hidden" name="post_id" value="<?= $comment['post_id'] ?>">

        <div class="form-group">
          <label>Commentaire *</label>
          <textarea name="content" rows="4" required><?= htmlspecialchars($comment['content']) ?></textarea>
        </div>

        <button type="submit">💾 Enregistrer les modifications</button>
      </form>
    </div>
  </main>
</body>
</html>
