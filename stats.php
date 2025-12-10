<?php
session_start();
require_once 'controller/CategorieC.php';
require_once 'controller/produitC.php';

$categorieC = new CategorieC();
$produitC = new ProduitC();

// Récupérer les données brutes
$categories = $categorieC->listCategories();
$produits = $produitC->listProduits();

// --- TRAITEMENT DES DONNÉES ---
// 1. Produits par Catégorie
$catMap = [];
foreach ($categories as $cat) { $catMap[$cat['idc']] = $cat['nomC']; }

$prodPerCat = [];
foreach ($catMap as $id => $name) { $prodPerCat[$name] = 0; }
foreach ($produits as $prod) {
    if (isset($catMap[$prod['category']])) { $prodPerCat[$catMap[$prod['category']]]++; }
}
$labelsProdCat = array_keys($prodPerCat);
$dataProdCat = array_values($prodPerCat);

// 2. Évolution
$dateStats = [];
foreach ($categories as $cat) {
    if (!empty($cat['dateC'])) {
        $month = substr($cat['dateC'], 0, 7); 
        if (!isset($dateStats[$month])) $dateStats[$month] = 0;
        $dateStats[$month]++;
    }
}
ksort($dateStats);
$labelsDate = array_keys($dateStats);
$dataDate = array_values($dateStats);

// 3. Créateurs
$creatorStats = [];
foreach ($categories as $cat) {
    $creator = !empty($cat['nom_Createur']) ? $cat['nom_Createur'] : 'Inconnu';
    if (!isset($creatorStats[$creator])) $creatorStats[$creator] = 0;
    $creatorStats[$creator]++;
}
arsort($creatorStats);
$labelsCreator = array_keys($creatorStats);
$dataCreator = array_values($creatorStats);

// 4. Matrix & 5. Polar
$allConditions = []; $allStatus = []; $matrixData = [];
foreach ($produits as $prod) {
    $cond = !empty($prod['condition']) ? $prod['condition'] : 'Non spécifié';
    $stat = !empty($prod['statut']) ? $prod['statut'] : 'Indéfini';
    if (!in_array($cond, $allConditions)) $allConditions[] = $cond;
    if (!in_array($stat, $allStatus)) $allStatus[] = $stat;
}
sort($allConditions);
foreach ($allStatus as $st) { $matrixData[$st] = array_fill_keys($allConditions, 0); }
$statusCounts = array_fill_keys($allStatus, 0);
foreach ($produits as $p) {
    $cond = !empty($p['condition']) ? $p['condition'] : 'Non spécifié';
    $stat = !empty($p['statut']) ? $p['statut'] : 'Indéfini';
    $matrixData[$stat][$cond]++;
    $statusCounts[$stat]++;
}

$datasetsStacked = [];
$colorsStacked = ['#4BC0C0', '#FF6384', '#FFCE56', '#36A2EB', '#9966FF'];
$i = 0;
foreach ($matrixData as $statusName => $condCounts) {
    $datasetsStacked[] = ['label' => ucfirst($statusName), 'data' => array_values($condCounts), 'backgroundColor' => $colorsStacked[$i++ % count($colorsStacked)]];
}

