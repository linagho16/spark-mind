<?php
// Vérifier que $event existe avant de l'utiliser
if (!isset($event) || empty($event)) {
    header('Location: index.php?action=events');
    exit;
}

$pageTitle = "Modifier l'Événement";
ob_start();

// Afficher les messages de session
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>

<div class="page-header">
    <h2>Modifier l'Événement</h2>
    <a href="index.php?action=events" class="btn btn-secondary">← Retour à la liste</a>
</div>

<form action="process_event.php?action=update&id=<?php echo $event['id']; ?>" method="POST" class="event-form">
    <div class="form-group">
        <label for="titre">Titre *</label>
        <input type="text" id="titre" name="titre" class="form-control" 
               value="<?php echo htmlspecialchars($event['titre'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Description *</label>
        <textarea id="description" name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="lieu">Lieu *</label>
            <input type="text" id="lieu" name="lieu" class="form-control" 
                   value="<?php echo htmlspecialchars($event['lieu'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="prix">Prix (€)</label>
            <input type="number" id="prix" name="prix" class="form-control" 
                   step="0.01" min="0" value="<?php echo htmlspecialchars($event['prix'] ?? '0'); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="date_event">Date de l'événement *</label>
        <input type="date" id="date_event" name="date_event" class="form-control" 
               value="<?php echo !empty($event['date_event']) ? date('Y-m-d', strtotime($event['date_event'])) : ''; ?>" required>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="index.php?action=events" class="btn btn-secondary">Annuler</a>
        <a href="index.php?action=delete&id=<?php echo $event['id']; ?>" 
           class="btn btn-danger" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
            Supprimer
        </a>
    </div>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>