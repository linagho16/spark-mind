<?php
// updatedon.php - Add this at the VERY TOP
session_start();

// Check if don data is passed (from controller)
if (!isset($don)) {
    // If accessed directly, redirect to dons list
    header('Location: ../index.php?action=dons&message=not_found');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Don - Admin</title>
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

        /* SIDEBAR */
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

        /* FORM STYLES */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin: 1rem 0;
            max-width: 800px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1f8c87;
            box-shadow: 0 0 0 3px rgba(31, 140, 135, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .btn-save {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(31, 140, 135, 0.3);
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 140, 135, 0.4);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
        }
        
        .message-error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
            font-weight: 500;
        }
        
        .message-success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #c3e6cb;
            font-weight: 500;
        }
        
        .photo-preview {
            margin-top: 0.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px dashed #dee2e6;
        }
        
        .photo-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
        }
        
        .remove-photo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .remove-photo input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .remove-photo label {
            display: inline;
            font-weight: normal;
            margin: 0;
            cursor: pointer;
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        .form-help {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
            font-style: italic;
        }
        
        .current-value {
            background: #e9ecef;
            padding: 0.5rem;
            border-radius: 6px;
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: #495057;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1.5rem 0;
        }
        
        .info-section h4 {
            margin: 0 0 1rem 0;
            color: #495057;
            font-size: 1.1rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            font-size: 0.9rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .badge {
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .form-grid {
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

            .form-container {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-cancel, .btn-save {
                width: 100%;
                text-align: center;
                justify-content: center;
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
            <a href="/aide_solitaire/index.php?action=dons" class="nav-item">
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
                <h1>Modifier le Don #<?php echo $don['id']; ?></h1>
                <p>Modifiez les informations de ce don</p>
            </div>
            <div class="header-right">
                <a href="/aide_solitaire/index.php?action=dons" class="btn-cancel">
                    ← Retour à la liste
                </a>
            </div>
        </header>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['message'])): ?>
            <?php
            $messages = [
                'updated' => ['type' => 'success', 'text' => 'Don modifié avec succès!'],
                'error' => ['type' => 'error', 'text' => 'Erreur lors de la modification du don!'],
                'not_found' => ['type' => 'error', 'text' => 'Don non trouvé!']
            ];
            $message = $messages[$_GET['message']] ?? null;
            ?>
            <?php if ($message): ?>
                <div class="message-<?php echo $message['type']; ?>">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="message-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <!-- FIXED FORM ACTION -->
            <form method="POST" action="/aide_solitaire/index.php?action=update_don&id=<?php echo $don['id']; ?>">
                <div class="form-grid">
                    <!-- Type de Don -->
                    <div class="form-group">
                        <label for="type_don" class="required-field">Type de don</label>
                        <select id="type_don" name="type_don" class="form-select" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="Vêtements" <?php echo $don['type_don'] == 'Vêtements' ? 'selected' : ''; ?>>👕 Vêtements</option>
                            <option value="Nourriture" <?php echo $don['type_don'] == 'Nourriture' ? 'selected' : ''; ?>>🍞 Nourriture</option>
                            <option value="Médicaments" <?php echo $don['type_don'] == 'Médicaments' ? 'selected' : ''; ?>>💊 Médicaments</option>
                            <option value="Équipement" <?php echo $don['type_don'] == 'Équipement' ? 'selected' : ''; ?>>🔧 Équipement</option>
                            <option value="Argent" <?php echo $don['type_don'] == 'Argent' ? 'selected' : ''; ?>>💰 Argent</option>
                            <option value="Services" <?php echo $don['type_don'] == 'Services' ? 'selected' : ''; ?>>🤝 Services</option>
                            <option value="Autre" <?php echo $don['type_don'] == 'Autre' ? 'selected' : ''; ?>>🎁 Autre</option>
                        </select>
                        <div class="form-help">Choisissez le type de don</div>
                    </div>

                    <!-- Quantité -->
                    <div class="form-group">
                        <label for="quantite" class="required-field">Quantité</label>
                        <input type="number" id="quantite" name="quantite" class="form-control" value="<?php echo htmlspecialchars($don['quantite']); ?>" required min="1" step="1">
                        <div class="form-help">Nombre d'articles ou montant</div>
                    </div>

                    <!-- État de l'objet -->
                    <div class="form-group">
                        <label for="etat_object">État de l'objet</label>
                        <input type="text" id="etat_object" name="etat_object" class="form-control" value="<?php echo htmlspecialchars($don['etat_object'] ?? ''); ?>" placeholder="Ex: Neuf, Bon état, Usé, Comme neuf...">
                        <div class="form-help">Décrivez l'état des articles (optionnel)</div>
                    </div>

                    <!-- Région -->
                    <div class="form-group">
                        <label for="region" class="required-field">Région</label>
                        <select id="region" name="region" class="form-select" required>
                            <option value="">Sélectionnez une région</option>
                            <option value="Tunis" <?php echo $don['region'] == 'Tunis' ? 'selected' : ''; ?>>Tunis</option>
                            <option value="Sfax" <?php echo $don['region'] == 'Sfax' ? 'selected' : ''; ?>>Sfax</option>
                            <option value="Sousse" <?php echo $don['region'] == 'Sousse' ? 'selected' : ''; ?>>Sousse</option>
                            <option value="Kairouan" <?php echo $don['region'] == 'Kairouan' ? 'selected' : ''; ?>>Kairouan</option>
                            <option value="Bizerte" <?php echo $don['region'] == 'Bizerte' ? 'selected' : ''; ?>>Bizerte</option>
                            <option value="Gabès" <?php echo $don['region'] == 'Gabès' ? 'selected' : ''; ?>>Gabès</option>
                            <option value="Ariana" <?php echo $don['region'] == 'Ariana' ? 'selected' : ''; ?>>Ariana</option>
                            <option value="Gafsa" <?php echo $don['region'] == 'Gafsa' ? 'selected' : ''; ?>>Gafsa</option>
                            <option value="Monastir" <?php echo $don['region'] == 'Monastir' ? 'selected' : ''; ?>>Monastir</option>
                            <option value="Autre" <?php echo $don['region'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                        </select>
                        <div class="form-help">Région de disponibilité du don</div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label for="description">Description détaillée</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez le don en détail... (matériaux, dimensions, spécificités, etc.)"><?php echo htmlspecialchars($don['description']); ?></textarea>
                    <div class="form-help">Fournissez une description complète du don</div>
                </div>

                <!-- Informations de base -->
                <div class="info-section">
                    <h4>Informations de base</h4>
                    <div class="info-grid">
                        <div>
                            <strong>ID du don:</strong> #<?php echo $don['id']; ?>
                        </div>
                        <div>
                            <strong>Date de création:</strong> <?php echo date('d/m/Y à H:i', strtotime($don['date_don'])); ?>
                        </div>
                        <div>
                            <strong>Statut:</strong> 
                            <span class="badge badge-active"><?php echo ucfirst($don['statut'] ?? 'actif'); ?></span>
                        </div>
                        <div>
                            <strong>Dernière modification:</strong> 
                            <?php echo isset($don['date_modification']) ? date('d/m/Y à H:i', strtotime($don['date_modification'])) : 'Jamais'; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="/aide_solitaire/index.php?action=dons" class="btn-cancel">
                        Annuler
                    </a>
                    <button type="submit" class="btn-save">
                        💾 Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.message-success, .message-error');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const quantite = document.getElementById('quantite').value;
            if (quantite < 1) {
                alert('La quantité doit être au moins 1');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>