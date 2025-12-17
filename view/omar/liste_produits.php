<?php
require_once __DIR__ . '/../../controller/produitC.php';

$produitC = new ProduitC();
$produits = $produitC->listProduits();

$produitsDisponibles = array_filter($produits, function ($produit) {
    return strtolower($produit['statut']) === 'disponible';
});

$totalDisponibles = count($produitsDisponibles);
$categoriesDisponibles = [];

foreach ($produitsDisponibles as $produit) {
    $cat = $produit['category'] ?? 'Divers';
    if (!isset($categoriesDisponibles[$cat])) {
        $categoriesDisponibles[$cat] = 0;
    }
    $categoriesDisponibles[$cat]++;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Produits disponibles</title>
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
  }

  *{ box-sizing:border-box; }
  body{
    margin:0;
    font-family:'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background:var(--bg);
    color:var(--text);
  }

  /* ===== Layout global ===== */
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

  /* ===== Sidebar branding ===== */
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

  /* ===== Header sticky (top-nav vibe) ===== */
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

  /* ===== Stats section (même style cartes) ===== */
  .stats-section{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap:14px;
    margin: 18px 0 22px;
  }

  .stat-card{
    border-radius:18px;
    padding:16px 16px;
    background: var(--card);
    box-shadow: 0 12px 26px rgba(0,0,0,0.20);
    position:relative;
    overflow:hidden;
    text-align:left;
    transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
  }
  .stat-card::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:radial-gradient(circle at top left,rgba(255,255,255,.45),transparent 60%);
    opacity:.0;
    transition:opacity .25s ease;
  }
  .stat-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 16px 34px rgba(0,0,0,0.28);
    filter: brightness(1.02);
  }
  .stat-card:hover::before{ opacity:1; }

  .stat-card .stat-icon{
    font-size:28px;
    margin-bottom:8px;
  }
  .stat-card .stat-label{
    display:block;
    color:var(--muted);
    font-size:12px;
    letter-spacing:1px;
    text-transform:uppercase;
    font-weight:600;
    margin-bottom:6px;
  }
  .stat-card .stat-value{
    font-size:28px;
    font-weight:800;
    color:#02282f;
    font-family:'Playfair Display', serif;
  }

  /* ===== Products grid/cards ===== */
  .products-grid{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap:14px;
    margin: 18px 0 40px;
  }

  .product-card{
    position:relative;
    border-radius:18px;
    overflow:hidden;
    background: var(--card);
    box-shadow: 0 12px 26px rgba(0,0,0,0.25);
    display:flex;
    flex-direction:column;
    transition: transform .18s ease, box-shadow .18s ease;
  }
  .product-card::before{
    content:"";
    position:absolute;
    inset:-40%;
    background:radial-gradient(circle at top left,rgba(255,255,255,.4),transparent 60%);
    opacity:0;
    transition: opacity .25s ease;
    pointer-events:none;
  }
  .product-card:hover{
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 16px 34px rgba(0,0,0,0.35);
  }
  .product-card:hover::before{ opacity:1; }

  .product-card img{
    width:100%;
    height:220px;
    object-fit:cover;
    background:#f5f5f5;
    transition: transform .35s ease;
  }
  .product-card:hover img{ transform: scale(1.05); }

  .product-body{
    padding:14px 16px 16px;
    flex:1;
    display:flex;
    flex-direction:column;
    gap:8px;
  }

  .product-body h3{
    margin:0;
    font-size:16px;
    font-weight:800;
    color:#02282f;
  }

  .product-meta{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin:2px 0 4px;
  }

  .badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    border:1px solid rgba(0,0,0,.05);
    box-shadow: 0 6px 14px rgba(0,0,0,.10);
    background: rgba(251,237,215,.9);
    color:#1A464F;
  }

  .badge.category{
    background: rgba(125,90,166,.18);
    color:#1a0f22;
  }

  .badge.condition{
    background: rgba(236,117,70,.20);
    color:#2c130d;
  }

  .product-body p{
    margin:0;
    color: rgba(26,70,79,.80);
    font-size:14px;
    line-height:1.6;
    flex:1;
    display:-webkit-box;
    -webkit-line-clamp:3;
    -webkit-box-orient: vertical;
    overflow:hidden;
  }

  .product-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    padding-top:12px;
    border-top:1px solid rgba(0,0,0,.06);
  }

  .product-actions small{
    color:#0f7a50;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:8px;
    font-size:13px;
  }
  .product-actions small::before{
    content:'';
    width:10px;
    height:10px;
    background:#27ae60;
    border-radius:50%;
    box-shadow:0 0 0 2px rgba(39,174,96,.20);
  }

  .product-actions a{
    background: var(--orange);
    color:#fff;
    padding:10px 16px;
    border-radius:999px;
    text-decoration:none;
    font-weight:700;
    box-shadow: 0 8px 18px rgba(236,117,70,.45);
    position:relative;
    overflow:hidden;
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
  }
  .product-actions a::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,rgba(255,255,255,.35),transparent 60%);
    transform:translateX(-120%);
    transition:transform .4s ease;
  }
  .product-actions a:hover::before{ transform:translateX(20%); }
  .product-actions a:hover{
    transform: translateY(-2px) scale(1.03);
    filter: brightness(1.05);
    box-shadow: 0 10px 24px rgba(236,117,70,.55);
  }

  /* Empty state */
  .empty-state{
    border-radius:24px;
    background: var(--card);
    box-shadow: 0 18px 40px rgba(96,84,84,.18);
    padding: 50px 22px;
    text-align:center;
  }
  .empty-state h3{
    font-family:'Playfair Display', serif;
    margin:0 0 10px;
    color:#1A464F;
  }
  .empty-state p{ margin:0 0 18px; color:var(--muted); }

  /* Responsive */
  @media (max-width: 980px){
    .sidebar{ position:sticky; width:auto; height:auto; border-right:none; }
    .main-content{ margin-left:0; }
  }
  @media (max-width: 768px){
    .products-grid{ grid-template-columns: 1fr; }
    .stats-section{ grid-template-columns: 1fr; }
    .header h1{ font-size:22px; }
  }
