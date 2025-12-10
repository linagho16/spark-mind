<?php
// Recherche, Tri et Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $perPage;

if ($search) {
    $totalEvents = $eventModel->countAllEvents($search);
    $events = $eventModel->search($search, $perPage, $offset, $sortBy);
} else {
    $totalEvents = $eventModel->countAllEvents();
    $events = $eventModel->getAllEvents($perPage, $offset, $sortBy);
}

$totalPages = ceil($totalEvents / $perPage);
?>
<div class="page-title">
    <span style="font-size: 1.5em;">📅</span>
    <h1>Liste des Événements</h1>
</div>

<?php if ($search): ?>
<div class="search-info">
    <p>🔍 Recherche : <strong><?= htmlspecialchars($search) ?></strong> 
       (<?= $totalEvents ?> résultat<?= $totalEvents > 1 ? 's' : '' ?>)
       <a href="?action=events" class="btn-clear-search">✖ Effacer</a>
    </p>
</div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div class="sort-options">
        <label for="sortEvents" style="margin-right: 10px; font-weight: 600;">📅 Trier par :</label>
        <select id="sortEvents" class="sort-select" onchange="window.location.href='?action=events&sort=' + this.value + '<?= $search ? '&search=' . urlencode($search) : '' ?>'">
            <option value="date_desc" <?= $sortBy == 'date_desc' ? 'selected' : '' ?>>Date (récent → ancien)</option>
            <option value="date_asc" <?= $sortBy == 'date_asc' ? 'selected' : '' ?>>Date (ancien → récent)</option>
            <option value="titre_asc" <?= $sortBy == 'titre_asc' ? 'selected' : '' ?>>Titre (A → Z)</option>
            <option value="titre_desc" <?= $sortBy == 'titre_desc' ? 'selected' : '' ?>>Titre (Z → A)</option>
            <option value="prix_desc" <?= $sortBy == 'prix_desc' ? 'selected' : '' ?>>Prix (élevé → faible)</option>
            <option value="prix_asc" <?= $sortBy == 'prix_asc' ? 'selected' : '' ?>>Prix (faible → élevé)</option>
        </select>
    </div>
    <a href="?action=create_event" class="btn btn-primary">🎭 Nouvel Événement</a>
</div>

<?php if (empty($events)): ?>
    <div class="empty-state">
        <div>📭</div>
        <h3>Aucun événement trouvé</h3>
        <p>Commencez par créer votre premier événement</p>
        <a href="?action=create_event" class="btn btn-primary">Créer un événement</a>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Lieu</th>
                <th>Date</th>
                <th>Prix</th>
                <th>Durée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><strong><?= htmlspecialchars($event['titre']) ?></strong></td>
                <td><?= htmlspecialchars(substr($event['description'], 0, 50)) ?>...</td>
                <td><?= htmlspecialchars($event['lieu']) ?></td>
                <td><?= date('d/m/Y', strtotime($event['date_event'])) ?></td>
                <td><?= number_format($eventModel->getEventById($event['id'])['prix'], 2) ?> €</td>
                <td><?= htmlspecialchars($event['duree'] ?? '') ?></td>
                <td class="actions">    
                    <a href="?action=reservations&event_id=<?= $event['id'] ?>" 
                       class="btn btn-success" title="Voir réservations">📋</a>
                    <a href="?action=edit_event&id=<?= $event['id'] ?>" 
                       class="btn btn-warning" title="Modifier">✏️</a>
                    <a href="process_event.php?action=delete&id=<?= $event['id'] ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Supprimer cet événement ?')" title="Supprimer">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?action=events&page=<?= $page - 1 ?>" class="pagination-btn">← Précédent</a>
        <?php endif; ?>
        
        <div class="pagination-pages">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?action=events&page=<?= $i ?>" 
                   class="pagination-number <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        
        <?php if ($page < $totalPages): ?>
            <a href="?action=events&page=<?= $page + 1 ?>" class="pagination-btn">Suivant →</a>
        <?php endif; ?>
    </div>
    
    <div class="pagination-info">
        Affichage de <?= min($offset + 1, $totalEvents) ?> 
        à <?= min($offset + $perPage, $totalEvents) ?> 
        sur <?= $totalEvents ?> événements
    </div>
    <?php endif; ?>
<?php endif; ?>