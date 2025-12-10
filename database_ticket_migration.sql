-- Ajout des colonnes pour le système de tickets
ALTER TABLE reservations 
ADD COLUMN ticket_code VARCHAR(255) NULL UNIQUE AFTER reference,
ADD COLUMN ticket_status ENUM('pending', 'issued', 'used', 'cancelled') DEFAULT 'pending' AFTER ticket_code,
ADD COLUMN issued_at DATETIME NULL AFTER ticket_status,
ADD COLUMN used_at DATETIME NULL AFTER issued_at,
ADD INDEX idx_ticket_code (ticket_code),
ADD INDEX idx_ticket_status (ticket_status);
