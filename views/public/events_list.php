<?php
// Liste de tous les événements
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $events = $eventModel->search($search);
} else {
    $events = $eventModel->getAllEvents();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Tous les événements</title>

  <link rel="stylesheet" href="style.css">

  <!-- Polices identiques -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --orange:#ec7546;
      --turquoise:#1f8c87;
      --violet:#7d5aa6;

      /* Compat avec ton code existant */
      --primary: var(--orange);
      --success: var(--turquoise);
      --accent: var(--violet);

      --bg-card:#FFF7EF;
      --bg-main:#FBEDD7;
      --text-dark:#02282f;
      --text-medium:#1A464F;

      --radius: 16px;
      --radius-lg: 24px;
      --shadow: 0 18px 40px rgba(96, 84, 84, 0.18);

      --secondary: rgba(0,0,0,0.08);
    }

    body{
      margin:0;
      font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      background:#FBEDD7;
      color: var(--text-dark);
    }

    .site{ min-height:100vh; }

    /* ======= TOP NAV (identique) ======= */
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 24px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.03);
      animation: navFade 0.7s ease-out;
    }

    .top-nav::after{
      content:"";
      position:absolute;
      inset:auto 40px -2px 40px;
      height:2px;
      background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87);
      opacity:.35;
      border-radius:999px;
    }

    .brand-block { display:flex; align-items:center; gap:10px; }
    .logo-img {
      width: 40px; height: 40px; border-radius: 50%;
      object-fit: cover;
      box-shadow:0 6px 14px rgba(79, 73, 73, 0.18);
      animation: logoPop 0.6s ease-out;
    }
    .brand-text { display:flex; flex-direction:column; }
    .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      letter-spacing: 1px;
      text-transform:uppercase;
      animation: titleGlow 2.8s ease-in-out infinite alternate;
    }
    .brand-tagline { font-size: 12px; color: #1A464F; opacity: 0.8; }

    @keyframes navFade { from {opacity:0; transform:translateY(-16px);} to {opacity:1; transform:translateY(0);} }
    @keyframes logoPop{ from{transform:scale(.8) translateY(-6px); opacity:0;} to{transform:scale(1) translateY(0); opacity:1;} }
    @keyframes titleGlow{ from{text-shadow:0 0 0 rgba(125,90,166,0.0);} to{text-shadow:0 4px 16px rgba(125,90,166,0.55);} }

    .btn-orange {
      background: var(--orange);
      color: #ffffff;
      border: none;
      border-radius: 999px;
      padding: 8px 18px;
      font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      position:relative;
      overflow:hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
      text-decoration:none;
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
    .btn-orange:hover {
      transform: translateY(-2px) scale(1.03);
      filter: brightness(1.05);
      box-shadow: 0 10px 24px rgba(236, 117, 70, 0.55);
    }

    /* ======= QUOTE ======= */
    .page-quote {
      text-align: center;
      margin: 22px auto 14px auto;
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      opacity: 0.95;
      position:relative;
      animation: quoteFade 1s ease-out;
      padding: 0 18px;
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
      background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87);
      opacity:.6;
    }
    @keyframes quoteFade{ from{opacity:0; transform:translateY(-8px);} to{opacity:1; transform:translateY(0);} }

    /* ======= CONTAINER ======= */
    .container{
      padding: 10px 20px 60px;
      max-width: 1100px;
      margin: 0 auto;
    }

    h1{
      font-family:'Playfair Display', serif;
      letter-spacing:.2px;
      opacity:0; transform: translateY(18px);
    }

    /* Search bar (SPARKMIND look) */
    .search-bar{
      background:#f5f5f5;
      border-radius: 24px;
      box-shadow: var(--shadow);
      padding: 18px;
      position:relative;
      overflow:hidden;
      margin-bottom: 18px;
      opacity:0; transform: translateY(18px);
      transition: opacity .8s ease, transform .8s ease;
    }
    .search-bar::before,
    .search-bar::after{
      content:"";
      position:absolute;
      border-radius:999px;
      filter:blur(18px);
      opacity:.55;
      mix-blend-mode:screen;
      animation: floatBlob 10s ease-in-out infinite alternate;
      pointer-events:none;
    }
    .search-bar::before{
      width:120px; height:120px; top:-40px; left:20px;
      background:rgba(127, 71, 192, 0.6);
    }
    .search-bar::after{
      width:160px; height:160px; bottom:-50px; right:10px;
      background:rgba(31,140,135,.7);
      animation-delay:-4s;
    }
    @keyframes floatBlob{ from{transform:translateY(0) translateX(0);} to{transform:translateY(16px) translateX(-8px);} }

    .search-form{
      position:relative;
      z-index:1;
      display:flex;
      gap: 10px;
      flex-wrap:wrap;
      align-items:center;
    }

    .search-input{
      flex: 1 1 320px;
      border-radius: 999px;
      border: 1px solid rgba(0,0,0,0.10);
      padding: 12px 14px;
      outline:none;
      background: rgba(255,255,255,0.65);
      transition: box-shadow .18s ease, transform .18s ease, border-color .18s ease, filter .18s ease;
      font-family:'Poppins', sans-serif;
    }
    .search-input:focus{
      border-color: rgba(236,117,70,0.55);
      box-shadow: 0 10px 24px rgba(236,117,70,0.22);
      transform: translateY(-1px);
      filter: brightness(1.01);
    }

    .search-btn{
      background: var(--orange);
      color:#fff;
      border:none;
      border-radius: 999px;
      padding: 12px 16px;
      cursor:pointer;
      box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
      font-family:'Poppins', sans-serif;
      font-weight:600;
    }
    .search-btn:hover{
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 12px 26px rgba(236, 117, 70, 0.55);
      filter: brightness(1.03);
    }

    /* Buttons (compat) */
    .btn{
      border-radius: 999px;
      padding: 10px 16px;
      font-size: 14px;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      border: none;
      cursor:pointer;
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
      box-shadow: 0 8px 18px rgba(0,0,0,0.12);
      font-weight:600;
    }
    .btn:hover{ transform: translateY(-2px) scale(1.02); box-shadow: 0 12px 26px rgba(0,0,0,0.18); filter: brightness(1.02); }

    .btn-secondary{ background:#FFF7EF; color:#1A464F; }

    .btn-book{
      background: var(--orange);
      color:#fff;
      box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
    }

    /* Search results info box */
    .search-info{
      background:#FFF7EF;
      border-radius: 18px;
      box-shadow: 0 10px 24px rgba(0,0,0,0.12);
      padding: 12px 14px;
      margin-bottom: 18px;
      border-left: 4px solid var(--orange);
      opacity:0; transform: translateY(18px);
      transition: opacity .7s ease, transform .7s ease;
    }

    /* Empty */
    .empty-state{
      background:#FFF7EF;
      border-radius: 18px;
      padding: 26px;
      box-shadow: 0 12px 26px rgba(0,0,0,0.18);
      text-align:center;
      opacity:0; transform: translateY(18px);
      transition: opacity .7s ease, transform .7s ease;
    }
    .empty-state-icon{ font-size: 42px; margin-bottom: 8px; }

    /* Events grid + cards (SPARKMIND look) */
    .events-grid{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 14px;
      margin-top: 12px;
    }

    .event-card{
      background:#FFF7EF;
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25);
      overflow:hidden;
      cursor:pointer;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, opacity 0.6s ease, transform 0.6s ease;
      position:relative;
    }
    .event-card::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
      pointer-events:none;
    }
    .event-card:hover{ transform: translateY(-4px) scale(1.02); box-shadow: 0 16px 34px rgba(0,0,0,0.35); }
    .event-card:hover::before{ opacity:1; }

    .event-image{
      height: 140px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size: 42px;
      background: linear-gradient(135deg, rgba(31,140,135,.8), rgba(86,199,194,.85));
    }

    .event-content{ padding: 16px 16px 14px; }
    .event-title{
      margin:0 0 6px 0;
      font-family:'Playfair Display', serif;
      font-size: 20px;
      color:#02282f;
    }
    .event-description{ margin:0 0
0 10px 0; color:#1A464F; opacity:.92; line-height:1.6; }

    .event-meta{
      display:flex;
      flex-wrap:wrap;
      gap: 10px 12px;
      margin: 10px 0 14px;
      color:#1A464F;
      opacity:.92;
      font-size: 13px;
    }
    .meta-item{
      display:inline-flex;
      align-items:center;
      gap:6px;
      background: rgba(0,0,0,0.06);
      padding: 4px 10px;
      border-radius: 999px;
    }

    .event-footer{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
      margin-top: 10px;
    }

    .event-price{
      font-weight: 700;
      color:#02282f;
    }
    .event-price small{ font-weight: 500; opacity:.75; }
  </style>
</head>

<body>
<div class="site">

  <header class="main-header top-nav">
    <div class="brand-block">
      <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
      <div class="brand-text">
        <span class="brand-name">SPARKMIND</span>
        <span class="brand-tagline">Quand la pensée devient espoir</span>
      </div>
    </div>

    <div class="header-actions">
      <a class="btn-orange" href="/sparkmind_mvc_100percent/index.php?page=events_home">
        <span class="icon"></span>
        <span>Retour</span>
      </a>
    </div>
  </header>

  <h2 class="page-quote">« Trouver un événement, c’est déjà commencer à guérir. »</h2>

  <div class="container">

    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-dark);">
      📅 Tous les événements
    </h1>

    <!-- Search Bar (contenu inchangé) -->
