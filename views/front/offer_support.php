<?php // Vue : proposer du soutien ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Proposer du soutien</title>

  <link rel="stylesheet" href="style.css">

  <style>
    :root{
      --orange:#ec7546;
      --turquoise:#1f8c87;
      --violet:#7d5aa6;
    }

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

    .page-quote {
      text-align: center;
      margin: 22px auto 14px auto;
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      opacity: 0.95;
      position:relative;
      animation: quoteFade 1s ease-out;
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

    .space-main { padding: 10px 20px 60px; }

    .space-hero {
      position: relative;
      overflow: hidden;
      border-radius: 24px;
      max-width: 1100px;
      margin: 10px auto 40px auto;
      box-shadow: 0 18px 40px rgba(96, 84, 84, 0.18);
      background: #f5f5f5;
    }

    .space-hero::before,
    .space-hero::after{
      content:"";
      position:absolute;
      border-radius:999px;
      filter:blur(18px);
      opacity:.55;
      mix-blend-mode:screen;
      animation: floatBlob 10s ease-in-out infinite alternate;
    }
    .space-hero::before{
      width:120px; height:120px; top:-40px; left:20px;
      background:rgba(127, 71, 192, 0.6);
    }
    .space-hero::after{
      width:160px; height:160px; bottom:-50px; right:10px;
      background:rgba(31,140,135,.7);
      animation-delay:-4s;
    }
    @keyframes floatBlob{ from{transform:translateY(0) translateX(0);} to{transform:translateY(16px) translateX(-8px);} }

    .space-content {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: 1fr;
      gap: 18px;
      padding: 32px 30px 30px;
    }

    .space-title{
      font-family:'Playfair Display', serif;
      font-size: 30px;
      margin: 0;
      opacity: 0;
      transform: translateY(18px);
      color:#02282f !important;
    }

    .space-text{
      font-size: 17px;
      line-height: 1.7;
      margin: 0;
      max-width: 820px;
      opacity: 0;
      transform: translateY(18px);
      color:#020202 !important;
    }

    .card-row {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      margin-top: 10px;
    }

    .space-card {
      position: relative;
      flex: 1 1 260px;
      min-width: 240px;
      border-radius: 18px;
      padding: 16px 16px 14px;
      text-decoration: none;
      color: #1A464F;
      font-size: 16px;
      background: #FFF7EF;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25);
      display: flex;
      flex-direction: column;
      gap: 6px;
      overflow: hidden;
      opacity: 0;
      transform: translateY(24px) scale(0.97);
      transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, opacity 0.6s ease, transform 0.6s ease;
    }

    .space-card::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
    }

    .space-card:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 16px 34px rgba(0, 0, 0, 0.35); }
    .space-card:hover::before{ opacity:1; }

    .space-card.events { background:linear-gradient(135deg,#1f8c87,#56c7c2); color:#072828; }
    .space-card.groups { background:linear-gradient(135deg,#7d5aa6,#b58bf0); color:#1a0f22; }
    .space-card.chat   { background:linear-gradient(135deg,#ec7546,#ffb38f); color:#2c130d; }

    .space-card .badge {
      align-self: flex-start;
      padding: 2px 10px;
      border-radius: 999px;
      font-size: 12px;
      background: rgba(0,0,0,0.08);
    }
    .space-card .bubble { position: absolute; right: 12px; bottom: 10px; font-size: 26px; opacity: 0.85; }

    .back-row {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      justify-content: flex-start;
    }

    .back-home {
      margin-top: 10px;
      border-radius: 999px;
      border: none;
      padding: 8px 16px;
      font-size: 14px;
      cursor: pointer;
      background: #FFF7EF;
      color: #1A464F;
      box-shadow: 0 6px 14px rgba(0,0,0,0.15);
      transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
    }
    .back-home:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(0,0,0,0.22); filter: brightness(1.02); }
  </style>

  <!-- Polices (si tu veux les mêmes sur toutes tes pages) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="site">

    <!-- HEADER -->
    <header class="main-header top-nav">
      <div class="brand-block">
        <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
        <div class="brand-text">
          <span class="brand-name">SPARKMIND</span>
          <span class="brand-tagline">Quand la pensée devient espoir</span>
        </div>
      </div>

      <div class="header-actions">
        <button class="btn-orange" onclick="window.location.href='index.php?page=profile'">
          <span class="icon">⭐</span>
          <span>Mon profil</span>
        </button>
      </div>
    </header>

    <h2 class="page-quote">« Proposer du soutien, c’est déjà soigner. »</h2>

    <main class="space-main">

      <section class="space-hero">
        <div class="space-content">

          <h1 class="space-title">Choisis une façon d’aider</h1>

          <p class="space-text">
            Tu peux proposer ton soutien via des <strong>événements</strong>, rejoindre / créer des <strong>groupes</strong>,
            ou proposer des <strong>dons</strong>.
          </p>

          <div class="card-row">
            <a class="space-card events" href="index.php?page=events_list" id="eventsLink">
              <span>Événements</span>
              <span class="badge">Voir / proposer un événement</span>
              <span class="bubble">📅</span>
            </a>

            <a class="space-card groups" href="/sparkmind_mvc_100percent/index.php?page=frontoffice" id="groupsLink">
              <span>Groupes/dons</span>
              <span class="badge">Rejoindre / créer un groupe/Proposer ce que vous donnez</span>
              <span class="bubble">👥</span>
            </a>

            <a class="space-card chat" href="/sparkmind_mvc_100percent/index.php?page=produits" id="groupsLink">
              <span>Marketplace</span>
              <span class="badge">Proposer des produits</span>
              <span class="bubble"></span>
            </a>

 
          </div>

        </div>
      </section>

      <div class="back-row">
        <button class="back-home" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=main'">
          ⬅ Retour
        </button>
      </div>

    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const title = document.querySelector(".space-title");
      const text  = document.querySelector(".space-text");
      const cards = document.querySelectorAll(".space-card");

      setTimeout(() => {
        if (title) {
          title.style.opacity = "1";
          title.style.transform = "translateY(0)";
          title.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        }
      }, 150);

      setTimeout(() => {
        if (text) {
          text.style.opacity = "1";
          text.style.transform = "translateY(0)";
          text.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        }
      }, 260);

      cards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = "1";
          card.style.transform = "translateY(0) scale(1)";
        }, 380 + index * 140);
      });
    });
  </script>
</body>
</html>