</style>

</head>
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
            <a href="/sparkmind_mvc_100percent/index.php?page=liste_produits" class="nav-item active">
                <span>📦</span>
                <span>Produits</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=ajouter_produit" class="nav-item">
                <span>➕</span>
                <span>Ajouter un Produit</span>
            </a>
            <a href="/sparkmind_mvc_100percent/index.php?page=produits" class="nav-item">
                <span></span>
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
                <h1>Produits Disponibles</h1>
                <p class="subtitle">Découvrez tous les dons prêts à être remis</p>
            </div>
            <button class="btn-help" onclick="window.location.href='index.php?page=offer_support'">
                <span>❓</span>
                <span>Besoin d'aide</span>
        </header>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <span class="stat-label">Total disponibles</span>
                <div class="stat-value"><?php echo $totalDisponibles; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏷️</div>
                <span class="stat-label">Catégories</span>
                <div class="stat-value"><?php echo count($categoriesDisponibles); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✨</div>
                <span class="stat-label">Nouveautés</span>
                <div class="stat-value"><?php echo min($totalDisponibles, 12); ?></div>
            </div>
        </div>

        <!-- Products Section -->
        <?php if ($totalDisponibles === 0): ?>
            <div class="empty-state">
                <div style="font-size: 5rem; margin-bottom: 20px;">📭</div>
                <h3>Aucun produit disponible pour le moment</h3>
                <p>Revenez bientôt ou proposez un don depuis notre formulaire.</p>
                <a href="ajouterProduit.php" class="btn-primary" style="text-decoration: none; display: inline-block;">
                    + Proposer un don
                </a>
            </div>
        <?php else: ?>
            <div class="products-grid">
  <?php foreach ($produitsDisponibles as $produit): ?>
    <?php
      $photoUrl = '/sparkmind_mvc_100percent/view/omar/logo.png'; // fallback

      if (!empty($produit['photo'])) {
        $p = ltrim($produit['photo'], '/');

        if (preg_match('#^https?://#', $p)) {
          $photoUrl = $p;
        } else {
          $photoUrl = '/sparkmind_mvc_100percent/' . $p; // uploads/xxx.jpg
        }
      }
    ?>

    <div class="product-card">
      <img
        src="<?= htmlspecialchars($photoUrl) ?>"
        alt="<?= htmlspecialchars($produit['title']) ?>"
        onerror="this.onerror=null; this.src='/sparkmind_mvc_100percent/view/omar/logo.png';"
      >

      <div class="product-body">
        <h3><?= htmlspecialchars($produit['title']) ?></h3>

        <div class="product-meta">
          <span class="badge category"><?= htmlspecialchars($produit['category']) ?></span>
          <span class="badge condition"><?= htmlspecialchars($produit['condition']) ?></span>
        </div>

        <p><?= nl2br(htmlspecialchars(substr($produit['description'], 0, 140))) ?>...</p>

        <div class="product-actions">
          <small>Disponible</small>
          <a href="/sparkmind_mvc_100percent/index.php?page=details_produit&id=<?= (int)$produit['id'] ?>">
            Voir détails <span>→</span>
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

        <?php endif; ?>

        <!-- Footer -->
        <div style="text-align: center; padding: 40px 0; color: #666; border-top: 1px solid rgba(0,0,0,0.05); margin-top: 40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </main>
</body>
</html>
