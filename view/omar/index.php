<?php
require_once __DIR__ . '/../../controller/produitC.php';

$produitC = new ProduitC();
$produits = $produitC->listProduits();

// Filtrer uniquement les produits disponibles
$produitsDisponibles = array_filter($produits, function ($produit) {
    return strtolower($produit['statut']) === 'disponible';
});

$totalDisponibles = count($produitsDisponibles);
?>
<!DOCTYPE html>
<html lang="fr">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Marketplace Solidaire</title>
    <link rel="stylesheet" href="formlaire.css">
        <style>
    :root{
        --orange:#ec7546;
        --turquoise:#1f8c87;
        --violet:#7d5aa6;

        --bg:#fbedD7;         /* fond beige */
        --card:#FFF7EF;       /* cartes beige clair */
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

    /* Layout global (sidebar + content) */
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

    /* Sidebar branding */
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

    /* Menu */
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

    /* Header top (sticky comme top-nav) */
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

    /* Welcome banner -> style "space-hero" */
    .welcome-banner{
        position:relative;
        overflow:hidden;
        border-radius:24px;
        margin: 18px 0 34px;
        padding: 34px 30px 28px;
        background:#f5f5f5;
        box-shadow: 0 18px 40px rgba(96,84,84,.18);
    }

    .welcome-banner::before,
    .welcome-banner::after{
        content:"";
        position:absolute;
        border-radius:999px;
        filter:blur(18px);
        opacity:.55;
        mix-blend-mode:screen;
        animation: floatBlob 10s ease-in-out infinite alternate;
    }
    .welcome-banner::before{
        width:140px; height:140px;
        top:-50px; left:18px;
        background:rgba(127, 71, 192, 0.6);
    }
    .welcome-banner::after{
        width:190px; height:190px;
        bottom:-70px; right:10px;
        background:rgba(31,140,135,.7);
        animation-delay:-4s;
    }
    @keyframes floatBlob{ from{transform:translateY(0) translateX(0);} to{transform:translateY(16px) translateX(-8px);} }

    .welcome-banner h2{
        margin:0 0 10px 0;
        font-family:'Playfair Display', serif;
        font-size:30px;
        color:#02282f;
    }
    .welcome-banner p{
        margin:0 0 16px 0;
        font-size:16px;
        line-height:1.7;
        color:#020202;
        max-width:820px;
    }

    .cta-button{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background: var(--orange);
        color:#fff;
        border-radius:999px;
        padding:10px 18px;
        text-decoration:none;
        font-weight:600;
        box-shadow: 0 8px 18px rgba(236,117,70,.45);
        position:relative;
        overflow:hidden;
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }
    .cta-button::before{
        content:"";
        position:absolute;
        inset:0;
        background:linear-gradient(120deg,rgba(255,255,255,.35),transparent 60%);
        transform:translateX(-120%);
        transition:transform .4s ease;
    }
    .cta-button:hover::before{ transform:translateX(20%); }
    .cta-button:hover{
        transform: translateY(-2px) scale(1.03);
        filter: brightness(1.05);
        box-shadow: 0 10px 24px rgba(236,117,70,.55);
    }

    .welcome-stats{
        display:flex;
        flex-wrap:wrap;
        gap:14px;
        margin-top:18px;
        padding-top:16px;
        border-top:1px solid rgba(0,0,0,.06);
    }
    .stat-item{
        flex:1 1 220px;
        min-width:200px;
        background: var(--card);
        border-radius:18px;
        padding:14px 16px;
        box-shadow: 0 12px 26px rgba(0,0,0,.12);
        position:relative;
        overflow:hidden;
    }
    .stat-item::after{
        content:"";
        position:absolute;
        inset:auto 14px 12px auto;
        width:52px; height:52px;
        border-radius:999px;
        background: radial-gradient(circle at top left, rgba(255,255,255,.55), transparent 60%);
        opacity:.9;
    }
    .stat-value{
        display:block;
        font-size:28px;
        font-weight:700;
        color:#02282f;
    }
    .stat-label{
        display:block;
        font-size:12px;
        letter-spacing:1px;
        text-transform:uppercase;
        color:rgba(26,70,79,.75);
        margin-top:4px;
    }

    /* Section header */
    .section-header{
        text-align:center;
        margin: 10px 0 18px;
    }
    .section-header h2{
        font-family:'Playfair Display', serif;
        font-size:24px;
        margin:0 0 6px;
        color:#1A464F;
    }
    .section-header p{
        margin:0;
        color:var(--muted);
    }

    /* Products grid */
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
        transform: translateY(10px);
        opacity:0;
        animation: cardIn .6s ease forwards;
    }
    .product-card:nth-child(2){ animation-delay:.08s; }
    .product-card:nth-child(3){ animation-delay:.16s; }
    .product-card:nth-child(4){ animation-delay:.24s; }
    @keyframes cardIn{ to{ transform: translateY(0); opacity:1; } }

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
        transition: transform .18s ease, box-shadow .18s ease;
        box-shadow: 0 16px 34px rgba(0,0,0,0.35);
    }
    .product-card:hover::before{ opacity:1; }

    .product-img-container{
        height:220px;
        background:#f5f5f5;
        overflow:hidden;
        position:relative;
    }
    .product-img{
        width:100%;
        height:100%;
        object-fit:cover;
        transition: transform .35s ease;
    }
    .product-card:hover .product-img{ transform: scale(1.05); }

    .category-badge{
        position:absolute;
        top:12px;
        left:12px;
        padding:6px 12px;
        border-radius:999px;
        font-size:12px;
        background:rgba(251,237,215,.95);
        border:1px solid rgba(0,0,0,.05);
        box-shadow: 0 6px 14px rgba(0,0,0,.12);
        color:#1A464F;
        font-weight:600;
    }

    .product-info{
        padding:14px 16px 16px;
        display:flex;
        flex-direction:column;
        gap:8px;
        flex:1;
    }
    .product-title{
        margin:0;
        font-size:16px;
        font-weight:700;
        color:#02282f;
    }
    .product-description{
        margin:0;
        color:rgba(26,70,79,.80);
        font-size:14px;
        line-height:1.6;
    }

    .product-footer{
        margin-top:auto;
        padding-top:12px;
        border-top:1px solid rgba(0,0,0,.06);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
    }

    .availability{
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#0f7a50;
        font-weight:600;
    }
    .dot{
        width:10px; height:10px;
        border-radius:50%;
        background:#27ae60;
        box-shadow:0 0 0 2px rgba(39,174,96,.20);
    }

    .view-details{
        text-decoration:none;
        font-weight:700;
        color:var(--violet);
        display:inline-flex;
        align-items:center;
        gap:6px;
        transition: gap .25s ease, color .25s ease;
    }
    .view-details:hover{
        gap:10px;
        color:var(--turquoise);
    }

    /* Empty state */
    .empty-state{
        border-radius:24px;
        background: var(--card);
        box-shadow: 0 18px 40px rgba(96,84,84,.18);
        padding: 50px 22px;
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
        .header h1{ font-size:22px; }
        .welcome-banner h2{ font-size:24px; }
        .products-grid{ grid-template-columns: 1fr; }
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
<a href="/sparkmind_mvc_100percent/index.php?page=produits" class="nav-item active">
    <span>🏠</span>
    <span>Accueil</span>
</a>

        <a href="/sparkmind_mvc_100percent/index.php?page=liste_produits" class="nav-item">
            <span>📦</span>
            <span>Produits</span>
        </a>

        <a href="/sparkmind_mvc_100percent/index.php?page=ajouter_produit" class="nav-item">
            <span>➕</span>
            <span>Ajouter un Produit</span>
        </a>

        <a href="/sparkmind_mvc_100percent/index.php?page=offer_support" class="nav-item">
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
                <h1>Marketplace Solidaire</h1>
                <p class="subtitle">Donnez une seconde vie à vos objets</p>
            </div>
            <button class="btn-help" onclick="window.location.href='index.php?page=offer_support'">
                <span>❓</span>
                <span>Besoin d'aide</span>
        </header>

        <!-- Welcome Banner -->
        <section class="welcome-banner">
            <h2>Bienvenue sur SparkMind</h2>
            <p>Rejoignez notre communauté solidaire. Échangez, donnez et trouvez des trésors uniques tout en préservant notre planète.</p>
            
            <a href="/sparkmind_mvc_100percent/index.php?page=frontoffice" class="cta-button">
                + Proposer un don
            </a>
            
            <div class="welcome-stats">
                <div class="stat-item">
                    <span class="stat-value"><?= $totalDisponibles ?></span>
                    <span class="stat-label">Produits Disponibles</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">500+</span>
                    <span class="stat-label">Membres Actifs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">100%</span>
                    <span class="stat-label">Gratuit</span>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="produits">
            <div class="section-header">
                <h2>Derniers Arrivages</h2>
                <p>Découvrez les pépites ajoutées par notre communauté</p>
            </div>

            <?php if (empty($produitsDisponibles)): ?>
                <div class="empty-state">
                    <div style="font-size: 5rem; margin-bottom: 20px;">📦</div>
                    <h3>Aucun produit pour le moment</h3>
                    <p>Soyez le premier à proposer un don !</p>
                    <a href="ajouterProduit.php" class="btn-primary" style="text-decoration: none; display: inline-block;">
                        Ajouter un Produit
                    </a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($produitsDisponibles as $produit): ?>
                        <article class="product-card">
                            <div class="product-img-container">
                                <?php 
                                // Construire le chemin de l'image
                                $photoUrl = '/sparkmind_mvc_100percent/view/omar/logo.png'; // fallback

                                if (!empty($produit['photo'])) {
                                    // En DB on veut stocker un truc comme: uploads/nom_image.jpg
                                    $p = ltrim($produit['photo'], '/');

                                    // Si c'est déjà une URL http
                                    if (preg_match('#^https?://#', $p)) {
                                        $photoUrl = $p;
                                    } else {
                                        $photoUrl = '/sparkmind_mvc_100percent/' . $p;
                                    }
                                }
                                ?>
                                <img src="<?= htmlspecialchars($photoUrl) ?>"
                                    alt="<?= htmlspecialchars($produit['title']) ?>"
                                    class="product-img"
                                    onerror="this.onerror=null; this.src='/sparkmind_mvc_100percent/view/omar/logo.png';">

                            </div>
                            
                            <div class="product-info">
                                <h3 class="product-title"><?= htmlspecialchars($produit['title']) ?></h3>
                                <p class="product-description">
                                    <?= nl2br(htmlspecialchars(substr($produit['description'], 0, 100))) ?>...
                                </p>
                                
                                <div class="product-footer">
                                    <div class="availability">
                                        <span class="dot"></span>
                                        Disponible
                                    </div>
                                    <a href="/sparkmind_mvc_100percent/index.php?page=details_produit&id=<?= (int)$produit['id'] ?>" class="view-details">
                                        Voir détails <span>→</span>
                                    </a>

                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Footer -->
        <div style="text-align: center; padding: 40px 0; color: #666; border-top: 1px solid rgba(0,0,0,0.05); margin-top: 40px;">
            <p>&copy; 2024 SparkMind. Tous droits réservés.</p>
        </div>
    </main>
</body>
</html>
