<?php
// Vue backoffice d'accueil admin (AdminController::home)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>SPARKMIND — Back Office (Admin)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css" />
</head>

<body>
  <div class="layout">
    
    <aside class="sidebar">
      <a class="brand" href="index.php?page=main" title="Retour au site">
        <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo" />
        <span class="brand-name">SPARKMIND</span>
      </a>

      <nav class="menu">
        <a class="item active" data-section="dashboard">
          <span>Tableau de bord</span>
        </a>
        <a class="item" href="index.php?page=admin_users">
          <span>Utilisateurs</span>
        </a>
        <a class="item" href="index.php?page=admin_notifications">
          <span>Notifications</span>
        </a>
      </nav>


      <div class="sidebar-foot">
        <a class="link" href="index.php?page=front">← Front Office</a>
      </div>
    </aside>

    <!-- ✅ plus de "*/main" -->
    <main class="main">
      
      <header class="topbar">
        <div class="search">
          <input type="text" placeholder="Rechercher (utilisateurs, posts, id…)" id="globalSearch">
          <button id="searchBtn" aria-label="Rechercher">🔍</button>
        </div>
        <div class="actions">
          <button class="pill ghost" id="themeToggle" title="Basculer thème">🌓</button>
          <a class="pill" href="index.php?page=logout">Déconnexion</a>
        </div>
      </header>

      <!-- ✅ plus de "*/Dashboard" -->

      <section class="section show" id="dashboard">
        <h1 class="title">Tableau de bord</h1>

        <div class="kpis">
          <div class="kpi">
            <span class="kpi-label">Utilisateurs</span>
            <span class="kpi-value" id="kpiUsers">1 248</span>
          </div>
          <div class="kpi">
            <span class="kpi-label">Demandes actives</span>
            <span class="kpi-value" id="kpiAsks">132</span>
          </div>
          <div class="kpi">
            <span class="kpi-label">Offres actives</span>
            <span class="kpi-value" id="kpiOffers">98</span>
          </div>
          <div class="kpi">
            <span class="kpi-label">Signalements ouverts</span>
            <span class="kpi-value warn" id="kpiReports">7</span>
          </div>
        </div>

        <div class="panel">
          <div class="panel-head">
            <h2>Activité récente</h2>
            <div class="filters">
              <select>
                <option>Aujourd’hui</option>
                <option>7 derniers jours</option>
                <option>30 jours</option>
              </select>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Type</th><th>Titre</th><th>Par</th><th>Date</th><th>État</th><th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="badge turquoise">Demande</span></td>
                  <td>Besoin de soutien scolaire</td>
                  <td>@lina</td>
                  <td>12/11/2025 09:12</td>
                  <td><span class="status pending">En attente</span></td>
                  <td><button class="btn tiny" data-open="contentModal">Examiner</button></td>
                </tr>
                <tr>
                  <td><span class="badge violet">Offre</span></td>
                  <td>Don de vêtements hiver</td>
                  <td>@amine</td>
                  <td>12/11/2025 08:44</td>
                  <td><span class="status ok">Publié</span></td>
                  <td><button class="btn tiny alt">Masquer</button></td>
                </tr>
                <tr>
                  <td><span class="badge orange">Anonyme</span></td>
                  <td>J’ai besoin de parler</td>
                  <td>—</td>
                  <td>11/11/2025 23:05</td>
                  <td><span class="status review">À modérer</span></td>
                  <td><button class="btn tiny" data-open="contentModal">Modérer</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- (tout le reste de tes sections: users, help-asks, help-offers, anonymous, reports, content, messages, settings)
           je ne les recopie pas ici, tu peux garder le même code, sans les "*/..." -->

      <!-- … ton code existant pour les autres <section> … -->

    </main>
  </div>

  <!-- Modales -->
  <dialog class="modal" id="userModal">
    <!-- … inchangé … -->
  </dialog>

  <dialog class="modal" id="contentModal">
    <!-- … inchangé … -->
  </dialog>

  <script src="admin.js"></script>
</body>
</html>
