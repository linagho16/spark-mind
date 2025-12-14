<?php // Vue front office ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Bienvenue dans l’espace principal</title>

  <!-- Styles globaux de ton site -->
  <link rel="stylesheet" href="style.css">

  <!-- Barre floue + animations & boutons dynamiques -->
  <style>
    :root{
      --orange:#ec7546;
      --turquoise:#1f8c87;
      --violet:#7d5aa6;
    }

    /* Barre du haut comme page1_1 */
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96); /* même bg que ton site */
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

    .top-nav .brand-block {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .top-nav .logo-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow:0 6px 14px rgba(79, 73, 73, 0.18);
      animation: logoPop 0.6s ease-out;
    }

    .top-nav .brand-text {
      display: flex;
      flex-direction: column;
    }

    .top-nav .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      letter-spacing: 1px;
      text-transform:uppercase;
      animation: titleGlow 2.8s ease-in-out infinite alternate;
    }

    .top-nav .brand-tagline {
      font-size: 12px;
      color: #1A464F;
      opacity: 0.8;
    }

    .top-nav .header-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Animation de la barre */
    @keyframes navFade {
      from {
        opacity: 0;
        transform: translateY(-16px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes logoPop{
      from{
        transform:scale(.8) translateY(-6px);
        opacity:0;
      }
      to{
        transform:scale(1) translateY(0);
        opacity:1;
      }
    }

    @keyframes titleGlow{
      from{
        text-shadow:0 0 0 rgba(125,90,166,0.0);
      }
      to{
        text-shadow:0 4px 16px rgba(125,90,166,0.55);
      }
    }

    /* Boutons existants (on les rend un peu plus dynamiques) */
    .header-actions .btn-orange {
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

    .btn-orange:hover::before{
      transform:translateX(20%);
    }

    .btn-orange:hover {
      transform: translateY(-2px) scale(1.03);
      filter: brightness(1.05);
      box-shadow: 0 10px 24px rgba(236, 117, 70, 0.55);
    }

    .btn-orange span.icon {
      font-size: 16px;
    }

    /* Citation en haut */
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

    @keyframes quoteFade{
      from{
        opacity:0;
        transform:translateY(-8px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    /* Mise en page de l’espace principal */
    .space-main {
      padding: 10px 20px 60px;
    }

    /* HERO PRINCIPAL */
    .space-hero {
      position: relative;
      overflow: hidden;
      border-radius: 24px;
      max-width: 1100px;
      margin: 10px auto 40px auto;
      box-shadow: 0 18px 40px rgba(96, 84, 84, 0.18);
      background: #f5f5f5;
    }

    /* petites bulles décoratives */
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
      width:120px;
      height:120px;
      top:-40px;
      left:20px;
      background:rgba(127, 71, 192, 0.6);
    }

    .space-hero::after{
      width:160px;
      height:160px;
      bottom:-50px;
      right:10px;
      background:rgba(31,140,135,.7);
      animation-delay:-4s;
    }

    @keyframes floatBlob{
      from{ transform:translateY(0) translateX(0); }
      to{   transform:translateY(16px) translateX(-8px); }
    }

    .space-bg {
      position: absolute;
      inset: 0;
      overflow: hidden;
    }

    .space-bg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.82) contrast(1);
      transform: scale(1.08);
      animation: heroPan 22s ease-in-out infinite alternate;
    }

    @keyframes heroPan{
      from{
        transform:scale(1.08) translate3d(0,-6px,0);
      }
      to{
        transform:scale(1.16) translate3d(-10px,4px,0);
      }
    }

    .space-bg-overlay {
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at top left, rgba(125,90,166,0.55), transparent 55%),
        radial-gradient(circle at bottom right, rgba(236,117,70,0.55), transparent 55%);
      mix-blend-mode: soft-light;
      opacity: 0.9;
    }

    .space-content {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
      gap: 26px;
      padding: 32px 30px 30px;
      color: #EAF2F7;
    }

    @media (max-width: 820px) {
      .space-content {
        grid-template-columns: 1fr;
      }
    }

    .space-left {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .space-title {
      font-family: 'Playfair Display', serif;
      font-size: 30px;
      margin: 0;
      opacity: 0;
      transform: translateY(18px);
    }
    .space-title {
      color: #02282f !important; /* ou autre couleur */
   }


    .space-text {
      font-size: 17px;
      line-height: 1.7;
      margin: 0;
      max-width: 520px;
      opacity: 0;
      transform: translateY(18px);
    }

    .space-text {
    color: #020202 !important; /* turquoise foncé */
    }


    /* Cartes Demande / Offre d’aide */
    .card-row {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      margin-top: 8px;
    }

    .space-card {
      position: relative;
      flex: 1 1 220px;
      min-width: 220px;
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
      transition:
        transform 0.18s ease,
        box-shadow 0.18s ease,
        background 0.18s ease,
        opacity 0.6s ease,
        transform 0.6s ease;
    }

    .space-card::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
    }

    .space-card span:first-child {
      font-weight: 500;
    }

    .space-card .badge {
      align-self: flex-start;
      padding: 2px 10px;
      border-radius: 999px;
      font-size: 12px;
      background: rgba(0,0,0,0.06);
    }

    .space-card .bubble {
      position: absolute;
      right: 10px;
      bottom: 8px;
      font-size: 24px;
      opacity: 0.8;
    }

    .space-card.ask {
      background:linear-gradient(135deg,#8439bc,#a35af0);
      color:#031b23;
    }

    .space-card.offer {
      background:linear-gradient(135deg,#dd773c,#f07233);
      color:#2c130d;
    }

    .space-card:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 16px 34px rgba(0, 0, 0, 0.35);
    }

    .space-card:hover::before{
      opacity:1;
    }

    /* Bouton Discussions anonymes */
    .cta-discuss {
      margin-top: 18px;
    }

    .btn-discuss {
      border-radius: 999px;
      border: none;
      padding: 10px 20px;
      font-size: 15px;
      cursor: pointer;
      font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: #1f8c87;
      color: #ffffff;
      box-shadow: 0 10px 22px rgba(31, 140, 135, 0.5);
      opacity: 0;
      transform: translateY(18px);
      position:relative;
      overflow:hidden;
      transition:
        transform 0.18s ease,
        box-shadow 0.18s ease,
        filter 0.18s ease,
        opacity 0.6s ease,
        transform 0.6s ease;
    }

    .btn-discuss::before{
      content:"";
      position:absolute;
      inset:0;
      background:radial-gradient(circle at 0 0,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
    }

    .btn-discuss .face {
      font-size: 18px;
      width:26px;
      height:26px;
      border-radius:50%;
      background:#fff;
      display:grid;
      place-items:center;
      color:#1f8c87;
    }

    .btn-discuss:hover {
      transform: translateY(-2px) scale(1.03);
      filter: brightness(1.03);
      box-shadow: 0 14px 28px rgba(31, 140, 135, 0.6);
    }

    .btn-discuss:hover::before{
      opacity:1;
    }

    /* Colonne droite */
    .space-side {
      display: flex;
      align-items: stretch;
      justify-content: center;
    }

    .side-card {
      background: rgba(217, 188, 153, 0.9);
      border-radius: 32px;
      padding: 18px 18px 24px;
      box-shadow: 0 18px 40px rgba(137, 101, 101, 0.5);
      width: 300px;          /* largeur fixe de la carte */
      max-width: 300px;      /* pour être sûre que ça ne s’élargit pas plus */
      display: flex;
      flex-direction: column;
      gap: 12px;
      opacity: 0;
      transform: translateY(26px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }


    .side-card:hover{
      transform:translateY(20px) scale(1.02);
      box-shadow:0 18px 36px rgba(0,0,0,.55);
    }

    .side-image {
      width: 100%;
      height: 400px;      /* hauteur fixe de l’image */
      border-radius: 24px;
      object-fit: cover;  /* garde le bon cadrage */
    }


    /* Bouton retour */
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

    .back-home:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.22);
      filter: brightness(1.02);
    }

    
    @media (max-width:768px){
      .space-hero{
        border-radius:20px;
      }
      .space-content{
        padding:24px 18px 22px;
      }
      .page-quote{
        font-size:1.6rem;
      }
    }
  </style>
</head>

<body>
  <div class="site">

    <!-- HEADER / BARRE DU HAUT -->
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

    
    <h2 class="page-quote">
      « Quand la pensée devient espoir. »
    </h2>

    <main class="space-main">

      
      <section class="space-hero">



        
        <div class="space-content">

          <div class="space-left">
            <h1 class="space-title">
              Bienvenue dans l’espace principal
            </h1>
            <p class="space-text">
              Ici, tu peux demander de l’aide, proposer ton soutien ou participer
              à des discussions anonymes sur des sujets profonds, honnêtes et parfois
              difficiles. Un espace pensé pour l’écoute, l’entraide et le partage.
            </p>

            <div class="card-row">
              <a class="space-card ask" href="#" id="askLink">
                <span>Demande de l’aide…</span>
                <span class="badge">Créer une demande</span>
                <span class="bubble">💭</span>
              </a>

              <a class="space-card offer" href="index.php?page=offer_support" id="offerLink">

                <span>Offre d’aide…</span>
                <span class="badge">Proposer du soutien</span>
                <span class="bubble">🤝</span>
              </a>
            </div>


            <div class="cta-discuss">
              <button class="btn-discuss" id="discussBtn">
                <span>Discussions anonymes</span>
                <span class="face">😊</span>
              </button>
            </div>
          </div>

          <!-- Colonne droite -->
          <aside class="space-side">
            <div class="side-card">
              <video class="side-video" autoplay muted loop playsinline>
                  <source src="video/video5.mp4" type="video/mp4">
              </video>

            </div>
          </aside>

        </div>
      </section>

      <!-- Bouton retour -->
      <div class="back-row">
        <button class="back-home" onclick="window.location.href='index.php?page=front_step'">
          ⬅ Retour à l’accueil
        </button>
      </div>

    </main>
  </div>

  <!-- Animations d’apparition -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const title = document.querySelector(".space-title");
      const text = document.querySelector(".space-text");
      const cards = document.querySelectorAll(".space-card");

      const discussBtn = document.querySelector(".btn-discuss");
      const sideCard = document.querySelector(".side-card");

      // Apparition progressive à l’arrivée
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

      setTimeout(() => {
        if (discussBtn) {
          discussBtn.style.opacity = "1";
          discussBtn.style.transform = "translateY(0)";
        }
      }, 700);

      setTimeout(() => {
        if (sideCard) {
          sideCard.style.opacity = "1";
          sideCard.style.transform = "translateY(0)";
        }
      }, 620);
    });
  </script>
</body>
</html>
