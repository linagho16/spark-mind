<?php
require_once('controller/produitC.php');

// Paramètres de filtre
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : null;
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$etat = isset($_GET['etat']) ? $_GET['etat'] : null;
$condition = isset($_GET['condition']) ? $_GET['condition'] : null;
$tri = isset($_GET['tri']) ? $_GET['tri'] : null;

$produitC = new ProduitC();
$produits = $produitC->filtrerProduits($recherche, $categorie, $etat, $condition, $tri);

// Générer un boundary unique pour le format multipart
$boundary = "----=_NextPart_" . md5(time());
$filename = "produits_export_" . date("Y-m-d_H-i-s") . ".xls";

// En-têtes pour forcer le téléchargement en format Excel (MHTML)
header("Content-Type: application/vnd.ms-excel; name=\"$filename\"");
header("Content-Disposition: attachment; filename=\"$filename\"");

// En-tête MIME principal
echo "MIME-Version: 1.0\r\n";
echo "Content-Type: multipart/related; boundary=\"$boundary\"\r\n\r\n";

// ----------------------------------------
// PARTIE 1 : Document HTML
// ----------------------------------------
echo "--$boundary\r\n";
echo "Content-Location: file:///C:/dummy.htm\r\n";
echo "Content-Transfer-Encoding: 8bit\r\n";
echo "Content-Type: text/html; charset=\"utf-8\"\r\n\r\n";

// Tableau pour collecter les images à attacher
$attachments = [];
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<style>
    body { font-family: Arial, sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 10px; text-align: left; vertical-align: middle; }
    th { background-color: #1f8c87; color: #fff; font-weight: bold; text-align: center; height: 50px; }
    .header-title { font-size: 24px; font-weight: bold; margin-bottom: 20px; text-align: center; color: #1f8c87; }
    .img-cell { text-align: center; width: 80px; height: 80px; }
    .status-disponible { color: #28a745; font-weight: bold; }
    .status-reserve { color: #ffc107; font-weight: bold; }
    .status-vendu { color: #dc3545; font-weight: bold; }
</style>
</head>
<body>

    <div class="header-title">Liste des Produits - SparkMind</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th width="80">Photo</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Condition</th>
                <th>Statut</th>
                <th>Date Ajout</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td style="text-align: center;">#<?php echo $produit['id']; ?></td>
                    <td class="img-cell">
                        <?php 
                        $imgSrc = "";
                        if (!empty($produit['photo'])) {
                            // Liste des chemins possibles à tester
                            $candidates = [];
                            
                            // 1. Chemin tel quel (ex: absolu ou relatif valide)
                            $candidates[] = $produit['photo'];
                            
                            // 2. Dans le dossier uploads (relatif)
                            $candidates[] = 'uploads/' . $produit['photo'];
                            
                            // 3. Juste le nom de fichier dans uploads (nettoyage type basename)
                            $candidates[] = 'uploads/' . basename($produit['photo']);
                            
                            // 4. Chemin absolu construit
                            $candidates[] = __DIR__ . '/uploads/' . basename($produit['photo']);

                            $imagePath = null;
                            foreach ($candidates as $testPath) {
                                if (file_exists($testPath) && is_file($testPath)) {
                                    $imagePath = $testPath;
                                    break;
                                }
                            }

                            // Si image trouvée, on prépare l'attachement
                            if ($imagePath) {
                                $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                                if($ext == 'jpg' || $ext == 'jpeg') $ext = 'jpeg';
                                elseif($ext == 'png') $ext = 'png';
                                elseif($ext == 'gif') $ext = 'gif';
                                else $ext = 'jpeg'; // Fallback
                                
                                // ID unique pour cette image
                                $cid = "img_" . $produit['id'] . "_" . md5($imagePath);
                                $mime = "image/" . $ext;

                                // On stocke les données pour la fin du fichier
                                if (!isset($attachments[$cid])) {
                                    $attachments[$cid] = [
                                        'path' => $imagePath,
                                        'mime' => $mime
                                    ];
                                }
                                $imgSrc = "cid:" . $cid;
                            }
                        }
                        ?>
                        
                        <?php if (!empty($imgSrc)): ?>
                            <img src="<?php echo $imgSrc; ?>" width="60" height="60" style="object-fit: contain; vertical-align: middle;">
                        <?php else: ?>
                            <span style="color: #999; font-size: 10px;">Aucune photo</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo htmlspecialchars($produit['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($produit['description']); ?></td>
                    <td><?php echo htmlspecialchars($produit['nomC']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($produit['condition'])); ?></td>
                    <td>
                        <?php $sClass = 'status-' . strtolower($produit['statut']); ?>
                        <span class="<?php echo $sClass; ?>"><?php echo htmlspecialchars(ucfirst($produit['statut'])); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($produit['dateC']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php
echo "\r\n\r\n";

// ----------------------------------------
// PARTIE 2 : Attachements (Images)
// ----------------------------------------
foreach ($attachments as $cid => $info) {
    if (file_exists($info['path'])) {
        $data = file_get_contents($info['path']);
        $base64 = chunk_split(base64_encode($data));
        
        echo "--$boundary\r\n";
        echo "Content-Location: file:///C:/fake/" . $cid . "\r\n"; 
        echo "Content-ID: <$cid>\r\n";
        echo "Content-Type: " . $info['mime'] . "\r\n";
        echo "Content-Transfer-Encoding: base64\r\n\r\n";
        echo $base64;
        echo "\r\n";
    }
}

echo "--$boundary--\r\n";
exit;
?>
