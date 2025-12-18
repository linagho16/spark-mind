<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Gestion des Posts - BackOffice</title>
    <link rel="stylesheet" href="assets/css/sty.css" />

    <!-- ✅ Même style CSS SparkMind (identique aux pages précédentes) -->
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

        /* Header sticky + blur */
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

        /* Main wrapper */
        .wrap{
            max-width:1100px;
            margin: 28px auto 40px;
            padding: 0 18px 30px;
        }

        /* Boutons */
        .btn-view,
        .btn-comment{
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
            transition: transform .15s ease, filter .15s ease, box-shadow .15s ease;
        }

        .btn-view:hover,
        .btn-comment:hover{
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        .btn-view:active,
        .btn-comment:active{
            transform: translateY(0);
        }

        /* Card principale */
        .post-item{
            background: rgba(255, 247, 239, 0.95);
            border-radius: 24px;
            padding: 18px 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
            border: 1px solid rgba(0,0,0,.06);
        }

        .post-item h2{
            margin:0 0 12px;
            font-family:'Playfair Display', serif;
            color:#1A464F;
        }

        /* Table harmonisée */
        table{
            width:100%;
            border-collapse: collapse;
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            border: 1px solid rgba(0,0,0,.06);
        }

        thead tr{
            background: rgba(245,226,196,.55) !important;
            border-bottom: 1px solid rgba(0,0,0,.06) !important;
        }

        th, td{
            padding: 12px !important;
            font-size: 13px;
            border-bottom: 1px solid rgba(0,0,0,.06);
            vertical-align: top;
            color:#1A464F;
        }

        tbody tr:hover{
            background: rgba(245,226,196,.30);
        }

        /* Badge type */
        .donation-badge{
            display:inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight:700;
            color:#fff;
            white-space: nowrap;
        }

        /* Boutons danger */
        button{
            border:none;
            cursor:pointer;
            border-radius: 999px;
            font-weight:700;
            color:#fff;
            transition: transform .15s ease, filter .15s ease;
        }

        button:hover{
            transform: translateY(-1px);
            filter: brightness(1.02);
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
                <h1>Gestion des Posts</h1>
                <p class="subtitle">BackOffice - Administration</p>
            </div>
        </div>
    </header>

    <main class="wrap" style="grid-template-columns: 1fr;">
        <a href="/sparkmind_mvc_100percent/index.php?page=admin_forum" class="btn-view" style="width: fit-content; margin-bottom: 20px;">
            ← Retour au dashboard
        </a>

        <div class="post-item">
            <h2>📝 Liste des posts (<?= count($posts) ?>)</h2>

            <?php if (empty($posts)): ?>
                <p style="text-align: center; color: #718096; padding: 30px;">Aucun post pour le moment.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #fef8f3; border-bottom: 2px solid #e8d5c4;">
                                <th>ID</th>
                                <th>Type</th>
                                <th>Titre</th>
                                <th>Contenu</th>
                                <th>Date</th>
                                <th style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($posts as $post): ?>
                                <tr>
                                    <td><?= $post['id'] ?></td>
                                    <td>
                                        <span class="donation-badge" style="background-color: <?= $post['color'] ?? '#667eea' ?>">
                                            <?= $post['icon'] ?? '🎁' ?> <?= htmlspecialchars($post['type_name'] ?? 'Autre') ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($post['titre'] ?: '-') ?></td>
                                    <td style="max-width: 300px;">
                                        <?= substr(htmlspecialchars($post['contenu']), 0, 80) ?>...
                                    </td>
                                    <td style="font-size: 12px; color: #718096;">
                                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                    </td>
                                    <td style="text-align:center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="index.php?action=show&id=<?= $post['id'] ?>" class="btn-comment" style="font-size: 12px;">👁️ Voir</a>
                                            <form method="post" action="index.php?action=admin_delete_post" onsubmit="return confirm('Supprimer ce post ?');" style="margin: 0;">
                                                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                                <button type="submit" style="background: #e53e3e; padding: 6px 12px; font-size: 12px;">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
