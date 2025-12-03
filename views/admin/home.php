

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

  <style>
    .kpi {
        width: 1000px;      /* 🔥 taille du conteneur */
        height: 500px;     /* 🔥 hauteur */
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;

        /* Optionnel si tu veux un cadre stylé */
        background: rgba(255,255,255,0.7);
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .kpi-icon {
        width: 500px;  
            /* 🔥 taille de l'image */
        height: auto;
        object-fit: contain;
        border-radius: 12px; /* Optionnel */
    }
</style>

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
          <span>Frontoffice</span>
        </a>
        <a class="item" href="index.php?page=admin_users">
          <span>Utilisateurs</span>
        </a>
        <a class="item" href="index.php?page=admin_notifications">
          <span>Notifications</span>
        </a>
                <a class="item" href="index.php?page=admin_users">
          <span>🙍‍♀️🙍Demandeurs</span>
        </a>
        <a class="item" href="index.php?page=admin_notifications">
          <span>🎁Donneurs</span>
        </a>
        <a class="item" href="index.php?page=admin_notifications">
          <span>🗣Expressions/Messages</span>
        </a>
         <a class="item" href="index.php?page=admin_notifications">
          <span>📎Evenements</span>
        </a>


      </nav>


      <div class="sidebar-foot">
        <a class="link" href="index.php?page=front">← Front Office</a>
      </div>
    </aside>

    <!-- ✅ plus de "*/main" -->
    <main class="main">
      
      <header class="topbar">

        <div class="actions">
          <button class="pill ghost" id="themeToggle" title="Basculer thème">🌓</button>
          <a class="pill" href="index.php?page=logout">Déconnexion</a>
        </div>
      </header>

      <!-- ✅ plus de "*/Dashboard" -->

      <section class="section show" id="dashboard">
        <h1 class="title">Bienvenue</h1>

        <div class="kpis">
          <div class="kpi">
            <img src="images/image2.jpg" alt="Icône utilisateurs" class="kpi-icon">
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
