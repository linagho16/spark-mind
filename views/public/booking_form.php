<?php
// Formulaire de réservation
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
$placesDisponibles = 100 - $placesReservees;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPARKMIND — Réservation</title>

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

      /* si jamais ton code les appelle */
      --secondary: rgba(0,0,0,0.08);
      --error: #c62828;
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

    /* ======= LAYOUT ======= */
    .container{
      padding: 10px 20px 60px;
      max-width: 1100px;
      margin: 0 auto;
    }

    /* Retour link (style pill doux) */
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

    /* Grid responsive */
    .booking-grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      align-items:start;
    }
    @media (max-width: 900px){
      .booking-grid{ grid-template-columns: 1fr; }
    }

    /* Summary card (look SPARKMIND) */
    .summary-card{
      position: sticky;
      top: 88px; /* sous la nav */
      background: #FFF7EF;
      padding: 22px;
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25);
      overflow:hidden;
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: opacity .6s ease, transform .6s ease, box-shadow .18s ease;
    }
    .summary-card:hover{ box-shadow: 0 16px 34px rgba(0,0,0,0.35); }

    .summary-title{
      font-family:'Playfair Display', serif;
      margin: 0 0 14px 0;
      color: var(--text-dark);
      font-size: 22px;
    }

    .summary-head{
      margin-bottom: 12px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    /* Petites lignes meta en “pills” */
    .meta-line{
      display:flex;
      align-items:center;
      gap: 10px;
    }
    .meta-icon{ font-size: 22px; }
    .meta-box{
      display:flex;
      flex-direction:column;
      gap:2px;
    }
    .meta-label{ font-size: 12px; color: var(--text-medium); opacity:.85; }
    .meta-value{ font-weight: 600; color:#02282f; }

    #totalPreview{
      background: #FBEDD7 !important;
      border-radius: 18px !important;
      box-shadow: inset 0 0 0 1px rgba(0,0,0,0.04);
    }

    /* Form card */
    .booking-form{
      background: #FFF7EF;
      padding: 22px;
      border-radius: 18px;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.25);
      opacity:0; transform: translateY(24px) scale(0.97);
      transition: opacity .6s ease, transform .6s ease, box-shadow .18s ease;
    }
    .booking-form:hover{ box-shadow: 0 16px 34px rgba(0,0,0,0.35); }

    .booking-form h2{
      font-family:'Playfair Display', serif;
      margin: 0 0 14px 0;
      color: var(--text-dark);
      font-size: 22px;
    }

    /* Inputs */
    .form-group{ margin-bottom: 14px; }
    .form-row{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    @media (max-width: 700px){
      .form-row{ grid-template-columns: 1fr; }
    }

    .form-label{
      display:block;
      font-weight: 600;
      color:#1A464F;
      margin-bottom: 6px;
      font-size: 13px;
    }

    .form-control{
      width:100%;
      border-radius: 14px;
      border: 1px solid rgba(0,0,0,0.10);
      padding: 12px 12px;
      outline:none;
      background: rgba(255,255,255,0.65);
      transition: box-shadow .18s ease, transform .18s ease, border-color .18s ease, filter .18s ease;
      font-family:'Poppins', sans-serif;
    }
    .form-control:focus{
      border-color: rgba(236,117,70,0.55);
      box-shadow: 0 10px 24px rgba(236,117,70,0.22);
      transform: translateY(-1px);
      filter: brightness(1.01);
    }

    /* Boutons */
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

    /* Info bloc (ton contenu inchangé, on harmonise juste) */
    .info-box{
      background: rgba(31,140,135,0.12);
      border: 1px solid rgba(31,140,135,0.18);
      padding: 12px 14px;
      border-radius: 16px;
      margin-bottom: 16px;
    }
    .info-box p{
      margin:0;
      color:#1A464F;
      font-size: 0.9rem;
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


  </header>

  <h2 class="page-quote">« Réserver, c’est faire un pas vers l’expérience. »</h2>

  <div class="container">

    <a href="/sparkmind_mvc_100percent/index.php?page=events_home&id=<?= $eventId ?>"
       class="back-link"
       style="color: var(--primary); text-decoration: none; font-weight: 600;">
      ← Retour à l'événement
    </a>

    <div class="booking-grid">

      <!-- Event Summary (contenu inchangé) -->
      <div class="summary-card" style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); position: sticky; top: 2rem;">
        <h2 class="summary-title" style="margin-bottom: 1.5rem; color: var(--text-dark);">📋 Résumé de l'événement</h2>

        <div class="summary-head" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--secondary);">
          <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem;">
            <?= htmlspecialchars($event['titre']) ?>
          </h3>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div class="meta-line" style="display: flex; align-items: center; gap: 0.75rem;">
            <span class="meta-icon" style="font-size: 1.5rem;">📅</span>
            <div class="meta-box">
              <div class="meta-label" style="font-size: 0.85rem; color: var(--text-medium);">Date</div>
              <div class="meta-value" style="font-weight: 600;"><?= date('d/m/Y', strtotime($event['date_event'])) ?></div>
            </div>
          </div>

          <div class="meta-line" style="display: flex; align-items: center; gap: 0.75rem;">
            <span class="meta-icon" style="font-size: 1.5rem;">📍</span>
            <div class="meta-box">
              <div class="meta-label" style="font-size: 0.85rem; color: var(--text-medium);">Lieu</div>
              <div class="meta-value" style="font-weight: 600;"><?= htmlspecialchars($event['lieu']) ?></div>
            </div>
          </div>

          <div class="meta-line" style="display: flex; align-items: center; gap: 0.75rem;">
            <span class="meta-icon" style="font-size: 1.5rem;">💰</span>
            <div class="meta-box">
              <div class="meta-label" style="font-size: 0.85rem; color: var(--text-medium);">Prix par place</div>
              <div class="meta-value" style="font-weight: 600; font-size: 1.3rem; color: var(--primary);">
                <?= number_format($event['prix'], 2, ',', ' ') ?> €
              </div>
            </div>
          </div>

          <div class="meta-line" style="display: flex; align-items: center; gap: 0.75rem;">
            <span class="meta-icon" style="font-size: 1.5rem;">🎫</span>
            <div class="meta-box">
              <div class="meta-label" style="font-size: 0.85rem; color: var(--text-medium);">Places disponibles</div>
              <div class="meta-value" style="font-weight: 600; color: <?= $placesDisponibles < 10 ? 'var(--error)' : 'var(--success)' ?>;">
                <?= $placesDisponibles ?> / 100
              </div>
            </div>
          </div>
        </div>

        <div id="totalPreview" style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-main); border-radius: var(--radius); text-align: center;">
          <div style="font-size: 0.9rem; color: var(--text-medium); margin-bottom: 0.5rem;">Montant total</div>
          <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
            <span id="totalAmount">0.00</span> €
          </div>
        </div>
      </div>

      <!-- Booking Form (contenu inchangé) -->
      <div class="booking-form">
        <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">🎫 Réserver votre place</h2>

        <form action="process_public_reservation.php" method="POST" onsubmit="return validateForm()">
          <input type="hidden" name="event_id" value="<?= $eventId ?>">
          <input type="hidden" name="prix_unitaire" value="<?= $event['prix'] ?>">

          <div class="form-group">
            <label class="form-label">👤 Nom complet *</label>
            <input type="text" name="nom_client" class="form-control" required placeholder="Votre nom et prénom">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">📧 Email *</label>
              <input type="email" name="email" class="form-control" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
              <label class="form-label">📞 Téléphone *</label>
              <input type="tel" name="telephone" class="form-control" required placeholder="06 12 34 56 78">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">🎫 Nombre de places * (max: <?= $placesDisponibles ?>)</label>
            <input type="number" name="nombre_places" id="nombrePlaces"
                   class="form-control" required min="1" max="<?= $placesDisponibles ?>"
                   value="1" onchange="updateTotal()">
          </div>

          <div class="form-group">
            <label class="form-label">💳 Méthode de paiement *</label>
            <select name="methode_paiement" class="form-control" required>
              <option value="carte">Carte bancaire</option>
              <option value="especes">Espèces</option>
              <option value="virement">Virement</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">📝 Notes (optionnel)</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires..."></textarea>
          </div>

          <div class="info-box" style="background: #E8F5E9; padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
            <p style="margin: 0; color: #2E7D32; font-size: 0.9rem;">
              ℹ️ Vous recevrez un email de confirmation avec votre référence de réservation.
            </p>
          </div>

          <button type="submit" class="btn btn-book" style="width: 100%; font-size: 1.1rem; padding: 1.25rem;">
            ✅ Confirmer la réservation
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
const prixUnitaire = <?= $event['prix'] ?>;

