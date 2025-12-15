<?php
session_start();
require_once __DIR__ . '/../../Model/donmodel.php';
require_once __DIR__ . '/../../Model/groupemodel.php';


$donModel = new DonModel();
$groupeModel = new GroupeModel();

// Get donations without groups
$donsWithoutGroup = $donModel->getDonsWithFiltersAndGroupes([
    'without_groupe' => true,
    'statut' => 'frontoffice'
]);

// Get all active groups
$groupes = $groupeModel->getGroupesWithFilters(['statut' => 'actif']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['don_id']) && isset($_POST['groupe_id'])) {
    $success = $donModel->assignToGroupe($_POST['don_id'], $_POST['groupe_id']);
    if ($success) {
        $message = "Don #{$_POST['don_id']} assigné au groupe avec succès!";
        // Refresh the list
        $donsWithoutGroup = $donModel->getDonsWithFiltersAndGroupes([
            'without_groupe' => true,
            'statut' => 'frontoffice'
        ]);
    } else {
        $error = "Erreur lors de l'assignation";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner des Dons aux Groupes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        .assign-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        select, button {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Assigner des Dons aux Groupes</h1>
        
        <?php if (isset($message)): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <h2>Dons sans groupe (<?php echo count($donsWithoutGroup); ?>)</h2>
        
        <?php if (empty($donsWithoutGroup)): ?>
            <p>Tous les dons sont assignés à des groupes! 🎉</p>
            <p><a href="browse_dons.php">Voir les dons avec groupes</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Région</th>
                        <th>Date</th>
                        <th>Assigner à</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donsWithoutGroup as $don): ?>
                    <tr>
                        <td>#<?php echo $don['id']; ?></td>
                        <td><?php echo htmlspecialchars($don['type_don']); ?></td>
                        <td><?php echo htmlspecialchars($don['quantite']); ?></td>
                        <td><?php echo htmlspecialchars($don['region']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($don['date_don'])); ?></td>
                        <td>
                            <form method="POST" class="assign-form">
                                <input type="hidden" name="don_id" value="<?php echo $don['id']; ?>">
                                <select name="groupe_id" required>
                                    <option value="">Sélectionner un groupe</option>
                                    <?php foreach ($groupes as $groupe): ?>
                                        <?php if ($groupe['region'] === $don['region'] || $groupe['region'] === 'National'): ?>
                                        <option value="<?php echo $groupe['id']; ?>">
                                            <?php echo htmlspecialchars($groupe['nom']); ?> 
                                            (<?php echo htmlspecialchars($groupe['region']); ?>)
                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">Assigner</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <p><a href="browse_dons.php">← Retour aux dons</a></p>
    </div>
</body>
</html>