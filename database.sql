-- Script SQL pour créer la table events
-- Base de données: evenement

CREATE DATABASE IF NOT EXISTS `evenement` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `evenement`;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `prix` decimal(10,2) DEFAULT 0.00,
  `date_event` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exemple d'insertion de données de test (optionnel)
-- INSERT INTO `events` (`titre`, `description`, `lieu`, `prix`, `date_event`) VALUES
-- ('Concert de Rock', 'Un concert de rock avec plusieurs groupes locaux', 'Salle des Fêtes', 25.00, '2024-12-31'),
-- ('Conférence Tech', 'Conférence sur les nouvelles technologies web', 'Centre de Congrès', 50.00, '2024-12-15'),
-- ('Festival de Musique', 'Festival de musique en plein air', 'Parc Central', 35.00, '2024-12-20');

-- Table des réservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `nom_client` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `nombre_places` int(11) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `statut` enum('en attente','confirmée','annulée') DEFAULT 'en attente',
  `methode_paiement` varchar(50) DEFAULT 'carte',
  `notes` text,
  `date_reservation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

