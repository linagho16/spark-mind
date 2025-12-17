<?php
// Détail d'un événement
if (!$eventId) {
    header('Location: ?action=events');
    exit;
}

$event = $eventModel->getEventById($eventId);
if (!$event) {
    $_SESSION['message'] = "Événement introuvable.";
    $_SESSION['message_type'] = 'error';
    header('Location: ?action=events');
    exit;
}

$eventReservations = $reservation->getByEvent($eventId);
$placesReservees = array_sum(array_column($eventReservations, 'nombre_places'));
$placesDisponibles = 100 - $placesReservees; // Assuming 100 places per event
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Détail événement</title>

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
      --warning: #f59e0b;
      --error: #c62828;
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

    /* Retour pill */
    .back-link{
      display:inline-flex;
      align-items:center;
      gap:8px;
      margin-bottom: 18px;
      padding: 8px 14px;
      border-radius: 999px;
      background:#FFF7EF;
      color:#1A464F;
      text-decoration:none;
      font-weight:600;
      box-shadow: 0 6px 14px rgba(0,0,0,0.15);
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
      opacity:0; transform: translateY(12px);
    }
    .back-link:hover{ transform: translateY(-1px); box-shadow:0 10px 20px rgba(0,0,0,0.22); filter: brightness(1.02); }

    /* Event detail wrapper (carte principale) */
    .event-detail{
      background:#f5f5f5;
      border-radius: 24px;
      box-shadow: var(--shadow);
      overflow:hidden;
      position:relative;
      opacity:0; transform: translateY(18px);
      transition: opacity .8s ease, transform .8s ease;
    }
    .event-detail::before,
    .event-detail::after{
      content:"";
      position:absolute;
      border-radius:999px;
      filter:blur(18px);
      opacity:.55;
      mix-blend-mode:screen;
      animation: floatBlob 10s ease-in-out infinite alternate;
      pointer-events:none;
    }
    .event-detail::before{
      width:120px; height:120px; top:-40px; left:20px;
      background:rgba(127, 71, 192, 0.6);
    }
    .event-detail::after{
      width:160px; height:160px; bottom:-50px; right:10px;
      background:rgba(31,140,135,.7);
      animation-delay:-4s;
    }
    @keyframes floatBlob{ from{transform:translateY(0) translateX(0);} to{transform:translateY(16px) translateX(-8px);} }

    .event-header{
      position:relative;
      z-index:1;
      padding: 30px 30px 10px;
    }
    .event-header h1{
      font-family:'Playfair Display', serif;
      letter-spacing:.2px;
      margin:0 0 10px 0;
      opacity:0; transform: translateY(18px);
    }
    .event-header p{
      margin:0;
      opacity:0; transform: translateY(18px);
    }

    /* Meta cards */
    .event-detail-meta{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 14px;
      padding: 18px 30px 26px;
    }

    .detail-item{
      background:#FFF7EF;
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0,0,0,0.25);
      padding: 14px 14px 12px;
      position:relative;
      overflow:hidden;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: opacity .6s ease, transform .6s ease, box-shadow .18s ease;
    }
    .detail-item::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
      pointer-events:none;
    }
    .detail-item:hover{ box-shadow: 0 16px 34px rgba(0,0,0,0.35); transform: translateY(-4px) scale(1.02); }
    .detail-item:hover::before{ opacity:1; }

    .detail-label{
      font-size: 12px;
      color: var(--text-medium);
      opacity: .9;
      margin-bottom: 6px;
      font-weight: 600;
    }
    .detail-value{
      font-size: 16px;
      font-weight: 700;
      color:#02282f;
    }

    /* CTA / Alert blocks */
    .cta-block,
    .full-block,
    .stats-wrap{
      margin: 0 30px 26px;
      border-radius: 18px;
      opacity:0; transform: translateY(18px);
      transition: opacity .7s ease, transform .7s ease;
    }

    .cta-block{
      background: #FBEDD7;
      padding: 22px;
      text-align:center;
      box-shadow: inset 0 0 0 1px rgba(0,0,0,0.04);
    }

    .full-block{
      background:#FFF3CD;
      padding: 22px;
      text-align:center;
      color:#856404;
      box-shadow: 0 10px 24px rgba(0,0,0,0.12);
    }

    /* bouton book */
    .btn{
      border-radius: 999px;
      padding: 10px 18px;
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
    }
    .btn:hover{ transform: translateY(-2px) scale(1.02); box-shadow: 0 12px 26px rgba(0,0,0,0.18); filter: brightness(1.02); }

    .btn-book{
      background: var(--orange);
      color:#fff;
      box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
    }

    /* Stats cards */
    .stats-title{
      font-family:'Playfair Display', serif;
      margin: 0 0 12px 0;
      color: var(--text-dark);
      opacity:0; transform: translateY(12px);
      transition: opacity .7s ease, transform .7s ease;
    }

    .stats-grid{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 14px;
    }

    .stat-card{
      background:#FFF7EF;
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0,0,0,0.25);
      padding: 16px;
      text-align:center;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: opacity .6s ease, transform .6s ease, box-shadow .18s ease;
    }
    .stat-card:hover{ box-shadow: 0 16px 34px rgba(0,0,0,0.35); transform: translateY(-4px) scale(1.02); }
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
      <a class="btn-orange" href="index.php?page=profile">
        <span class="icon">⭐</span>
        <span>Mon profil</span>
      </a>
    </div>
  </header>

  <h2 class="page-quote">« Chaque événement est une rencontre avec soi et les autres. »</h2>

  <div class="container">

    <a href="?action=events"
       class="back-link"
       style="display: inline-block; margin-bottom: 2rem; color: var(--primary); text-decoration: none; font-weight: 600;">
      ← Retour aux événements
    </a>

    <div class="event-detail">

      <div class="event-header">
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-dark);">
          <?= htmlspecialchars($event['titre']) ?>
        </h1>
        <p style="font-size: 1.2rem; color: var(--text-medium); line-height: 1.8;">
          <?= nl2br(htmlspecialchars($event['description'])) ?>
        </p>
      </div>

      <div class="event-detail-meta">
        <div class="detail-item">
          <div class="detail-label">📅 Date</div>
          <div class="detail-value"><?= date('d/m/Y', strtotime($event['date_event'])) ?></div>
        </div>

        <div class="detail-item">
          <div class="detail-label">📍 Lieu</div>
          <div class="detail-value"><?= htmlspecialchars($event['lieu']) ?></div>
        </div>

        <?php if (!empty($event['duree'])): ?>
        <div class="detail-item">
          <div class="detail-label">⏱️ Durée</div>
          <div class="detail-value"><?= htmlspecialchars($event['duree']) ?></div>
        </div>
        <?php endif; ?>

        <div class="detail-item">
          <div class="detail-label">💰 Prix</div>
          <div class="detail-value"><?= number_format($event['prix'], 2, ',', ' ') ?> €</div>
        </div>

        <div class="detail-item">
          <div class="detail-label">🎫 Places disponibles</div>
          <div class="detail-value" style="<?= $placesDisponibles < 10 ? 'color: var(--error);' : '' ?>">
            <?= $placesDisponibles ?> / 100
          </div>
        </div>

        <div class="detail-item">
          <div class="detail-label">👥 Réservations</div>
          <div class="detail-value"><?= count($eventReservations) ?></div>
        </div>
      </div>

      <?php if ($placesDisponibles > 0): ?>
        <div class="cta-block" style="margin-top: 3rem; text-align: center; padding: 2rem; background: var(--bg-main); border-radius: var(--radius-lg);">
          <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Prêt à réserver ?</h3>
          <p style="color: var(--text-medium); margin-bottom: 2rem;">
            Réservez dès maintenant votre place pour cet événement !
          </p>
          <a href="?action=book&id=<?= $event['id'] ?>" class="btn btn-book" style="font-size: 1.2rem; padding: 1.25rem 3rem;">
            🎫 Réserver maintenant
          </a>
        </div>
      <?php else: ?>
        <div class="full-block" style="margin-top: 3rem; text-align: center; padding: 2rem; background: #FFF3CD; border-radius: var(--radius-lg); color: #856404;">
          <h3 style="margin-bottom: 0.5rem;">😔 Complet</h3>
          <p style="margin: 0;">Toutes les places pour cet événement ont été réservées.</p>
        </div>
      <?php endif; ?>

      <?php if (!empty($eventReservations)): ?>
        <div class="stats-wrap" style="margin-top: 3rem;">
          <h3 class="stats-title" style="margin-bottom: 1rem; color: var(--text-dark);">📊 Statistiques de l'événement</h3>

          <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div class="stat-card" style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?= $placesReservees ?></div>
              <div style="color: var(--text-medium); font-size: 0.9rem;">Places réservées</div>
            </div>

            <div class="stat-card" style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 2rem; font-weight: 700; color: var(--success);">
                <?= count(array_filter($eventReservations, fn($r) => $r['statut'] === 'confirmée')) ?>
              </div>
              <div style="color: var(--text-medium); font-size: 0.9rem;">Confirmées</div>
            </div>

            <div class="stat-card" style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
              <div style="font-size: 2rem; font-weight: 700; color: var(--warning);">
                <?= count(array_filter($eventReservations, fn($r) => $r['statut'] === 'en attente')) ?>
              </div>
              <div style="color: var(--text-medium); font-size: 0.9rem;">En attente</div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
  // Animations d’entrée façon SPARKMIND
  document.addEventListener("DOMContentLoaded", () => {
    const back = document.querySelector(".back-link");
    const wrap = document.querySelector(".event-detail");
    const title = document.querySelector(".event-header h1");
    const text  = document.querySelector(".event-header p");
    const meta  = document.querySelectorAll(".detail-item");
    const cta   = document.querySelector(".cta-block, .full-block");
    const statsTitle = document.querySelector(".stats-title");
    const statsCards = document.querySelectorAll(".stat-card");

    setTimeout(() => {
      if(back){
        back.style.opacity = "1";
        back.style.transform = "translateY(0)";
        back.style.transition = "opacity 0.7s ease, transform 0.7s ease";
      }
    }, 140);

    setTimeout(() => {
      if(wrap){
        wrap.style.opacity = "1";
        wrap.style.transform = "translateY(0)";
      }
    }, 220);

    setTimeout(() => {
      if(title){
        title.style.opacity = "1";
        title.style.transform = "translateY(0)";
        title.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }
    }, 320);

    setTimeout(() => {
      if(text){
        text.style.opacity = "1";
        text.style.transform = "translateY(0)";
        text.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }
    }, 420);

    meta.forEach((card, i) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0) scale(1)";
      }, 520 + i * 120);
    });

    setTimeout(() => {
      if(cta){
        cta.style.opacity = "1";
        cta.style.transform = "translateY(0)";
      }
    }, 520 + meta.length * 120 + 120);

    setTimeout(() => {
      if(statsTitle){
        statsTitle.style.opacity = "1";
        statsTitle.style.transform = "translateY(0)";
      }
    }, 520 + meta.length * 120 + 260);

    statsCards.forEach((card, i) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0) scale(1)";
      }, 520 + meta.length * 120 + 360 + i * 120);
    });
  });
</script>

</body>
</html>
