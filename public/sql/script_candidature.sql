CREATE TABLE annonce_emploi(
   id SERIAL PRIMARY KEY,
   titre VARCHAR(200),
   description TEXT,
   competences_requises TEXT,
   diplomes_requis TEXT,
   experience_min INT,
   niveau_responsabilite VARCHAR(100),
   autonomie_requise TEXT,
   date_publication DATE,
   date_limite DATE,
   statut VARCHAR(50),
   id_poste BIGINT UNSIGNED,
   id_type_contrat BIGINT UNSIGNED,
   id_manager BIGINT UNSIGNED,
   FOREIGN KEY(id_poste) REFERENCES Poste(id),
   FOREIGN KEY(id_type_contrat) REFERENCES Type_Contrat(id),
   FOREIGN KEY(id_manager) REFERENCES Employe(id)
);
    CREATE TABLE candidature(
   id SERIAL PRIMARY KEY,
   nom VARCHAR(100),
   prenom VARCHAR(100),
   email VARCHAR(100),
   telephone VARCHAR(50),
   date_naissance DATE,
   adresse VARCHAR(255),
   cv_path VARCHAR(255),
   lettre_motivation_path VARCHAR(255),
   diplomes_path VARCHAR(255),
   experience_annees INT,
   note_manager DECIMAL(4,2),
   note_rh DECIMAL(4,2),
   score_final DECIMAL(4,2),
   statut VARCHAR(50),
   date_candidature DATE,
   date_entretien DATETIME,
   accepte_essai TINYINT(1),
   id_annonce BIGINT UNSIGNED,
   FOREIGN KEY(id_annonce) REFERENCES annonce_emploi(id)
);

CREATE TABLE IF NOT EXISTS utilisateurs(
   id SERIAL PRIMARY KEY,
   username VARCHAR(100) UNIQUE NOT NULL,
   password VARCHAR(255) NOT NULL,
   role VARCHAR(50) NOT NULL,
   nom VARCHAR(100),
   prenom VARCHAR(100),
   email VARCHAR(100),
   actif TINYINT(1) DEFAULT 1,
   date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
   id_employe BIGINT UNSIGNED,
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);

-- Table des entretiens
CREATE TABLE IF NOT EXISTS entretien(
   id SERIAL PRIMARY KEY,
   date_entretien DATETIME NOT NULL,
   lieu VARCHAR(255),
   statut VARCHAR(50) DEFAULT 'planifie',
   note_manager DECIMAL(4,2),
   qualites_manager TEXT,
   defauts_manager TEXT,
   note_rh DECIMAL(4,2),
   observation_rh TEXT,
   note_finale DECIMAL(4,2),
   notation_etoiles INT,
   decision VARCHAR(50),
   date_decision DATETIME,
   id_candidature BIGINT UNSIGNED NOT NULL,
   id_manager BIGINT UNSIGNED,
   id_rh BIGINT UNSIGNED,
   FOREIGN KEY(id_candidature) REFERENCES candidature(id) ON DELETE CASCADE,
   FOREIGN KEY(id_manager) REFERENCES utilisateurs(id),
   FOREIGN KEY(id_rh) REFERENCES utilisateurs(id)
);

-- Table des documents candidatures
CREATE TABLE IF NOT EXISTS document_candidature(
   id SERIAL PRIMARY KEY,
   type_document VARCHAR(100),
   chemin_fichier VARCHAR(500),
   nom_original VARCHAR(255),
   taille INT,
   date_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
   id_candidature BIGINT UNSIGNED NOT NULL,
   FOREIGN KEY(id_candidature) REFERENCES candidature(id) ON DELETE CASCADE
);



ALTER TABLE candidature 
ADD COLUMN demande_contrat_direct TINYINT(1) DEFAULT 0,
ADD COLUMN qualifications TEXT,
ADD COLUMN competences TEXT,
ADD COLUMN dernier_diplome VARCHAR(255),
ADD COLUMN etablissement VARCHAR(255),
ADD COLUMN langue_parlee VARCHAR(255),
ADD COLUMN statut_entretien VARCHAR(50) DEFAULT 'en_attente',
ADD COLUMN type_contrat_accorde VARCHAR(100),
ADD COLUMN date_debut_travail DATE,
ADD COLUMN decision_finale VARCHAR(50),
ADD COLUMN date_decision DATETIME;
ALTER TABLE candidature
ADD COLUMN IF NOT EXISTS genre VARCHAR(20) DEFAULT NULL;



ALTER TABLE candidature 
ADD COLUMN IF NOT EXISTS id_manager BIGINT UNSIGNED,
ADD FOREIGN KEY (id_manager) REFERENCES utilisateurs(id);

-- Ajouter visible_public dans entretien
ALTER TABLE entretien 
ADD COLUMN IF NOT EXISTS visible_public TINYINT(1) DEFAULT 0;
-- Ajouter le champ statut_publication à la table entretien
ALTER TABLE entretien 
ADD COLUMN IF NOT EXISTS statut_publication VARCHAR(50) DEFAULT 'brouillon' 
COMMENT 'brouillon ou publie';

-- Mettre à jour les entretiens existants
UPDATE entretien 
SET statut_publication = CASE 
    WHEN visible_public = 1 THEN 'publie'
    ELSE 'brouillon'
END
WHERE statut_publication IS NULL;
