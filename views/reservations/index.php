<?php
// Recherche, Tri et Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $perPage;

if ($search) {
    $totalReservations = $reservation->count($search);
    $reservations = $reservation->search($search, $perPage, $offset, $sortBy);
} else {
    $totalReservations = $reservation->count();
    $reservations = $reservation->getAll($perPage, $offset, $sortBy);
}

$totalPages = ceil($totalReservations / $perPage);
?>
<div class="page-title">
    <span style="font-size: 1.5em;">📋</span>
    <h1>Liste des Réservations</h1>
</div>

<?php if ($search): ?>
<div class="search-info">
    <p>🔍 Recherche : <strong><?= htmlspecialchars($search) ?></strong> 
       (<?= $totalReservations ?> résultat<?= $totalReservations > 1 ? 's' : '' ?>)
       <a href="?action=reservations" class="btn-clear-search">✖ Effacer</a>
    </p>
</div>
<?php endif; ?>

<div class="sort-options" style="margin-bottom: 20px;">
    <label for="sortReservations" style="margin-right: 10px; font-weight: 600;">📅 Trier par :</label>
    <select id="sortReservations" class="sort-select" onchange="window.location.href='?action=reservations&sort=' + this.value + '<?= $search ? '&search=' . urlencode($search) : '' ?>'">
        <option value="date_desc" <?= $sortBy == 'date_desc' ? 'selected' : '' ?>>Date réservation (récent → ancien)</option>
        <option value="date_asc" <?= $sortBy == 'date_asc' ? 'selected' : '' ?>>Date réservation (ancien → récent)</option>
        <option value="event_date_asc" <?= $sortBy == 'event_date_asc' ? 'selected' : '' ?>>Date événement (proche → loin)</option>
        <option value="event_date_desc" <?= $sortBy == 'event_date_desc' ? 'selected' : '' ?>>Date événement (loin → proche)</option>
        <option value="client_asc" <?= $sortBy == 'client_asc' ? 'selected' : '' ?>>Client (A → Z)</option>
        <option value="montant_desc" <?= $sortBy == 'montant_desc' ? 'selected' : '' ?>>Montant (élevé → faible)</option>
    </select>
</div>

<?php if (empty($reservations)): ?>
    <div class="empty-state">
        <div>📭</div>
        <h3>Aucune réservation trouvée</h3>
        <p>Créez votre première réservation</p>
        <a href="?action=create_reservation" class="btn btn-primary">Créer une réservation</a>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Client</th>
                <th>Événement</th>
                <th>Places</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Ticket</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $res): ?>
            <tr>
                <td><strong><?= htmlspecialchars($res['reference']) ?></strong></td>
                <td>
                    <strong><?= htmlspecialchars($res['nom_client']) ?></strong><br>
                    <small><?= htmlspecialchars($res['email']) ?></small><br>
                    <small><?= htmlspecialchars($res['telephone']) ?></small>
                </td>
                <td>
                    <?= htmlspecialchars($res['event_titre'] ?? 'N/A') ?><br>
                    <small><?= date('d/m/Y', strtotime($res['date_event'] ?? '')) ?></small>
                </td>
                <td><?= $res['nombre_places'] ?></td>
                <td><?= number_format($res['montant_total'], 2) ?> €</td>
                <td><?= date('d/m/Y H:i', strtotime($res['date_reservation'])) ?></td>
                <td>
                    <?php
                    $badgeClass = [
                        'confirmée' => 'badge-success',
                        'en attente' => 'badge-warning',
                        'annulée' => 'badge-danger'
                    ][$res['statut']] ?? 'badge-warning';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= $res['statut'] ?></span>
                </td>
                <td style="text-align: center;">
                    <?php if (!empty($res['ticket_code'])): ?>
                        <span class="badge badge-<?= $res['ticket_status'] ?? 'secondary' ?>" style="font-size: 11px;">
                            <?php 
                            $ticketIcons = [
                                'pending' => '⏳',
                                'issued' => '🎫',
                                'used' => '✅',
                                'cancelled' => '❌'
                            ];
                            echo $ticketIcons[$res['ticket_status'] ?? 'pending'];
                            echo ' ' . ucfirst($res['ticket_status'] ?? 'pending');
                            ?>
                        </span>
                    <?php elseif ($res['statut'] === 'confirmée'): ?>
                        <button onclick="issueTicket(<?= $res['id'] ?>)" class="btn btn-primary" style="padding: 4px 8px; font-size: 12px;" title="Émettre un ticket">
                            🎫 Émettre
                        </button>
                    <?php else: ?>
                        <span style="color: #999;">-</span>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <?php if (!empty($res['ticket_code'])): ?>
                        <a href="ticket_view.php?id=<?= $res['id'] ?>" 
                           class="btn btn-info" 
                           title="Voir le ticket" 
                           target="_blank">🎫</a>
                    <?php endif; ?>
                    <a href="?action=edit_reservation&id=<?= $res['id'] ?>" 
                       class="btn btn-warning" 
                       title="Modifier">✏️</a>
                    <?php if ($res['statut'] == 'en attente'): ?>
                        <a href="process_reservation.php?action=update_status&id=<?= $res['id'] ?>&status=confirmée" 
                           class="btn btn-success" 
                           onclick="return confirm('Confirmer cette réservation?')" title="Confirmer">✅</a>
                    <?php endif; ?>
                    <?php if ($res['statut'] != 'annulée'): ?>
                        <a href="process_reservation.php?action=update_status&id=<?= $res['id'] ?>&status=annulée" 
                           class="btn btn-danger" 
                           onclick="return confirm('Annuler cette réservation?')" title="Annuler">❌</a>
                    <?php endif; ?>
                    <a href="process_reservation.php?action=delete&id=<?= $res['id'] ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Supprimer définitivement cette réservation?')" title="Supprimer">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <script>
    async function issueTicket(reservationId) {
        if (!confirm('Émettre un ticket pour cette réservation ?')) {
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('reservation_id', reservationId);
            
            const response = await fetch('api/ticket_operations.php?action=issue', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('✅ Ticket émis avec succès!\nCode: ' + data.data.ticket_code);
                location.reload();
            } else {
                alert('❌ Erreur: ' + (data.error || 'Impossible d\'émettre le ticket'));
            }
        } catch (error) {
            alert('❌ Erreur: ' + error.message);
        }
    }
    </script>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?action=reservations&page=<?= $page - 1 ?>" class="pagination-btn">← Précédent</a>
        <?php endif; ?>
        
        <div class="pagination-pages">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?action=reservations&page=<?= $i ?>" 
                   class="pagination-number <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        
        <?php if ($page < $totalPages): ?>
            <a href="?action=reservations&page=<?= $page + 1 ?>" class="pagination-btn">Suivant →</a>
        <?php endif; ?>
    </div>
    
    <div class="pagination-info">
        Affichage de <?= min($offset + 1, $totalReservations) ?> 
        à <?= min($offset + $perPage, $totalReservations) ?> 
        sur <?= $totalReservations ?> réservations
    </div>
    <?php endif; ?>
<?php endif; ?>