-- Script pour insérer des données de test
-- À exécuter si vous voulez tester avec des données d'exemple

USE `evenement`;

-- Insérer des événements de test (si la table est vide)
INSERT INTO `events` (`titre`, `description`, `lieu`, `prix`, `date_event`) VALUES
('Concert de Jazz', 'Soirée jazz avec des artistes locaux', 'Salle de concert municipale', 35.00, '2025-12-20'),
('Conférence Tech 2025', 'Conférence sur les nouvelles technologies web et IA', 'Centre de Congrès', 75.00, '2025-12-25'),
('Festival Gastronomique', 'Découvrez les saveurs de notre région', 'Parc Central', 45.00, '2026-01-15'),
('Atelier Photographie', 'Apprenez les bases de la photographie professionnelle', 'Studio Photo Pro', 120.00, '2026-01-20'),
('Marathon de la Ville', 'Course de 42km à travers la ville', 'Centre-ville', 25.00, '2026-02-10');

-- Vérifier le nombre d'événements
SELECT COUNT(*) as 'Nombre événements' FROM events;

-- Afficher tous les événements
SELECT * FROM events ORDER BY date_event ASC;

-- Si vous voulez aussi insérer des réservations de test
INSERT INTO `reservations` (`event_id`, `nom_client`, `email`, `telephone`, `nombre_places`, `montant_total`, `reference`, `statut`, `methode_paiement`, `notes`) 
VALUES
(1, 'Jean Dupont', 'jean.dupont@email.com', '0612345678', 2, 70.00, 'RES-20251210-001', 'confirmée', 'carte', 'Client VIP'),
(1, 'Marie Martin', 'marie.martin@email.com', '0687654321', 1, 35.00, 'RES-20251210-002', 'en attente', 'especes', ''),
(2, 'Pierre Durand', 'pierre.durand@email.com', '0698765432', 3, 225.00, 'RES-20251210-003', 'confirmée', 'virement', 'Réservation groupe'),
(3, 'Sophie Bernard', 'sophie.bernard@email.com', '0623456789', 2, 90.00, 'RES-20251210-004', 'confirmée', 'carte', ''),
(4, 'Luc Petit', 'luc.petit@email.com', '0634567890', 1, 120.00, 'RES-20251210-005', 'en attente', 'cheque', 'Première réservation');

-- Vérifier le nombre de réservations
SELECT COUNT(*) as 'Nombre réservations' FROM reservations;

-- Afficher les statistiques
SELECT 
    COUNT(*) as total_reservations,
    SUM(CASE WHEN statut = 'confirmée' THEN 1 ELSE 0 END) as confirmees,
    SUM(CASE WHEN statut = 'en attente' THEN 1 ELSE 0 END) as en_attente,
    SUM(CASE WHEN statut = 'annulée' THEN 1 ELSE 0 END) as annulees,
    SUM(montant_total) as revenu_total
FROM reservations;
