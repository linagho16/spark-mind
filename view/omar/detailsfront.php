<?php
require_once __DIR__ . '/../../controller/produitC.php';
require_once __DIR__ . '/../../controller/categorieC.php';
require_once __DIR__ . '/../../model/produit.php';

$produitC = new ProduitC();
$categorieC = new CategorieC();
$message = '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    $message = "Identifiant du produit manquant.";
} else {
    try {
        $produit = $produitC->showProduit($id);
        if (!$produit) {
            $message = "Produit introuvable.";
        } else {
            $cat = $categorieC->showCategorie($produit['category']);
            $nomCategorie = $cat ? $cat['nomC'] : 'Inconnue';
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

/* ✅ URL image (1 seule logique, 1 seule image affichée) */
$photoUrl = '/sparkmind_mvc_100percent/view/omar/logo.png'; // fallback

if (!empty($produit) && !empty($produit['photo'])) {
    $p = ltrim($produit['photo'], '/');

    if (preg_match('#^https?://#', $p)) {
        $photoUrl = $p;
    } else {
        // En DB on doit stocker : uploads/xxxx.jpg (ou uploads/dossier/xxxx.jpg)
        $photoUrl = '/sparkmind_mvc_100percent/' . $p;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Détails du Produit</title>
    <link rel="stylesheet" href="formlaire.css">

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
  }

  *{ box-sizing:border-box; }
  body{
    margin:0;
    font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background:var(--bg);
    color:var(--text);
  }

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

  .sidebar-footer{ margin-top:18px; }
  .info-box{
    border-radius:16px;
    padding:14px 14px;
    background:var(--card);
    box-shadow: 0 12px 26px rgba(0,0,0,.12);
  }
  .info-box h4{ margin:0 0 6px 0; font-size:14px; }
  .info-box p{ margin:0; color:var(--muted); font-size:12px; }

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
  }

  @keyframes navFade { from {opacity:0; transform:translateY(-16px);} to {opacity:1; transform:translateY(0);} }

  .detail-container{
    margin-top: 16px;
    border-radius:24px;
    overflow:hidden;
    background:#f5f5f5;
    box-shadow: 0 18px 40px rgba(96,84,84,.18);
    position:relative;
  }

  .detail-grid{
    position:relative;
    z-index:1;
    display:grid;
    grid-template-columns: 1.05fr .95fr;
    gap:0;
  }

  .product-image-section{
    background: rgba(255,255,255,.55);
    display:flex;
    align-items:center;
    justify-content:center;
    min-height: 520px;
  }

  /* ✅ classe unique pour l'image */
  .product-image{
    width:100%;
    height:100%;
    min-height:520px;
    object-fit:cover;
    display:block;
  }

  .product-info-section{
    padding: 22px 22px;
    display:flex;
    flex-direction:column;
    gap:12px;
  }

  .product-category-badge{
    align-self:flex-start;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    background: rgba(125,90,166,.18);
    color:#1a0f22;
    border:1px solid rgba(0,0,0,.05);
    box-shadow: 0 6px 14px rgba(0,0,0,.10);
  }

  .product-title{
    margin:0;
    font-family:'Playfair Display', serif;
    font-size:28px;
    color:#02282f;
    line-height:1.15;
  }

  .product-meta{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    padding: 10px 0 12px;
    border-bottom:1px solid rgba(0,0,0,.06);
  }

  .meta-item{
    display:flex;
    align-items:center;
    gap:8px;
    padding:8px 10px;
    border-radius:16px;
    background: rgba(255,255,255,.55);
    border:1px solid rgba(0,0,0,.05);
    box-shadow: 0 10px 20px rgba(0,0,0,.10);
    font-size:13px;
    color:#02282f;
  }

  #qrcode{
    border-radius:16px;
    background: rgba(255,255,255,.7);
    padding:10px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow: 0 10px 20px rgba(0,0,0,.10);
  }

  .product-description{
    margin-top: 6px;
    border-radius:18px;
    background: var(--card);
    border:1px solid rgba(0,0,0,.04);
    box-shadow: 0 12px 26px rgba(0,0,0,.14);
    padding: 14px 14px;
    color: rgba(26,70,79,.85);
    line-height:1.75;
    font-size:14px;
  }

  .action-buttons{
    margin-top:auto;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    padding-top: 10px;
  }

  .btn-contact{
    flex:1;
    background: var(--orange);
    color:#fff;
    padding:10px 16px;
    border-radius:999px;
    text-decoration:none;
    font-weight:800;
    text-align:center;
    box-shadow: 0 8px 18px rgba(236,117,70,.45);
  }

  .btn-back{
    padding:10px 16px;
    border-radius:999px;
    text-decoration:none;
    font-weight:800;
    color: var(--text);
    background: rgba(255,255,255,.7);
    border:1px solid rgba(0,0,0,.08);
    box-shadow: 0 8px 18px rgba(0,0,0,.10);
  }

  .error-state{
    margin-top: 16px;
    border-radius:24px;
    background: var(--card);
    box-shadow: 0 18px 40px rgba(96,84,84,.18);
    padding: 50px 22px;
    text-align:center;
    border-left: 6px solid var(--danger);
  }

  @media (max-width: 1024px){
    .detail-grid{ grid-template-columns: 1fr; }
    .product-image, .product-image-section{ min-height: 380px; }
  }

  @media (max-width: 980px){
    .sidebar{ position:sticky; width:auto; height:auto; border-right:none; }
    .main-content{ margin-left:0; }
  }
</style>
</head>

<body>
    <aside class="sidebar">
        <div class="logo">
            <h2>SparkMind</h2>
            <p>« Quand la pensée devient espoir. »</p>
        </div>

        <nav class="nav-menu">
            <a href="/sparkmind_mvc_100percent/index.php?page=produits" class="nav-item">
                <span>🏠</span><span>Accueil</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=liste_produits" class="nav-item">
                <span>📦</span><span>Produits</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=ajouter_produit" class="nav-item">
                <span>➕</span><span>Ajouter un Produit</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=produits" class="nav-item">
                <span></span><span>Retour</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="info-box">
                <h4>Besoin d'aide ?</h4>
                <p>Contactez notre équipe pour toute question</p>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div>
                <h1>Détails du Produit</h1>
                <p class="subtitle">Découvrez les informations complètes</p>
            </div>
            <div class="header-actions">
                <button class="btn-help" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=produits'">← Retour</button>
            </div>
        </header>

        <?php if (!empty($message)): ?>
            <div class="error-state">
                <div style="font-size: 4rem; margin-bottom: 20px;">⚠️</div>
                <h3>Oups !</h3>
                <p><?= htmlspecialchars($message) ?></p>
                <button class="btn-contact" onclick="window.location.href='/sparkmind_mvc_100percent/index.php?page=produits'" style="display:inline-block;width:auto;">
                    Retourner à l'accueil
                </button>
            </div>

        <?php elseif (!empty($produit)): ?>
            <div class="detail-container">
                <div class="detail-grid">

                    <div class="product-image-section">
                        <img
                            src="<?= htmlspecialchars($photoUrl) ?>"
                            alt="<?= htmlspecialchars($produit['title']) ?>"
                            class="product-image"
                            onerror="this.onerror=null; this.src='/sparkmind_mvc_100percent/view/omar/logo.png';"
                        >
                    </div>

                    <div class="product-info-section">
                        <span class="product-category-badge"><?= htmlspecialchars($nomCategorie) ?></span>

                        <h2 class="product-title"><?= htmlspecialchars($produit['title']) ?></h2>

                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-icon">✨</span>
                                <span><strong>État:</strong> <?= htmlspecialchars($produit['condition']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">📍</span>
                                <span><strong>Statut:</strong> <?= htmlspecialchars($produit['statut']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">📅</span>
                                <span>Ajouté récemment</span>
                            </div>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <img id="qrcode" alt="QR Code" style="max-width: 150px;">
                        </div>

                        <div class="product-description">
                            <h3 style="color:#0a6661;margin-bottom:15px;font-size:1.2em;">Description</h3>
                            <?= nl2br(htmlspecialchars($produit['description'])) ?>
                        </div>

                        <div class="action-buttons">
                            <a href="#" class="btn-contact">💬 Contacter le donneur</a>
                            <a href="/sparkmind_mvc_100percent/index.php?page=produits" class="btn-back">Retour</a>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <div style="text-align:center;padding:40px 0;color:#666;margin-top:40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (!empty($produit)): ?>
        <?php
            $pId = $id;
            $pTitle = $produit['title'];
            $pCat = isset($nomCategorie) ? $nomCategorie : '';
            $pCond = isset($produit['condition']) ? $produit['condition'] : '';
            $pStat = isset($produit['statut']) ? $produit['statut'] : '';

            $data = "ID:$pId\nTitre: $pTitle\nCat: $pCat\nCond: $pCond\nStat: $pStat";
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);
        ?>
        var qrImg = document.getElementById("qrcode");
        if (qrImg) qrImg.src = "<?= $qrUrl ?>";
        <?php endif; ?>
    });
    </script>
</body>
</html>
