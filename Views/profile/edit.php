<?php
// Vue édition de profil (ProfileController::edit)
// variable disponible : $user
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier mon profil — SPARKMIND</title>

  <!-- Polices (comme dans show.php) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Play...Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles globaux du profil (même fichier que show.php) -->
  <link rel="stylesheet" href="profil.css">

  <!-- Styles spécifiques à la page d’édition -->
  <style>
    .edit-form {
      display: grid;
      gap: 12px;
      margin-top: 18px;
    }

    .edit-form .field-group {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .edit-form label {
      font-size: 13px;
      font-weight: 600;
      color: #555;
    }

    .edit-form input {
      padding: 8px 12px;
      border-radius: 12px;
      border: 1px solid rgba(0,0,0,0.12);
      font-size: 14px;
      outline: none;
    }

    .edit-form input:focus {
      border-color: #1f8c87;
      box-shadow: 0 0 0 2px rgba(31,140,135,0.15);
    }

    .actions {
      margin-top: 20px;
      display: flex;
      gap: 12px;
    }

    .btn-primary {
      border: none;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <!-- 🔹 Barre du haut identique à la page profil -->
  <header class="main-header top-nav">
    <div class="brand-block">
      <img src="images/logo.jpg" alt="Logo SPARKMIND" class="logo-img">
    </div>

  </header>

  <!-- 🔹 Contenu principal identique, mais avec formulaire -->
  <main class="profile-container">

    <section class="profile-card">

      
    <div class="photo-section">
    <img
        src="<?= !empty($user['photo']) ? htmlspecialchars($user['photo']) : 'profil.jpg' ?>"
        class="profile-img"
        alt="Photo de profil">


        <form action="index.php?page=upload_photo" method="post" enctype="multipart/form-data" style="margin-top:10px;">
            <input
                type="file"
                name="photo"
                accept="image/*"
                required
                onchange="previewPhoto(event)">
            <button type="submit" class="change-btn">📤 Télécharger la photo</button>
        </form>
    </div>


</form>

      </div>

      <!-- Titre -->
      <h1 class="name">
        <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
      </h1>
      <p class="role">
        Modification de votre profil
      </p>

      <!-- Formulaire d’édition -->
      <form action="index.php?page=profile_edit" method="post" class="info-section edit-form">

        <div class="field-group">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom"
                 value="<?= htmlspecialchars($user['nom']); ?>" required>
        </div>

        <div class="field-group">
          <label for="prenom">Prénom</label>
          <input type="text" id="prenom" name="prenom"
                 value="<?= htmlspecialchars($user['prenom']); ?>" required>
        </div>

        <div class="field-group">
          <label for="naissance">Date de naissance</label>
          <input type="date" id="naissance" name="naissance"
                 value="<?= htmlspecialchars($user['naissance']); ?>">
        </div>

        <div class="field-group">
          <label for="tel">Numéro de téléphone</label>
          <input type="text" id="tel" name="tel"
                 value="<?= htmlspecialchars($user['tel']); ?>">
        </div>

        <div class="field-group">
          <label for="adresse">Adresse</label>
          <input type="text" id="adresse" name="adresse"
                 value="<?= htmlspecialchars($user['adresse']); ?>">
        </div>

        <div class="field-group">
          <label for="ville">Ville</label>
          <input type="text" id="ville" name="ville"
                 value="<?= htmlspecialchars($user['ville']); ?>">
        </div>

        <div class="field-group">
          <label for="profession">Profession</label>
          <input type="text" id="profession" name="profession"
                 value="<?= htmlspecialchars($user['profession']); ?>">
        </div>

        <div class="field-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
                 value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="actions">
          <a href="index.php?page=profile" class="btn-ghost">⬅ Retour</a>
          <button type="submit" class="btn-primary">💾 Enregistrer</button>
        </div>

      </form>

    </section>

  </main>

  <a class="help" href="#" title="Aide">?</a>

</body>
</html>
