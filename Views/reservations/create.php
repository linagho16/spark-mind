<?php
// Vérifier que $events est défini et n'est pas vide
if (!isset($events) || empty($events)) {
    // Page complète avec style SPARKMIND même en cas d'absence d'events
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
      <meta charset="UTF-8" />
      <title>SPARKMIND — Nouvelle réservation</title>
      <meta name="viewport" content="width=device-width, initial-scale=1" />

      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

      <style>
        body {
          margin: 0;
          min-height: 100vh;
          background:
            radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
            radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
            #FBEDD7;
          font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
          color: #1A464F;
        }
        .layout{ min-height:100vh; display:flex; }
        .sidebar{
          width:260px;
          background:linear-gradient(#ede8deff 50%, #f7f1eb 100%);
          border-right:1px solid rgba(0,0,0,.06);
          padding:18px 14px;
          display:flex;
          flex-direction:column;
          gap:12px;
          position:sticky;
          top:0;
          height:100vh;
        }
        .sidebar .brand{
          display:flex; align-items:center; gap:10px;
          text-decoration:none; padding:10px 10px;
          border-radius:14px; color:#1A464F;
        }
        .sidebar .brand-name{
          font-family:'Playfair Display', serif;
          font-weight:800; font-size:18px; color:#1A464F;
        }
        .menu{ display:flex; flex-direction:column; gap:6px; margin-top:6px; }
        .menu-title{
          font-size:10px; font-weight:700; letter-spacing:.06em;
          color:#1A464F; padding:8px 12px 6px; text-transform:uppercase;
        }
        .menu-item{
          display:flex; align-items:center; gap:10px;
          padding:10px 12px; border-radius:12px;
          text-decoration:none; color:#1A464F; font-weight:600;
        }
        .menu-item:hover{ background:#f5e2c4ff; }
        .menu-item.active{ background:#f5e2c4ff; color:#0b3936ff; }
        .sidebar-foot{
          margin-top:auto; padding-top:10px; border-top:1px solid rgba(0,0,0,.06);
        }
        .sidebar-foot .link{
          display:block; padding:10px 12px; border-radius:12px;
          text-decoration:none; color:#1A464F; font-weight:600;
        }
        .sidebar-foot .link:hover{ background:#f5e2c4ff; }

        .main{ flex:1; min-width:0; }
        .site{ min-height:100vh; display:flex; flex-direction:column; }

        .top-nav{
          position:sticky; top:0; z-index:100;
          backdrop-filter: blur(14px);
          background: rgba(251, 237, 215, 0.96);
          display:flex; align-items:center; justify-content:space-between;
          padding:10px 24px;
        }
        .brand-block{ display:flex; align-items:center; gap:12px; }
        .brand-text{ display:flex; flex-direction:column; line-height:1.1; }
        .brand-name{ font-family:'Playfair Display', serif; font-size:20px; color:#1A464F; }
        .brand-tagline{ font-size:11px; color:#1A464F; opacity:.8; }

        .header-actions{ display:flex; gap:10px; align-items:center; }
        .btn-nav{
          border:none; cursor:pointer;
          padding:8px 14px; border-radius:999px;
          font-size:13px; font-weight:500;
          background:#1A464F; color:#fff;
          text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
        }
        .btn-nav.secondary{
          background:transparent; color:#1A464F;
          border:1px solid rgba(26,70,79,0.35);
        }

        .admin-main{ flex:1; max-width:1100px; margin:32px auto 40px; padding:0 18px 30px; }
        .admin-card{
          background: rgba(255, 247, 239, 0.95);
          border-radius: 24px;
          padding: 24px 22px 26px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        }
        .admin-card h1{ margin:0 0 6px; font-family:'Playfair Display', serif; font-size:26px; }
        .admin-subtitle{ font-size:13px; margin-bottom:18px; color:#444; }

        .empty-state{
          padding:26px 16px;
          text-align:center;
          color:#444;
          background: rgba(255,255,255,0.9);
          border-radius:18px;
          box-shadow:0 8px 18px rgba(0,0,0,0.06);
        }
        .empty-state .emoji{ font-size:40px; margin-bottom:10px; }

        @media (max-width: 900px) { .sidebar{ width:220px; } }
        @media (max-width: 800px) {
          .sidebar{ position:relative; height:auto; }
          .layout{ flex-direction:column; }
        }
      </style>
    </head>
    <body>
      <div class="layout">

        <aside class="sidebar">
          <a class="brand"><span class="brand-name">SPARKMIND</span></a>
          <div style="margin:-6px 12px 6px; color:#6B5F55; font-size:12px;">Gestion des réservations</div>

          <nav class="menu">
            <div class="menu-title">📊 Dashboard admin</div>
            <a class="menu-item" href="?action=dashboard">📊 Tableau de bord</a>
            <a class="menu-item" href="?action=events">📅 Événements</a>
            <a class="menu-item" href="?action=reservations">🎫 Réservations</a>
            <a class="menu-item active" href="?action=create_reservation">➕ Nouvelle Réservation</a>
            <a class="menu-item" href="?action=create_event">✨ Nouvel Événement</a>
          </nav>

          <div class="sidebar-foot">
            <a class="link" href="?action=front">← Front Office</a>
          </div>
        </aside>

        <div class="main">
          <div class="site">

            <header class="top-nav">
              <div class="brand-block">
                <div class="brand-text">
                  <span class="brand-name">SPARKMIND</span>
                  <span class="brand-tagline">Nouvelle réservation</span>
                </div>
              </div>
              <div class="header-actions">
                <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
                <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
              </div>
            </header>

            <main class="admin-main">
              <section class="admin-card">
                <div class="empty-state">
                  <div class="emoji">⚠️</div>
                  <h1 style="margin:0 0 6px;">Aucun événement disponible</h1>
                  <p style="margin:0 0 12px;">Veuillez créer un événement d'abord.</p>
                  <a href="?action=create_event" class="btn-nav">✨ Créer un événement</a>
                </div>
              </section>
            </main>

          </div>
        </div>
      </div>
    </body>
    </html>
    <?php
    return;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Nouvelle réservation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND — IDENTIQUE AUX PAGES PRÉCÉDENTES */
    body {
      margin: 0;
      min-height: 100vh;
      background:
        radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
        radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
        #FBEDD7;
      font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      color: #1A464F;
    }

    .layout{ min-height:100vh; display:flex; }

    .sidebar{
      width:260px;
      background:linear-gradient(#ede8deff 50%, #f7f1eb 100%);
      border-right:1px solid rgba(0,0,0,.06);
      padding:18px 14px;
      display:flex;
      flex-direction:column;
      gap:12px;
      position:sticky;
      top:0;
      height:100vh;
    }

    .sidebar .brand{
      display:flex;
      align-items:center;
      gap:10px;
      text-decoration:none;
      padding:10px 10px;
      border-radius:14px;
      color:#1A464F;
    }

    .sidebar .brand-name{
      font-family:'Playfair Display', serif;
      font-weight:800;
      font-size:18px;
      color:#1A464F;
    }

    .menu{ display:flex; flex-direction:column; gap:6px; margin-top:6px; }

    .menu-title{
      font-size:10px;
      font-weight:700;
      letter-spacing:.06em;
      color:#1A464F;
      padding:8px 12px 6px;
      text-transform:uppercase;
    }

    .menu-item{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius:12px;
      text-decoration:none;
      color:#1A464F;
      font-weight:600;
    }

    .menu-item:hover{ background:#f5e2c4ff; }
    .menu-item.active{ background:#f5e2c4ff; color:#0b3936ff; }

    .sidebar-foot{
      margin-top:auto;
      padding-top:10px;
      border-top:1px solid rgba(0,0,0,.06);
    }

    .sidebar-foot .link{
      display:block;
      padding:10px 12px;
      border-radius:12px;
      text-decoration:none;
      color:#1A464F;
      font-weight:600;
    }

    .sidebar-foot .link:hover{ background:#f5e2c4ff; }

    .main{ flex:1; min-width:0; }
    .site{ min-height:100vh; display:flex; flex-direction:column; }

    .top-nav{
      position:sticky;
      top:0;
      z-index:100;
      backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96);
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:10px 24px;
    }

    .brand-block{ display:flex; align-items:center; gap:12px; }
    .brand-text{ display:flex; flex-direction:column; line-height:1.1; }
    .brand-name{ font-family:'Playfair Display', serif; font-size:20px; color:#1A464F; }
    .brand-tagline{ font-size:11px; color:#1A464F; opacity:.8; }

    .header-actions{ display:flex; gap:10px; align-items:center; }

    .btn-nav{
      border:none;
      cursor:pointer;
      padding:8px 14px;
      border-radius:999px;
      font-size:13px;
      font-weight:500;
      background:#1A464F;
      color:#fff;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
    }
    .btn-nav.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26,70,79,0.35);
    }

    .admin-main{ flex:1; max-width:1100px; margin:32px auto 40px; padding:0 18px 30px; }
    .admin-card{
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 24px 22px 26px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    }
    .admin-card h1{ margin:0 0 6px; font-family:'Playfair Display', serif; font-size:26px; }
    .admin-subtitle{ font-size:13px; margin-bottom:18px; color:#444; }

    /* ✅ Form cards comme admin-form-card */
    .form-card{
      background:#fff7ef;
      border-radius:18px;
      padding:14px 14px 16px;
      box-shadow:0 8px 18px rgba(0,0,0,0.08);
      margin-bottom:14px;
    }

    .form-card h2{
      margin:0 0 10px;
      font-size:18px;
      font-weight:600;
      color:#1A464F;
    }

    .form-row{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-bottom:10px;
    }

    .form-field{
      flex: 1 1 220px;
      font-size:13px;
    }

    .form-field label{
      display:block;
      margin-bottom:3px;
      font-size:12px;
      color:#444;
      font-weight:600;
    }

    .form-control{
      width:100%;
      padding:8px 10px;
      border-radius:12px;
      border:1px solid #ccc;
      font-size:13px;
      outline:none;
      background:#fff;
    }

    .form-control:focus{
      border-color:#1A464F;
      box-shadow:0 0 0 2px rgba(26,70,79,0.15);
    }

    .total-box{
      padding:10px 12px;
      background: rgba(255,255,255,0.9);
      border-radius: 16px;
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      font-size: 14px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }

    .total-box strong{
      font-size:16px;
    }

    .form-actions{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      margin-top:14px;
    }

    .empty-state{
      padding:26px 16px;
      text-align:center;
      color:#444;
      background: rgba(255,255,255,0.9);
      border-radius:18px;
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
    }
    .empty-state .emoji{ font-size:40px; margin-bottom:10px; }

    @media (max-width: 900px) { .sidebar{ width:220px; } }
    @media (max-width: 800px) {
      .sidebar{ position:relative; height:auto; }
      .layout{ flex-direction:column; }
    }
  </style>
</head>

<body>

<div class="layout">

  <!-- ✅ SIDEBAR -->
  <aside class="sidebar">
    <a class="brand">
      <span class="brand-name">SPARKMIND</span>
    </a>

    <div style="margin: -6px 12px 6px; color:#6B5F55; font-size:12px;">
      Gestion des réservations
    </div>

    <nav class="menu">
      <div class="menu-title">📊 Dashboard admin</div>
      <a class="menu-item active" href="/sparkmind_mvc_100percent/index.php?page=events_dashboard">📊 Tableau de bord</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_list">📅 Événements</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservations_list">🎫 Réservations</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php">➕ Nouvelle Réservation</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=event_create">✨ Nouvel Événement</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_scan">📷 Scanner</a>

    </nav>

    <div class="sidebar-foot">
      <a class="link" href="?action=front">← Front Office</a>
    </div>
  </aside>

  <!-- ✅ MAIN -->
  <div class="main">
    <div class="site">

      <header class="top-nav">
        <div class="brand-block">
          <div class="brand-text">
            <span class="brand-name">SPARKMIND</span>
            <span class="brand-tagline">Créer une nouvelle réservation</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <h1>➕ Nouvelle Réservation</h1>
          <p class="admin-subtitle">Remplissez le formulaire pour créer une nouvelle réservation</p>

          <form action="process_reservation.php?action=create" method="POST" class="reservation-form">

            <!-- Informations Client -->
            <div class="form-card">
              <h2>👤 Informations Client</h2>

              <div class="form-row">
                <div class="form-field" style="flex: 1 1 320px;">
                  <label for="nom_client">Nom complet *</label>
                  <input type="text" id="nom_client" name="nom_client" class="form-control"
                         required placeholder="Ex: Jean Dupont">
                </div>
              </div>

              <div class="form-row">
                <div class="form-field">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="form-control"
                         placeholder="exemple@email.com">
                </div>

                <div class="form-field">
                  <label for="telephone">Téléphone</label>
                  <input type="tel" id="telephone" name="telephone" class="form-control"
                         placeholder="06 12 34 56 78">
                </div>
              </div>
            </div>

            <!-- Sélection Événement -->
            <div class="form-card">
              <h2>🎭 Sélectionner un événement *</h2>

              <div class="form-row">
                <div class="form-field" style="flex: 1 1 520px;">
                  <label for="event_id">Choisissez un événement</label>
                  <select id="event_id" name="event_id" class="form-control" required>
                    <option value="">-- Veuillez sélectionner un événement --</option>
                    <?php foreach ($events as $event): ?>
                      <option value="<?php echo (int)$event['id']; ?>"
                              data-price="<?php echo (float)$event['prix']; ?>">
                        <?php echo htmlspecialchars($event['titre']); ?>
                        (<?php echo date('d/m/Y', strtotime($event['date_event'])); ?> -
                        <?php echo number_format((float)$event['prix'], 2, ',', ' '); ?> €)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-field">
                  <label for="nombre_places">Nombre de places *</label>
                  <input type="number" id="nombre_places" name="nombre_places"
                         class="form-control" min="1" value="1" required>
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex: 1 1 320px;">
                  <label>Montant total</label>
                  <div class="total-box">
                    <span>Total</span>
                    <strong><span id="montant_total">0.00</span> €</strong>
                    <input type="hidden" id="montant_total_input" name="montant_total" value="0">
                  </div>
                </div>
              </div>
            </div>

            <!-- Paiement -->
            <div class="form-card">
              <h2>💳 Méthode de paiement</h2>

              <div class="form-row">
                <div class="form-field">
                  <label for="methode_paiement">Méthode de paiement</label>
                  <select id="methode_paiement" name="methode_paiement" class="form-control">
                    <option value="carte">Carte bancaire</option>
                    <option value="especes">Espèces</option>
                    <option value="cheque">Chèque</option>
                    <option value="virement">Virement</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 520px;">
                  <label for="notes">Notes (optionnel)</label>
                  <textarea id="notes" name="notes" class="form-control" rows="3"
                            placeholder="Remarques particulières..."></textarea>
                </div>
              </div>
            </div>

            <!-- Boutons -->
            <div class="form-actions">
              <button type="submit" class="btn-nav">✅ Créer la réservation</button>
              <a href="/sparkmind_mvc_100percent/index.php?page=events_dashboard" class="btn-nav secondary">↩️ Annuler</a>
            </div>

          </form>

        </section>
      </main>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const eventSelect = document.getElementById('event_id');
  const placesInput = document.getElementById('nombre_places');
  const montantTotal = document.getElementById('montant_total');
  const montantTotalInput = document.getElementById('montant_total_input');

  function calculateTotal() {
    const selectedOption = eventSelect.options[eventSelect.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
    const places = parseInt(placesInput.value) || 0;
    const total = price * places;

    if (montantTotal) montantTotal.textContent = total.toFixed(2);
    if (montantTotalInput) montantTotalInput.value = total.toFixed(2);
  }

  if (eventSelect && placesInput) {
    eventSelect.addEventListener('change', calculateTotal);
    placesInput.addEventListener('input', calculateTotal);
    calculateTotal();
  }

  // Validation
  const form = document.querySelector('.reservation-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value || !field.value.toString().trim()) {
          field.style.borderColor = '#f56565';
          isValid = false;
        } else {
          field.style.borderColor = '';
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires (*).');
      }
    });
  }
});
</script>

</body>
</html>