// KPI
$totalProduits = count($produits);
$totalCategories = count($categories);
$totalActiveAds = 0;
foreach ($produits as $p) { if ($p['statut'] === 'disponible') $totalActiveAds++; }
$percentActive = $totalProduits > 0 ? round(($totalActiveAds / $totalProduits) * 100, 1) : 0;
$topCreatorName = !empty($labelsCreator) ? $labelsCreator[0] : '-';
$topCreatorCount = !empty($dataCreator) ? $dataCreator[0] : 0;
$percentTopCreator = $totalCategories > 0 ? round(($topCreatorCount / $totalCategories) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparkMind - Statistiques</title>
    <link rel="stylesheet" href="view/back office/back.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        /* Styles WEB existants */
        .stats-container-fluid { padding: 20px; }
        .kpi-row { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .kpi-card { flex: 1; min-width: 240px; background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; }
        .kpi-content h3 { margin: 0; font-size: 2.5em; font-weight: 700; color: #2d3436; }
        .kpi-content p { margin: 5px 0 0; color: #636e72; font-weight: 500; }
        .kpi-badge { display: inline-block; margin-top: 8px; padding: 4px 8px; border-radius: 6px; font-size: 0.85em; font-weight: 600; }
        .badge-success { background: #e8f5e9; color: #00b894; }
        .badge-info { background: #e3f2fd; color: #0984e3; }
        .badge-warning { background: #fff3e0; color: #e17055; }
        .kpi-icon-wrapper { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8em; }
        .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; margin-bottom: 30px; }
        .chart-box { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        .chart-box.full-width { grid-column: 1 / -1; }
        .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f1f2f6; }
        .chart-header h3 { margin: 0; color: #2d3436; font-size: 1.1em; }
        @media (max-width: 992px) { .charts-grid { grid-template-columns: 1fr; } }
        .icon-blue { background: #e3f2fd; color: #0984e3; }
        .icon-purple { background: #f3e5f5; color: #8e44ad; }
        .icon-green { background: #e8f5e9; color: #00b894; }
        .icon-orange { background: #fff3e0; color: #e17055; }
        .btn-export { background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: white; border: none; padding: 12px 25px; border-radius: 50px; cursor: pointer; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); display: flex; align-items: center; gap: 10px; text-decoration: none; transition: all 0.3s ease; }
        .btn-export:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(108, 92, 231, 0.6); }

        /* --- STYLES SPÉCIFIQUES POUR LE PDF (Template caché) --- */
        #pdf-template {
            width: 100%;
            background: white;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            /* Caché par défaut, visible uniquement pour html2pdf */
            display: none; 
        }

        .pdf-page {
            padding: 40px;
            width: 100%; /* S'adapte au conteneur PDF */
            box-sizing: border-box;
            background: white;
            page-break-after: always;
            min-height: 900px; /* Force une certaine hauteur de page */
            position: relative;
        }
        
        .pdf-page:last-child {
            page-break-after: avoid;
        }

        .pdf-header {
            border-bottom: 2px solid #6c5ce7;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pdf-title h1 { color: #6c5ce7; font-size: 24px; margin: 0; }
        .pdf-title p { color: #888; margin: 5px 0 0; font-size: 14px; }
        .pdf-meta { text-align: right; font-size: 12px; color: #666; }

        .pdf-section-title {
            background: #f8f9fa;
            border-left: 5px solid #6c5ce7;
            padding: 10px 15px;
            margin: 30px 0 20px;
            font-size: 18px;
            font-weight: bold;
            color: #2d3436;
        }

        /* Organisation KPI pour PDF : Liste propre */
        .pdf-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 40px;
        }
        .pdf-kpi-item {
            background: #fdfdfd;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .pdf-kpi-val { font-size: 24px; font-weight: bold; color: #2d3436; display: block; }
        .pdf-kpi-label { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; display: block; }
        .pdf-kpi-sub { font-size: 11px; color: #6c5ce7; margin-top: 5px; display: block; }

        /* Organisation Charts pour PDF */
        .pdf-chart-container {
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid #f0f0f0;
            padding: 15px;
            border-radius: 8px;
        }
        .pdf-chart-container h4 { margin: 0 0 15px; color: #444; font-size: 14px; text-transform: uppercase; }
        .pdf-chart-img {
            max-width: 100%;
            height: auto;
            max-height: 350px; /* Limite hauteur */
        }

        .row-2-cols {
            display: flex;
            gap: 20px;
        }
        .col-half { flex: 1; }

        .pdf-footer {
            position: absolute;
            bottom: 20px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="view/back office/logo.png" alt="Logo" class="sidebar-logo">
            <h2>SparkMind</h2>
            <p class="admin-role">Admin</p>
        </div>
        <nav class="sidebar-nav">
            <a href="liste.php" class="nav-item">📊 Tableau de bord</a>
            <a href="liste.php" class="nav-item">📦 Produits</a>
            <a href="listeCategories.php" class="nav-item">🏷️ Catégories</a>
            <a href="stats.php" class="nav-item active">📈 Statistiques</a>
        </nav>
    </div>

    <!-- WEB DISPLAY -->
    <div class="main-content">
        <div class="top-bar">
            <h1>Statistiques & Pourcentages</h1>
            <div class="top-bar-actions">
                <button onclick="downloadFormatPDF()" class="btn-export">
                    <span class="btn-export-icon">📥</span> Export Rapport PDF
                </button>
            </div>
        </div>

        <div class="stats-container-fluid" id="web-report">
            <div class="kpi-row">
                <div class="kpi-card"><div class="kpi-content"><h3><?php echo $totalProduits; ?></h3><p>Produits</p><span class="kpi-badge badge-info">100% Cat.</span></div><div class="kpi-icon-wrapper icon-blue">📦</div></div>
                <div class="kpi-card"><div class="kpi-content"><h3><?php echo $totalActiveAds; ?></h3><p>En ligne</p><span class="kpi-badge badge-success"><?php echo $percentActive; ?>%</span></div><div class="kpi-icon-wrapper icon-green">✅</div></div>
                <div class="kpi-card"><div class="kpi-content"><h3><?php echo $topCreatorName; ?></h3><p>Top Contrib.</p><span class="kpi-badge badge-warning"><?php echo $percentTopCreator; ?>%</span></div><div class="kpi-icon-wrapper icon-orange">🏆</div></div>
                <div class="kpi-card"><div class="kpi-content"><h3><?php echo $totalCategories; ?></h3><p>Catégories</p></div><div class="kpi-icon-wrapper icon-purple">🏷️</div></div>
            </div>

            <div class="charts-grid">
                <div class="chart-box full-width">
                    <div class="chart-header"><h3>📊 Répartition Produits</h3></div>
                    <div style="height: 300px;"><canvas id="chart1"></canvas></div>
                </div>
                <div class="chart-box">
                    <div class="chart-header"><h3>📈 Évolution Créations</h3></div>
                    <div style="height: 250px;"><canvas id="chart2"></canvas></div>
                </div>
                <div class="chart-box">
                    <div class="chart-header"><h3>🏆 Leaders</h3></div>
                    <div style="height: 250px;"><canvas id="chart3"></canvas></div>
                </div>
                <div class="chart-box full-width">
                    <div class="chart-header"><h3>🎯 Vue Statut Globale</h3></div>
                    <div style="height: 300px; display: flex; justify-content: center;"><canvas id="chart4"></canvas></div>
                </div>
                <div class="chart-box full-width">
                    <div class="chart-header"><h3>🔍 Croisement: État vs Statut</h3></div>
                    <div style="height: 300px;"><canvas id="chart5"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF TEMPLATE (Hidden) -->
    <div id="pdf-template">
        <!-- Page 1: Vue d'ensemble -->
        <div class="pdf-page">
            <div class="pdf-header">
                <div class="pdf-title">
                    <h1>Rapport Analytique</h1>
                    <p>SparkMind - Gestion de Stock</p>
                </div>
                <div class="pdf-meta">
                    Généré le <?php echo date('d/m/Y à H:i'); ?><br>
                    Par Admin
                </div>
            </div>

            <div class="pdf-section-title">1. Indicateurs Clés de Performance</div>
            
            <div class="pdf-kpi-grid">
                <div class="pdf-kpi-item">
                    <span class="pdf-kpi-val"><?php echo $totalProduits; ?></span>
                    <span class="pdf-kpi-label">Produits Totaux</span>
                </div>
                <div class="pdf-kpi-item">
                    <span class="pdf-kpi-val"><?php echo $totalActiveAds; ?></span>
                    <span class="pdf-kpi-label">Annonces Actives</span>
                    <span class="pdf-kpi-sub"><?php echo $percentActive; ?>% du stock</span>
                </div>
                <div class="pdf-kpi-item">
                    <span class="pdf-kpi-val"><?php echo $totalCategories; ?></span>
                    <span class="pdf-kpi-label">Catégories Actives</span>
                </div>
                <div class="pdf-kpi-item">
                    <span class="pdf-kpi-val" style="font-size:18px;"><?php echo $topCreatorName; ?></span>
                    <span class="pdf-kpi-label">Meilleur Contributeur</span>
                </div>
            </div>

            <div class="pdf-section-title">2. Répartition du Catalogue</div>
            <div class="pdf-chart-container">
                <h4>Volume de produits par catégorie</h4>
                <img id="img-chart1" class="pdf-chart-img" />
            </div>

            <div class="pdf-footer">Page 1/2 - SparkMind Confidential Report</div>
        </div>

        <!-- Page 2: Détails -->
        <div class="pdf-page">
            <div class="pdf-header">
                <div class="pdf-title">
                    <h1>Rapport Analytique</h1>
                    <p>Détail des performances</p>
                </div>
            </div>

            <div class="pdf-section-title">3. Dynamique & Contributeurs</div>
            
            <div class="row-2-cols">
                <div class="col-half pdf-chart-container">
                    <h4>Tendance de création</h4>
                    <img id="img-chart2" class="pdf-chart-img" />
                </div>
                <div class="col-half pdf-chart-container">
                    <h4>Classement Créateurs</h4>
                    <img id="img-chart3" class="pdf-chart-img" />
                </div>
            </div>

            <div class="pdf-section-title">4. Santé du Stock</div>
            
            <div class="row-2-cols">
                <div class="col-half pdf-chart-container">
                    <h4>Distribution des Statuts</h4>
                    <img id="img-chart4" class="pdf-chart-img" style="max-height: 250px;" />
                </div>
                <div class="col-half pdf-chart-container">
                    <h4>Corrélation État / Disponibilité</h4>
                    <img id="img-chart5" class="pdf-chart-img" />
                </div>
            </div>

            <div class="pdf-footer">Page 2/2 - SparkMind Confidential Report</div>
        </div>
    </div>

    <script>
        // CONFIG DATA
        const labelsProdCat = <?php echo json_encode($labelsProdCat); ?>;
        const dataProdCat = <?php echo json_encode($dataProdCat); ?>;
        const labelsDate = <?php echo json_encode($labelsDate); ?>;
        const dataDate = <?php echo json_encode($dataDate); ?>;
        const labelsCreator = <?php echo json_encode($labelsCreator); ?>;
        const dataCreator = <?php echo json_encode($dataCreator); ?>;
        const matrixLabels = <?php echo json_encode($allConditions); ?>;
        const matrixDatasets = <?php echo json_encode($datasetsStacked); ?>;
        const statusLabels = <?php echo json_encode(array_keys($statusCounts)); ?>;
        const statusData = <?php echo json_encode(array_values($statusCounts)); ?>;

        Chart.defaults.font.family = 'Segoe UI';
        const tooltipPct = {
            callbacks: {
                label: function(ctx) {
                    let val = ctx.raw;
                    let tot = ctx.dataset.data.reduce((a, b) => a + b, 0);
                    let pct = tot > 0 ? Math.round((val/tot)*100) : 0;
                    return ctx.dataset.label + ": " + val + " (" + pct + "%)";
                }
            }
        };

        // RENDER WEB CHARTS
        new Chart(document.getElementById('chart1'), { type: 'bar', data: { labels: labelsProdCat, datasets: [{ label: 'Produits', data: dataProdCat, backgroundColor: '#6c5ce7', borderRadius:4 }] }, options: { maintainAspectRatio:false, plugins:{tooltip:tooltipPct} } });
        new Chart(document.getElementById('chart2'), { type: 'line', data: { labels: labelsDate, datasets: [{ label: 'Ajouts', data: dataDate, borderColor: '#00b894', fill: true, backgroundColor:'rgba(0,184,148,0.1)' }] }, options: { maintainAspectRatio:false, plugins:{legend:{display:false}} } });
        new Chart(document.getElementById('chart3'), { type: 'bar', data: { labels: labelsCreator, datasets: [{ label: 'Catégories', data: dataCreator, backgroundColor: ['#ff7675','#fab1a0','#ffeaa7','#55efc4','#74b9ff'] }] }, options: { indexAxis:'y', maintainAspectRatio:false, plugins:{legend:{display:false}} } });
        new Chart(document.getElementById('chart4'), { type: 'doughnut', data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: ['#ff7675','#55efc4','#ffeaa7','#a29bfe'] }] }, options: { maintainAspectRatio:false, plugins:{position:'right'} } });
        new Chart(document.getElementById('chart5'), { type: 'bar', data: { labels: matrixLabels, datasets: matrixDatasets }, options: { scales:{x:{stacked:true},y:{stacked:true}}, maintainAspectRatio:false } });

        // EXPORT FUNCTION PRO
        function downloadFormatPDF() {
            // 1. Convert Canvas to Images for the Template
            const charts = ['chart1','chart2','chart3','chart4','chart5'];
            charts.forEach(id => {
                const canvas = document.getElementById(id);
                const img = document.getElementById('img-' + id);
                if(canvas && img) {
                    img.src = canvas.toDataURL('image/png', 1.0);
                }
            });

            // 2. Prepare Element
            const element = document.getElementById('pdf-template');
            element.style.display = 'block'; // Make visible for capture

            const opt = {
                margin: 0,
                filename: 'Rapport_SparkMind_Stock.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'pt', format: 'a4', orientation: 'portrait' } 
            };

            // 3. Generate
            html2pdf().set(opt).from(element).save().then(() => {
                element.style.display = 'none'; // Hide again
            });
        }
    </script>
</body>
</html>
