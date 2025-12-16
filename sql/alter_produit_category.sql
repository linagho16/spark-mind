-- Script pour modifier la colonne category en INT et ajouter une clé étrangère

-- Étape 1: Modifier le type de la colonne category
ALTER TABLE produit 
MODIFY COLUMN category INT NOT NULL;

-- Étape 2: Ajouter une clé étrangère (optionnel mais recommandé)
ALTER TABLE produit 
ADD CONSTRAINT fk_produit_categorie 
FOREIGN KEY (category) REFERENCES categorie(idc)
ON DELETE RESTRICT 
ON UPDATE CASCADE;

-- Note: Si vous avez déjà des données dans la table produit,
-- vous devrez d'abord mettre à jour les valeurs de category
-- pour qu'elles correspondent aux idc de la table categorie
