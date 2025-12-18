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

  <!-- CSS -->
  <link rel="stylesheet" href="style.css">

  <!-- Barre floue animée en haut -->
  <style>
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.9);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 24px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.03);
      animation: navFade 0.7s ease-out;
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
    }

    .top-nav .brand-tagline {
      font-size: 12px;
      color: #1A464F;
      opacity: 0.8;
    }

    .top-nav .header-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    @keyframes navFade {
      from {
        opacity: 0;
        transform: translateY(-12px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
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
        <button class="pill" onclick="window.location.href='index.php?page=login'">
            Se connecter
        </button>

        <button class="btn-consulter" onclick="window.location.href='index.php?page=register'">
            S’inscrire
        </button>

      </div>
    </header>


    <main>

      <section class="hero" 
              style="display:flex; align-items:center; justify-content:center; 
                      gap:40px; padding:60px 20px;">

        <!-- VIDEO À GAUCHE -->
        <div class="hero-video" style="flex:1; max-width:45%;">
          <video autoplay loop muted playsinline 
                  style="width:100%; border-radius:20px;">
            <source src="video/video.mp4" type="video/mp4">
            Votre navigateur ne supporte pas la vidéo.
          </video>
        </div>

        <!-- TEXTE À DROITE -->
        <div class="hero-text-block" style="flex:1; max-width:50%;">
          <h2 style="font-size:32px; color:#1A464F; margin-bottom:20px; 
                    font-family:'Playfair Display', serif;">
            Qui sommes-nous ? 🕊️
          </h2>

          <p style="font-size:18px; line-height:1.7; color:#1A464F; margin-bottom:18px;">
            Nous, c’est celles et ceux qui savent ce que c’est de garder tout pour soi,
            de se sentir seul avec ses pensées, ou de ne pas savoir à qui parler sans avoir
            peur d’être jugé. SPARKMIND est né d’un besoin simple et vrai : offrir un endroit
            où les gens peuvent respirer, se confier, demander de l’aide ou simplement être
            entendus.
          </p>

          <p style="font-size:18px; line-height:1.7; color:#1A464F; margin-bottom:18px;">
            Ici, tu peux écrire librement. Tu peux parler de ce qui te fait mal, de ce qui
            te fait peur. Et en face, tu trouveras des personnes bienveillantes, anonymes
            ou non, prêtes à écouter, accompagner et t’offrir un peu de lumière.
          </p>

          <p style="font-size:18px; line-height:1.7; color:#1A464F; margin-bottom:18px;">
            Mais SPARKMIND, ce n’est pas seulement un espace de paroles. C’est aussi un lieu
            où la solidarité devient réelle. Ici, des gens partagent ce qu’ils peuvent donner,
            sans argent et sans rien attendre en retour : vêtements, livres, objets utiles,
            services, conseils… ou simplement du temps.
          </p>

          <p style="font-size:18px; line-height:1.7; color:#1A464F; margin-bottom:18px;">
            Nous permettons aussi la création de groupes solidaires pour organiser des dons,
            des collectes — comme pour le Ramadan ou d’autres moments importants — et même
            pour offrir une seconde chance à des animaux qui cherchent une famille.
          </p>

          <p style="font-size:18px; line-height:1.7; color:#1A464F; margin-bottom:18px;">
            SPARKMIND évolue grâce à chacun de vous : ceux qui cherchent du soutien, ceux qui
            en offrent, ceux qui partagent, ceux qui donnent, ceux qui créent des groupes
            d’entraide ou qui veulent changer un petit bout du monde autour d’eux.
          </p>

          <p style="font-size:18px; line-height:1.7; color:#1A464F;">
            Nous ne sommes pas des professionnels, juste des humains qui croient profondément
            en la bonté, en le partage et en la beauté de l’entraide. Ici, personne n’est de
            trop. Chacun compte. Et si tu lis ceci… alors toi aussi, tu as ta place. 💛
          </p>
        </div>

      </section>

      <section class="section-text" 
              style="display:flex; flex-direction:column; gap:30px; padding:60px 20px; max-width:1000px; margin:auto;">

        <article class="text-block fade-card" 
                style="background:#FFF7EF; padding:25px 30px; border-radius:18px; 
                        box-shadow:0 8px 20px rgba(0,0,0,0.08); opacity:0; transform:translateY(30px); transition:0.8s;">
          <h2 style="color:#1A464F; font-family:'Playfair Display', serif; margin-bottom:10px;">
            Nos Objectifs 💌
          </h2>
          <p style="font-size:18px; line-height:1.7; color:#1A464F;">
            Chez <strong>SPARKMIND</strong>, notre mission est de créer un espace
            d’entraide et de bienveillance, où chacun peut partager ses difficultés,
            ses idées et trouver le soutien dont il a besoin. Nous croyons profondément
            que la solidarité commence par la parole, l’écoute et le respect.
          </p>
        </article>

        <article class="text-block fade-card" 
                style="background:#FFF7EF; padding:25px 30px; border-radius:18px; 
                        box-shadow:0 8px 20px rgba(0,0,0,0.08); opacity:0; transform:translateY(30px); transition:0.8s;">
          <h2 style="color:#1A464F; font-family:'Playfair Display', serif; margin-bottom:10px;">
            Notre Histoire 💡
          </h2>
          <p style="font-size:18px; line-height:1.7; color:#1A464F;">
            L’idée de <strong>SPARKMIND</strong> est née d’une envie simple :
            rapprocher les esprits à travers l’aide mutuelle. Tout a commencé avec
            une petite communauté de personnes passionnées par l’humain, désireuses
            de bâtir un espace positif où chaque voix compte.
          </p>
        </article>

        <article class="text-block fade-card" 
                style="background:#FFF7EF; padding:25px 30px; border-radius:18px; 
                        box-shadow:0 8px 20px rgba(0,0,0,0.08); opacity:0; transform:translateY(30px); transition:0.8s;">
          <h2 style="color:#1A464F; font-family:'Playfair Display', serif; margin-bottom:10px;">
            Nos Valeurs 💟
          </h2>
          <p style="font-size:18px; line-height:1.7; color:#1A464F;">
            Empathie, confiance, respect et espoir guident chacune de nos actions.
            Nous croyons qu’ensemble, même les pensées les plus discrètes peuvent
            allumer les plus grandes lumières.
          </p>
        </article>

      </section>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const cards = document.querySelectorAll(".fade-card");

          // Initialisation : on met tout caché + on prépare la transition
          cards.forEach((card, index) => {
            card.style.opacity = "0";
            card.style.transform = "translateY(30px)";
            card.style.transition = "opacity 0.8s ease, transform 0.8s ease";
            // Petit décalage entre les cartes (effet cascade)
            card.style.transitionDelay = (index * 0.15) + "s";
          });

          function updateCards() {
            cards.forEach(card => {
              const rect = card.getBoundingClientRect();
              const inView = rect.top < window.innerHeight - 80 && rect.bottom > 0;

              if (inView) {
                // Carte visible → on affiche + remonte
                card.style.opacity = "1";
                card.style.transform = "translateY(0)";
              } else {
                // Carte hors écran → on recache (pour rejouer l’anim)
                card.style.opacity = "0";
                card.style.transform = "translateY(30px)";
              }
            });
          }

          window.addEventListener("scroll", updateCards);
          window.addEventListener("resize", updateCards);
          updateCards(); // pour le premier affichage
        });
      </script>

      <!-- NOS ACTIONS -->
      <section class="actions" 
              style="padding:80px 20px; background:#FBEDD7;">
        <div class="actions-inner" 
              style="max-width:1100px; margin:0 auto; text-align:center;">

          <h2 class="section-title"
              style="font-family:'Playfair Display', serif; font-size:32px; color:#1A464F; margin-bottom:10px;">
            Nos Actions 💫
          </h2>

          <p class="actions-intro"
              style="max-width:700px; margin:0 auto 40px auto; font-size:18px; line-height:1.7; color:#1A464F;">
            SPARKMIND, ce n’est pas seulement des mots : ce sont aussi des actions
            concrètes pour semer l’espoir, encourager le partage et créer un espace
            où chacun a sa place.
          </p>

          <!-- Petite barre déco -->
          <div style="width:80px; height:4px; background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87); 
                      border-radius:999px; margin:0 auto 40px auto;"></div>

          <div class="actions-grid"
                style="
                  display:grid;
                  grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
                  gap:24px;
                ">

            <!-- CARD 1 -->
            <article class="action-card action-anim" data-side="left"
                    style="
                      background:#FFFFFF;
                      border-radius:20px;
                      padding:24px 22px;
                      text-align:left;
                      box-shadow:0 10px 24px rgba(0,0,0,0.10);
                      opacity:0;
                      transform:translateX(-60px) scale(0.96);
                    ">
              <div class="action-icon"
                    style="
                      width:52px; height:52px;
                      border-radius:50%;
                      display:grid; place-items:center;
                      background:rgba(125,90,166,0.12);
                      font-size:26px;
                      margin-bottom:14px;
                    ">
                🤝
              </div>
              <h3 style="margin-bottom:8px; font-size:20px; color:#1A464F;">
                Accompagnement
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Offrir un espace d’écoute bienveillant pour partager ses difficultés,
                ses émotions et ses combats du quotidien, sans jugement.
              </p>
            </article>

            <!-- CARD 2 -->
            <article class="action-card action-anim" data-side="right"
                    style="
                      background:#FFFFFF;
                      border-radius:20px;
                      padding:24px 22px;
                      text-align:left;
                      box-shadow:0 10px 24px rgba(0,0,0,0.10);
                      opacity:0;
                      transform:translateX(60px) scale(0.96);
                    ">
              <div class="action-icon"
                    style="
                      width:52px; height:52px;
                      border-radius:50%;
                      display:grid; place-items:center;
                      background:rgba(236,117,70,0.12);
                      font-size:26px;
                      margin-bottom:14px;
                    ">
                💬
              </div>
              <h3 style="margin-bottom:8px; font-size:20px; color:#1A464F;">
                Discussions anonymes
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Permettre à chacun de s’exprimer librement, avec ou sans prénom,
                pour dire enfin ce qu’il n’ose dire nulle part ailleurs.
              </p>
            </article>

            <!-- CARD 3 -->
            <article class="action-card action-anim" data-side="left"
                    style="
                      background:#FFFFFF;
                      border-radius:20px;
                      padding:24px 22px;
                      text-align:left;
                      box-shadow:0 10px 24px rgba(0,0,0,0.10);
                      opacity:0;
                      transform:translateX(-60px) scale(0.96);
                    ">
              <div class="action-icon"
                    style="
                      width:52px; height:52px;
                      border-radius:50%;
                      display:grid; place-items:center;
                      background:rgba(31,140,135,0.12);
                      font-size:26px;
                      margin-bottom:14px;
                    ">
                👥
              </div>
              <h3 style="margin-bottom:8px; font-size:20px; color:#1A464F;">
                Groupes solidaires
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Créer des groupes pour s’entraider, parler de sujets profonds,
                organiser des collectes, des actions et des moments de partage.
              </p>
            </article>

            <!-- CARD 4 -->
            <article class="action-card action-anim" data-side="right"
                    style="
                      background:#FFFFFF;
                      border-radius:20px;
                      padding:24px 22px;
                      text-align:left;
                      box-shadow:0 10px 24px rgba(0,0,0,0.10);
                      opacity:0;
                      transform:translateX(60px) scale(0.96);
                    ">
              <div class="action-icon"
                    style="
                      width:52px; height:52px;
                      border-radius:50%;
                      display:grid; place-items:center;
                      background:rgba(125,90,166,0.1);
                      font-size:26px;
                      margin-bottom:14px;
                    ">
                🎁
              </div>
              <h3 style="margin-bottom:8px; font-size:20px; color:#1A464F;">
                Dons & Partage
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Partager ce que l’on peut offrir sans argent : objets, livres,
                vêtements, temps, aide, ou même donner une chance à des animaux.
              </p>
            </article>

          </div>
        </div>
      </section>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const actionCards = document.querySelectorAll(".action-anim");

          actionCards.forEach((card, index) => {
            const side = card.dataset.side || (index % 2 === 0 ? "left" : "right");
            card.dataset.side = side;

            const offset = side === "left" ? "-60px" : "60px";

            // Position de départ + transition
            card.style.opacity = "0";
            card.style.transform = `translateX(${offset}) scale(0.96)`;
            card.style.transition = "opacity 0.9s ease, transform 0.9s ease, box-shadow 0.4s ease, background-color 0.4s ease";

            // Effet hover (glow doux)
            card.addEventListener("mouseenter", () => {
              card.style.boxShadow = "0 18px 35px rgba(0,0,0,0.16)";
              card.style.backgroundColor = "#FFFDF8";
            });

            card.addEventListener("mouseleave", () => {
              card.style.boxShadow = "0 10px 24px rgba(0,0,0,0.10)";
              card.style.backgroundColor = "#FFFFFF";
            });
          });

          function updateActionCards() {
            actionCards.forEach((card, index) => {
              const rect = card.getBoundingClientRect();
              const inView = rect.top < window.innerHeight - 80 && rect.bottom > 0;

              if (inView) {
                // Quand la carte entre dans la vue → elle glisse et apparaît
                card.style.opacity = "1";
                card.style.transform = "translateX(0) scale(1)";
                card.style.transitionDelay = (index * 0.12) + "s"; // cascade légère
              } else {
                // Si tu veux qu'elles rejouent quand tu remontes/redescends :
                const side = card.dataset.side === "left" ? "-60px" : "60px";
                card.style.opacity = "0";
                card.style.transform = `translateX(${side}) scale(0.96)`;
                card.style.transitionDelay = "0s";
              }
            });
          }

          window.addEventListener("scroll", updateActionCards);
          window.addEventListener("resize", updateActionCards);
          updateActionCards();
        });
      </script>

      <!-- COMMENT ÇA MARCHE -->
      <section class="how-it-works" 
              style="padding:80px 20px; background:#FFFFFF;">
        <div style="max-width:1100px; margin:0 auto; text-align:center;">

          <h2 style="font-family:'Playfair Display', serif; font-size:30px; color:#1A464F; margin-bottom:10px;">
            Comment ça marche ? ✨
          </h2>

          <p style="max-width:700px; margin:0 auto 40px auto; font-size:18px; line-height:1.7; color:#1A464F;">
            En quelques étapes, tu peux rejoindre la communauté, partager ce que tu vis
            et recevoir un soutien bienveillant.
          </p>

          <div style="width:80px; height:4px; background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87); 
                      border-radius:999px; margin:0 auto 40px auto;"></div>

          <div style="
                display:grid;
                grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
                gap:24px;
              ">

            <!-- Étape 1 -->
            <article class="how-step step-anim"
                      style="
                        background:#FFF7EF;
                        border-radius:18px;
                        padding:24px 22px;
                        text-align:left;
                        box-shadow:0 10px 24px rgba(0,0,0,0.08);
                        opacity:0;
                        transform:translateY(30px) scale(0.96);
                      ">
              <div style="font-size:32px; margin-bottom:10px;">🕊️</div>
              <h3 style="font-size:20px; color:#1A464F; margin-bottom:8px;">
                1. Rejoins SPARKMIND
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Crée ton compte en quelques instants et découvre un espace
                pensé pour l’écoute, le partage et la bienveillance.
              </p>
            </article>

            <!-- Étape 2 -->
            <article class="how-step step-anim"
                      style="
                        background:#FFF7EF;
                        border-radius:18px;
                        padding:24px 22px;
                        text-align:left;
                        box-shadow:0 10px 24px rgba(0,0,0,0.08);
                        opacity:0;
                        transform:translateY(30px) scale(0.96);
                      ">
              <div style="font-size:32px; margin-bottom:10px;">💬</div>
              <h3 style="font-size:20px; color:#1A464F; margin-bottom:8px;">
                2. Partage ce que tu vis
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Raconte ce que tu ressens, anonymement ou non. Lis les histoires
                des autres et participe aux échanges bienveillants.
              </p>
            </article>

            <!-- Étape 3 -->
            <article class="how-step step-anim"
                      style="
                        background:#FFF7EF;
                        border-radius:18px;
                        padding:24px 22px;
                        text-align:left;
                        box-shadow:0 10px 24px rgba(0,0,0,0.08);
                        opacity:0;
                        transform:translateY(30px) scale(0.96);
                      ">
              <div style="font-size:32px; margin-bottom:10px;">🤝</div>
              <h3 style="font-size:20px; color:#1A464F; margin-bottom:8px;">
                3. Reçois du soutien réel
              </h3>
              <p style="font-size:16px; line-height:1.6; color:#1A464F;">
                Rejoins des groupes solidaires, participe à des actions d’entraide,
                trouve une écoute sincère et des gestes concrets.
              </p>
            </article>

          </div>
        </div>
      </section>

      <!-- LA COMMUNAUTÉ EN CHIFFRES -->
      <section class="stats"
              style="padding:70px 20px 80px; background:#FBEDD7;">
        <div style="max-width:1100px; margin:0 auto; text-align:center;">
          <h2 style="font-family:'Playfair Display', serif; font-size:30px; color:#1A464F; margin-bottom:10px;">
            Une communauté qui grandit chaque jour 🌱
          </h2>
          <p style="max-width:650px; margin:0 auto 40px auto; font-size:18px; line-height:1.7; color:#1A464F;">
            Derrière chaque chiffre, il y a une histoire, une rencontre, un geste de soutien.
          </p>

          <div style="
                display:grid;
                grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
                gap:26px;
                margin-top:10px;
              ">
            <div style="
                  background:#FFFFFF;
                  border-radius:18px;
                  padding:22px 18px;
                  box-shadow:0 8px 20px rgba(0,0,0,0.08);
                ">
              <div class="stat-number" data-counter="1200"
                    style="font-size:30px; font-weight:600; color:#1A464F; margin-bottom:6px;">
                0
              </div>
              <div style="font-size:15px; color:#1A464F;">
                membres solidaires
              </div>
            </div>

            <div style="
                  background:#FFFFFF;
                  border-radius:18px;
                  padding:22px 18px;
                  box-shadow:0 8px 20px rgba(0,0,0,0.08);
                ">
              <div class="stat-number" data-counter="650"
                    style="font-size:30px; font-weight:600; color:#1A464F; margin-bottom:6px;">
                0
              </div>
              <div style="font-size:15px; color:#1A464F;">
                discussions partagées
              </div>
            </div>

            <div style="
                  background:#FFFFFF;
                  border-radius:18px;
                  padding:22px 18px;
                  box-shadow:0 8px 20px rgba(0,0,0,0.08);
                ">
              <div class="stat-number" data-counter="180"
                    style="font-size:30px; font-weight:600; color:#1A464F; margin-bottom:6px;">
                0
              </div>
              <div style="font-size:15px; color:#1A464F;">
                groupes d’entraide
              </div>
            </div>

            <div style="
                  background:#FFFFFF;
                  border-radius:18px;
                  padding:22px 18px;
                  box-shadow:0 8px 20px rgba(0,0,0,0.08);
                ">
              <div class="stat-number" data-counter="320"
                    style="font-size:30px; font-weight:600; color:#1A464F; margin-bottom:6px;">
                0
              </div>
              <div style="font-size:15px; color:#1A464F;">
                dons & gestes solidaires
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- TÉMOIGNAGES -->
      <section class="testimonials"
              style="padding:80px 20px; background:#FFFFFF;">
        <div style="max-width:1100px; margin:0 auto; text-align:center;">
          <h2 style="font-family:'Playfair Display', serif; font-size:30px; color:#1A464F; margin-bottom:10px;">
            Ce que disent les membres 💛
          </h2>
          <p style="max-width:650px; margin:0 auto 40px auto; font-size:18px; line-height:1.7; color:#1A464F;">
            Ils et elles ont trouvé un espace pour déposer leurs pensées, se sentir moins seuls
            et redécouvrir la force du soutien.
          </p>

          <div style="
                display:grid;
                grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
                gap:24px;
              ">
            <article class="testimonial-card testimonial-anim"
                      style="
                        background:#FFF7EF;
                        border-radius:20px;
                        padding:22px 20px 20px;
                        text-align:left;
                        box-shadow:0 10px 24px rgba(0,0,0,0.1);
                        opacity:0;
                        transform:translateY(40px) scale(0.95);
                      ">
              <p style="font-style:italic; font-size:16px; line-height:1.7; color:#1A464F; margin-bottom:14px;">
                “SPARKMIND m’a permis de mettre des mots sur ce que je vivais, sans me sentir jugée.”
              </p>
              <div style="display:flex; align-items:center; gap:10px;">
                <div style="
                      width:34px; height:34px; border-radius:50%;
                      background:#ec7546; display:grid; place-items:center;
                      color:white; font-weight:600; font-size:16px;
                    ">
                  L
                </div>
                <div style="font-size:14px; color:#1A464F;">
                  <strong>kiki</strong> — 22 ans
                </div>
              </div>
            </article>

            <article class="testimonial-card testimonial-anim"
                      style="
                        background:#FFF7EF;
                        border-radius:20px;
                        padding:22px 20px 20px;
                        text-align:left;
                        box-shadow:0 10px 24px rgba(0,0,0,0.1);
                        opacity:0;
                        transform:translateY(40px) scale(0.95);
                      ">
              <p style="font-style:italic; font-size:16px; line-height:1.7; color:#1A464F; margin-bottom:14px;">
                “J’ai enfin trouvé un endroit où parler librement de mes pensées sans masque.”
              </p>
              <div style="display:flex; align-items:center; gap:10px;">
                <div style="
                      width:34px; height:34px; border-radius:50%;
                      background:#1f8c87; display:grid; place-items:center;
                      color:white; font-weight:600; font-size:16px;
                    ">
                  M
                </div>
                <div style="font-size:14px; color:#1A464F;">
                  <strong>panaché</strong> — 20 ans
                </div>
              </div>
            </article>

            <article class="testimonial-card testimonial-anim"
                      style="
                        background:#FFF7EF;
                        border-radius:20px;
                        padding:22px 20px 20px;
                        text-align:left;
                        box-shadow:0 10px 24px rgba(0,0,0,0.1);
                        opacity:0;
                        transform:translateY(40px) scale(0.95);
                      ">
              <p style="font-style:italic; font-size:16px; line-height:1.7; color:#1A464F; margin-bottom:14px;">
                “La solidarité ici est réelle : j’ai reçu autant d’écoute que d’aide concrète.”
              </p>
              <div style="display:flex; align-items:center; gap:10px;">
                <div style="
                      width:34px; height:34px; border-radius:50%;
                      background:#7d5aa6; display:grid; place-items:center;
                      color:white; font-weight:600; font-size:16px;
                    ">
                  A
                </div>
                <div style="font-size:14px; color:#1A464F;">
                  <strong>liza</strong> — 24 ans
                </div>
              </div>
            </article>
          </div>
        </div>
      </section>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const stepCards = document.querySelectorAll(".step-anim");
          const testimonialCards = document.querySelectorAll(".testimonial-anim");
          const counters = document.querySelectorAll(".stat-number");

          // Initialisation des cartes "étapes"
          stepCards.forEach((card, index) => {
            card.style.opacity = "0";
            card.style.transform = "translateY(30px) scale(0.96)";
            card.style.transition = "opacity 0.8s ease, transform 0.8s ease";
            card.style.transitionDelay = (index * 0.12) + "s";
          });

          // Initialisation des cartes "témoignages"
          testimonialCards.forEach((card, index) => {
            card.style.opacity = "0";
            card.style.transform = "translateY(40px) scale(0.95)";
            card.style.transition = "opacity 0.9s ease, transform 0.9s ease, box-shadow 0.4s ease";
            card.style.transitionDelay = (index * 0.15) + "s";

            card.addEventListener("mouseenter", () => {
              card.style.boxShadow = "0 18px 35px rgba(0,0,0,0.16)";
            });
            card.addEventListener("mouseleave", () => {
              card.style.boxShadow = "0 10px 24px rgba(0,0,0,0.10)";
            });
          });

          // Animation des chiffres
          counters.forEach(counter => {
            counter.dataset.animated = "false";
          });

          function animateCounter(counter) {
            const target = parseInt(counter.dataset.counter || "0", 10);
            const duration = 1300; // ms
            let startTime = null;

            function step(timestamp) {
              if (!startTime) startTime = timestamp;
              const progress = Math.min((timestamp - startTime) / duration, 1);
              const value = Math.floor(progress * target);
              counter.textContent = value.toLocaleString("fr-FR");

              if (progress < 1) {
                requestAnimationFrame(step);
              }
            }

            requestAnimationFrame(step);
          }

          function handleRevealOnScroll() {
            const vh = window.innerHeight;

            // Étapes
            stepCards.forEach(card => {
              const rect = card.getBoundingClientRect();
              const inView = rect.top < vh - 80 && rect.bottom > 0;
              if (inView) {
                card.style.opacity = "1";
                card.style.transform = "translateY(0) scale(1)";
              } else {
                card.style.opacity = "0";
                card.style.transform = "translateY(30px) scale(0.96)";
              }
            });

            // Témoignages
            testimonialCards.forEach(card => {
              const rect = card.getBoundingClientRect();
              const inView = rect.top < vh - 80 && rect.bottom > 0;
              if (inView) {
                card.style.opacity = "1";
                card.style.transform = "translateY(0) scale(1)";
              } else {
                card.style.opacity = "0";
                card.style.transform = "translateY(40px) scale(0.95)";
              }
            });

            // Chiffres
            counters.forEach(counter => {
              if (counter.dataset.animated === "true") return;
              const rect = counter.getBoundingClientRect();
              const inView = rect.top < vh - 60 && rect.bottom > 0;

              if (inView) {
                counter.dataset.animated = "true";
                animateCounter(counter);
              }
            });
          }

          window.addEventListener("scroll", handleRevealOnScroll);
          window.addEventListener("resize", handleRevealOnScroll);
          handleRevealOnScroll();
        });
      </script>

    </main>
  </div>

  <!-- POPUP "À PROPOS DE NOUS" -->
  <div class="about-modal" id="aboutModal">
    <div class="about-backdrop" id="aboutBackdrop"></div>

    <div class="about-card">
      <button class="about-close" id="aboutClose">&times;</button>

      <h2>Qui sommes-nous ? 🕊️</h2>

      <p>
        Nous, c’est celles et ceux qui savent ce que c’est de garder tout pour soi,
        de se sentir seul avec ses pensées, ou de ne pas savoir à qui parler sans avoir
        peur d’être jugé. SPARKMIND est né d’un besoin simple et vrai : offrir un endroit
        où les gens peuvent respirer, se confier, demander de l’aide ou simplement être
        entendus.
      </p>

      <p>
        Ici, tu peux écrire librement. Tu peux parler de ce qui te fait mal, de ce qui
        te fait peur. Et en face, tu trouveras des personnes bienveillantes, anonymes
        ou non, prêtes à écouter, accompagner et t’offrir un peu de lumière.
      </p>

      <p>
        Mais SPARKMIND, ce n’est pas seulement un espace de paroles. C’est aussi un lieu
        où la solidarité devient réelle. Ici, des gens partagent ce qu’ils peuvent donner,
        sans argent et sans rien attendre en retour : vêtements, livres, objets utiles,
        services, conseils… ou simplement du temps.
      </p>

      <p>
        Nous permettons aussi la création de groupes solidaires pour organiser des dons,
        des collectes — comme pour le Ramadan ou d’autres moments importants — et même
        pour offrir une seconde chance à des animaux qui cherchent une famille.
      </p>

      <p>
        SPARKMIND évolue grâce à chacun de vous : ceux qui cherchent du soutien, ceux qui
        en offrent, ceux qui partagent, ceux qui donnent, ceux qui créent des groupes
        d’entraide ou qui veulent changer un petit bout du monde autour d’eux.
      </p>

      <p>
        Nous ne sommes pas des professionnels, juste des humains qui croient profondément
        en la bonté, en le partage et en la beauté de l’entraide. Ici, personne n’est
        de trop. Chacun compte. Et si tu lis ceci… alors toi aussi, tu as ta place. 💛
      </p>
    </div>
  </div>

  <!-- JS pour la popup -->
  <script>
    const aboutBtn = document.getElementById("aboutBtn");
    const aboutModal = document.getElementById("aboutModal");
    const aboutClose = document.getElementById("aboutClose");
    const aboutBackdrop = document.getElementById("aboutBackdrop");

    function openAbout() {
      aboutModal.classList.add("open");
    }

    function closeAbout() {
      aboutModal.classList.remove("open");
    }

    if (aboutBtn) {
      aboutBtn.addEventListener("click", openAbout);
    }
    if (aboutClose) {
      aboutClose.addEventListener("click", closeAbout);
    }
    if (aboutBackdrop) {
      aboutBackdrop.addEventListener("click", closeAbout);
    }

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeAbout();
      }
    });
  </script>
</body>
</html>