<div class="search-bar">
  <form method="GET"
        action="/sparkmind_mvc_100percent/index.php"
        class="search-form">

    <input type="hidden" name="page" value="events_list_public">

    <input type="text"
           name="search"
           class="search-input"
           placeholder="Rechercher un événement par titre, lieu, description..."
           value="<?= htmlspecialchars($search ?? '') ?>">

    <button type="submit" class="search-btn">🔍 Rechercher</button>

    <?php if (!empty($search)): ?>
      <a href="/sparkmind_mvc_100percent/index.php?page=events_list_public"
         class="btn btn-secondary">
        ✖ Effacer
      </a>
    <?php endif; ?>
  </form>
</div>


    <?php if ($search): ?>
      <div class="search-info" style="background: var(--bg-card); padding: 1rem 1.5rem; border-radius: var(--radius); margin-bottom: 2rem; border-left: 4px solid var(--primary);">
        <p style="margin: 0;">
          🔍 <strong><?= count($events) ?></strong> résultat<?= count($events) > 1 ? 's' : '' ?> pour
          "<strong><?= htmlspecialchars($search) ?></strong>"
        </p>
      </div>
    <?php endif; ?>

    <!-- Events Grid (contenu inchangé) -->
    <?php if (empty($events)): ?>
      <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3>Aucun événement trouvé</h3>
        <p><?= $search ? 'Essayez avec d\'autres mots-clés' : 'Aucun événement disponible pour le moment' ?></p>
      </div>
    <?php else: ?>
      <div class="events-grid">
        <?php foreach ($events as $event): ?>
          <div class="event-card"
     onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=event_detail&id=<?= (int)$event['id'] ?><?= !empty($_GET['email']) ? '&email=' . urlencode($_GET['email']) : '' ?>'">

            <div class="event-image">🎭</div>
            <div class="event-content">
              <h3 class="event-title"><?= htmlspecialchars($event['titre']) ?></h3>
              <p class="event-description">
                <?= htmlspecialchars(substr($event['description'], 0, 120)) ?>...
              </p>

              <div class="event-meta">
                <div class="meta-item">
                  <span>📅</span>
                  <span><?= date('d/m/Y', strtotime($event['date_event'])) ?></span>
                </div>
                <div class="meta-item">
                  <span>📍</span>
                  <span><?= htmlspecialchars($event['lieu']) ?></span>
                </div>
                <?php if (!empty($event['duree'])): ?>
                  <div class="meta-item">
                    <span>⏱️</span>
                    <span><?= htmlspecialchars($event['duree']) ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <div class="event-footer">
                <div class="event-price">
                  <?= number_format($event['prix'], 2, ',', ' ') ?> €
                  <small>/place</small>
                </div>

