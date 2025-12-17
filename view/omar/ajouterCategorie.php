<?php
require_once 'controller/CategorieC.php';
require_once 'model/categorie.php';

$categorieC = new CategorieC();

$error = null;
$success = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des données
        if (empty($_POST['nomC']) || empty($_POST['descriptionC']) || 
            empty($_POST['dateC']) || empty($_POST['nom_Createur'])) {
            $error = "Tous les champs obligatoires doivent être remplis !";
        } else {
            // Créer l'objet catégorie
            $categorie = new Categorie(
                null,
                $_POST['nomC'],
                $_POST['descriptionC'],
                $_POST['dateC'],
                $_POST['nom_Createur']
            );

            // Ajouter la catégorie
            $success = $categorieC->addCategorie($categorie);
        }
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Ajouter une Catégorie</title>
    <link rel="stylesheet" href="view/front office/formlaire.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<style>
  :root{
    --orange:#ec7546;
    --turquoise:#1f8c87;
    --violet:#7d5aa6;

    --bg:#fbedD7;
    --card:#FFF7EF;
    --text:#1A464F;
    --muted:rgba(26,70,79,.75);
    --danger:#d64545;
    --success:#198754;
  }

  *{ box-sizing:border-box; }
  body{
    margin:0;
    font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background:var(--bg);
    color:var(--text);
  }

  /* Layout global */
  .sidebar{
    position:fixed;
    left:0; top:0;
    width:280px;
    height:100vh;
    padding:18px 16px;
    background:rgba(255,247,239,.92);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-right:1px solid rgba(0,0,0,.04);
    box-shadow: 0 18px 40px rgba(96,84,84,.12);
    overflow:auto;
  }

  .main-content{
    margin-left:280px;
    min-height:100vh;
    padding: 18px 22px 60px;
  }

  /* Sidebar */
  .sidebar .logo h2{
    margin:0;
    font-family:'Playfair Display', serif;
    letter-spacing:1px;
    text-transform:uppercase;
    font-size:22px;
    color:#1A464F;
    text-shadow: 0 4px 16px rgba(125,90,166,.25);
  }
  .sidebar .logo p{
    margin:6px 0 16px 0;
    color:var(--muted);
    font-size:12px;
  }

  .nav-menu{ display:flex; flex-direction:column; gap:10px; margin-top:10px; }
  .nav-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:14px;
    text-decoration:none;
    color:var(--text);
    background:rgba(255,255,255,.55);
    border:1px solid rgba(0,0,0,.03);
    box-shadow: 0 6px 14px rgba(0,0,0,.08);
    transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
  }
  .nav-item:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,.14);
    filter: brightness(1.02);
  }
  .nav-item.active{
    background: linear-gradient(135deg, rgba(125,90,166,.22), rgba(236,117,70,.22), rgba(31,140,135,.22));
    border-color: rgba(0,0,0,.04);
  }

  .sidebar-footer{ margin-top:18px; }
  .info-box{
    border-radius:16px;
    padding:14px 14px;
    background:var(--card);
    box-shadow: 0 12px 26px rgba(0,0,0,.12);
  }
  .info-box h4{ margin:0 0 6px 0; font-size:14px; }
  .info-box p{ margin:0; color:var(--muted); font-size:12px; }

  /* Header sticky */
  .header{
    position: sticky;
    top: 0;
    z-index: 50;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 12px 18px;
    border-radius:18px;
    background: rgba(251, 237, 215, 0.96);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 12px 26px rgba(0,0,0,0.10);
    animation: navFade 0.7s ease-out;
  }
  .header::after{
    content:"";
    position:absolute;
    inset:auto 30px -2px 30px;
    height:2px;
    background:linear-gradient(90deg,var(--violet),var(--orange),var(--turquoise));
    opacity:.35;
    border-radius:999px;
  }
  .header h1{
    margin:0;
    font-family:'Playfair Display', serif;
    font-size:26px;
    letter-spacing:.4px;
  }
  .subtitle{
    margin:4px 0 0 0;
    font-size:13px;
    color:var(--muted);
  }

  .btn-help{
    background: var(--orange);
    color:#fff;
    border:none;
    border-radius:999px;
    padding:8px 18px;
    font-size:14px;
    cursor:pointer;
    box-shadow: 0 8px 18px rgba(236, 117, 70, 0.45);
    display:inline-flex;
    align-items:center;
    gap:6px;
    position:relative;
    overflow:hidden;
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
  }
  .btn-help::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,rgba(255,255,255,.35),transparent 60%);
    transform:translateX(-120%);
    transition:transform .4s ease;
  }
  .btn-help:hover::before{ transform:translateX(20%); }
  .btn-help:hover{
    transform: translateY(-2px) scale(1.03);
    filter: brightness(1.05);
    box-shadow: 0 10px 24px rgba(236, 117, 70, 0.55);
  }

  @keyframes navFade { from {opacity:0; transform:translateY(-16px);} to {opacity:1; transform:translateY(0);} }

  /* Notifications */
  .notification{
    border-radius:18px;
    padding: 12px 14px;
    margin: 14px 0 12px;
    box-shadow: 0 12px 26px rgba(0,0,0,.14);
    border: 1px solid rgba(0,0,0,.05);
    background: var(--card);
  }
  .notification.error{ border-left: 6px solid var(--danger); }
  .notification.success{ border-left: 6px solid var(--success); }

  /* Form container (hero look) */
  .form-container{
    margin-top: 14px;
    border-radius:24px;
    background:#f5f5f5;
    box-shadow: 0 18px 40px rgba(96,84,84,.18);
    overflow:hidden;
    position:relative;
  }
  .form-container::before,
  .form-container::after{
    content:"";
    position:absolute;
    border-radius:999px;
    filter:blur(18px);
    opacity:.55;
    mix-blend-mode:screen;
    animation: floatBlob 10s ease-in-out infinite alternate;
    pointer-events:none;
  }
  .form-container::before{
    width:140px; height:140px;
    top:-50px; left:18px;
    background:rgba(127, 71, 192, 0.6);
  }
  .form-container::after{
    width:190px; height:190px;
    bottom:-70px; right:10px;
    background:rgba(31,140,135,.7);
    animation-delay:-4s;
  }
  @keyframes floatBlob{ from{transform:translateY(0) translateX(0);} to{transform:translateY(16px) translateX(-8px);} }

  form{ padding: 18px 18px 6px; }

  /* Sections */
  .form-section{
    background: var(--card);
    border-radius: 18px;
    padding: 16px 16px;
    margin: 14px 0;
    box-shadow: 0 12px 26px rgba(0,0,0,.18);
    border: 1px solid rgba(0,0,0,.04);
    position:relative;
    overflow:hidden;
  }
  .section-header{
    display:flex;
    align-items:flex-start;
    gap:12px;
    margin-bottom: 12px;
  }
  .section-icon{
    width:42px;
    height:42px;
    display:grid;
    place-items:center;
    border-radius:14px;
    background: rgba(251,237,215,.95);
    box-shadow: 0 8px 18px rgba(0,0,0,.10);
    border:1px solid rgba(0,0,0,.04);
    font-size:18px;
  }
  .section-title{
    margin:0;
    font-family:'Playfair Display', serif;
    font-size:20px;
    color:#02282f;
  }
  .section-description{
    margin:2px 0 0 0;
    color:var(--muted);
    font-size:13px;
  }

  /* Grid */
  .form-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px 12px;
    margin-top: 12px;
  }
  .form-group{ display:flex; flex-direction:column; gap:6px; }
  .form-group.full-width{ grid-column: 1 / -1; }

  label{
    font-size:13px;
    font-weight:600;
    color:#02282f;
  }
  .required{ color: var(--orange); }

  input[type="text"],
  input[type="date"],
  select,
  textarea{
    width:100%;
    border-radius:14px;
    border:1px solid rgba(0,0,0,.08);
    background: rgba(255,255,255,.7);
    padding: 11px 12px;
    font-size:14px;
    outline:none;
    transition: box-shadow .18s ease, border-color .18s ease;
  }
  textarea{ min-height: 120px; resize: vertical; }

  input:focus, select:focus, textarea:focus{
    border-color: rgba(31,140,135,.45);
    box-shadow: 0 0 0 4px rgba(31,140,135,.16);
  }

  /* Attestation */
  .attestation-box{
    margin-top: 10px;
    border-radius:18px;
    background: rgba(255,255,255,.55);
    border:1px solid rgba(0,0,0,.05);
    box-shadow: 0 10px 20px rgba(0,0,0,.10);
    padding: 14px 14px;
  }
  .checkbox-special{
    display:flex;
    align-items:flex-start;
    gap:10px;
    cursor:pointer;
    color:#02282f;
    font-weight:600;
    font-size:13px;
  }
  .checkbox-special input{ margin-top:3px; accent-color: var(--orange); }

  .error-message{
    color: var(--danger);
    font-size:12px;
    min-height: 14px;
  }

  /* Buttons */
  .form-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top: 14px;
  }

  .btn-primary{
    background: var(--orange);
    color:#fff;
    border:none;
    border-radius:999px;
    padding:10px 18px;
    font-weight:800;
    cursor:pointer;
    box-shadow: 0 8px 18px rgba(236,117,70,.45);
    position:relative;
    overflow:hidden;
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
  }
  .btn-primary::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,rgba(255,255,255,.35),transparent 60%);
    transform:translateX(-120%);
    transition:transform .4s ease;
  }
  .btn-primary:hover::before{ transform:translateX(20%); }
  .btn-primary:hover{
    transform: translateY(-2px) scale(1.03);
    filter: brightness(1.05);
    box-shadow: 0 10px 24px rgba(236,117,70,.55);
  }

  .btn-secondary{
    background: rgba(255,255,255,.7);
    color: var(--text);
    border: 1px solid rgba(0,0,0,.08);
    border-radius:999px;
    padding:10px 18px;
    font-weight:800;
    cursor:pointer;
    box-shadow: 0 8px 18px rgba(0,0,0,.10);
    transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
  }
  .btn-secondary:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 22px rgba(0,0,0,.14);
    filter: brightness(1.02);
  }

  /* Responsive */
  @media (max-width: 980px){
    .sidebar{ position:sticky; width:auto; height:auto; border-right:none; }
    .main-content{ margin-left:0; }
  }
  @media (max-width: 860px){
    .form-grid{ grid-template-columns: 1fr; }
    .header h1{ font-size:22px; }
  }
