<?php
// $requests : liste des demandes d'aide
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>SPARKMIND — Demandes d'aide (Admin)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- tu peux copier le même CSS que users.php pour le look -->
</head>
<body>
  <!-- même header que users.php, avec logo + boutons retour / déconnexion -->

  <main class="admin-main">
    <section class="admin-card">
      <h1>Demandes d'aide</h1>

      <!-- Petit filtre par statut -->
      <form method="get" action="index.php" style="margin-bottom:10px;">
        <input type="hidden" name="page" value="admin_help_requests">
        <label>Statut :
          <select name="statut" onchange="this.form.submit()">
            <?php
              $current = $_GET['statut'] ?? 'all';
              $options = [
                'all'      => 'Toutes',
                'pending'  => 'En attente',
                'accepted' => 'Acceptées',
                'rejected' => 'Refusées',
              ];
              foreach ($options as $value => $label):
            ?>
              <option value="<?= $value ?>" <?= $current === $value ? 'selected' : '' ?>>
                <?= $label ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
      </form>

      <?php if (empty($requests)): ?>
        <p>Aucune demande trouvée.</p>
      <?php else: ?>
        <div class="users-table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Utilisateur</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($requests as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['id']) ?></td>
                <td><?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?><br><small><?= htmlspecialchars($r['email']) ?></small></td>
                <td><?= htmlspecialchars($r['titre']) ?></td>
                <td><?= nl2br(htmlspecialchars($r['contenu'])) ?></td>
                <td><?= htmlspecialchars($r['date_creation']) ?></td>
                <td><?= htmlspecialchars($r['statut']) ?></td>
                <td>
                  <?php if ($r['statut'] === 'pending'): ?>
                    <form method="post" action="index.php?page=admin_help_request_action" style="display:inline;">
                      <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                      <button type="submit" name="action" value="accept">Accepter</button>
                      <button type="submit" name="action" value="reject">Refuser</button>
                    </form>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
