<?php
// Détail d'une réservation
$resId = $_GET['id'] ?? null;
$searchEmail = $_GET['email'] ?? '';

if (!$resId || !$searchEmail) {
    header('Location: ?action=my_reservations');
    exit;
}

// Récupérer la réservation avec vérification de l'email
// ✅ MODIF: supprimer e.duree (colonne inexistante)
$stmt = $pdo->prepare("
    SELECT r.*, e.titre as event_titre, e.description as event_description, 
           e.date_event, e.lieu, e.prix
    FROM reservations r
    JOIN events e ON r.event_id = e.id
    WHERE r.id = :id AND r.email = :email
");
$stmt->execute([':id' => $resId, ':email' => $searchEmail]);
$res = $stmt->fetch();

if (!$res) {
    $_SESSION['message'] = "Réservation introuvable ou accès non autorisé.";
    $_SESSION['message_type'] = 'error';
    header('Location: ?action=my_reservations');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Détail réservation</title>

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
      --primary-dark: #d9643b; /* fallback pour ton dégradé */
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

    /* Wrapper principal (effet SPARKMIND) */
    .event-detail{
      background:#f5f5f5;
      border-radius: 24px;
      box-shadow: var(--shadow);
      overflow:hidden;
      position:relative;
      padding: 24px;
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

    /* Badges + pills */
    .badge{
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 12px;
      font-weight: 800;
      white-space:nowrap;
      background: rgba(0,0,0,0.08);
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:6px;
    }
    .badge-success{ background: rgba(31,140,135,0.18); color:#0b3b39; }
    .badge-warning{ background: rgba(245,158,11,0.20); color:#6a3b00; }
    .badge-danger{ background: rgba(198,40,40,0.18); color:#6b0f0f; }

    /* Boutons */
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
      font-weight:700;
    }
    .btn:hover{ transform: translateY(-2px) scale(1.02); box-shadow: 0 12px 26px rgba(0,0,0,0.18); filter: brightness(1.02); }
    .btn-primary{ background: var(--orange); color:#fff; box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45); }
    .btn-secondary{ background:#FFF7EF; color:#1A464F; }

    /* Cartes internes */
    .panel{
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0,0,0,0.18);
      opacity:0; transform: translateY(18px);
      transition: opacity .7s ease, transform .7s ease;
    }

    /* Pour tes .info-item / .detail-item (sans changer le HTML) */
    .info-item, .detail-item{
      background: rgba(0,0,0,0.05);
      border-radius: 14px;
      padding: 10px 12px;
    }
    .info-label{
      font-size: 12px;
      color: var(--text-medium);
      font-weight: 700;
      opacity:.9;
    }
    .info-value{
      font-size: 13px;
      font-weight: 800;
      color:#02282f;
    }
    .detail-label{
      font-size: 12px;
      color: var(--text-medium);
      font-weight: 800;
      opacity:.9;
      margin-bottom: 6px;
    }
    .detail-value{
      font-size: 16px;
      font-weight: 800;
      color:#02282f;
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

  <h2 class="page-quote">« Une réservation, c’est une promesse de présence. »</h2>

  <div class="container">

    <a href="?action=my_reservations&email=<?= urlencode($searchEmail) ?>"
       class="back-link"
       style="display: inline-block; margin-bottom: 2rem; color: var(--primary); text-decoration: none; font-weight: 600;">
      ← Retour à mes réservations
    </a>

    <div class="event-detail">

      <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border-radius: var(--radius-lg) var(--radius-lg) 0 0; margin: -3rem -3rem 2rem -3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🎫</div>
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Détails de votre réservation</h1>
        <div style="font-size: 1.5rem; font-weight: 700; opacity: 0.95;">
          <?= htmlspecialchars($res['reference']) ?>
        </div>
      </div>

      <!-- Status Badge -->
      <div style="text-align: center; margin-bottom: 2rem;">
        <span class="badge <?= $res['statut'] === 'confirmée' ? 'badge-success' : ($res['statut'] === 'annulée' ? 'badge-danger' : 'badge-warning') ?>"
              style="font-size: 1.1rem; padding: 0.75rem 2rem;">
          <?= $res['statut'] === 'confirmée' ? '✅ Réservation confirmée' : ($res['statut'] === 'annulée' ? '❌ Réservation annulée' : '⏳ En attente de confirmation') ?>
        </span>
      </div>

      <!-- Event Info -->
      <div class="panel" style="background: var(--bg-main); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
          <span>🎭</span> Événement
        </h2>
        <h3 style="font-size: 1.8rem; color: var(--primary); margin-bottom: 1rem;">
          <?= htmlspecialchars($res['event_titre']) ?>
        </h3>
        <p style="color: var(--text-medium); line-height: 1.8; margin-bottom: 1.5rem;">
          <?= nl2br(htmlspecialchars($res['event_description'])) ?>
        </p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 1.5rem;">📅</span>
            <div>
              <div style="font-size: 0.85rem; color: var(--text-medium);">Date</div>
              <div style="font-weight: 600;"><?= date('d/m/Y', strtotime($res['date_event'])) ?></div>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 1.5rem;">📍</span>
            <div>
              <div style="font-size: 0.85rem; color: var(--text-medium);">Lieu</div>
              <div style="font-weight: 600;"><?= htmlspecialchars($res['lieu']) ?></div>
            </div>
          </div>

          <!-- ✅ MODIF: on supprime l'affichage de la durée (car champ non sélectionné / inexistant) -->

        </div>
      </div>

      <!-- Client Info -->
      <div class="panel" style="background: var(--bg-card); border: 2px solid var(--secondary); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
          <span>👤</span> Informations client
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
          <div class="info-item">
            <span class="info-label">Nom complet</span>
            <span class="info-value"><?= htmlspecialchars($res['nom_client']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Email</span>
            <span class="info-value"><?= htmlspecialchars($res['email']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Téléphone</span>
            <span class="info-value"><?= htmlspecialchars($res['telephone']) ?></span>
          </div>
        </div>
      </div>

      <!-- Booking Details -->
      <div class="panel" style="background: var(--bg-card); border: 2px solid var(--primary); padding: 2rem; border-radius: var(--radius-lg);">
        <h2 style="margin-bottom: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
          <span>💰</span> Détails de la réservation
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
          <div class="detail-item">
            <div class="detail-label">🎫 Nombre de places</div>
            <div class="detail-value" style="font-size: 2rem;"><?= $res['nombre_places'] ?></div>
          </div>
          <div class="detail-item">
            <div class="detail-label">💵 Prix unitaire</div>
            <div class="detail-value"><?= number_format($res['prix'], 2, ',', ' ') ?> €</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">💳 Méthode paiement</div>
            <div class="detail-value"><?= htmlspecialchars($res['methode_paiement'] ?? 'Non spécifié') ?></div>
          </div>
          <div class="detail-item" style="background: var(--primary); color: white;">
            <div class="detail-label" style="color: rgba(255,255,255,0.9);">💰 MONTANT TOTAL</div>
            <div class="detail-value" style="font-size: 2rem; color: white;">
              <?= number_format($res['montant_total'], 2, ',', ' ') ?> €
            </div>
          </div>
        </div>

        <div style="padding: 1rem; background: var(--bg-main); border-radius: var(--radius);">
          <div style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0.25rem;">
            📅 Date de réservation
          </div>
          <div style="font-weight: 600;">
            <?= date('d/m/Y à H:i:s', strtotime($res['date_reservation'])) ?>
          </div>
        </div>

        <?php if (!empty($res['notes'])): ?>
        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--bg-main); border-radius: var(--radius);">
          <div style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0.5rem;">📝 Notes :</div>
          <div style="color: var(--text-dark); line-height: 1.6;"><?= nl2br(htmlspecialchars($res['notes'])) ?></div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Actions -->
      <div style="margin-top: 2rem; text-align: center;">


      <a href="/sparkmind_mvc_100percent/index.php?page=my_reservations&email=<?= urlencode($searchEmail) ?>"
        class="btn btn-secondary">
        📋 Mes autres réservations
      </a>

      </div>

    </div>
  </div>
</div>

<script>
  // Animations d’entrée façon SPARKMIND
  document.addEventListener("DOMContentLoaded", () => {
    const back = document.querySelector(".back-link");
    const wrap = document.querySelector(".event-detail");
    const panels = document.querySelectorAll(".panel");

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

    panels.forEach((p, i) => {
      setTimeout(() => {
        p.style.opacity = "1";
        p.style.transform = "translateY(0)";
      }, 320 + i * 140);
    });
  });
</script>

</body>
</html>
