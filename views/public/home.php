<?php
// Page d'accueil publique
$upcomingEvents = $eventModel->getUpcomingEvents(6);
$stats = $reservation->getStats();
?>

<div class="container">
    <!-- Hero Section -->
    <section class="hero">
        <h1>🎭 Découvrez nos événements</h1>
        <p>Réservez votre place pour des expériences inoubliables</p>
        <div class="hero-buttons">
            <a href="?action=events" class="btn btn-primary">Voir tous les événements</a>
            <a href="?action=my_reservations" class="btn btn-secondary">Mes réservations</a>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" style="margin-bottom: 3rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎭</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?= $eventModel->countEvents() ?></div>
                <div style="color: var(--text-medium); font-weight: 600;">Événements disponibles</div>
            </div>
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">👥</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);"><?= $stats['confirmées'] ?? 0 ?></div>
                <div style="color: var(--text-medium); font-weight: 600;">Réservations confirmées</div>
            </div>
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); text-align: center; box-shadow: var(--shadow);">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">⭐</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--accent);">4.8/5</div>
                <div style="color: var(--text-medium); font-weight: 600;">Satisfaction client</div>
            </div>
        </div>
    </section>

    <!-- Featured Events -->
    <section class="featured-events">
        <h2 style="font-size: 2rem; margin-bottom: 2rem; color: var(--text-dark);">
            🔥 Événements à venir
        </h2>
        
        <?php if (empty($upcomingEvents)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Aucun événement à venir</h3>
                <p>Revenez bientôt pour découvrir nos prochains événements</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($upcomingEvents as $event): ?>
                <div class="event-card" onclick="window.location.href='?action=event_detail&id=<?= $event['id'] ?>'">
                    <div class="event-image">
                        🎭
                    </div>
                    <div class="event-content">
                        <h3 class="event-title"><?= htmlspecialchars($event['titre']) ?></h3>
                        <p class="event-description">
                            <?= htmlspecialchars(substr($event['description'], 0, 100)) ?>...
                        </p>
                        <div class="event-meta">
                            <div class="meta-item">
                                <span>📅</span>
                                <span><?= date('d/m/Y', strtotime($event['date_event'])) ?></span>
                            </div>
                            <div class="meta-item">
                                <span>📍</span>
                                <span><?= htmlspecialchars($event['lieu']) ?></span>
                            </div>
                            <?php if (!empty($event['duree'])): ?>
                            <div class="meta-item">
                                <span>⏱️</span>
                                <span><?= htmlspecialchars($event['duree']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="event-footer">
                            <div class="event-price">
                                <?= number_format($event['prix'], 2, ',', ' ') ?> €
                                <small>/place</small>
                            </div>
                            <a href="?action=book&id=<?= $event['id'] ?>" 
                               class="btn btn-book" 
                               onclick="event.stopPropagation()">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($upcomingEvents) >= 6): ?>
            <div style="text-align: center; margin-top: 3rem;">
                <a href="?action=events" class="btn btn-primary">Voir tous les événements →</a>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <!-- Why Choose Us -->
    <section style="margin-top: 5rem;">
        <h2 style="font-size: 2rem; margin-bottom: 2rem; text-align: center; color: var(--text-dark);">
            Pourquoi nous choisir ?
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Réservation rapide</h3>
                <p style="color: var(--text-medium);">Réservez votre place en quelques clics seulement</p>
            </div>
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🔒</div>
                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Paiement sécurisé</h3>
                <p style="color: var(--text-medium);">Vos transactions sont 100% sécurisées</p>
            </div>
            <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Support 24/7</h3>
                <p style="color: var(--text-medium);">Notre équipe est là pour vous aider</p>
            </div>
        </div>
    </section>
</div>
