<!-- Create this file as view/Backoffice/statistics.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Dons - Dashboard Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #FBEDD7;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR - Same as other pages */
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .logo {
            text-align: center;
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 2rem;
        }

        .logo h2 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .nav-menu {
            flex: 1;
            padding: 0 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 1.2rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .nav-item:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background-color: rgba(255,255,255,0.25);
            color: white;
            font-weight: 600;
        }

        .nav-item .icon {
            font-size: 1.3rem;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: auto;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            width: calc(100% - 260px);
        }

        /* TOP HEADER */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            padding: 2rem;
            border-radius: 20px;
            color: white;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .header-left h1 {
            font-size: 2rem;
            margin-bottom: 0.3rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header-left p {
            opacity: 0.95;
            font-size: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ec7546;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 50%;
            font-weight: 600;
            z-index: 10;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: 2px solid rgba(255,255,255,0.6);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.1);
            background-color: rgba(255,255,255,0.4);
        }

        /* STATS SUMMARY */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.8rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }

        .stat-card.stat-teal::before {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
        }

        .stat-card.stat-purple::before {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
        }

        .stat-card.stat-coral::before {
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
        }

        .stat-card.stat-orange::before {
            background: linear-gradient(135deg, #ec7546, #f4a261);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-icon {
            font-size: 2.5rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
        }

        .stat-card.stat-teal .stat-icon {
            background: linear-gradient(135deg, rgba(31,140,135,0.1), rgba(126,221,213,0.15));
        }

        .stat-card.stat-purple .stat-icon {
            background: linear-gradient(135deg, rgba(125,90,166,0.1), rgba(181,140,224,0.15));
        }

        .stat-card.stat-coral .stat-icon {
            background: linear-gradient(135deg, rgba(236,157,120,0.1), rgba(251,220,193,0.15));
        }

        .stat-card.stat-orange .stat-icon {
            background: linear-gradient(135deg, rgba(236,117,70,0.1), rgba(244,162,97,0.15));
        }

        .stat-info {
            flex: 1;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 0.3rem;
            color: #333;
            font-weight: 700;
        }

        .stat-info p {
            color: #666;
            font-size: 0.95rem;
        }

        /* CHARTS CONTAINER */
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-header h2 {
            font-size: 1.4rem;
            color: #333;
            font-weight: 600;
        }

        .chart-controls {
            display: flex;
            gap: 1rem;
        }

        .chart-select {
            padding: 0.5rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
            cursor: pointer;
        }

        .chart-canvas {
            width: 100%;
            height: 300px;
            position: relative;
        }

        /* DETAILED STATS */
        .detailed-stats {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-bottom: 2.5rem;
        }

        .detailed-stats h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #333;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .stat-item h4 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #1f8c87;
            font-weight: 700;
        }

        .stat-item p {
            color: #666;
            font-size: 0.9rem;
        }

        /* TIME PERIOD FILTER */
        .time-filter {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .time-filter label {
            font-weight: 600;
            color: #333;
        }

        .time-select {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-select:focus {
            outline: none;
            border-color: #1f8c87;
            box-shadow: 0 0 0 3px rgba(31,140,135,0.1);
        }

        .btn-export {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(125, 90, 166, 0.3);
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(125, 90, 166, 0.4);
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 1.5rem 0;
            }

            .logo h2 {
                font-size: 1.5rem;
            }

            .nav-item span:not(.icon) {
                display: none;
            }

            .nav-item {
                justify-content: center;
                padding: 0.9rem;
            }

            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
                padding: 1rem;
            }

            .top-header {
                flex-direction: column;
                gap: 1.5rem;
            }

            .stats-summary {
                grid-template-columns: 1fr;
            }

            .charts-container {
                grid-template-columns: 1fr;
            }

            .chart-canvas {
                height: 250px;
            }

            .time-filter {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
    <!-- Include Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1>📈 Statistiques des Dons</h1>
                <p>Analyse et visualisation des données des donations</p>
            </div>
            <div class="header-right">
                <button class="btn-export" onclick="exportStatistics()">
                    📥 Exporter les statistiques
                </button>
                <div class="user-profile">
                    <span class="notification-badge">3</span>
                    <div class="avatar">👤</div>
                </div>
            </div>
        </header>

        <!-- Time Period Filter -->
        <div class="time-filter">
            <label for="timePeriod">Période d'analyse :</label>
            <select id="timePeriod" class="time-select" onchange="updateStatistics()">
                <option value="7days">7 derniers jours</option>
                <option value="30days">30 derniers jours</option>
                <option value="3months">3 derniers mois</option>
                <option value="6months">6 derniers mois</option>
                <option value="1year" selected>1 année</option>
                <option value="all">Toutes les données</option>
            </select>
        </div>

        <!-- Stats Summary -->
        <div class="stats-summary">
            <div class="stat-card stat-teal">
                <div class="stat-icon">🎁</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_dons'] ?? 0; ?></h3>
                    <p>Total des dons</p>
                </div>
            </div>

            <div class="stat-card stat-purple">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3><?php echo $stats['recent_dons'] ?? 0; ?></h3>
                    <p>Dons récents (7 jours)</p>
                </div>
            </div>

            <div class="stat-card stat-coral">
                <div class="stat-icon">📍</div>
                <div class="stat-info">
                    <h3>
                        <?php 
                        if (isset($stats['dons_by_region']) && count($stats['dons_by_region']) > 0) {
                            echo count($stats['dons_by_region']);
                        } else {
                            echo '0';
                        }
                        ?>
                    </h3>
                    <p>Régions actives</p>
                </div>
            </div>

            <div class="stat-card stat-orange">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <h3>
                        <?php 
                        if (isset($stats['dons_by_type']) && count($stats['dons_by_type']) > 0) {
                            echo count($stats['dons_by_type']);
                        } else {
                            echo '0';
                        }
                        ?>
                    </h3>
                    <p>Types de dons</p>
                </div>
            </div>
        </div>
  <div class="sidebar">
    <!-- Logo -->
    <div class="logo">
      <h2>🤝 Aide Solidaire</h2>
      <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">Administration</p>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="nav-menu">
      <!-- Dashboard -->
      <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="nav-item active">
        <span class="icon">📊</span>
        <span>Tableau de bord</span>
      </a>
      
      <!-- Donations Section -->
      <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600;">
        GESTION DES DONS
      </div>
      
      <a href="/aide_solitaire/controller/donC.php?action=dons" class="nav-item">
        <span class="icon">🎁</span>
        <span>Tous les dons</span>
      </a>
      
      <a href="/aide_solitaire/controller/donC.php?action=create_don" class="nav-item">
        <span class="icon">➕</span>
        <span>Ajouter un don</span>
      </a>
      
      <a href="/aide_solitaire/controller/donC.php?action=statistics" class="nav-item">
        <span class="icon">📈</span>
        <span>Statistiques dons</span>
      </a>
      
      <!-- Groups Section -->
      <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; margin-top: 1rem;">
        GESTION DES GROUPES
      </div>
      
      <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="nav-item">
        <span class="icon">👥</span>
        <span>Tous les groupes</span>
      </a>
      
      <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="nav-item">
        <span class="icon">➕</span>
        <span>Ajouter un groupe</span>
      </a>
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
      
      
      <!-- FrontOffice Link -->
      <a href="/aide_solitaire/view/frontoffice/index.php" style="display: block; text-align: center; margin-top: 0.5rem; padding: 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; text-decoration: none; color: white; font-size: 0.9rem; transition: all 0.3s ease;">
        <span>🌐</span>
        <span>Voir le site public</span>
      </a>
    </div>
  </div>                      
        <!-- Charts Container -->
        <div class="charts-container">
            <!-- Dons by Type Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2>📦 Dons par type</h2>
                    <div class="chart-controls">
                        <select class="chart-select" onchange="updateTypeChart(this.value)">
                            <option value="bar">Barres</option>
                            <option value="pie">Camembert</option>
                            <option value="doughnut">Anneau</option>
                        </select>
                    </div>
                </div>
                <div class="chart-canvas">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>

            <!-- Dons by Region Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2>📍 Dons par région</h2>
                    <div class="chart-controls">
                        <select class="chart-select" onchange="updateRegionChart(this.value)">
                            <option value="bar">Barres</option>
                            <option value="pie">Camembert</option>
                            <option value="doughnut">Anneau</option>
                        </select>
                    </div>
                </div>
                <div class="chart-canvas">
                    <canvas id="regionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Dons by Month Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h2>📅 Évolution mensuelle des dons</h2>
                <div class="chart-controls">
                    <select class="chart-select" onchange="updateMonthlyChart(this.value)">
                        <option value="line">Ligne</option>
                        <option value="bar">Barres</option>
                    </select>
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="detailed-stats">
            <h2>📋 Statistiques détaillées</h2>
            <div class="stats-grid">
                <!-- Type Breakdown -->
                <?php if (isset($stats['dons_by_type']) && !empty($stats['dons_by_type'])): ?>
                    <?php foreach ($stats['dons_by_type'] as $type): ?>
                        <div class="stat-item">
                            <h4><?php echo $type['count']; ?></h4>
                            <p><?php echo htmlspecialchars($type['type_don']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="stat-item">
                        <h4>0</h4>
                        <p>Aucun type de don</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Region Breakdown -->
        <div class="detailed-stats">
            <h2>🌍 Répartition par région</h2>
            <div class="stats-grid">
                <?php if (isset($stats['dons_by_region']) && !empty($stats['dons_by_region'])): ?>
                    <?php foreach ($stats['dons_by_region'] as $region): ?>
                        <div class="stat-item">
                            <h4><?php echo $region['count']; ?></h4>
                            <p><?php echo htmlspecialchars($region['region']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="stat-item">
                        <h4>0</h4>
                        <p>Aucune région</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        // Charts data from PHP
        const typeData = <?php echo json_encode($stats['dons_by_type'] ?? []); ?>;
        const regionData = <?php echo json_encode($stats['dons_by_region'] ?? []); ?>;

        let typeChart, regionChart, monthlyChart;

        function initializeCharts() {
            // Dons by Type Chart
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            typeChart = new Chart(typeCtx, {
                type: 'bar',
                data: {
                    labels: typeData.map(item => item.type_don),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: typeData.map(item => item.count),
                        backgroundColor: [
                            '#1f8c87', '#7d5aa6', '#ec9d78', '#ec7546',
                            '#10b981', '#3b82f6', '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Dons by Region Chart
            const regionCtx = document.getElementById('regionChart').getContext('2d');
            regionChart = new Chart(regionCtx, {
                type: 'bar',
                data: {
                    labels: regionData.map(item => item.region),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: regionData.map(item => item.count),
                        backgroundColor: [
                            '#f59e0b', '#84cc16', '#06b6d4', '#8b5cf6',
                            '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Monthly Evolution Chart (dummy data - you need to implement this in your model)
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Dons mensuels',
                        data: [12, 19, 8, 15, 22, 18, 25, 12, 19, 21, 16, 24],
                        borderColor: '#1f8c87',
                        backgroundColor: 'rgba(31, 140, 135, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de dons'
                            }
                        }
                    }
                }
            });
        }

        function updateTypeChart(chartType) {
            typeChart.destroy();
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            typeChart = new Chart(typeCtx, {
                type: chartType,
                data: {
                    labels: typeData.map(item => item.type_don),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: typeData.map(item => item.count),
                        backgroundColor: [
                            '#1f8c87', '#7d5aa6', '#ec9d78', '#ec7546',
                            '#10b981', '#3b82f6', '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: chartType !== 'bar'
                        }
                    }
                }
            });
        }

        function updateRegionChart(chartType) {
            regionChart.destroy();
            const regionCtx = document.getElementById('regionChart').getContext('2d');
            regionChart = new Chart(regionCtx, {
                type: chartType,
                data: {
                    labels: regionData.map(item => item.region),
                    datasets: [{
                        label: 'Nombre de dons',
                        data: regionData.map(item => item.count),
                        backgroundColor: [
                            '#f59e0b', '#84cc16', '#06b6d4', '#8b5cf6',
                            '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: chartType !== 'bar'
                        }
                    }
                }
            });
        }

        function updateMonthlyChart(chartType) {
            monthlyChart.destroy();
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            monthlyChart = new Chart(monthlyCtx, {
                type: chartType,
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Dons mensuels',
                        data: [12, 19, 8, 15, 22, 18, 25, 12, 19, 21, 16, 24],
                        borderColor: '#1f8c87',
                        backgroundColor: chartType === 'line' 
                            ? 'rgba(31, 140, 135, 0.1)' 
                            : '#1f8c87',
                        borderWidth: 3,
                        fill: chartType === 'line',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de dons'
                            }
                        }
                    }
                }
            });
        }

        function updateStatistics() {
            const timePeriod = document.getElementById('timePeriod').value;
            // Here you would typically make an AJAX call to update statistics
            // For now, we'll just show a message
            alert(`Mise à jour des statistiques pour la période: ${timePeriod}`);
            // In a real implementation, you would fetch new data and update charts
        }

        function exportStatistics() {
            // Create CSV content
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Add header
            csvContent += "Type,Région,Compte\n";
            
            // Add type data
            if (typeData.length > 0) {
                typeData.forEach(item => {
                    csvContent += `"${item.type_don}",,"${item.count}"\n`;
                });
            }
            
            // Add region data
            if (regionData.length > 0) {
                regionData.forEach(item => {
                    csvContent += `,"${item.region}","${item.count}"\n`;
                });
            }
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `statistiques_dons_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>