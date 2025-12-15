<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Types de Sujet - BackOffice</title>
    <link rel="stylesheet" href="assets/css/sty.css" />

    <!-- ✅ CSS SparkMind identique aux autres pages BackOffice -->
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}

        body{
            min-height:100vh;
            font-family:'Poppins',system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
            color:#1A464F;
            background:
              radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
              radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
              #FBEDD7;
        }

        /* Header */
        .toppage{
            position: sticky;
            top: 0;
            z-index: 90;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            background: rgba(251, 237, 215, 0.96);
            border-bottom: 1px solid rgba(0,0,0,.06);
            padding: 10px 24px;
        }

        .logo-title{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .logo-title img{
            width:44px;
            height:44px;
            border-radius:50%;
            object-fit:cover;
        }

        .title-block h1{
            font-family:'Playfair Display',serif;
            font-size:20px;
            margin:0;
            color:#1A464F;
        }

        .subtitle{
            margin:0;
            font-size:12px;
            color:#1A464F;
            opacity:.8;
        }

        /* Wrapper */
        .wrap{
            max-width:1100px;
            margin: 28px auto 40px;
            padding: 0 18px 30px;
        }

        /* Boutons */
        .btn-view{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            border:none;
            cursor:pointer;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            background:#1A464F;
            color:#fff;
            text-decoration:none;
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
            transition: transform .15s ease, filter .15s ease;
        }

        .btn-view:hover{
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        /* Cards */
        .post-item{
            background: rgba(255, 247, 239, 0.95);
            border-radius: 24px;
            padding: 18px 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
            border: 1px solid rgba(0,0,0,.06);
        }

        .post-item h2,
        .post-item h3{
            margin:0 0 10px;
            font-family:'Playfair Display', serif;
            color:#1A464F;
        }

        /* Grid cards */
        .post-item > div.post-item{
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .post-item > div.post-item:hover{
            transform: translateY(-4px);
            box-shadow: 0 14px 26px rgba(0,0,0,0.22);
        }

        /* Text override inline */
        p[style*="color: #718096"]{
            color:#6B5F55 !important;
        }

        /* Responsive */
        @media (max-width: 768px){
            .toppage{ padding: 10px 14px; }
            .wrap{ margin: 18px auto 30px; padding: 0 14px 20px; }
            .logo-title img{ width:40px;height:40px; }
            .title-block h1{ font-size:18px; }
        }
    </style>
</head>

<body>
    <header class="toppage">
        <div class="logo-title">
            <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="SparkMind logo" />

            <div class="title-block">
                <h1>Types de sujet</h1>
                <p class="subtitle">BackOffice - Administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
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
