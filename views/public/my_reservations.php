<?php
// Mes réservations - Recherche par email
$searchEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$myReservations = [];

if ($searchEmail) {
    // Rechercher les réservations par email
    $stmt = $pdo->prepare("
        SELECT r.*, e.titre as event_titre, e.date_event, e.lieu, e.prix
        FROM reservations r
        JOIN events e ON r.event_id = e.id
        WHERE r.email = :email
        ORDER BY r.date_reservation DESC
    ");
    $stmt->execute([':email' => $searchEmail]);
    $myReservations = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Mes réservations</title>

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

    h1{
      font-family:'Playfair Display', serif;
      letter-spacing:.2px;
      opacity:0; transform: translateY(18px);
    }

    /* Search bar */
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
    .btn-primary{ background: var(--orange); color:#fff; box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45); }

    /* Intro card */
    .intro-card{
      background:#FFF7EF;
      border-radius: 24px;
      box-shadow: var(--shadow);
      padding: 28px;
      text-align:center;
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

    /* Found info */
    .found-info{
      background:#FFF7EF;
      border-radius: 18px;
      box-shadow: 0 10px 24px rgba(0,0,0,0.12);
      padding: 12px 14px;
      margin-bottom: 18px;
      border-left: 4px solid var(--turquoise);
      opacity:0; transform: translateY(18px);
      transition: opacity .7s ease, transform .7s ease;
    }

    /* Reservation cards */
    .reservation-card{
      background:#FFF7EF;
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0,0,0,0.25);
      padding: 18px;
      margin-bottom: 14px;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: opacity .6s ease, transform .6s ease, box-shadow .18s ease;
      position:relative;
      overflow:hidden;
    }
    .reservation-card::before{
      content:"";
      position:absolute;
      inset:-40%;
      background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
      opacity:0;
      transition:opacity .25s ease;
      pointer-events:none;
    }
    .reservation-card:hover{
      box-shadow: 0 16px 34px rgba(0,0,0,0.35);
      transform: translateY(-4px) scale(1.01);
    }
    .reservation-card:hover::before{ opacity:1; }

    .reservation-header{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap: 12px;
      margin-bottom: 12px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .reservation-ref{
      font-weight: 800;
      color:#02282f;
      font-size: 16px;
    }

    .badge{
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 12px;
      font-weight: 700;
      white-space:nowrap;
      background: rgba(0,0,0,0.08);
    }
    .badge-success{ background: rgba(31,140,135,0.18); color:#0b3b39; }
    .badge-warning{ background: rgba(245,158,11,0.20); color:#6a3b00; }
    .badge-danger{ background: rgba(198,40,40,0.18); color:#6b0f0f; }

    .reservation-body{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 10px 14px;
    }

    .info-item{
      background: rgba(0,0,0,0.05);
      border-radius: 14px;
      padding: 10px 12px;
      display:flex;
      justify-content:space-between;
      gap: 10px;
      align-items:center;
    }
    .info-label{
      font-size: 12px;
      color: var(--text-medium);
      font-weight: 700;
      opacity:.9;
    }
    .info-value{
      font-size: 13px;
      font-weight: 700;
      color:#02282f;
      text-align:right;
    }

    .notes-box{
      margin-top: 12px;
      padding: 12px;
      background: var(--bg-main);
      border-radius: 16px;
      box-shadow: inset 0 0 0 1px rgba(0,0,0,0.04);
    }
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

  <h2 class="page-quote">« Retrouver une réservation, c’est retrouver un repère. »</h2>

  <div class="container">

    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-dark);">
      📋 Mes réservations
    </h1>

    <!-- Search by Email (contenu inchangé) -->
    <div class="search-bar">
      <form method="GET" action="" class="search-form">
        <input type="hidden" name="action" value="my_reservations">
        <input type="email"
               name="email"
               class="search-input"
               placeholder="Entrez votre email pour retrouver vos réservations..."
               value="<?= htmlspecialchars($searchEmail) ?>"
               required>
        <button type="submit" class="search-btn">🔍 Rechercher mes réservations</button>
        <?php if ($searchEmail): ?>
          <a href="?action=my_reservations" class="btn btn-secondary">✖ Effacer</a>
        <?php endif; ?>
      </form>
    </div>

    <?php if (!$searchEmail): ?>
      <div class="intro-card" style="background: var(--bg-card); padding: 3rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
        <div style="font-size: 4rem; margin-bottom: 1rem;">📧</div>
        <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Retrouvez vos réservations</h3>
        <p style="color: var(--text-medium); max-width: 600px; margin: 0 auto;">
          Entrez l'adresse email utilisée lors de votre réservation pour consulter toutes vos réservations et leurs détails.
        </p>
      </div>

    <?php elseif (empty($myReservations)): ?>
      <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3>Aucune réservation trouvée</h3>
        <p>Aucune réservation n'a été trouvée pour l'email <strong><?= htmlspecialchars($searchEmail) ?></strong></p>
        <a href="?action=events" class="btn btn-primary">Découvrir nos événements</a>
      </div>

    <?php else: ?>
      <div class="found-info" style="background: var(--bg-card); padding: 1rem 1.5rem; border-radius: var(--radius); margin-bottom: 2rem; border-left: 4px solid var(--success);">
        <p style="margin: 0;">
          ✅ <strong><?= count($myReservations) ?></strong> réservation<?= count($myReservations) > 1 ? 's' : '' ?> trouvée<?= count($myReservations) > 1 ? 's' : '' ?> pour
          <strong><?= htmlspecialchars($searchEmail) ?></strong>
        </p>
      </div>

      <?php foreach ($myReservations as $res): ?>
        <div class="reservation-card">
          <div class="reservation-header">
            <div>
              <div class="reservation-ref">
                🎫 <?= htmlspecialchars($res['reference']) ?>
              </div>
              <div style="color: var(--text-medium); font-size: 0.9rem; margin-top: 0.25rem;">
                Réservé le <?= date('d/m/Y à H:i', strtotime($res['date_reservation'])) ?>
              </div>
            </div>

            <span class="badge <?= $res['statut'] === 'confirmée' ? 'badge-success' : ($res['statut'] === 'annulée' ? 'badge-danger' : 'badge-warning') ?>">
              <?= $res['statut'] === 'confirmée' ? '✅ Confirmée' : ($res['statut'] === 'annulée' ? '❌ Annulée' : '⏳ En attente') ?>
            </span>
          </div>

          <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 0.5rem;">
              <?= htmlspecialchars($res['event_titre']) ?>
            </h3>
          </div>

          <div class="reservation-body">
            <div class="info-item">
              <span class="info-label">📅 Date événement</span>
              <span class="info-value"><?= date('d/m/Y', strtotime($res['date_event'])) ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">📍 Lieu</span>
              <span class="info-value"><?= htmlspecialchars($res['lieu']) ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">👤 Nom</span>
              <span class="info-value"><?= htmlspecialchars($res['nom_client']) ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">📞 Téléphone</span>
              <span class="info-value"><?= htmlspecialchars($res['telephone']) ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">🎫 Places</span>
              <span class="info-value"><?= $res['nombre_places'] ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">💰 Montant total</span>
              <span class="info-value" style="color: var(--primary); font-size: 1.2rem;">
                <?= number_format($res['montant_total'], 2, ',', ' ') ?> €
              </span>
            </div>
            <div class="info-item">
              <span class="info-label">💳 Paiement</span>
              <span class="info-value"><?= htmlspecialchars($res['methode_paiement'] ?? 'Non spécifié') ?></span>
            </div>
          </div>

          <?php if (!empty($res['notes'])): ?>
            <div class="notes-box" style="margin-top: 1rem; padding: 1rem; background: var(--bg-main); border-radius: var(--radius);">
              <div style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0.25rem;">📝 Notes :</div>
              <div style="color: var(--text-dark);"><?= nl2br(htmlspecialchars($res['notes'])) ?></div>
            </div>
          <?php endif; ?>

          <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--secondary); display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="?action=event_detail&id=<?= $res['event_id'] ?>" class="btn btn-secondary">
              👁️ Voir l'événement
            </a>
            <a href="?action=reservation_detail&id=<?= $res['id'] ?>&email=<?= urlencode($searchEmail) ?>" class="btn btn-primary">
              📄 Détails complets
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>
</div>

<script>
  // Animations d’entrée façon SPARKMIND
  document.addEventListener("DOMContentLoaded", () => {
    const title = document.querySelector("h1");
    const search = document.querySelector(".search-bar");
    const intro = document.querySelector(".intro-card");
    const empty = document.querySelector(".empty-state");
    const info  = document.querySelector(".found-info");
    const cards = document.querySelectorAll(".reservation-card");

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
      [intro, empty, info].forEach(el => {
        if(el){
          el.style.opacity = "1";
          el.style.transform = "translateY(0)";
        }
      });
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
