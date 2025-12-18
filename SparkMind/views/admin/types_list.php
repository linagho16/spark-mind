<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Types de Donations - BackOffice</title>
    <link rel="stylesheet" href="assets/css/sty.css" />
</head>
<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="assets/img/Logo__1_-removebg-preview.png" alt="SparkMind logo" />
            <div class="title-block">
                <h1>Types de Donations</h1>
                <p class="subtitle">BackOffice - Administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="index.php?action=admin" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au dashboard
        </a>

        <div class="post-item">
            <h2>📋 Types de donations (<?= count($types) ?>)</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php foreach($types as $type): ?>
                    <div class="post-item" style="text-align: center; padding: 20px;">
                        <div style="font-size: 48px; margin-bottom: 10px;"><?= $type['icon'] ?></div>
                        <h3 style="margin-bottom: 8px; color: <?= $type['color'] ?>;">
                            <?= htmlspecialchars($type['name']) ?>
                        </h3>
                        <p style="font-size: 13px; color: #718096;">
                            <?= htmlspecialchars($type['description']) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>