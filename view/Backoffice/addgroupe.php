<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Groupe - Dashboard Admin</title>
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

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            width: calc(100% - 260px);
        }

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

        .btn-primary {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
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
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
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
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .form-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #1f8c87;
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-container {
                padding: 1.5rem;
            }
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 6px solid #ef4444;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e1e5e9;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <!-- SIDEBAR NAVIGATION - Add this to your backoffice files -->
<div class="sidebar">
    <!-- Logo -->
    <div class="logo">
        <h2>🤝 Aide Solidaire</h2>
        <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">Administration</p>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <!-- Dashboard -->
        <a href="/aide_solitaire/controller/donC.php?action=dashboard" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php' || (!isset($_GET['action']) && isset($_GET['action']) == 'dashboard')) ? 'active' : ''; ?>">
            <span class="icon">📊</span>
            <span>Tableau de bord</span>
        </a>
        
        <!-- Donations Section -->
        <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600;">
            GESTION DES DONS
        </div>
        
        <a href="/aide_solitaire/controller/donC.php?action=dons" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'dons') ? 'active' : ''; ?>">
            <span class="icon">🎁</span>
            <span>Tous les dons</span>
        </a>
        
        <a href="/aide_solitaire/controller/donC.php?action=create_don" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'create_don') ? 'active' : ''; ?>">
            <span class="icon">➕</span>
            <span>Ajouter un don</span>
        </a>
        
        <a href="/aide_solitaire/controller/donC.php?action=statistics" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'statistics') ? 'active' : ''; ?>">
            <span class="icon">📈</span>
            <span>Statistiques dons</span>
        </a>
        
        <!-- Groups Section -->
        <div style="padding: 1rem 0.5rem 0.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 600; margin-top: 1rem;">
            GESTION DES GROUPES
        </div>
        
        <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'groupes') ? 'active' : ''; ?>">
            <span class="icon">👥</span>
            <span>Tous les groupes</span>
        </a>
        
        <a href="/aide_solitaire/controller/groupeC.php?action=create_groupe" class="nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'create_groupe') ? 'active' : ''; ?>">
            <span class="icon">➕</span>
            <span>Ajouter un groupe</span>
        </a>
        
        <!-- FrontOffice Link -->
        <a href="/aide_solitaire/view/frontoffice/index.php" style="display: block; text-align: center; margin-top: 0.5rem; padding: 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; text-decoration: none; color: white; font-size: 0.9rem; transition: all 0.3s ease;">
            <span>🌐</span>
            <span>Voir le site public</span>
        </a>
    </div>
</div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1>Créer un Nouveau Groupe</h1>
                <p>Ajoutez un nouveau groupe de solidarité</p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-secondary">← Retour</a>
            </div>
        </header>

        <!-- Error Message -->
        <?php if (isset($error) && $error): ?>
            <div class="error-message">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Creation Form -->
        <div class="form-container">
            <form method="POST" action="/aide_solitaire/controller/groupeC.php?action=create_groupe">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom du groupe *</label>
                        <input type="text" name="nom" class="form-control" required 
                               placeholder="Ex: Association Solidarité Tunis">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Type de groupe *</label>
                        <select name="type" class="form-control" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Santé">🏥 Santé</option>
                            <option value="Éducation">📚 Éducation</option>
                            <option value="Seniors">👵 Seniors</option>
                            <option value="Jeunesse">👦 Jeunesse</option>
                            <option value="Culture">🎨 Culture</option>
                            <option value="Urgence">🚨 Urgence</option>
                            <option value="Animaux">🐾 Animaux</option>
                            <option value="Environnement">🌿 Environnement</option>
                            <option value="Religieux">🌙 Religieux</option>
                            <option value="Social">🤝 Social</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Région *</label>
                        <select name="region" class="form-control" required>
                            <option value="">Sélectionner une région</option>
                            <option value="Tunis">Tunis</option>
                            <option value="Sfax">Sfax</option>
                            <option value="Sousse">Sousse</option>
                            <option value="Kairouan">Kairouan</option>
                            <option value="Bizerte">Bizerte</option>
                            <option value="Gabès">Gabès</option>
                            <option value="Ariana">Ariana</option>
                            <option value="Gafsa">Gafsa</option>
                            <option value="Monastir">Monastir</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Responsable *</label>
                        <input type="text" name="responsable" class="form-control" required 
                               placeholder="Ex: Mohamed Ali">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required 
                               placeholder="exemple@association.tn">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Téléphone *</label>
                        <input type="tel" name="telephone" class="form-control" required 
                               placeholder="Ex: +216 12 345 678">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description du groupe</label>
                    <textarea name="description" class="form-control" 
                              placeholder="Décrivez les activités et objectifs du groupe..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">➕ Créer le groupe</button>
                    <a href="/aide_solitaire/controller/groupeC.php?action=groupes" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>