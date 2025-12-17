<?php
// Page d'accueil publique

// Page d'accueil publique (les variables viennent du routeur)
$upcomingEvents = $upcomingEvents ?? [];
$stats          = $stats ?? [];


?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Événements</title>

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
      --text-dark:#02282f;
      --text-medium:#1A464F;
      --radius-lg: 24px;
      --shadow: 0 18px 40px rgba(96, 84, 84, 0.18);
    }

    body{
      margin:0;
      font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      background: #FBEDD7;
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
    .header-actions { display:flex; align-items:center; gap:10px; }

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

    /* ======= TITRE QUOTE (identique) ======= */
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

    /* ======= MAIN WRAPPER (style SPARKMIND) ======= */
    .container{
      padding: 10px 20px 60px;
      max-width: 1100px;
      margin: 0 auto;
    }

    /* HERO façon “space-hero” */
    .hero{
      position: relative;
      overflow:hidden;
      border-radius: 24px;
      margin: 10px auto 28px auto;
      box-shadow: var(--shadow);
      background: #f5f5f5;
      padding: 32px 30px 30px;
    }
    .hero::before,
    .hero::after{
      content:"";
      position:absolute;
      border-radius:999px;
      filter:blur(18px);
      opacity:.55;
      mix-blend-mode:screen;
      animation: floatBlob 10s ease-in-out infinite alternate;
      pointer-events:none;
    }
    .hero::before{
      width:120px; height:120px; top:-40px; left:20px;
      background:rgba(127, 71, 192, 0.6);
    }
    .hero::after{
      width:160px; height:160px; bottom:-50px; right:10px;
      background:rgba(31,140,135,.7);
      animation-delay:-4s;
    }
    @keyframes floatBlob{ from{transform:translateY(0) translateX(0);} to{transform:translateY(16px) translateX(-8px);} }

    .hero h1{
      font-family:'Playfair Display', serif;
      font-size: 30px;
      margin: 0 0 10px 0;
      color: var(--text-dark);
      opacity:0; transform: translateY(18px);
    }
    .hero p{
      font-size: 17px;
      line-height: 1.7;
      margin: 0 0 16px 0;
      max-width: 820px;
      color: #020202;
      opacity:0; transform: translateY(18px);
    }

    .hero-buttons{
      display:flex;
      flex-wrap:wrap;
      gap: 10px;
      margin-top: 10px;
    }

    /* On “re-skin” tes btn existants, sans changer les classes */
    .btn{
      border-radius: 999px;
      padding: 10px 18px;
      font-size: 14px;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:8px;
      border: none;
      cursor:pointer;
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
      box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }
    .btn:hover{ transform: translateY(-2px) scale(1.02); box-shadow: 0 12px 26px rgba(0,0,0,0.18); filter: brightness(1.02); }

    .btn-primary{ background: var(--orange); color:#fff; box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45); }
    .btn-secondary{ background: #FFF7EF; color:#1A464F; }

    /* Stats cards (même look “space-card”) */
    .stats-section > div > div{
      background: #FFF7EF !important;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25) !important;
      border-radius: 18px !important;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, opacity 0.6s ease, transform 0.6s ease;
      position:relative;
      overflow:hidden;
    }
    .stats-section > div > div::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
    }
    .stats-section > div > div:hover{ transform: translateY(-4px) scale(1.02); box-shadow: 0 16px 34px rgba(0,0,0,0.35) !important; }
    .stats-section > div > div:hover::before{ opacity:1; }

    /* Section titres */
    .featured-events h2{
      font-family:'Playfair Display', serif;
      letter-spacing: .2px;
      opacity:0; transform: translateY(18px);
    }

    /* Events grid + cards */
    .events-grid{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 14px;
      margin-top: 12px;
    }

    .event-card{
      background: #FFF7EF;
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
    .event-description{ margin:0 0 10px 0; color:#1A464F; opacity:.92; line-height:1.6; }

    .event-meta{
      display:flex;
      flex-wrap:wrap;
      gap: 10px 12px;
      margin: 10px 0 14px;
      color:#1A464F;
      opacity:.92;
      font-size: 13px;
    }
    .meta-item{ display:inline-flex; align-items:center; gap:6px; background: rgba(0,0,0,0.06); padding: 4px 10px; border-radius: 999px; }

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

    .btn-book{
      background: var(--orange);
      color: #fff;
      border-radius: 999px;
      padding: 8px 14px;
      box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
    }

    /* Empty state */
    .empty-state{
      background:#FFF7EF;
      border-radius: 18px;
      padding: 26px;
      box-shadow: 0 12px 26px rgba(0,0,0,0.18);
      text-align:center;
      opacity:0; transform: translateY(18px);
    }
    .empty-state-icon{ font-size: 42px; margin-bottom: 8px; }

    /* “Pourquoi nous choisir” cards */
    section[style*="margin-top: 5rem"] > div > div{
      background:#FFF7EF !important;
      border-radius: 18px !important;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25) !important;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, opacity 0.6s ease, transform 0.6s ease;
      position:relative;
      overflow:hidden;
    }

    /* Petite ligne “retour” (optionnel) */
    .back-row{
      display:flex;
      justify-content:flex-start;
      margin-top: 18px;
    }
    .back-home{
      border-radius:999px;
      border:none;
      padding: 8px 16px;
      font-size:14px;
      cursor:pointer;
      background:#FFF7EF;
      color:#1A464F;
      box-shadow:0 6px 14px rgba(0,0,0,0.15);
      transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
    }
    .back-home:hover{ transform: translateY(-1px); box-shadow:0 10px 20px rgba(0,0,0,0.22); filter: brightness(1.02); }
  </style>
</head>

<body>
<div class="site">

  <!-- HEADER (même structure que ta page SPARKMIND) -->
  <header class="main-header top-nav">
    <div class="brand-block">
      <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
      <div class="brand-text">
        <span class="brand-name">SPARKMIND</span>
        <span class="brand-tagline">Quand la pensée devient espoir</span>
      </div>
    </div>


  </header>

  <h2 class="page-quote">« Découvrez nos événements. »</h2>

  <div class="container">
    <!-- Hero Section (contenu inchangé) -->
    <section class="hero">
      <h1>🎭 Découvrez nos événements</h1>
      <p>Réservez votre place pour des expériences inoubliables</p>
      <div class="hero-buttons">
        <a href="index.php?page=events_list_public" class="btn btn-primary">
        Voir tous les événements
        </a>

        <a href="index.php?page=my_reservations" class="btn btn-secondary">
        Mes réservations
        </a>

      </div>
    </section>

    <!-- Stats Section (contenu inchangé) -->
    <section class="stats-section" style="margin-bottom: 3rem;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
          <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎭</div>
          <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?= $eventModel->countEvents() ?></div>
          <div style="color: var(--text-medium); font-weight: 600;">Événements disponibles</div>
        </div>
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
          <div style="font-size: 3rem; margin-bottom: 0.5rem;">👥</div>
          <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);"><?= $stats['confirmées'] ?? 0 ?></div>
          <div style="color: var(--text-medium); font-weight: 600;">Réservations confirmées</div>
        </div>
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
          <div style="font-size: 3rem; margin-bottom: 0.5rem;">⭐</div>
          <div style="font-size: 2.5rem; font-weight: 700; color: var(--accent);">4.8/5</div>
          <div style="color: var(--text-medium); font-weight: 600;">Satisfaction client</div>
        </div>
      </div>
    </section>

    <!-- Featured Events (contenu inchangé) -->
    <section class="featured-events">
      <h2 style="font-size: 2rem; margin-bottom: 2rem; color: var(--text-dark);">
        🔥 Événements à venir
      </h2>

      <?php if (empty($upcomingEvents)): ?>
        <div class="empty-state">
          <div class="empty-state-icon">📭</div>
          <h3>Aucun événement à venir</h3>
          <p>Revenez bientôt pour découvrir nos prochains événements</p>
        </div>
      <?php else: ?>
        <div class="events-grid">
          <?php foreach ($upcomingEvents as $event): ?>
            <div class="event-card"
     onclick="window.location.href='index.php?page=event_detail&id=<?= (int)$event['id'] ?>'">

              <div class="event-image">🎭</div>

              <div class="event-content">
                <h3 class="event-title"><?= htmlspecialchars($event['titre']) ?></h3>
                <p class="event-description">
                  <?= htmlspecialchars(substr($event['description'], 0, 100)) ?>...
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

                <a href="index.php?page=booking_form&id=<?= (int)$event['id'] ?>"
                class="btn btn-book"
                onclick="event.stopPropagation()">
                Réserver
                </a>


                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <?php if (count($upcomingEvents) >= 6): ?>
          <div style="text-align: center; margin-top: 3rem;">
            <a href="index.php?page=events_list_public" class="btn btn-primary">Voir tous les événements →</a>

          </div>
        <?php endif; ?>
      <?php endif; ?>
    </section>

    <!-- Why Choose Us (contenu inchangé) -->
    <section style="margin-top: 5rem;">
      <h2 style="font-size: 2rem; margin-bottom: 2rem; text-align: center; color: var(--text-dark);">
        Pourquoi nous choisir ?
      </h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center;">
          <div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
          <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Réservation rapide</h3>
          <p style="color: var(--text-medium);">Réservez votre place en quelques clics seulement</p>
        </div>
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center;">
          <div style="font-size: 3rem; margin-bottom: 1rem;">🔒</div>
          <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Paiement sécurisé</h3>
          <p style="color: var(--text-medium);">Vos transactions sont 100% sécurisées</p>
        </div>
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center;">
          <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
          <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Support 24/7</h3>
          <p style="color: var(--text-medium);">Notre équipe est là pour vous aider</p>
        </div>
      </div>
    </section>

    <div class="back-row">
      <button class="back-home" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=offer_support'">⬅ Retour</button>
    </div>
  </div>
</div>

<script>
  // Animations d’entrée identiques (title/text/cards)
  document.addEventListener("DOMContentLoaded", () => {
    const heroTitle = document.querySelector(".hero h1");
    const heroText  = document.querySelector(".hero p");
    const sectionTitle = document.querySelector(".featured-events h2");
    const cards = document.querySelectorAll(".event-card");
    const statCards = document.querySelectorAll(".stats-section > div > div");
    const empty = document.querySelector(".empty-state");
    const whyCards = document.querySelectorAll('section[style*="margin-top: 5rem"] > div > div');

    setTimeout(() => {
      if (heroTitle) {
        heroTitle.style.opacity = "1";
        heroTitle.style.transform = "translateY(0)";
        heroTitle.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }
    }, 150);

    setTimeout(() => {
      if (heroText) {
        heroText.style.opacity = "1";
        heroText.style.transform = "translateY(0)";
        heroText.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }
    }, 260);

    setTimeout(() => {
      if (sectionTitle) {
        sectionTitle.style.opacity = "1";
        sectionTitle.style.transform = "translateY(0)";
        sectionTitle.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }
    }, 300);

    statCards.forEach((card, i) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0) scale(1)";
      }, 340 + i * 140);
    });

    if (empty) {
      setTimeout(() => {
        empty.style.opacity = "1";
        empty.style.transform = "translateY(0)";
        empty.style.transition = "opacity 0.8s ease, transform 0.8s ease";
      }, 420);
    }

    cards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0) scale(1)";
      }, 420 + index * 140);
    });

    whyCards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0) scale(1)";
      }, 520 + index * 140);
    });
  });
</script>
</body>
</html>
