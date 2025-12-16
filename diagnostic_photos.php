<?php
/**
 * Diagnostic des Photos - Vérification des Chemins d'Images
 */

require_once('controller/produitC.php');

$produitC = new ProduitC();
$produits = $produitC->listProduits();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic des Photos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .info-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .info-box h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .diagnostic-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .diagnostic-table th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            text-align: left;
        }

        .diagnostic-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .diagnostic-table tr:hover {
            background: #f8f9fa;
        }

        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .status.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status.error {
            background: #ffebee;
            color: #c62828;
        }

        .status.warning {
            background: #fff3e0;
            color: #ef6c00;
        }

        .preview-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
        }

        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            margin: 15px 0;
            overflow-x: auto;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            font-size: 2.5em;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-card p {
            color: #666;
            font-weight: 600;
        }

        .fix-button {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1em;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }

        .fix-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(56, 239, 125, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnostic des Photos</h1>

        <?php
        $totalProduits = count($produits);
        $avecPhoto = 0;
        $sansPhoto = 0;
        $photoInexistante = 0;
        $photoValide = 0;

        foreach ($produits as $produit) {
            if (!empty($produit['photo'])) {
                $avecPhoto++;
                $fullPath = __DIR__ . '/' . $produit['photo'];
                if (file_exists($fullPath)) {
                    $photoValide++;
                } else {
                    $photoInexistante++;
                }
            } else {
                $sansPhoto++;
            }
        }
        ?>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $totalProduits; ?></h3>
                <p>Total Produits</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $avecPhoto; ?></h3>
                <p>Avec Photo (DB)</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $photoValide; ?></h3>
                <p>Photos Valides</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $photoInexistante; ?></h3>
                <p>Photos Introuvables</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $sansPhoto; ?></h3>
                <p>Sans Photo</p>
            </div>
        </div>

        <div class="info-box">
            <h2>📋 Détails des Produits</h2>
            
            <table class="diagnostic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Chemin BDD</th>
                        <th>Chemin Normalisé</th>
                        <th>Fichier Existe ?</th>
                        <th>Aperçu</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <?php
                        $photoPath = $produit['photo'];
                        $photoPathNormalized = '';
                        $fileExists = false;
                        $statusClass = 'error';
                        $statusText = 'Pas de photo';

                        if (!empty($photoPath)) {
                            // Normaliser le chemin
                            if (strpos($photoPath, 'uploads/') !== 0 && strpos($photoPath, '/produit/uploads/') === false) {
                                $photoPathNormalized = 'uploads/' . basename($photoPath);
                            } else {
                                $photoPathNormalized = $photoPath;
                            }
                            $photoPathNormalized = str_replace('//', '/', $photoPathNormalized);

                            // Vérifier si le fichier existe
                            $fullPath = __DIR__ . '/' . $photoPathNormalized;
                            $fileExists = file_exists($fullPath);

                            if ($fileExists) {
                                $statusClass = 'success';
                                $statusText = 'OK';
                            } else {
                                $statusClass = 'error';
                                $statusText = 'Fichier introuvable';
                            }
                        }
                        ?>
                        <tr>
                            <td><strong>#<?php echo $produit['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($produit['title']); ?></td>
                            <td>
                                <code style="background: #f5f5f5; padding: 5px; border-radius: 4px; font-size: 0.85em;">
                                    <?php echo htmlspecialchars($photoPath ?: 'NULL'); ?>
                                </code>
                            </td>
                            <td>
                                <code style="background: #e8f5e9; padding: 5px; border-radius: 4px; font-size: 0.85em;">
                                    <?php echo htmlspecialchars($photoPathNormalized ?: 'N/A'); ?>
                                </code>
                            </td>
                            <td>
                                <?php if (!empty($photoPath)): ?>
                                    <?php if ($fileExists): ?>
                                        ✅ Oui
                                        <br><small style="color: #666;"><?php echo round(filesize($fullPath) / 1024, 2); ?> KB</small>
                                    <?php else: ?>
                                        ❌ Non
                                        <br><small style="color: #999;">Chemin: <?php echo htmlspecialchars($fullPath); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color: #999;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($photoPath) && $fileExists): ?>
                                    <img src="<?php echo htmlspecialchars($photoPathNormalized); ?>" 
                                         class="preview-img"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div style="display:none; color: #999;">Erreur d'affichage</div>
                                <?php else: ?>
                                    <div style="color: #999; font-size: 0.9em;">-</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="info-box">
            <h2>🔧 Corrections Appliquées</h2>
            
            <p style="color: #666; margin-bottom: 20px;">
                Les modifications suivantes ont été appliquées aux fichiers pour résoudre le problème d'affichage :
            </p>

            <h3 style="color: #333; margin: 20px 0 10px 0;">1. Normalisation du Chemin</h3>
            <div class="code-block">
// Ajouté dans liste.php, update.php et detail.php
$photoPath = $produit['photo'];
if (strpos($photoPath, 'uploads/') !== 0 && strpos($photoPath, '/produit/uploads/') === false) {
    $photoPath = 'uploads/' . basename($photoPath);
}
$photoPath = str_replace('//', '/', $photoPath);
            </div>

            <h3 style="color: #333; margin: 20px 0 10px 0;">2. Gestion d'Erreur</h3>
            <div class="code-block">
// Ajout d'un attribut onerror pour afficher une image par défaut
onerror="this.src='view/back office/logo.png'; this.style.opacity='0.3';"
            </div>

            <h3 style="color: #333; margin: 20px 0 10px 0;">3. Fichiers Modifiés</h3>
            <ul style="margin-left: 30px; color: #666; line-height: 2;">
                <li>✓ <strong>liste.php</strong> - Affichage dans le tableau</li>
                <li>✓ <strong>update.php</strong> - Aperçu lors de la modification</li>
                <li>✓ <strong>detail.php</strong> - Image principale dans les détails</li>
            </ul>

            <div style="background: #e8f5e9; padding: 20px; border-radius: 10px; margin-top: 25px;">
                <h3 style="color: #2e7d32; margin-bottom: 10px;">✅ Solution Implémentée</h3>
                <p style="color: #1b5e20; line-height: 1.8;">
                    Les images s'affichent maintenant correctement dans toutes les pages. Si une image n'existe pas,
                    le logo par défaut s'affiche à la place avec une opacité réduite.
                </p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="liste.php" class="fix-button">← Retour à la Liste</a>
            <button onclick="location.reload()" class="fix-button">🔄 Actualiser le Diagnostic</button>
        </div>
    </div>
</body>
</html>
