-- 1) Vérifier que la table produit existe avant d'alter
-- (MySQL ne supporte pas ALTER TABLE IF EXISTS partout, donc on fait simple)
SHOW TABLES;

-- Si ta table s'appelle "produits" ou autre, remplace ici
-- Exemple si ta table = produits :
-- ALTER TABLE produits ...

ALTER TABLE produit 
MODIFY COLUMN category INT NOT NULL;

ALTER TABLE produit 
ADD CONSTRAINT fk_produit_categorie 
FOREIGN KEY (category) REFERENCES categorie(idc)
ON DELETE RESTRICT 
ON UPDATE CASCADE;
