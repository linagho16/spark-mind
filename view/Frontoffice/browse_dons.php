<?php
// frontoffice/browse_dons.php - Browse all donations
session_start();
require_once __DIR__ . '/../../Model/donmodel.php';
require_once __DIR__ . '/../../Model/groupemodel.php';

try {
    $model = new DonModel();
    
    // Get filters from URL
    $filters = [];
    
    if (isset($_GET['type_don']) && !empty($_GET['type_don'])) {
        $filters['type_don'] = $_GET['type_don'];
    }
    
    if (isset($_GET['region']) && !empty($_GET['region'])) {
        $filters['region'] = $_GET['region'];
    }
    
    if (isset($_GET['groupe_id']) && !empty($_GET['groupe_id'])) {
        $filters['groupe_id'] = $_GET['groupe_id'];
    }
    
    // MODIFICATION IMPORTANTE ICI :
    // Pour frontoffice, on veut afficher les dons 'actif', 'en_attente' et 'payé'
    $filters['statut'] = 'frontoffice'; // Ceci utilise un filtre spécial
    
    // Get donations with filters
    $dons = $model->getDonsWithFiltersAndGroupes($filters);
    
    // Reste du code...
    // Debug: Vérifier ce qui est récupéré
    error_log("Nombre de dons récupérés: " . count($dons));
    if (!empty($dons)) {
        error_log("Premier don récupéré: " . print_r($dons[0], true));
    }
    
    // Get unique types and regions for filters
    $allDons = $model->getAllDons();
    $types = array_unique(array_column($allDons, 'type_don'));
    $regions = array_unique(array_column($allDons, 'region'));
    
    // Vérifier les messages de succès
    if (isset($_GET['message'])) {
        if ($_GET['message'] == 'don_created') {
            $success_message = "✅ Votre don a été créé avec succès !";
        } elseif ($_GET['message'] == 'paiement_success') {
            $success_message = "✅ Paiement effectué avec succès ! Votre don financier est maintenant disponible.";
        }
    }
    
} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    error_log("Erreur dans browse_dons.php: " . $e->getMessage());
    $dons = [];
    $types = [];
    $regions = [];
}

