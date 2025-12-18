<?php
/**
 * Interface de scan de tickets
 * Permet de valider les tickets à l'entrée des événements
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Scanner de Tickets</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Polices (identique) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ✅ STYLE SPARKMIND (identique aux autres pages) */
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
    }

    .btn-nav.secondary{
      background:transparent;
      color:#1A464F;
      border:1px solid rgba(26, 70, 79, 0.35);
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

    .form-card{
      background:#fff7ef;
      border-radius:18px;
      padding:14px 14px 16px;
      box-shadow:0 8px 18px rgba(0,0,0,0.08);
      margin-bottom:14px;
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
      padding:10px 12px;
      border-radius:12px;
      border:1px solid #ccc;
      font-size:13px;
      outline:none;
      background:#fff;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    .form-control:focus{
      border-color:#1A464F;
      box-shadow:0 0 0 2px rgba(26,70,79,0.15);
    }

    .filters-bar{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      align-items:center;
      padding:10px 14px;
      border-radius:16px;
      background: rgba(255,255,255,0.9);
      box-shadow:0 8px 18px rgba(0,0,0,0.06);
      margin-bottom:18px;
      justify-content:space-between;
    }

    .stats-row{
      display:flex;
      flex-wrap:wrap;
      gap:12px;
      margin: 8px 0 0;
    }

    .stat-card{
      flex:1 1 160px;
      background: rgba(255,255,255,0.9);
      padding: 10px 12px;
      border-radius: 14px;
      box-shadow: 0 8px 18px rgba(0,0,0,0.08);
      font-size: 13px;
      border-left: 4px solid rgba(26,70,79,0.25);
    }
    .stat-card strong{ font-size: 18px; display:block; margin-bottom:2px; }
    .stat-card.success{ border-left-color:#1b6b2a; }
    .stat-card.danger{ border-left-color:#b02222; }

    /* ✅ Résultat validation (SPARKMIND look) */
    .validation-result{
      padding: 12px 14px;
      border-radius: 16px;
      background: rgba(255,255,255,0.9);
      box-shadow: 0 8px 18px rgba(0,0,0,0.06);
      margin-bottom: 14px;
      font-size: 13px;
      color:#444;
      animation: slideIn .25s ease;
    }

    @keyframes slideIn{
      from{ opacity:0; transform: translateY(-8px); }
      to{ opacity:1; transform: translateY(0); }
    }

    .validation-result.success{ border-left: 4px solid #1b6b2a; }
    .validation-result.error{ border-left: 4px solid #b02222; }
    .validation-result.warning{ border-left: 4px solid #8a4b00; }

    .result-header{
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom:8px;
      font-size:14px;
      font-weight:700;
      color:#1A464F;
    }

    .result-icon{ font-size:18px; }

    .result-details{
      margin-top:10px;
      padding-top:10px;
      border-top:1px solid rgba(0,0,0,0.08);
    }

    .result-details p{ margin:6px 0; }

    @media (max-width: 900px) { .sidebar{ width:220px; } }
    @media (max-width: 800px) {
      .sidebar{ position:relative; height:auto; }
      .layout{ flex-direction:column; }
      .filters-bar{ flex-direction:column; align-items:stretch; }
      .stats-row{ flex-direction:column; }
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
      Gestion des tickets
    </div>

    <nav class="menu">
      <div class="menu-title">📊 Dashboard admin</div>

      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_dashboard">📊 Tableau de bord</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=events_list">📅 Événements</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservations_list">🎫 Réservations</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=reservation_create">➕ Nouvelle Réservation</a>
      <a class="menu-item" href="/sparkmind_mvc_100percent/index.php?page=event_create">✨ Nouvel Événement</a>
      <a class="menu-item active" href="/sparkmind_mvc_100percent/index.php?page=scanner">📷 Scanner</a>
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
            <span class="brand-tagline">Scanner de tickets</span>
          </div>
        </div>

        <div class="header-actions">
          <a class="btn-nav secondary" href="?action=main">🏠 Espace utilisateur</a>
          <a class="btn-nav" href="?action=logout">🚪 Déconnexion</a>
        </div>
      </header>

      <main class="admin-main">
        <section class="admin-card">

          <h1>🎫 Scanner de Tickets</h1>
          <p class="admin-subtitle">Scannez ou saisissez le code du ticket pour le valider.</p>

          <!-- Formulaire de validation -->
          <div class="form-card">
            <form id="ticketValidationForm" onsubmit="validateTicket(event)">
              <div class="form-field" style="margin-bottom:10px;">
                <label for="ticketCode">Code du ticket</label>
                <input
                  type="text"
                  id="ticketCode"
                  name="ticket_code"
                  class="form-control"
                  placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx.signature"
                  required
                  autofocus
                >
              </div>

              <button type="submit" class="btn-nav" style="width:100%;">
                🔍 Valider le Ticket
              </button>
            </form>
          </div>

          <!-- Zone de résultat -->
          <div id="validationResult" class="validation-result" style="display:none;"></div>

          <!-- Statistiques -->
          <div class="filters-bar">
            <div style="font-weight:700;">Statistiques en temps réel</div>
            <div style="font-size:12px; color:#555;">Mise à jour automatique toutes les 10s</div>
          </div>

          <div class="stats-row">
            <div class="stat-card">
              <strong id="statTotal">-</strong>
              Total tickets
            </div>
            <div class="stat-card">
              <strong id="statIssued">-</strong>
              Émis
            </div>
            <div class="stat-card success">
              <strong id="statUsed">-</strong>
              Utilisés
            </div>
            <div class="stat-card danger">
              <strong id="statCancelled">-</strong>
              Annulés
            </div>
          </div>

        </section>
      </main>

    </div>
  </div>
</div>

<script>
// Charger les statistiques au démarrage
document.addEventListener('DOMContentLoaded', function() {
  loadStats();
  setInterval(loadStats, 10000);
});

// Fonction de validation de ticket
async function validateTicket(event) {
  event.preventDefault();

  const form = event.target;
  const ticketCode = form.ticket_code.value.trim();
  const resultDiv = document.getElementById('validationResult');

  resultDiv.style.display = 'block';
  resultDiv.className = 'validation-result';
  resultDiv.innerHTML = '<div style="text-align:center">⏳ Validation en cours...</div>';

  try {
    const formData = new FormData();
    formData.append('ticket_code', ticketCode);

    const response = await fetch('/sparkmind_mvc_100percent/api/ticket_operations.php?action=validate', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.success) {
      resultDiv.className = 'validation-result success';
      resultDiv.innerHTML = `
        <div class="result-header">
          <span class="result-icon">✅</span>
          <span>TICKET VALIDE</span>
        </div>
        <div class="result-details">
          <p><strong>Client:</strong> ${data.data.reservation.nom_client}</p>
          <p><strong>Événement:</strong> ${data.data.reservation.event_titre}</p>
          <p><strong>Places:</strong> ${data.data.reservation.nombre_places}</p>
          <p><strong>Référence:</strong> ${data.data.reservation.reference}</p>
          <p><strong>Validé à:</strong> ${new Date().toLocaleString('fr-FR')}</p>
        </div>
      `;

      loadStats();
      form.reset();
      // playSound('success');
    } else {
      let className = 'error';
      let icon = '❌';

      if (data.status === 'ALREADY_USED') {
        className = 'warning';
        icon = '⚠️';
      }

      resultDiv.className = 'validation-result ' + className;
      resultDiv.innerHTML = `
        <div class="result-header">
          <span class="result-icon">${icon}</span>
          <span>${data.status}</span>
        </div>
        <p style="margin:0;">${data.message}</p>
      `;

      // playSound('error');
    }
  } catch (error) {
    resultDiv.className = 'validation-result error';
    resultDiv.innerHTML = `
      <div class="result-header">
        <span class="result-icon">❌</span>
        <span>ERREUR</span>
      </div>
      <p style="margin:0;">Erreur lors de la validation: ${error.message}</p>
    `;
  }
}

// Charger les statistiques
async function loadStats() {
  try {
    const response = await fetch('/sparkmind_mvc_100percent/api/ticket_operations.php?action=stats');
    const data = await response.json();

    if (data.success) {
      document.getElementById('statTotal').textContent = data.data.total || 0;
      document.getElementById('statIssued').textContent = data.data.issued || 0;
      document.getElementById('statUsed').textContent = data.data.used || 0;
      document.getElementById('statCancelled').textContent = data.data.cancelled || 0;
    }
  } catch (error) {
    console.error('Erreur lors du chargement des stats:', error);
  }
}

// Support du scan par lecteur de code-barres
document.getElementById('ticketCode').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    document.getElementById('ticketValidationForm').dispatchEvent(new Event('submit'));
  }
});
</script>
</body>
</html>