function updateTotal() {
    const places = parseInt(document.getElementById('nombrePlaces').value) || 0;
    const total = (places * prixUnitaire).toFixed(2);
    document.getElementById('totalAmount').textContent = total;
}

function validateForm() {
    const places = parseInt(document.getElementById('nombrePlaces').value);
    const maxPlaces = <?= $placesDisponibles ?>;

    if (places < 1) {
        alert('Veuillez réserver au moins 1 place.');
        return false;
    }

    if (places > maxPlaces) {
        alert(`Seulement ${maxPlaces} places disponibles.`);
        return false;
    }

    return confirm(`Confirmer la réservation de ${places} place(s) pour un total de ${(places * prixUnitaire).toFixed(2)} € ?`);
}

// Initialize total
updateTotal();

/* Animations d'entrée façon SPARKMIND */
document.addEventListener("DOMContentLoaded", () => {
  const back = document.querySelector(".back-link");
  const summary = document.querySelector(".summary-card");
  const form = document.querySelector(".booking-form");

  setTimeout(() => {
    if(back){
      back.style.opacity = "1";
      back.style.transform = "translateY(0)";
      back.style.transition = "opacity 0.7s ease, transform 0.7s ease";
    }
  }, 160);

  [summary, form].forEach((el, i) => {
    setTimeout(() => {
      if(el){
        el.style.opacity = "1";
        el.style.transform = "translateY(0) scale(1)";
      }
    }, 260 + i * 140);
  });
});
</script>

</body>
</html>