<a href="/sparkmind_mvc_100percent/index.php?page=booking_form&id=<?= (int)$event['id'] ?><?= !empty($_GET['email']) ? '&email=' . urlencode($_GET['email']) : '' ?>"
   class="btn btn-book"
   onclick="event.stopPropagation()">
  Réserver
</a>

              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<script>
  // Animations d’entrée façon SPARKMIND
  document.addEventListener("DOMContentLoaded", () => {
    const title = document.querySelector("h1");
    const search = document.querySelector(".search-bar");
    const info = document.querySelector(".search-info");
    const empty = document.querySelector(".empty-state");
    const cards = document.querySelectorAll(".event-card");

    setTimeout(() => {
      if(title){
        title.style.opacity = "1";
        title.style.transform = "translateY(0)";
        title.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }
    }, 150);

    setTimeout(() => {
      if(search){
        search.style.opacity = "1";
        search.style.transform = "translateY(0)";
      }
    }, 260);

    setTimeout(() => {
      if(info){
        info.style.opacity = "1";
        info.style.transform = "translateY(0)";
      }
      if(empty){
        empty.style.opacity = "1";
        empty.style.transform = "translateY(0)";
      }
    }, 360);

    cards.forEach((card, i) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0) scale(1)";
      }, 420 + i * 120);
    });
  });
</script>

</body>
</html>