</style>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>SparkMind</h2>
            <p>« Quand la pensée devient espoir. »</p>
        </div>
        
        <nav class="nav-menu">
            <a href="/sparkmind_mvc_100percent/index.php?page=produits" class="nav-item">
                <span>🏠</span>
                <span>Accueil</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=liste_produits" class="nav-item">
                <span>📦</span>
                <span>Produits</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=ajouter_produit" class="nav-item">
                <span>➕</span>
                <span>Ajouter un don</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=ajouter_categorie" class="nav-item active">
                <span>🏷️</span>
                <span>Nouvelle Catégorie</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=ajouter_produit" class="nav-item">
                <span>👤</span>
                <span>Retour</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="info-box">
                <h4>Besoin d'aide ?</h4>
                <p>Contactez notre équipe pour toute question</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div>
                <h1>Ajouter une Catégorie</h1>
                <p class="subtitle">Organisez vos produits efficacement</p>
            </div>
            <button class="btn-help" onclick="window.location.href='index.php?page=offer_support'">
                <span>❓</span>
                <span>Besoin d'aide</span>
        </header>

        <!-- Notifications -->
        <?php if ($error): ?>
            <div class="notification error show">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="notification success show">
                ✅ <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Form Container -->
        <div class="form-container">
            <form id="categoryForm" method="POST" novalidate>
                
                <!-- Section 1: Informations de la Catégorie -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">📝</div>
                        <div>
                            <h2 class="section-title">Informations de la Catégorie</h2>
                            <p class="section-description">Définissez les détails de la catégorie</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Nom de la catégorie <span class="required">*</span></label>
                            <input type="text" id="nomC" name="nomC" placeholder="Ex: Électronique, Vêtements, Livres...">
                            <span class="error-message" id="nomC-error"></span>
                        </div>

                        <div class="form-group full-width">
                            <label>Description <span class="required">*</span></label>
                            <textarea id="descriptionC" name="descriptionC" placeholder="Décrivez brièvement cette catégorie..."></textarea>
                            <span class="error-message" id="descriptionC-error"></span>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Informations Complémentaires -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">ℹ️</div>
                        <div>
                            <h2 class="section-title">Informations Complémentaires</h2>
                            <p class="section-description">Détails administratifs</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Date de création <span class="required">*</span></label>
                            <input type="date" id="dateC" name="dateC" value="<?= date('Y-m-d') ?>">
                            <span class="error-message" id="dateC-error"></span>
                        </div>

                        <div class="form-group">
                            <label>Nom du créateur <span class="required">*</span></label>
                            <input type="text" id="nom_Createur" name="nom_Createur" placeholder="Ex: Admin, Votre nom...">
                            <span class="error-message" id="nom_Createur-error"></span>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Validation -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">✓</div>
                        <div>
                            <h2 class="section-title">Validation</h2>
                            <p class="section-description">Confirmez la création</p>
                        </div>
                    </div>

                    <div class="attestation-box">
                        <label class="checkbox-special">
                            <input type="checkbox" id="attestation" name="attestation">
                            <span>J'atteste que les informations fournies sont exactes.</span>
                        </label>
                        <span class="error-message" id="attestation-error"></span>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='view/front office/ajouterProduit.php'">Annuler</button>
                        <button type="submit" class="btn-primary">🚀 Ajouter la catégorie</button>
                    </div>
                </section>
            </form>
        </div>
    </main>
    
    <script src="controle_saisie.js"></script>
</body>
</html>
