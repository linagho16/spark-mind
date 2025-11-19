<?php // Vue front office ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Front Office - projet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- CSS global -->
  <link rel="stylesheet" href="style.css">

  <!-- STYLE SPÉCIAL + ANIMATIONS POUR LA PREMIÈRE PAGE -->
  <style>
    /* ---- Barre du haut façon verre flou ---- */
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96); /* dans ta palette */
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 24px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.04);
      animation: navFade 0.7s ease-out;
    }

    .top-nav .brand-block {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .top-nav .logo-img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }

    .top-nav .brand-text {
      display: flex;
      flex-direction: column;
      line-height: 1.1;
    }

    .top-nav .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      letter-spacing: 1px;
      color: #1A464F;
    }

    .top-nav .brand-tagline {
      font-size: 12px;
      color: #1A464F;
      opacity: 0.85;
    }

    .header-actions .btn-consulter {
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .header-actions .btn-consulter:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
      filter: brightness(1.03);
    }

    @keyframes navFade {
      from { opacity: 0; transform: translateY(-12px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ---- Fond général de la page ---- */
    body {
      margin: 0;
      min-height: 100vh;
      background:
        radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
        radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
        #FBEDD7;
      font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .site {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ---- HERO spécial ---- */
    .hero {
      position: relative;
      max-width: 1100px;
      margin: 40px auto 60px auto;
      padding: 40px 30px;
      border-radius: 28px;
      overflow: hidden;
      background: rgba(255, 247, 239, 0.92);
      box-shadow: 0 22px 45px rgba(0, 0, 0, 0.18);
      display: grid;
      grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
      gap: 28px;
      align-items: center;
      isolation: isolate;
    }

    /* lumières décoratives dans le hero */
    .hero::before,
    .hero::after {
      content: "";
      position: absolute;
      border-radius: 999px;
      filter: blur(35px);
      opacity: 0.8;
      z-index: -1;
    }

    .hero::before {
      width: 220px;
      height: 220px;
      background: rgba(125, 90, 166, 0.45);
      top: -60px;
      left: -40px;
      animation: glowMove 12s ease-in-out infinite alternate;
    }

    .hero::after {
      width: 260px;
      height: 260px;
      background: rgba(31, 140, 135, 0.40);
      bottom: -80px;
      right: -50px;
      animation: glowMove 14s ease-in-out infinite alternate-reverse;
    }

    @keyframes glowMove {
      from { transform: translate3d(0, 0, 0) scale(1); }
      to   { transform: translate3d(20px, -10px, 0) scale(1.1); }
    }

    .hero-content {
      position: relative;
      z-index: 1;
      color: #02161a;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    /* Apparition progressive */
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      animation: revealUp 0.9s ease forwards;
    }

    .reveal.delay-1 { animation-delay: 0.15s; }
    .reveal.delay-2 { animation-delay: 0.3s; }
    .reveal.delay-3 { animation-delay: 0.45s; }

    @keyframes revealUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .welcome {
      font-family: 'Playfair Display', serif;
      font-size: 42px;
      margin: 0;
      background: linear-gradient(90deg, #7d5aa6, #1f8c87, #ec7546);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      letter-spacing: 1px;
    }

    .quote {
      margin: 6px 0 10px 0;
      font-size: 20px;
      font-style: italic;
      color: #1A464F;
      position: relative;
      padding-left: 18px;
    }

    .quote::before {
      content: "";
      position: absolute;
      left: 0;
      top: 8px;
      width: 4px;
      height: 24px;
      border-radius: 999px;
      background: linear-gradient(180deg, #7d5aa6, #ec7546);
    }

    .hero-text {
      border-radius: 16px;
      padding: 16px 18px;
      background: rgba(255, 255, 255, 0.78);
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.08);
      backdrop-filter: blur(8px);
    }

    /* ---- Image à droite ---- */
    .hero-image {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
      transform: translateY(15px);
      animation: floatIn 1s ease forwards;
      animation-delay: 0.35s;
    }

    @keyframes floatIn {
      from { opacity: 0; transform: translateY(25px) scale(0.97); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .hero-image img {
      width: 100%;
      height: 100%;
      display: block;
      object-fit: cover;
      transform: scale(1.06);
      transition: transform 9s ease-out;
    }

    .hero-image:hover img {
      transform: scale(1.12);
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top, rgba(255, 255, 255, 0.15), transparent 55%),
                  linear-gradient(160deg, rgba(2, 22, 26, 0.05), rgba(2, 22, 26, 0.45));
      mix-blend-mode: soft-light;
    }

    /* Petites bulles décoratives dans le hero */
    .floating-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 12px;
    }

    .badge-pill {
      font-size: 12px;
      padding: 5px 12px;
      border-radius: 999px;
      background: rgba(125, 90, 166, 0.12);
      color: #1A464F;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      animation: softPulse 3s ease-in-out infinite;
    }

    .badge-pill span.icon {
      font-size: 14px;
    }

    .badge-pill.alt {
      background: rgba(31, 140, 135, 0.12);
    }

    @keyframes softPulse {
      0%, 100% { transform: translateY(0); opacity: 0.95; }
      50%      { transform: translateY(-2px); opacity: 1; }
    }

    /* Responsive */
    @media (max-width: 880px) {
      .hero {
        grid-template-columns: 1fr;
        padding: 28px 22px 30px;
        margin: 20px 16px 40px;
      }

      .hero-image {
        order: -1;
      }

      .welcome {
        font-size: 34px;
      }
    }

    @media (max-width: 520px) {
      .welcome {
        font-size: 30px;
      }
      .quote {
        font-size: 18px;
      }
    }
  </style>
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
        <button class="btn-consulter" onclick="window.location.href='index.php?page=front_step'">
          Consulter le site web
        </button>
      </div>
    </header>

    <main>

      <!-- HERO -->
      <section class="hero">
        <div class="hero-content">
          <h1 class="welcome reveal delay-1">Bienvenue</h1>
          <p class="quote reveal delay-2">« Quand la pensée devient espoir. »</p>

          <p class="hero-text reveal delay-3"
             style="font-size: 30px;line-height: 1.6;max-width: 750px;margin: 40px auto 20px auto;text-align: left;color: #02161a; font-weight: 500;font-family: 'Playfair Display', serif;">
            SPARKMIND est un espace d’écoute, de partage et d’entraide où tu peux
            parler librement, demander de l’aide de façon bienveillante et parfois anonyme.
          </p>

          <div class="floating-badges reveal delay-3">
            <div class="badge-pill">
              <span class="icon">💛</span>
              <span>Écoute bienveillante</span>
            </div>
            <div class="badge-pill alt">
              <span class="icon">🕊️</span>
              <span>Espace anonyme si tu le souhaites</span>
            </div>
          </div>
        </div>

        <figure class="hero-image">
          <video autoplay loop muted playsinline class="hero-video">
            <source src="video/video3.mp4" type="video/mp4">
            Votre navigateur ne supporte pas la vidéo.
          </video>
          <div class="hero-overlay"></div>
        </figure>
        <style>
          .hero-image {
            width: 100%;
            height: px; /* Ajuste ici si tu veux plus grand ou plus petit */
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
          }

          .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Remplit sans déformer */
            object-position: center;
            display: block;
            transform: scale(1.05);
            transition: transform 10s ease-out;
          }

          /* Zoom lent */
          .hero-image:hover .hero-video {
            transform: scale(1.12);
          }

          .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
              to bottom,
              rgba(0,0,0,0.1),
              rgba(0,0,0,0.35)
            );
            border-radius: 20px;
          }
          </style>


      </section>

    </main>
  </div>
</body>
</html>