// Définir les icônes pour les types de dons
$icons = [
    'Alimentaire' => '🍎',
    'Vêtements' => '👕',
    'Médicaments' => '💊',
    'Fournitures scolaires' => '📚',
    'Matériel médical' => '🏥',
    'Équipements sportifs' => '⚽',
    'Produits d\'hygiène' => '🚿',
    'Meubles' => '🛋️',
    'Électronique' => '💻',
    'Financier' => '💰',
    'Autre' => '🎁'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcourir les Dons - Aide Solidaire</title>
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
            line-height: 1.6;
        }

        /* Header - Dashboard Style */
        .header {
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            color: white;
            padding: 3rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            border-radius: 0 0 20px 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .pigeon-bg {
            position: absolute;
            bottom: 20px;
            right: 5%;
            font-size: 8rem;
            opacity: 0.15;
            z-index: 1;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

        /* Back Link - Dashboard Style */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            color: #1f8c87;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .back-link:hover {
            background: rgba(31, 140, 135, 0.1);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        /* Alert Messages */
        .alert {
            padding: 1.5rem 1.8rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border-left: 6px solid;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideIn 0.5s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(212, 237, 218, 0.2), rgba(40, 167, 69, 0.1));
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(248, 215, 218, 0.2), rgba(220, 53, 69, 0.1));
            color: #721c24;
            border-left-color: #dc3545;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Filters Container - Dashboard Style */
        .filters-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            animation: slideIn 0.5s ease;
        }

        .filters-title {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.8rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #333;
            font-size: 1rem;
        }

        .filter-select {
            padding: 1rem 1.2rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231f8c87' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.2rem center;
            background-size: 12px;
            padding-right: 3rem;
        }

        .filter-select:focus {
            outline: none;
            border-color: #1f8c87;
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.15);
            transform: translateY(-1px);
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-btn {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }

        .reset-btn {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        /* Results Count */
        .results-count {
            text-align: center;
            margin: 2rem 0;
            color: #666;
            font-size: 1.2rem;
            padding: 1rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .results-count strong {
            color: #1f8c87;
            font-size: 1.5rem;
        }

        /* Main Content Sections */
        .section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2.5rem 0;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.2rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .section-title h2 {
            color: #333;
            font-size: 1.6rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        /* Cards - Dashboard Style */
        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f3f5;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        /* NOUVEAU: Conteneur d'image */
        .card-image-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .card-image {
            transform: scale(1.05);
        }

        .card-image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(31,140,135,0.1), rgba(126,221,213,0.15));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }

        .card-header {
            padding: 1.5rem 1.5rem 0;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(31,140,135,0.1), rgba(126,221,213,0.15));
        }

        .card-title {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem 1.5rem 2rem;
        }

        .card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            color: #666;
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.8rem;
            background: #f8f9fa;
            border-radius: 20px;
        }

        .card-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        /* AJOUT: Style pour l'état de l'objet */
        .card-etat {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 235, 59, 0.15));
            padding: 0.8rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #ffc107;
        }

        .card-etat strong {
            color: #856404;
            margin-right: 0.5rem;
        }

        .card-actions {
            display: flex;
            gap: 0.8rem;
        }

        .btn {
            flex: 1;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px dashed #e1e5e9;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2.5rem 0;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .quick-actions h3 {
            color: #333;
            margin-bottom: 1.8rem;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.2rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            padding: 1.2rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn:nth-child(1) {
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
        }

        .action-btn:nth-child(2) {
            background: linear-gradient(135deg, #ec7546, #f4a261);
        }

        .action-btn:nth-child(3) {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 3rem;
        }

        .footer p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        /* Active filters indicator */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .filter-tag button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                max-width: 100%;
                padding: 0 1.5rem 2rem;
            }
            
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 2rem 1rem 3rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .filters-form {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .filter-btn,
            .reset-btn {
                width: 100%;
                justify-content: center;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .card-actions {
                flex-direction: column;
            }
            
            .section,
            .filters-container,
            .quick-actions {
                padding: 1.8rem;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem 2rem;
            }
            
            .card-header {
                padding: 1.5rem 1.5rem 0;
            }
            
            .card-body {
                padding: 1.5rem 1.5rem 1.5rem;
            }
            
            .card-meta {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn {
                padding: 0.8rem;
                font-size: 0.85rem;
            }
            
            .action-btn {
                padding: 1rem;
                font-size: 0.9rem;
            }
            
            .results-count {
                font-size: 1rem;
                padding: 0.8rem;
            }
            
            .footer {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>🔍 Parcourir les Dons</h1>
        <p>Trouvez ce dont vous avez besoin parmi nos dons disponibles</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <main class="container">
        <!-- Back Button -->
        <a href="index.php" class="back-link">
            <span>←</span>
            <span>Retour à l'accueil</span>
        </a>
        
        <!-- Messages d'alerte -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <span style="font-size: 1.5rem;">✅</span>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <span style="font-size: 1.5rem;">❌</span>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Active Filters -->
        <?php if (isset($_GET['type_don']) || isset($_GET['region']) || isset($_GET['groupe_id'])): ?>
        <div class="active-filters">
            <?php if (isset($_GET['type_don']) && !empty($_GET['type_don'])): ?>
            <span class="filter-tag">
                <span>🏷️ Type: <?php echo htmlspecialchars($_GET['type_don']); ?></span>
                <button onclick="removeFilter('type_don')">×</button>
            </span>
            <?php endif; ?>
            
            <?php if (isset($_GET['region']) && !empty($_GET['region'])): ?>
            <span class="filter-tag">
                <span>📍 Région: <?php echo htmlspecialchars($_GET['region']); ?></span>
                <button onclick="removeFilter('region')">×</button>
            </span>
            <?php endif; ?>
            
            <?php if (isset($_GET['groupe_id']) && !empty($_GET['groupe_id'])): 
                $groupeModel = new GroupeModel();
                $groupe = $groupeModel->getGroupeById($_GET['groupe_id']);
            ?>
            <span class="filter-tag">
                <span>👥 Groupe: <?php echo htmlspecialchars($groupe['nom'] ?? 'Inconnu'); ?></span>
                <button onclick="removeFilter('groupe_id')">×</button>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div class="filters-container">
            <div class="filters-title">
                <span>🔍</span>
                <span>Filtres de recherche</span>
            </div>
            <form method="GET" action="" class="filters-form">
                <div class="filter-group">
                    <label class="filter-label">Type de don</label>
                    <select name="type_don" class="filter-select">
                        <option value="">Tous les types</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" 
                                <?php echo isset($_GET['type_don']) && $_GET['type_don'] == $type ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Région</label>
                    <select name="region" class="filter-select">
                        <option value="">Toutes les régions</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?php echo htmlspecialchars($region); ?>" 
                                <?php echo isset($_GET['region']) && $_GET['region'] == $region ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($region); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Groupe</label>
                    <select name="groupe_id" class="filter-select">
                        <option value="">Tous les groupes</option>
                        <?php 
                        $groupeModel = new GroupeModel();
                        $activeGroupes = $groupeModel->getGroupesWithFilters(['statut' => 'actif']);
                        foreach ($activeGroupes as $groupe): ?>
                            <option value="<?php echo $groupe['id']; ?>" 
                                <?php echo isset($_GET['groupe_id']) && $_GET['groupe_id'] == $groupe['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($groupe['nom']); ?> (<?php echo htmlspecialchars($groupe['region']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="filter-btn">
                        <span>🔍</span>
                        <span>Appliquer les filtres</span>
                    </button>
                    <a href="browse_dons.php" class="reset-btn">
                        <span>🔄</span>
                        <span>Réinitialiser</span>
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Results Count -->
        <div class="results-count">
            <strong><?php echo count($dons); ?></strong> dons trouvés
            <?php if (isset($_GET['groupe_id']) && !empty($_GET['groupe_id'])): ?>
                <br><small>Filtrés par groupe</small>
            <?php endif; ?>
        </div>  
        
        <!-- Donations Grid -->
        <section class="section">
            <div class="section-title">
                <h2><span>🎁</span> Dons disponibles</h2>
            </div>
            
            <?php if (!empty($dons)): ?>
                <div class="grid">
                    <?php foreach ($dons as $don): ?>
                    <div class="card">
                        <div class="card-image-container">
                            <?php if (!empty($don['photos'])): ?>
                                <?php 
                                $imagePath = $don['photos'];
                                $testPaths = [
                                    $imagePath,
                                    '/' . $imagePath,
                                    '/aide_solitaire/' . $imagePath,
                                    'http://' . $_SERVER['HTTP_HOST'] . '/aide_solitaire/' . $imagePath
                                ];
                                $imageFound = false;
                                ?>
                                
                                <?php foreach ($testPaths as $testPath): ?>
                                    <?php 
                                    $filePath = str_replace('http://' . $_SERVER['HTTP_HOST'], $_SERVER['DOCUMENT_ROOT'], $testPath);
                                    if (file_exists($filePath)): 
                                        $imageFound = true;
                                    ?>
                                        <img src="<?php echo htmlspecialchars($testPath); ?>" 
                                             alt="Image de <?php echo htmlspecialchars($don['type_don']); ?>" 
                                             class="card-image">
                                        <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                
                                <?php if (!$imageFound): ?>
                                    <div class="card-image-placeholder">
                                        <?php echo $icons[$don['type_don']] ?? '🎁'; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="card-image-placeholder">
                                    <?php echo $icons[$don['type_don']] ?? '🎁'; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-header">
                            <h3 class="card-title"><?php echo htmlspecialchars($don['type_don']); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span>📦 <?php echo htmlspecialchars($don['quantite']); ?> unités</span>
                                <span>📍 <?php echo htmlspecialchars($don['region']); ?></span>
                                <span>📅 <?php echo date('d/m/Y', strtotime($don['date_don'])); ?></span>
                            </div>
                            
                            <?php if (!empty($don['groupe_nom'])): ?>
                            <div class="card-meta" style="background: linear-gradient(135deg, rgba(31,140,135,0.1), rgba(126,221,213,0.15)); padding: 0.8rem; border-radius: 10px; margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                <a href="view_groupe.php?id=<?php echo $don['groupe_id']; ?>" 
                                   style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.4rem 0.8rem; background: linear-gradient(135deg, #1f8c87, #7eddd5); color: #2c3b3aff ; border-radius: 20px; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: all 0.3s ease;">
                                    <span>👥</span>
                                    <span><?php echo htmlspecialchars($don['groupe_nom']); ?></span>
                                </a>
                                <?php if (!empty($don['groupe_type'])): ?>
                                    <span style="background: rgba(125, 90, 166, 0.2); color: #7d5aa6; padding: 0.3rem 0.6rem; border-radius: 15px; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.3rem; margin-left: 0.5rem;">
                                        <span>🏷️</span>
                                        <span><?php echo htmlspecialchars($don['groupe_type']); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- AFFICHAGE DE LA DESCRIPTION -->
                            <?php if (!empty($don['description'])): ?>
                                <p class="card-description"><?php echo nl2br(htmlspecialchars($don['description'])); ?></p>
                            <?php else: ?>
                                <p class="card-description" style="color: #999; font-style: italic;">Aucune description fournie</p>
                            <?php endif; ?>
                            
                            <!-- AFFICHAGE DE L'ÉTAT -->
                            <?php if (!empty($don['etat_object'])): ?>
                                <div class="card-etat">
                                    <strong>⭐ État:</strong> <?php echo htmlspecialchars($don['etat_object']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-actions">
                                <a href="view_don.php?id=<?php echo $don['id']; ?>" class="btn btn-primary">
                                    <span>🔍</span>
                                    <span>Voir détails</span>
                                </a>
                                <?php if (!empty($don['groupe_id'])): ?>
                                <a href="view_groupe.php?id=<?php echo $don['groupe_id']; ?>" class="btn btn-secondary">
                                    <span>👥</span>
                                    <span>Voir le groupe</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Aucun don ne correspond à vos critères.</p>
                    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
                        <a href="browse_dons.php" class="btn btn-primary" style="display: inline-flex; width: auto;">
                            <span>🔍</span>
                            <span>Voir tous les dons</span>
                        </a>
                        <a href="create_don.php" class="btn btn-secondary" style="display: inline-flex; width: auto;">
                            <span>➕</span>
                            <span>Proposer un don</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
        
        <!-- Quick Actions -->
        <section class="quick-actions">
            <h3>Vous ne trouvez pas ce que vous cherchez ?</h3>
            <div class="action-buttons">
                <a href="create_dons.php" class="action-btn">
                    <span>🎁</span>
                    <span>Proposer un don spécifique</span>
                </a>
                <a href="index.php" class="action-btn">
                    <span>🏠</span>
                    <span>Retour à l'accueil</span>
                </a>
                <a href="browse_groupes.php" class="action-btn">
                    <span>👥</span>
                    <span>Voir tous les groupes</span>
                </a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>© 2025 Aide Solidaire - La solidarité en action ❤️</p>
    </footer>

    <script>
        // Remove filter functionality
        function removeFilter(filterName) {
            const url = new URL(window.location);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        }

        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'slideIn 0.5s ease forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe cards when page loads
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                observer.observe(card);
            });
            
            // Auto-hide success messages
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 1000);
                });
            }, 5000);
            
            // Gérer les images qui ne se chargent pas
            document.querySelectorAll('.card-image').forEach(img => {
                img.onerror = function() {
                    this.style.display = 'none';
                    const placeholder = this.nextElementSibling;
                    if (placeholder && placeholder.classList.contains('card-image-placeholder')) {
                        placeholder.style.display = 'flex';
                    }
                };
            });
        });
    </script>
</body>
</html>