<?php
// dons.php - Add this at the VERY TOP
session_start();

// Check if we have the dons data (from controller)
if (!isset($dons)) {
    // If accessed directly, show a message and create empty arrays to prevent errors
    $dons = [];
    $filters = [];
    
    echo "<div style='background: #fff3cd; padding: 15px; border: 2px solid #ffc107; margin: 10px; border-radius: 5px;'>";
    echo "<strong>⚠️ Information:</strong> Cette page devrait être accessible via le contrôleur. ";
    echo "<a href='/aide_solitaire/index.php?action=dons' style='color: #007bff; text-decoration: none; font-weight: bold;'>Cliquez ici pour y accéder correctement</a>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Dons - Dashboard Admin</title>
    <!-- FIXED CSS PATH -->
    <link rel="stylesheet" href="backoffice.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
            z-index: 1000;
        }

        .logo {
            text-align: center;
            margin-bottom: 3rem;
            padding: 0 1.5rem;
        }

        .logo h2 {
            color: white;
            font-size: 1.5rem;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: #bdc3c7;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: #34495e;
            color: white;
        }

        .nav-item.active {
            background: #3498db;
            color: white;
        }

        .nav-item .icon {
            font-size: 1.2rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 2rem;
            left: 0;
            right: 0;
            padding: 0 1rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .header-left h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .header-left p {
            margin: 0.5rem 0 0 0;
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .filters {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            align-items: end;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }

        .filter-select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            background: white;
            transition: border-color 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-apply {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-reset {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3);
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
        }

        .message-alert {
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 6px solid;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .message-success {
            background: #d5f4e6;
            color: #166534;
            border-left-color: #10b981;
        }

        .message-error {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .total-count {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .export-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-export {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }

        th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 1.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 1.1rem;
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid #e1e5e9;
            vertical-align: middle;
            color: #2c3e50;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .table-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-icon {
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 45px;
            height: 45px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .btn-view {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .btn-icon:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
            background: white;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #7f8c8d;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .filters {
                grid-template-columns: 1fr 1fr auto auto;
            }
        }

        @media (max-width: 1200px) {
            .filters {
                grid-template-columns: 1fr 1fr;
            }
            
            .btn-apply, .btn-reset {
                grid-column: span 1;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .filters {
                grid-template-columns: 1fr;
            }
            
            .stats-bar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo">
            <h2>🕊️ Admin</h2>
        </div>
        <nav class="nav-menu">
            <a href="/aide_solitaire/index.php?action=dashboard" class="nav-item">
                <span class="icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="/aide_solitaire/index.php?action=dons" class="nav-item active">
                <span class="icon">🎁</span>
                <span>Dons</span>
            </a>
            <a href="/aide_solitaire/index.php?action=statistics" class="nav-item">
                <span class="icon">📈</span>
                <span>Statistiques</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="#" class="nav-item">
                <span class="icon">🚪</span>
                <span>Déconnexion</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1>Gestion des Dons</h1>
                <p>Gérez tous les dons du système</p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/index.php?action=create_don" class="btn-primary">+ Nouveau Don</a>
                <div class="user-profile">
                    <div class="avatar">👤</div>
                </div>
            </div>
        </header>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['message'])): ?>
            <?php
            $messages = [
                'created' => ['type' => 'success', 'text' => 'Don créé avec succès!'],
                'updated' => ['type' => 'success', 'text' => 'Don modifié avec succès!'],
                'deleted' => ['type' => 'success', 'text' => 'Don supprimé avec succès!'],
                'error' => ['type' => 'error', 'text' => 'Une erreur est survenue!'],
                'not_found' => ['type' => 'error', 'text' => 'Don non trouvé!']
            ];
            $message = $messages[$_GET['message']] ?? null;
            ?>
            <?php if ($message): ?>
                <div class="message-alert message-<?php echo $message['type']; ?>">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Filters -->
        <form method="GET" action="/aide_solitaire/index.php">
            <input type="hidden" name="action" value="dons">
            <div class="filters">
                <div class="filter-group">
                    <label class="filter-label">Type de don</label>
                    <select class="filter-select" name="type_don">
                        <option value="">Tous les types</option>
                        <option value="Vêtements" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>Vêtements</option>
                        <option value="Nourriture" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>Nourriture</option>
                        <option value="Médicaments" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>Médicaments</option>
                        <option value="Équipement" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Équipement' ? 'selected' : ''; ?>>Équipement</option>
                        <option value="Argent" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Argent' ? 'selected' : ''; ?>>Argent</option>
                        <option value="Services" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Services' ? 'selected' : ''; ?>>Services</option>
                        <option value="Autre" <?php echo isset($filters['type_don']) && $filters['type_don'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Région</label>
                    <select class="filter-select" name="region">
                        <option value="">Toutes les régions</option>
                        <option value="Tunis" <?php echo isset($filters['region']) && $filters['region'] == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                        <option value="Sfax" <?php echo isset($filters['region']) && $filters['region'] == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                        <option value="Sousse" <?php echo isset($filters['region']) && $filters['region'] == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                        <option value="Kairouan" <?php echo isset($filters['region']) && $filters['region'] == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                        <option value="Bizerte" <?php echo isset($filters['region']) && $filters['region'] == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                        <option value="Gabès" <?php echo isset($filters['region']) && $filters['region'] == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                        <option value="Ariana" <?php echo isset($filters['region']) && $filters['region'] == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                        <option value="Gafsa" <?php echo isset($filters['region']) && $filters['region'] == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                        <option value="Monastir" <?php echo isset($filters['region']) && $filters['region'] == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                        <option value="Autre" <?php echo isset($filters['region']) && $filters['region'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                    </select>
                </div>

                <button type="submit" class="btn-apply">🔍 Appliquer</button>
                <a href="/aide_solitaire/index.php?action=dons" class="btn-reset">🔄 Réinitialiser</a>
            </div>
        </form>

        <!-- Stats and Export -->
        <div class="stats-bar">
            <div class="total-count">
                📊 Total: <?php echo count($dons); ?> don(s)
            </div>
            <div class="export-buttons">
                <button class="btn-export" onclick="printTable()">🖨️ Imprimer</button>
                <button class="btn-export" onclick="exportToCSV()">📥 CSV</button>
            </div>
        </div>

        <!-- Dons Table -->
        <div class="table-container">
            <?php if (empty($dons)): ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <h3>Aucun don trouvé</h3>
                    <p>Aucun don ne correspond à vos critères de recherche.</p>
                    <a href="/aide_solitaire/index.php?action=create_don" class="btn-primary" style="margin-top: 1rem;">➕ Ajouter le premier don</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>État</th>
                            <th>Région</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dons as $don): ?>
                        <tr>
                            <td><strong>#<?php echo $don['id']; ?></strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.2rem;">
                                        <?php 
                                        $icons = [
                                            'Vêtements' => '👕',
                                            'Nourriture' => '🍞',
                                            'Médicaments' => '💊',
                                            'Équipement' => '🔧',
                                            'Argent' => '💰',
                                            'Services' => '🤝',
                                            'Autre' => '🎁'
                                        ];
                                        echo $icons[$don['type_don']] ?? '🎁';
                                        ?>
                                    </span>
                                    <?php echo htmlspecialchars($don['type_don']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($don['quantite']); ?></td>
                            <td>
                                <?php if (!empty($don['etat_object'])): ?>
                                    <span class="badge badge-active"><?php echo htmlspecialchars($don['etat_object']); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-pending">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-active"><?php echo htmlspecialchars($don['region']); ?></span>
                            </td>
                            <td>
                                <small><?php echo date('d/m/Y', strtotime($don['date_don'])); ?></small>
                                <br>
                                <small style="color: #666;"><?php echo date('H:i', strtotime($don['date_don'])); ?></small>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="/aide_solitaire/index.php?action=view_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-view" title="Voir">
                                        👁️
                                    </a>
                                    <a href="/aide_solitaire/index.php?action=edit_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-edit" title="Modifier">
                                        ✏️
                                    </a>
                                    <a href="/aide_solitaire/index.php?action=delete_don&id=<?php echo $don['id']; ?>" class="btn-icon btn-delete" title="Supprimer" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function printTable() {
            window.print();
        }

        function exportToCSV() {
            // Simple CSV export implementation
            const rows = [
                ['ID', 'Type', 'Quantité', 'État', 'Région', 'Date', 'Description']
            ];
            
            <?php foreach ($dons as $don): ?>
            rows.push([
                '<?php echo $don['id']; ?>',
                '<?php echo $don['type_don']; ?>',
                '<?php echo $don['quantite']; ?>',
                '<?php echo $don['etat_object']; ?>',
                '<?php echo $don['region']; ?>',
                '<?php echo date('d/m/Y H:i', strtotime($don['date_don'])); ?>',
                '<?php echo addslashes($don['description']); ?>'
            ]);
            <?php endforeach; ?>

            let csvContent = "data:text/csv;charset=utf-8,";
            rows.forEach(function(rowArray) {
                let row = rowArray.map(field => `"${field}"`).join(",");
                csvContent += row + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "dons_<?php echo date('Y-m-d'); ?>.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.message-alert');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>