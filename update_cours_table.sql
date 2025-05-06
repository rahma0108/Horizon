-- Ajouter les colonnes latitude et longitude à la table cours
ALTER TABLE cours ADD COLUMN latitude DECIMAL(10, 8) NULL;
ALTER TABLE cours ADD COLUMN longitude DECIMAL(11, 8) NULL;

-- Ajouter un index spatial pour améliorer les performances des requêtes géographiques
CREATE INDEX idx_cours_coordinates ON cours(latitude, longitude);
