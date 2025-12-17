<?php
// Récupérer la réservation
$reservationData = $reservation->getById($id);
if (!$reservationData) {
    $_SESSION['message'] = "Réservation introuvable.";
    $_SESSION['message_type'] = 'error';
    header('Location: index.php?action=reservations');
    exit;
}

// Récupérer tous les événements pour le select
$events = $eventModel->getAllEvents();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Modifier la réservation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND — IDENTIQUE */
    body{
      margin:0;
      min-height:100vh;
      background:
        radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
        radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
        #FBEDD7;
      font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      color:#1A464F;
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
    .brand-name{
      font-family:'Playfair Display', serif;
      font-size:20px;
      color:#1A464F;
    }
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
      white-space:nowrap;
    }

    .btn-nav.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26, 70, 79, 0.35);
    }

    .btn-nav.danger{ background:#E23B3B; }

    .admin-main{ flex:1; max-width:1100px; margin:32px auto 40px; padding:0 18px 30px; }

    .admin-card{
      background: rgba(255, 247, 239, 0.95);
      border-radius: 24px;
      padding: 24px 22px 26px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    }

    .admin-card h1{ margin:0 0 6px; font-family:'Playfair Display', serif; font-size:26px; }
    .admin-subtitle{ font-size:13px; margin:0 0 16px; color:#444; }

    /* ✅ Form card */
    .form-card{
      background:#fff7ef;
      border-radius:18px;
      padding:14px 14px 16px;
      box-shadow:0 8px 18px rgba(0,0,0,0.08);
      margin-top:12px;
    }

    .form-title{
      margin: 0 0 6px;
      font-size: 18px;
      font-weight: 700;
      color:#1A464F;
    }
    .form-sub{
      margin: 0 0 12px;
      font-size: 12px;
      color:#555;
    }

    .form-row{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-bottom:10px;
    }

    .form-field{
      flex:1 1 240px;
      font-size:13px;
    }

    .form-field label{
      display:block;
      margin-bottom:4px;
      font-size:12px;
      color:#444;
      font-weight:600;
    }

    .form-control{
      width:100%;
      padding:10px 12px;
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

    .money-box{
      padding: 10px 12px;
      background: rgba(255,255,255,0.9);
      border: 1px solid rgba(26,70,79,0.18);
      border-radius: 14px;
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      font-size: 14px;
      font-weight: 700;
      color:#1A464F;
      display:flex;
      align-items:center;
      gap:8px;
      min-height: 40px;
    }

    .actions-row{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      margin-top:14px;
      align-items:center;
    }

    .push-right{ margin-left:auto; }

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

    <div style="margin:-6px 12px 6px; color:#6B5F55; font-size:12px;">
      Gestion des réservations
    </div>

    <nav class="menu">
      <div class="menu-title">📊 Dashboard admin</div>

      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_dashboard">📊 Tableau de bord</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_list">📅 Événements</a>
      <a class="menu-item active" href="/sparkmind_mvc_100percent/index.php?page=reservations_list">🎫 Réservations</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservation_create">➕ Nouvelle Réservation</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=event_create">✨ Nouvel Événement</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=scanner">📷 Scanner</a>
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
            <span class="brand-tagline">Modifier la réservation</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:flex-start;">
            <div>
              <h1>✏️ Modifier la Réservation</h1>
              <p class="admin-subtitle">Référence : <strong><?= htmlspecialchars($reservationData['reference']) ?></strong></p>
            </div>
            <a href="?action=reservations" class="btn-nav secondary">← Retour</a>
          </div>

          <form action="process_reservation.php?action=update&id=<?= (int)$reservationData['id'] ?>" method="POST" class="reservation-form">

            <!-- 👤 Informations Client -->
            <div class="form-card">
              <div class="form-title">👤 Informations Client</div>
              <p class="form-sub">Modifiez les informations du client associées à cette réservation.</p>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 100%;">
                  <label for="nom_client">Nom complet *</label>
                  <input type="text" id="nom_client" name="nom_client" class="form-control"
                         required value="<?= htmlspecialchars($reservationData['nom_client']) ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-field">
                  <label for="email">Email *</label>
                  <input type="email" id="email" name="email" class="form-control"
                         required value="<?= htmlspecialchars($reservationData['email']) ?>">
                </div>

                <div class="form-field">
                  <label for="telephone">Téléphone *</label>
                  <input type="tel" id="telephone" name="telephone" class="form-control"
                         required value="<?= htmlspecialchars($reservationData['telephone']) ?>">
                </div>
              </div>
            </div>

            <!-- 🎭 Détails de la réservation -->
            <div class="form-card">
              <div class="form-title">🎭 Détails de la Réservation</div>
              <p class="form-sub">Sélectionnez l’événement et ajustez le nombre de places.</p>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 100%;">
                  <label for="event_id">Événement *</label>
                  <select id="event_id" name="event_id" class="form-control" required>
                    <option value="">-- Sélectionner un événement --</option>
                    <?php foreach ($events as $event): ?>
                      <option
                        value="<?= (int)$event['id'] ?>"
                        data-price="<?= htmlspecialchars($event['prix']) ?>"
                        <?= ((int)$event['id'] === (int)$reservationData['event_id']) ? 'selected' : '' ?>
                      >
                        <?= htmlspecialchars($event['titre']) ?>
                        (<?= date('d/m/Y', strtotime($event['date_event'])) ?> -
                        <?= number_format((float)$event['prix'], 2, ',', ' ') ?> €)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-field">
                  <label for="nombre_places">Nombre de places *</label>
                  <input type="number" id="nombre_places" name="nombre_places"
                         class="form-control" min="1" required
                         value="<?= (int)$reservationData['nombre_places'] ?>">
                </div>

                <div class="form-field">
                  <label>Montant total</label>
                  <div class="money-box">
                    💰 <span id="montant_total"><?= number_format((float)$reservationData['montant_total'], 2, ',', ' ') ?></span> €
                    <input type="hidden" id="montant_total_input" name="montant_total" value="<?= htmlspecialchars($reservationData['montant_total']) ?>">
                  </div>
                </div>
              </div>
            </div>

            <!-- 📊 Statut & paiement -->
            <div class="form-card">
              <div class="form-title">📊 Statut et Paiement</div>
              <p class="form-sub">Mettez à jour le statut et la méthode de paiement.</p>

              <div class="form-row">
                <div class="form-field">
                  <label for="statut">Statut *</label>
                  <select id="statut" name="statut" class="form-control" required>
                    <option value="en attente" <?= ($reservationData['statut'] === 'en attente') ? 'selected' : '' ?>>En attente</option>
                    <option value="confirmée" <?= ($reservationData['statut'] === 'confirmée') ? 'selected' : '' ?>>Confirmée</option>
                    <option value="annulée" <?= ($reservationData['statut'] === 'annulée') ? 'selected' : '' ?>>Annulée</option>
                  </select>
                </div>

                <div class="form-field">
                  <label for="methode_paiement">Méthode de paiement</label>
                  <select id="methode_paiement" name="methode_paiement" class="form-control">
                    <option value="carte" <?= ($reservationData['methode_paiement'] === 'carte') ? 'selected' : '' ?>>Carte bancaire</option>
                    <option value="especes" <?= ($reservationData['methode_paiement'] === 'especes') ? 'selected' : '' ?>>Espèces</option>
                    <option value="cheque" <?= ($reservationData['methode_paiement'] === 'cheque') ? 'selected' : '' ?>>Chèque</option>
                    <option value="virement" <?= ($reservationData['methode_paiement'] === 'virement') ? 'selected' : '' ?>>Virement</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-field" style="flex:1 1 100%;">
                  <label for="notes">Notes (optionnel)</label>
                  <textarea id="notes" name="notes" class="form-control" rows="3"><?= htmlspecialchars($reservationData['notes'] ?? '') ?></textarea>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="actions-row">
              <button type="submit" class="btn-nav">✅ Enregistrer les modifications</button>
              <a href="?action=reservations" class="btn-nav secondary">↩️ Annuler</a>

              <a href="process_reservation.php?action=delete&id=<?= (int)$reservationData['id'] ?>"
                 class="btn-nav danger push-right"
                 onclick="return confirm('Supprimer définitivement cette réservation ?')">
                🗑️ Supprimer
              </a>
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

    if (montantTotal) montantTotal.textContent = total.toFixed(2).replace('.', ',');
    if (montantTotalInput) montantTotalInput.value = total.toFixed(2);
  }

  if (eventSelect && placesInput) {
    eventSelect.addEventListener('change', calculateTotal);
    placesInput.addEventListener('input', calculateTotal);
  }

  // Validation du formulaire
  const form = document.querySelector('.reservation-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.style.borderColor = '#b02222';
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
