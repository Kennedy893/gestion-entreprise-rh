-- Active: 1751743068514@@127.0.0.1@3306@rh_docker
CREATE TABLE Type_Contrat(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50)
);

CREATE TABLE Employe(
   id SERIAL PRIMARY KEY,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   contact VARCHAR(50),
   photo VARCHAR(100),
   cin VARCHAR(50),
   date_naissance DATE,
   email VARCHAR(50),
   adresse VARCHAR(50),
   genre INT
);

CREATE TABLE Statut_Contrat(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50)
);

CREATE TABLE Type_Document(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50)
);

CREATE TABLE Document(
   id SERIAL PRIMARY KEY,
   chemin VARCHAR(250),
   id_type_document BIGINT UNSIGNED,
   id_employe BIGINT UNSIGNED,
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_type_document) REFERENCES Type_Document(id)
);

CREATE TABLE conge(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(150),
   paye INT,
   duree DECIMAL(15,2),
   frequence INT,
   jour INT,
   mois INT
);

CREATE TABLE absence(
   id SERIAL PRIMARY KEY,
   motif VARCHAR(255),
   date_debut DATE,
   date_fin DATE,
   id_document BIGINT UNSIGNED,
   id_conge BIGINT UNSIGNED,
   FOREIGN KEY(id_document) REFERENCES Document(id),
   FOREIGN KEY(id_conge) REFERENCES conge(id)
);

CREATE TABLE statut_abscence(
   id SERIAL PRIMARY KEY,
   date_statut DATE,
   statut INT,
   id_absence BIGINT UNSIGNED,
   FOREIGN KEY(id_absence) REFERENCES absence(id)
);

CREATE TABLE presence(
   id SERIAL PRIMARY KEY,
   date_travail DATE,
   entree TIME,
   sortie TIME,
   montant DECIMAL(15,2),
   id_employe BIGINT UNSIGNED,
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);

CREATE TABLE type_retenu(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(255)
);

CREATE TABLE data(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   valeur DECIMAL(25,2)
);

CREATE TABLE categorie(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100)
);

CREATE TABLE departement(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   fonction INT
);

CREATE TABLE Poste(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50),
   valeur INT,
   id_categorie BIGINT UNSIGNED,
   id_departement BIGINT UNSIGNED,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_departement) REFERENCES departement(id)
);

CREATE TABLE config_poste(
   id SERIAL PRIMARY KEY,
   duree_travail INT,
   entree TIME,
   sortie TIME,
   id_poste BIGINT UNSIGNED,
   FOREIGN KEY(id_poste) REFERENCES Poste(id)
);

CREATE TABLE config_retenu(
   id SERIAL PRIMARY KEY,
   pourcentage_entreprise DECIMAL(5,2),
   pourcentage_employer DECIMAL(5,2),
   salaire_min DECIMAL(25,2),
   salaire_max DECIMAL(25,2),
   id_categorie BIGINT UNSIGNED,
   id_type_retenu BIGINT UNSIGNED,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_type_retenu) REFERENCES type_retenu(id)
);

CREATE TABLE contrat_employe(
   id SERIAL PRIMARY KEY,
   date_debut DATE,
   date_fin DATE,
   duree INT,
   salaire DECIMAL(25,2),
   id_poste BIGINT UNSIGNED,
   id_employe BIGINT UNSIGNED,
   id_statut_contrat BIGINT UNSIGNED,
   id_type_contrat BIGINT UNSIGNED,
   FOREIGN KEY(id_poste) REFERENCES Poste(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_statut_contrat) REFERENCES Statut_Contrat(id),
   FOREIGN KEY(id_type_contrat) REFERENCES Type_Contrat(id)
);

CREATE TABLE avantage(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   montant DECIMAL(25,2),
   id_contrat_employe BIGINT UNSIGNED,
   FOREIGN KEY(id_contrat_employe) REFERENCES contrat_employe(id)
);

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


CREATE OR REPLACE VIEW v_stats_recrutement AS
SELECT 
    COUNT(DISTINCT a.id) as total_annonces,
    COUNT(DISTINCT CASE WHEN a.statut = 'active' THEN a.id END) as annonces_actives,
    COUNT(DISTINCT c.id) as total_candidatures,
    COUNT(DISTINCT CASE WHEN c.statut = 'en_attente' THEN c.id END) as candidatures_attente,
    COUNT(DISTINCT CASE WHEN c.statut = 'accepte' THEN c.id END) as candidatures_acceptees,
    COUNT(DISTINCT CASE WHEN c.statut = 'rejete' THEN c.id END) as candidatures_rejetees,
    COUNT(DISTINCT e.id) as total_entretiens,
    COUNT(DISTINCT CASE WHEN e.statut = 'planifie' THEN e.id END) as entretiens_planifies,
    COUNT(DISTINCT CASE WHEN e.statut = 'termine' THEN e.id END) as entretiens_termines
FROM annonce_emploi a
LEFT JOIN candidature c ON a.id = c.id_annonce
LEFT JOIN entretien e ON c.id = e.id_candidature;

-- Vue pour les détails candidatures avec annonces
DROP VIEW IF EXISTS v_candidatures_details;

-- Vue complète pour les candidatures avec toutes les décisions
CREATE OR REPLACE VIEW v_candidatures_details AS
SELECT 
    c.id,
    c.nom,
    c.prenom,
    c.email,
    c.telephone,
    c.date_naissance,
    c.adresse,
    c.cv_path,
    c.lettre_motivation_path,
    c.diplomes_path,
    c.experience_annees,
    c.note_manager,
    c.note_rh,
    c.score_final,
    c.statut,
    c.date_candidature,
    c.date_entretien,
    c.accepte_essai,
    c.demande_contrat_direct,
    c.qualifications,
    c.competences,
    c.dernier_diplome,
    c.etablissement,
    c.langue_parlee,
    c.statut_entretien,
    c.type_contrat_accorde,
    c.date_debut_travail,
    c.decision_finale,
    c.date_decision,
    c.genre,
    c.id_annonce,
    c.id_manager,
    
    -- Informations de l'annonce
    a.titre AS annonce_titre,
    a.description AS annonce_description,
    a.date_publication,
    a.date_limite,
    
    -- Informations du poste
    p.label AS poste_nom,
    p.valeur AS poste_valeur,
    
    -- Informations de l'entretien (si existe)
    e.id AS entretien_id,
    e.date_entretien AS entretien_date,
    e.lieu AS entretien_lieu,
    e.statut AS entretien_statut,
    e.note_manager AS entretien_note_manager,
    e.note_rh AS entretien_note_rh,
    e.note_finale AS entretien_note_finale,
    e.notation_etoiles AS entretien_notation_etoiles,
    e.decision AS entretien_decision,
    e.date_decision AS entretien_date_decision,
    
    -- Informations du manager
    u.nom AS manager_nom,
    u.prenom AS manager_prenom,
    
    -- CALCUL DU STATUT FINAL
    CASE
        -- Si entretien existe et a une décision
        WHEN e.decision IS NOT NULL THEN e.decision
        -- Sinon si candidature a une decision_finale
        WHEN c.decision_finale IS NOT NULL THEN c.decision_finale
        -- Sinon basé sur le statut
        WHEN c.statut IN ('entretien_termine', 'valide', 'actif') AND c.date_decision IS NOT NULL THEN 'accepte'
        WHEN c.statut IN ('rejete', 'refuse', 'decline') THEN 'rejete'
        ELSE 'en_attente'
    END AS status_computed

FROM candidature c
LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
LEFT JOIN Poste p ON a.id_poste = p.id
LEFT JOIN entretien e ON c.id = e.id_candidature
LEFT JOIN utilisateurs u ON c.id_manager = u.id
ORDER BY c.date_candidature DESC;

-- Ajouter des nouveaux statuts possibles pour les candidatures
-- 'en_attente' : Candidature déposée, en attente de traitement RH
-- 'envoye_manager' : Candidature envoyée au manager par RH
-- 'entretien_planifie' : Date d'entretien définie
-- 'entretien_termine' : Entretien passé, en attente évaluation RH
-- 'accepte' : Candidature acceptée
-- 'rejete' : Candidature rejetée


-- Vue pour les postes libres avec détails
CREATE OR REPLACE VIEW v_postes_libres AS
SELECT 
    p.id,
    p.label,
    p.valeur,
    p.id_categorie,
    p.id_departement,
    c.libelle as categorie_nom,
    d.libelle as departement_nom,
    -- Compter le nombre d'annonces actives pour ce poste
    COUNT(DISTINCT CASE WHEN ae.statut = 'active' THEN ae.id END) as annonces_actives,
    -- Compter le nombre d'employés actuellement sur ce poste
    COUNT(DISTINCT CASE WHEN ce.id_statut_contrat = 1 THEN ce.id_employe END) as employes_actifs,
    -- Calcul du nombre de postes libres (valeur - employés actifs - annonces actives)
    (p.valeur - 
     COUNT(DISTINCT CASE WHEN ce.id_statut_contrat = 1 THEN ce.id_employe END) -
     COUNT(DISTINCT CASE WHEN ae.statut = 'active' THEN ae.id END)
    ) as postes_disponibles,
    -- Statut du poste
    CASE 
        WHEN (p.valeur - 
              COUNT(DISTINCT CASE WHEN ce.id_statut_contrat = 1 THEN ce.id_employe END) -
              COUNT(DISTINCT CASE WHEN ae.statut = 'active' THEN ae.id END)
             ) > 0 THEN 'disponible'
        ELSE 'complet'
    END as statut_disponibilite
FROM Poste p
JOIN categorie c ON p.id_categorie = c.id
JOIN departement d ON p.id_departement = d.id
LEFT JOIN annonce_emploi ae ON p.id = ae.id_poste
LEFT JOIN contrat_employe ce ON p.id = ce.id_poste
GROUP BY p.id, p.label, p.valeur, p.id_categorie, p.id_departement, c.libelle, d.libelle;

-- Vue pour les statistiques des postes
CREATE OR REPLACE VIEW v_stats_postes AS
SELECT 
    COUNT(*) as total_postes,
    SUM(valeur) as total_capacite,
    SUM(CASE WHEN (
        valeur - 
        (SELECT COUNT(DISTINCT ce.id_employe) 
         FROM contrat_employe ce 
         WHERE ce.id_poste = Poste.id AND ce.id_statut_contrat = 1) -
        (SELECT COUNT(DISTINCT ae.id) 
         FROM annonce_emploi ae 
         WHERE ae.id_poste = Poste.id AND ae.statut = 'active')
    ) > 0 THEN 1 ELSE 0 END) as postes_avec_places_libres,
    SUM(valeur - 
        (SELECT COUNT(DISTINCT ce.id_employe) 
         FROM contrat_employe ce 
         WHERE ce.id_poste = Poste.id AND ce.id_statut_contrat = 1) -
        (SELECT COUNT(DISTINCT ae.id) 
         FROM annonce_emploi ae 
         WHERE ae.id_poste = Poste.id AND ae.statut = 'active')
    ) as total_places_libres
FROM Poste;

-- Vue pour les contrats avec détails employés
CREATE OR REPLACE VIEW v_contrats_details AS
SELECT 
    ce.id,
    ce.date_debut,
    ce.date_fin,
    ce.duree,
    ce.salaire,
    ce.id_poste,
    ce.id_employe,
    ce.id_statut_contrat,
    ce.id_type_contrat,
    e.nom as employe_nom,
    e.prenom as employe_prenom,
    e.email as employe_email,
    e.contact as employe_contact,
    e.cin as employe_cin,
    e.genre as employe_genre,
    p.label as poste_nom,
    c.libelle as categorie_nom,
    d.libelle as departement_nom,
    tc.label as type_contrat_nom,
    sc.label as statut_contrat_nom,
    -- Informations candidature si disponibles
    cand.id as candidature_id,
    cand.email as candidature_email
FROM contrat_employe ce
JOIN Employe e ON ce.id_employe = e.id
JOIN Poste p ON ce.id_poste = p.id
JOIN categorie c ON p.id_categorie = c.id
JOIN departement d ON p.id_departement = d.id
JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
LEFT JOIN candidature cand ON e.email = cand.email
ORDER BY ce.date_debut DESC;

-- Vue pour les candidats en attente d'évaluation
CREATE OR REPLACE VIEW v_candidats_en_evaluation AS
SELECT 
    c.id,
    c.nom,
    c.prenom,
    c.email,
    c.telephone,
    c.genre,
    c.statut,
    c.date_candidature,
    a.titre as annonce_titre,
    p.label as poste_nom,
    e.id as entretien_id,
    e.statut as entretien_statut,
    e.note_manager,
    e.note_rh,
    CASE 
        WHEN e.statut = 'termine' AND e.note_manager IS NOT NULL AND e.note_rh IS NULL THEN 1
        ELSE 0
    END as en_attente_evaluation_rh,
    CASE 
        WHEN e.statut = 'planifie' OR (e.statut = 'termine' AND e.note_manager IS NULL) THEN 1
        ELSE 0
    END as en_attente_entretien_manager
FROM candidature c
LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
LEFT JOIN Poste p ON a.id_poste = p.id
LEFT JOIN entretien e ON c.id = e.id_candidature
WHERE c.statut IN ('envoye_manager', 'entretien_planifie', 'entretien_termine')
  AND c.decision_finale IS NULL
ORDER BY c.date_candidature DESC;


SET FOREIGN_KEY_CHECKS = 0;

DROP TRIGGER IF EXISTS after_questionnaire_submit;
DROP VIEW IF EXISTS v_formulaires_formation_complets;
DROP TABLE IF EXISTS Questionnaire_Formation_Candidat;

SET FOREIGN_KEY_CHECKS = 1;


DROP TABLE IF EXISTS Evaluation_Formation;
DROP TABLE IF EXISTS Formulaire_Formation;
DROP TABLE IF EXISTS Candidat_Mise_Formation;
DROP TABLE IF EXISTS Historique_Remise_Formation_Employe;
DROP TABLE IF EXISTS Employe_Qualite_Performance;
DROP TABLE IF EXISTS Employe_Competence;
DROP TABLE IF EXISTS Candidat_Competence;
DROP TABLE IF EXISTS Poste_Competence;
DROP TABLE IF EXISTS Niveau_Competence;
DROP TABLE IF EXISTS Competence;

-- ========================================
-- CRÉATION DES TABLES
-- ========================================

-- Table des compétences
CREATE TABLE Competence (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(200) NOT NULL,
    description TEXT,
    categorie VARCHAR(100) COMMENT 'Ex: Informatique, Jardinage, Comptabilité',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des niveaux de compétence (référentiel 1-5)
CREATE TABLE Niveau_Competence (
    id INT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL,
    description TEXT
);


-- Association Poste-Compétence (compétences requises par poste)
CREATE TABLE Poste_Competence (
    id SERIAL PRIMARY KEY,
    id_poste BIGINT UNSIGNED NOT NULL,
    id_competence BIGINT UNSIGNED NOT NULL,
    niveau_requis INT NOT NULL COMMENT 'Niveau 1-5 requis',
    importance INT DEFAULT 5 COMMENT 'Importance 1-10 pour pondération',
    FOREIGN KEY (id_poste) REFERENCES Poste(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competence) REFERENCES Competence(id) ON DELETE CASCADE,
    FOREIGN KEY (niveau_requis) REFERENCES Niveau_Competence(id),
    UNIQUE KEY unique_poste_competence (id_poste, id_competence)
);

-- Compétences du candidat (auto-déclarées + évaluées)
CREATE TABLE Candidat_Competence (
    id SERIAL PRIMARY KEY,
    id_candidature BIGINT UNSIGNED NOT NULL,
    id_competence BIGINT UNSIGNED NOT NULL,
    niveau_declare INT COMMENT 'Niveau auto-déclaré par candidat',
    niveau_evalue INT COMMENT 'Niveau évalué lors entretien',
    date_evaluation DATETIME,
    FOREIGN KEY (id_candidature) REFERENCES candidature(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competence) REFERENCES Competence(id) ON DELETE CASCADE,
    UNIQUE KEY unique_candidat_competence (id_candidature, id_competence)
);

-- Table de mise en formation des candidats
CREATE TABLE Candidat_Mise_Formation (
    id SERIAL PRIMARY KEY,
    id_candidature BIGINT UNSIGNED NOT NULL,
    id_competence_deficitaire BIGINT UNSIGNED,
    niveau_requis INT,
    niveau_candidat INT,
    statut VARCHAR(50) DEFAULT 'proposee' COMMENT 'proposee, acceptee, en_formation, terminee, echouee',
    raison TEXT,
    date_mise_en_formation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_debut_formation DATETIME,
    date_fin_formation DATETIME,
    FOREIGN KEY (id_candidature) REFERENCES candidature(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competence_deficitaire) REFERENCES Competence(id)
);

-- Formulaire de formation rempli par le candidat
CREATE TABLE Formulaire_Formation (
    id SERIAL PRIMARY KEY,
    id_candidat_mise_formation BIGINT UNSIGNED NOT NULL,
    contenu_formation TEXT NOT NULL,
    duree_heures INT,
    methodologie VARCHAR(100),
    formateur VARCHAR(200),
    date_debut_formation DATE,
    date_fin_formation DATE,
    resultats TEXT,
    observations TEXT,
    certificat VARCHAR(50),
    date_submission DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_candidat_mise_formation) REFERENCES Candidat_Mise_Formation(id) ON DELETE CASCADE
);

-- Évaluation de la formation par le RH
CREATE TABLE Evaluation_Formation (
    id SERIAL PRIMARY KEY,
    id_formulaire_formation BIGINT UNSIGNED NOT NULL,
    id_rh BIGINT UNSIGNED NOT NULL,
    note_formation DECIMAL(4,2) NOT NULL COMMENT 'Note sur 20',
    note_competence_finale DECIMAL(4,2) NOT NULL COMMENT 'Note compétence acquise sur 20',
    observations TEXT,
    decision VARCHAR(50) NOT NULL COMMENT 'accepte ou rejete',
    date_evaluation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_formulaire_formation) REFERENCES Formulaire_Formation(id) ON DELETE CASCADE,
    FOREIGN KEY (id_rh) REFERENCES utilisateurs(id)
);

-- Compétences des employés
CREATE TABLE Employe_Competence (
    id SERIAL PRIMARY KEY,
    id_employe BIGINT UNSIGNED NOT NULL,
    id_competence BIGINT UNSIGNED NOT NULL,
    niveau INT NOT NULL COMMENT 'Niveau 1-5',
    date_acquisition DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_employe) REFERENCES Employe(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competence) REFERENCES Competence(id) ON DELETE CASCADE,
    UNIQUE KEY unique_employe_competence (id_employe, id_competence)
);

-- Performance et qualité des employés
CREATE TABLE Employe_Qualite_Performance (
    id SERIAL PRIMARY KEY,
    id_employe BIGINT UNSIGNED NOT NULL,
    score_performance INT DEFAULT 50 COMMENT 'Score 0-100%',
    statut_employe VARCHAR(50) DEFAULT 'actif' COMMENT 'actif, suspendu_formation, licencie',
    raison_suspension TEXT,
    frais_formation DECIMAL(15,2) DEFAULT 0,
    date_suspension DATETIME,
    FOREIGN KEY (id_employe) REFERENCES Employe(id) ON DELETE CASCADE,
    UNIQUE KEY unique_employe_performance (id_employe)
);

-- Historique des remises en formation des employés
CREATE TABLE Historique_Remise_Formation_Employe (
    id SERIAL PRIMARY KEY,
    id_employe BIGINT UNSIGNED NOT NULL,
    id_competence BIGINT UNSIGNED,
    ancien_score INT,
    statut VARCHAR(50) COMMENT 'proposee, acceptee, terminee',
    frais_formation DECIMAL(15,2),
    raison TEXT,
    date_remise_formation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_employe) REFERENCES Employe(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competence) REFERENCES Competence(id)
);


-- === Créer la table Questionnaire_Formation_Candidat ===
-- Utilise BIGINT UNSIGNED pour correspondre à SERIAL (qui est souvent BIGINT UNSIGNED)
CREATE TABLE IF NOT EXISTS Questionnaire_Formation_Candidat (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_formulaire_formation BIGINT UNSIGNED NOT NULL,
    id_candidature BIGINT UNSIGNED NOT NULL,

    -- Questions
    q1_contenu_attentes ENUM('oui','partiellement','non') NOT NULL,
    q2_qualite_pedagogique TINYINT NOT NULL CHECK (q2_qualite_pedagogique BETWEEN 1 AND 5),
    q3_objectifs_atteints ENUM('oui','partiellement','non') NOT NULL,
    q4_ameliorations TEXT,
    q5_formation_complementaire ENUM('oui','non') NOT NULL,

    -- Métadonnées
    date_soumission TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('soumis','evalue') DEFAULT 'soumis',

    -- Contraintes FK
    CONSTRAINT fk_qfc_ff FOREIGN KEY (id_formulaire_formation) REFERENCES Formulaire_Formation(id) ON DELETE CASCADE,
    CONSTRAINT fk_qfc_cand FOREIGN KEY (id_candidature) REFERENCES candidature(id) ON DELETE CASCADE,
    UNIQUE KEY unique_questionnaire (id_formulaire_formation)
);

-- === S'assurer que Formulaire_Formation contient les colonnes de tracking (ajoute si manquant) ===
ALTER TABLE Formulaire_Formation
    ADD COLUMN IF NOT EXISTS questionnaire_complete BOOLEAN DEFAULT FALSE AFTER date_submission,
    ADD COLUMN IF NOT EXISTS date_questionnaire_complete TIMESTAMP NULL AFTER questionnaire_complete;

-- === Recréer la vue (utilisant LEFT JOIN sur la table nouvellement créée) ===
CREATE OR REPLACE VIEW v_formulaires_formation_complets AS
SELECT 
    ff.*,
    cmf.id_candidature,
    cmf.statut AS statut_mise_formation,
    c.nom AS candidat_nom,
    c.prenom AS candidat_prenom,
    c.email AS candidat_email,
    qfc.id AS questionnaire_id,
    qfc.statut AS questionnaire_statut,
    qfc.date_soumission AS questionnaire_date_soumission
FROM Formulaire_Formation ff
JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
JOIN candidature c ON cmf.id_candidature = c.id
LEFT JOIN Questionnaire_Formation_Candidat qfc ON ff.id = qfc.id_formulaire_formation
ORDER BY ff.date_submission DESC;

-- === Trigger : marquer formulaire complet après insertion d'un questionnaire ===
DELIMITER $$
CREATE TRIGGER after_questionnaire_submit
AFTER INSERT ON Questionnaire_Formation_Candidat
FOR EACH ROW
BEGIN
    -- marquer le formulaire comme complété
    UPDATE Formulaire_Formation
    SET questionnaire_complete = TRUE,
        date_questionnaire_complete = NOW()
    WHERE id = NEW.id_formulaire_formation;
END$$
DELIMITER ;

-- === Indexes pour performance ===
CREATE INDEX IF NOT EXISTS idx_questionnaire_formulaire ON Questionnaire_Formation_Candidat (id_formulaire_formation);
CREATE INDEX IF NOT EXISTS idx_questionnaire_candidature ON Questionnaire_Formation_Candidat (id_candidature);
CREATE INDEX IF NOT EXISTS idx_ff_questionnaire ON Formulaire_Formation (questionnaire_complete);




-- ========================================
-- SUPPRESSION DES TABLES EXISTANTES
-- ========================================

SET FOREIGN_KEY_CHECKS = 0;



-- Suppression des vues
DROP VIEW IF EXISTS v_stats_competences;
DROP VIEW IF EXISTS v_historique_remise_formation_employe;
DROP VIEW IF EXISTS v_employes_performance;
DROP VIEW IF EXISTS v_employe_competence_detail;
DROP VIEW IF EXISTS v_formations_candidats_detail;
DROP VIEW IF EXISTS v_candidats_mise_en_formation;
DROP VIEW IF EXISTS v_candidature_competences_deficitaires;
DROP VIEW IF EXISTS v_candidature_competence_matching;
DROP VIEW IF EXISTS v_candidature_score_matching;
DROP VIEW IF EXISTS v_poste_competence_detail;






CREATE OR REPLACE VIEW v_poste_competence_detail AS
SELECT 
    pc.id,
    pc.id_poste,
    p.label as poste_nom,
    c.libelle as categorie_nom,
    pc.id_competence,
    comp.libelle as competence_nom,
    comp.description as competence_description,
    comp.categorie as competence_categorie,
    pc.niveau_requis,
    nc.libelle as niveau_requis_label,
    pc.importance
FROM Poste_Competence pc
JOIN Poste p ON pc.id_poste = p.id
JOIN categorie c ON p.id_categorie = c.id
JOIN Competence comp ON pc.id_competence = comp.id
JOIN Niveau_Competence nc ON pc.niveau_requis = nc.id;

-- Vue: Matching score candidature vs poste
CREATE OR REPLACE VIEW v_candidature_score_matching AS
SELECT 
    cand.id as id_candidature,
    cand.nom as candidat_nom,
    cand.prenom as candidat_prenom,
    cand.email as candidat_email,
    a.id as annonce_id,
    a.id_poste,
    p.label as poste_nom,
    c.libelle as categorie_libelle,
    COUNT(DISTINCT pc.id_competence) as nb_competences_requises,
    COUNT(DISTINCT CASE 
        WHEN cc.niveau_evalue >= pc.niveau_requis THEN pc.id_competence 
    END) as nb_competences_conformes,
    COALESCE(
        SUM(
            CASE 
                WHEN cc.niveau_evalue IS NOT NULL THEN 
                    (LEAST(cc.niveau_evalue, pc.niveau_requis) / pc.niveau_requis) * pc.importance * 100
                ELSE 0 
            END
        ) / NULLIF(SUM(pc.importance), 0),
        0
    ) as score_pondere_pct,
    CASE 
        WHEN cmf.statut IS NOT NULL THEN cmf.statut
        WHEN cand.decision_finale IS NOT NULL THEN cand.decision_finale
        ELSE 'candidat'
    END as statut_evaluation,
    e.id as entretien_id
FROM candidature cand
JOIN annonce_emploi a ON cand.id_annonce = a.id
JOIN Poste p ON a.id_poste = p.id
JOIN categorie c ON p.id_categorie = c.id
LEFT JOIN Poste_Competence pc ON p.id = pc.id_poste
LEFT JOIN Candidat_Competence cc ON cand.id = cc.id_candidature AND pc.id_competence = cc.id_competence
LEFT JOIN Candidat_Mise_Formation cmf ON cand.id = cmf.id_candidature
LEFT JOIN entretien e ON cand.id = e.id_candidature
GROUP BY cand.id, a.id, p.id, c.id, cmf.statut, e.id;

-- Vue: Matching détaillé compétence par compétence
CREATE OR REPLACE VIEW v_candidature_competence_matching AS
SELECT 
    cand.id as id_candidature,
    cand.nom as candidat_nom,
    cand.prenom as candidat_prenom,
    a.id_poste,
    p.label as poste_nom,
    pc.id_competence,
    comp.libelle as competence_nom,
    pc.niveau_requis,
    nc_requis.libelle as niveau_requis_label,
    COALESCE(cc.niveau_evalue, cc.niveau_declare, 0) as niveau_candidat,
    COALESCE(nc_candidat.libelle, 'Non évalué') as niveau_candidat_label,
    pc.importance,
    CASE 
        WHEN cc.niveau_evalue >= pc.niveau_requis THEN 100
        WHEN cc.niveau_evalue IS NOT NULL THEN (cc.niveau_evalue / pc.niveau_requis) * 100
        ELSE 0 
    END as score_pct,
    (pc.niveau_requis - COALESCE(cc.niveau_evalue, 0)) as ecart_niveau
FROM candidature cand
JOIN annonce_emploi a ON cand.id_annonce = a.id
JOIN Poste p ON a.id_poste = p.id
JOIN Poste_Competence pc ON p.id = pc.id_poste
JOIN Competence comp ON pc.id_competence = comp.id
JOIN Niveau_Competence nc_requis ON pc.niveau_requis = nc_requis.id
LEFT JOIN Candidat_Competence cc ON cand.id = cc.id_candidature AND pc.id_competence = cc.id_competence
LEFT JOIN Niveau_Competence nc_candidat ON cc.niveau_evalue = nc_candidat.id
ORDER BY cand.id, pc.importance DESC;

-- Vue: Compétences déficitaires
CREATE OR REPLACE VIEW v_candidature_competences_deficitaires AS
SELECT 
    cand.id as id_candidature,
    cand.nom as candidat_nom,
    cand.prenom as candidat_prenom,
    comp.id as id_competence,
    comp.libelle as competence_nom,
    pc.niveau_requis,
    COALESCE(cc.niveau_evalue, 0) as niveau_candidat,
    (pc.niveau_requis - COALESCE(cc.niveau_evalue, 0)) as ecart,
    pc.importance
FROM candidature cand
JOIN annonce_emploi a ON cand.id_annonce = a.id
JOIN Poste p ON a.id_poste = p.id
JOIN Poste_Competence pc ON p.id = pc.id_poste
JOIN Competence comp ON pc.id_competence = comp.id
LEFT JOIN Candidat_Competence cc ON cand.id = cc.id_candidature AND pc.id_competence = cc.id_competence
WHERE COALESCE(cc.niveau_evalue, 0) < pc.niveau_requis
ORDER BY cand.id, pc.importance DESC, ecart DESC;

-- Vue: Candidats mis en formation
CREATE OR REPLACE VIEW v_candidats_mise_en_formation AS
SELECT 
    cmf.id as id_mise_formation,
    cmf.id_candidature,
    c.nom as candidat_nom,
    c.prenom as candidat_prenom,
    c.email as candidat_email,
    a.titre as annonce_titre,
    p.label as poste_nom,
    comp.libelle as competence_nom,
    cmf.niveau_requis,
    cmf.niveau_candidat,
    cmf.statut,
    cmf.raison,
    cmf.date_mise_en_formation,
    cmf.date_debut_formation,
    cmf.date_fin_formation,
    ff.id as formulaire_id,
    ff.date_submission as formulaire_date,
    ef.id as evaluation_id,
    ef.note_formation,
    ef.note_competence_finale,
    ef.decision as evaluation_decision
FROM Candidat_Mise_Formation cmf
JOIN candidature c ON cmf.id_candidature = c.id
JOIN annonce_emploi a ON c.id_annonce = a.id
JOIN Poste p ON a.id_poste = p.id
LEFT JOIN Competence comp ON cmf.id_competence_deficitaire = comp.id
LEFT JOIN Formulaire_Formation ff ON cmf.id = ff.id_candidat_mise_formation
LEFT JOIN Evaluation_Formation ef ON ff.id = ef.id_formulaire_formation
ORDER BY cmf.date_mise_en_formation DESC;

-- Vue: Détail formations avec évaluations
CREATE OR REPLACE VIEW v_formations_candidats_detail AS
SELECT 
    ff.id as formulaire_id,
    ff.id_candidat_mise_formation,
    c.id as candidature_id,
    c.nom as candidat_nom,
    c.prenom as candidat_prenom,
    c.email as candidat_email,
    comp.libelle as competence_nom,
    ff.contenu_formation,
    ff.duree_heures,
    ff.methodologie,
    ff.formateur,
    ff.date_debut_formation,
    ff.date_fin_formation,
    ff.resultats,
    ff.observations,
    ff.certificat,
    ff.date_submission,
    ef.id as evaluation_id,
    ef.note_formation,
    ef.note_competence_finale,
    ef.observations as observations_rh,
    ef.decision,
    ef.date_evaluation,
    cmf.statut as statut_formation
FROM Formulaire_Formation ff
JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
JOIN candidature c ON cmf.id_candidature = c.id
LEFT JOIN Competence comp ON cmf.id_competence_deficitaire = comp.id
LEFT JOIN Evaluation_Formation ef ON ff.id = ef.id_formulaire_formation
ORDER BY ff.date_submission DESC;

-- Vue: Employés avec compétences
CREATE OR REPLACE VIEW v_employe_competence_detail AS
SELECT 
    e.id as id_employe,
    e.nom as nom_employe,
    e.prenom as prenom_employe,
    e.email as email_employe,
    comp.id as id_competence,
    comp.libelle as competence,
    comp.description as competence_description,
    ec.niveau as niveau_employe,
    nc.libelle as niveau_label,
    ec.date_acquisition
FROM Employe e
JOIN Employe_Competence ec ON e.id = ec.id_employe
JOIN Competence comp ON ec.id_competence = comp.id
JOIN Niveau_Competence nc ON ec.niveau = nc.id
ORDER BY e.nom, e.prenom, comp.libelle;

-- Vue: Performance employés
CREATE OR REPLACE VIEW v_employes_performance AS
SELECT 
    e.id as id_employe,
    e.nom as nom_employe,
    e.prenom as prenom_employe,
    e.email as email_employe,
    e.contact,
    ce.id_poste,
    p.label as poste_nom,
    d.libelle as nom_departement,
    ce.date_debut as date_embauche,
    eqp.score_performance,
    eqp.statut_employe as statut_performance,
    eqp.raison_suspension,
    eqp.frais_formation,
    eqp.date_suspension,
    CASE 
        WHEN eqp.score_performance < 10 THEN 'critique'
        WHEN eqp.score_performance < 25 THEN 'attention_requise'
        WHEN eqp.score_performance < 50 THEN 'amelioration_possible'
        WHEN eqp.score_performance < 75 THEN 'satisfaisant'
        ELSE 'excellent'
    END as categorie_performance
FROM Employe e
LEFT JOIN Employe_Qualite_Performance eqp ON e.id = eqp.id_employe
LEFT JOIN contrat_employe ce ON e.id = ce.id_employe
LEFT JOIN Poste p ON ce.id_poste = p.id
LEFT JOIN departement d ON p.id_departement = d.id
WHERE ce.id_statut_contrat = 1 OR ce.id IS NULL
ORDER BY e.nom, e.prenom;

-- Vue: Historique remises en formation employé
CREATE OR REPLACE VIEW v_historique_remise_formation_employe AS
SELECT 
    hrfe.id,
    hrfe.id_employe,
    e.nom as nom_employe,
    e.prenom as prenom_employe,
    comp.libelle as competence_nom,
    hrfe.ancien_score,
    hrfe.statut,
    hrfe.frais_formation,
    hrfe.raison,
    hrfe.date_remise_formation as date_formation
FROM Historique_Remise_Formation_Employe hrfe
JOIN Employe e ON hrfe.id_employe = e.id
LEFT JOIN Competence comp ON hrfe.id_competence = comp.id
ORDER BY hrfe.date_remise_formation DESC;

-- Vue: Statistiques globales
CREATE OR REPLACE VIEW v_stats_competences AS
SELECT 
    (SELECT COUNT(*) FROM Competence) as total_competences,
    (SELECT COUNT(*) FROM Candidat_Mise_Formation WHERE statut IN ('acceptee', 'en_formation')) as formations_actives,
    (SELECT COUNT(*) FROM Candidat_Mise_Formation WHERE statut = 'terminee') as formations_reussies,
    (SELECT COUNT(*) FROM Candidat_Mise_Formation WHERE statut = 'echouee') as formations_echouees,
    (SELECT COUNT(*) FROM Employe_Qualite_Performance WHERE statut_employe = 'actif') as employes_actifs,
    (SELECT COUNT(*) FROM Employe_Qualite_Performance WHERE statut_employe = 'suspendu_formation') as employes_formation,
    (SELECT COUNT(*) FROM Employe_Qualite_Performance WHERE statut_employe = 'licencie') as employes_licencies,
    (SELECT COUNT(*) FROM candidature WHERE decision_finale = 'accepte') as candidats_acceptes,
    (SELECT COUNT(*) FROM Candidat_Mise_Formation WHERE statut IN ('acceptee', 'en_formation', 'terminee')) as candidats_formation,
    (SELECT COUNT(*) FROM candidature WHERE decision_finale = 'rejete') as candidats_rejetes;


-- Initialisation des données de base pour le système RH

-- Types de contrat
INSERT INTO Type_Contrat (label) VALUES
('CDI - Contrat à Durée Indéterminée'),
('CDD - Contrat à Durée Déterminée'),
('Contrat d Essai'),
('Contrat de Travail Temporaire'),
('Stage'),
('Alternance');

-- Statuts de contrat
INSERT INTO Statut_Contrat (label) VALUES
('Actif'),
('En attente'),
('Terminé'),
('Suspendu'),
('Résilié');

-- Catégories de personnel (selon le PDF fourni)
INSERT INTO categorie (libelle) VALUES
('Ouvrier'),
('Employé'),
('Technicien / Agent de Maîtrise'),
('Cadre'),
('Dirigeant');

-- Départements
INSERT INTO departement (libelle, fonction) VALUES
('Direction Générale', 1),
('Ressources Humaines', 1),
('Finance et Comptabilité', 2),
('Informatique / IT', 2),
('Production', 3),
('Commercial et Ventes', 2),
('Marketing', 2),
('Logistique', 3),
('Qualité', 2),
('Maintenance', 3);

-- Postes par catégorie

-- Ouvriers
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Opérateur de Production', 1, 1, 5),
('Conducteur d Engins', 1, 1, 5),
('Technicien d Usine', 1, 1, 5),
('Manutentionnaire', 1, 1, 8),
('Agent de Maintenance', 1, 1, 10);

-- Employés
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Secrétaire', 2, 2, 2),
('Assistant Administratif', 2, 2, 2),
('Caissier', 2, 2, 6),
('Agent d Accueil', 2, 2, 2),
('Comptable Assistant', 2, 2, 3);

-- Techniciens / Agents de Maîtrise
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Chef dÉquipe Production', 3, 3, 5),
('Superviseur Logistique', 3, 3, 8),
('Technicien Informatique', 3, 3, 4),
('Contrôleur Qualité', 3, 3, 9),
('Responsable Maintenance', 3, 3, 10);

-- Cadres
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Responsable RH', 4, 4, 2),
('Chef de Projet IT', 4, 4, 4),
('Responsable Commercial', 4, 4, 6),
('Responsable Marketing', 4, 4, 7),
('Directeur de Production', 4, 4, 5),
('Chef Comptable', 4, 4, 3);

-- Dirigeants
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Directeur Général', 5, 5, 1),
('Directeur Financier', 5, 5, 3),
('Directeur des Opérations', 5, 5, 5),
('Directeur IT', 5, 5, 4);

-- Configuration des postes (horaires standards)
INSERT INTO config_poste (duree_travail, entree, sortie, id_poste) 
SELECT 8, '08:00:00', '17:00:00', id FROM Poste WHERE id_categorie IN (1, 2, 3);

INSERT INTO config_poste (duree_travail, entree, sortie, id_poste) 
SELECT 8, '09:00:00', '18:00:00', id FROM Poste WHERE id_categorie IN (4, 5);

-- Types de retenue (pour la paie)
INSERT INTO type_retenu (libelle) VALUES
('CNSS - Caisse Nationale de Sécurité Sociale'),
('IRSA - Impôt sur les Revenus Salariaux'),
('Mutuelle de Santé'),
('Retraite Complémentaire'),
('Prélèvement Assurance');

-- Configuration des retenues par catégorie
INSERT INTO config_retenu (pourcentage_entreprise, pourcentage_employer, salaire_min, salaire_max, id_categorie, id_type_retenu) VALUES
-- CNSS pour toutes catégories (13% entreprise, 1% employé)
(13.00, 1.00, 0, 999999999, 1, 1),
(13.00, 1.00, 0, 999999999, 2, 1),
(13.00, 1.00, 0, 999999999, 3, 1),
(13.00, 1.00, 0, 999999999, 4, 1),
(13.00, 1.00, 0, 999999999, 5, 1);

-- IRSA progressif selon catégorie
INSERT INTO config_retenu (pourcentage_entreprise, pourcentage_employer, salaire_min, salaire_max, id_categorie, id_type_retenu) VALUES
(0, 0, 0, 350000, 1, 2),     -- Ouvriers: exonérés jusqu'à 350000
(0, 5, 350001, 400000, 1, 2), -- 5% au-delà
(0, 10, 400001, 500000, 2, 2), -- Employés: 10%
(0, 15, 500001, 600000, 3, 2), -- TAM: 15%
(0, 20, 600001, 999999999, 4, 2), -- Cadres: 20%
(0, 20, 600001, 999999999, 5, 2); -- Dirigeants: 20%

-- Types de documents
INSERT INTO Type_Document (label) VALUES
('CV'),
('Lettre de Motivation'),
('Diplôme'),
('Certificat de Travail'),
('Pièce d Identité'),
('Justificatif de Domicile'),
('Attestation de Formation'),
('Bulletin de Salaire');

-- Employé Manager exemple (pour tester)
INSERT INTO Employe (nom, prenom, contact, cin, date_naissance, email, adresse, genre) VALUES
('RAKOTO', 'Jean', '+261 34 12 345 67', '101 234 567 890', '1985-05-15', 'jean.rakoto@entreprise.mg', 'Antananarivo', 1);

-- Données de configuration globale
INSERT INTO data (libelle, valeur) VALUES
('salaire_minimum_mensuel', 250000.00),
('heures_travail_semaine', 40.00),
('jours_conge_annuel', 22.00),
('taux_heure_supplementaire', 1.50);

-- Annonce exemple
INSERT INTO annonce_emploi (titre, description, competences_requises, diplomes_requis, experience_min, 
                            niveau_responsabilite, autonomie_requise, date_publication, date_limite, 
                            statut, id_poste, id_type_contrat, id_manager) VALUES
('Développeur Full Stack Senior', 
 'Nous recherchons un développeur full stack expérimenté pour rejoindre notre équipe IT. Vous serez en charge du développement et de la maintenance de nos applications web.',
 'PHP, MySQL, JavaScript, Vue.js, API REST, Git, Linux',
 'Licence en Informatique ou équivalent, Master souhaité',
 5,
 'Cadre',
 'Forte autonomie requise. Capacité à prendre des décisions techniques. Gestion de projet en mode agile.',
 CURDATE(),
 DATE_ADD(CURDATE(), INTERVAL 30 DAY),
 'active',
 17, -- Chef de Projet IT
 1,  -- CDI
 1   -- Manager ID
);


INSERT INTO utilisateurs (username, password, role, nom, prenom, email) VALUES
('admin1', 'cccc', 'admin', 'ANDRIANJAKA', 'Marie', 'sss@gmail.com');


-- WARNING : Mila jerena kely ny eto



-- Test data for formation/questionnaire flow
-- Inserts one candidature, one Candidat_Mise_Formation, one Formulaire_Formation
-- and one Questionnaire_Formation_Candidat (statut = 'soumis').
-- Run with: mysql -u <user> -p <database> < public/sql/insertion_formation_test.sql

START TRANSACTION;

-- 1) Insert a candidature (rejected so it appears in candidatures_rejetees)
INSERT INTO candidature (nom, prenom, email, date_candidature, decision_finale, date_decision, statut)
VALUES ('Test', 'Candidat', 'test.candidat@example.com', NOW(), 'rejete', NOW(), 'rejete');
SET @id_candidature = LAST_INSERT_ID();

-- 2) Insert a Candidat_Mise_Formation linked to the candidature
INSERT INTO Candidat_Mise_Formation (id_candidature, id_competence_deficitaire, niveau_requis, niveau_candidat, statut, date_mise_en_formation)
VALUES (@id_candidature, NULL, 3, 1, 'questionnaire_soumis', NOW());
SET @id_mise = LAST_INSERT_ID();

-- 3) Insert a Formulaire_Formation linked to the mise
INSERT INTO Formulaire_Formation (id_candidat_mise_formation, contenu_formation, duree_heures, methodologie, formateur, date_submission)
VALUES (@id_mise, 'Formation de test - contenu minimal', 16, 'Présentiel', 'Formateur Test', NOW());
SET @id_formulaire = LAST_INSERT_ID();

-- 4) Insert a Questionnaire_Formation_Candidat linked to the formulaire and candidature
INSERT INTO Questionnaire_Formation_Candidat (
    id_formulaire_formation,
    id_candidature,
    q1_contenu_attentes,
    q2_qualite_pedagogique,
    q3_objectifs_atteints,
    q4_ameliorations,
    q5_formation_complementaire,
    date_soumission,
    statut
) VALUES (
    @id_formulaire,
    @id_candidature,
    'oui',
    4,
    'oui',
    'Rien de particulier',
    'non',
    NOW(),
    'soumis'
);
SET @id_questionnaire = LAST_INSERT_ID();

-- 5) Ensure Formulaire_Formation is marked complete (trigger may already do this)
UPDATE Formulaire_Formation
SET questionnaire_complete = 1,
    date_questionnaire_complete = NOW()
WHERE id = @id_formulaire;

-- 6) Ensure mise status is questionnaire_soumis
UPDATE Candidat_Mise_Formation SET statut = 'questionnaire_soumis' WHERE id = @id_mise;

COMMIT;

-- Verification queries (run after the script):
-- SELECT * FROM candidature WHERE id = @id_candidature; -- if running interactively the variable is not set; instead query by email
-- SELECT * FROM candidature WHERE email = 'test.candidat@example.com';
-- SELECT * FROM Candidat_Mise_Formation WHERE id_candidature = (SELECT id FROM candidature WHERE email = 'test.candidat@example.com');
-- SELECT * FROM Formulaire_Formation WHERE id_candidat_mise_formation = (SELECT id FROM Candidat_Mise_Formation WHERE id_candidature = (SELECT id FROM candidature WHERE email = 'test.candidat@example.com'));
-- SELECT * FROM Questionnaire_Formation_Candidat WHERE id_candidature = (SELECT id FROM candidature WHERE email = 'test.candidat@example.com');

-- End of file




-- ========================================
-- INSERTION DES COMPÉTENCES
-- ========================================
-- Insertion des niveaux standards
INSERT INTO Niveau_Competence (id, libelle, description) VALUES
(1, 'Débutant', 'Connaissances de base, nécessite supervision'),
(2, 'Intermédiaire', 'Peut travailler avec assistance ponctuelle'),
(3, 'Confirmé', 'Autonome sur les tâches courantes'),
(4, 'Avancé', 'Expert, peut former les autres'),
(5, 'Maître', 'Niveau expert reconnu, innovateur');


-- Compétences pour Informatique
INSERT INTO Competence (libelle, description, categorie) VALUES
('Java', 'Langage de programmation orienté objet', 'Informatique'),
('Python', 'Langage de programmation polyvalent', 'Informatique'),
('SQL', 'Gestion de bases de données', 'Informatique'),
('JavaScript', 'Langage pour développement web', 'Informatique'),
('Réseau', 'Configuration et maintenance réseau', 'Informatique'),
('Sécurité informatique', 'Protection des systèmes', 'Informatique');

-- Compétences pour Production
INSERT INTO Competence (libelle, description, categorie) VALUES
('Conduite engins', 'Opération de machines lourdes', 'Production'),
('Maintenance industrielle', 'Entretien équipements', 'Production'),
('Contrôle qualité', 'Vérification conformité produits', 'Production'),
('Lecture plans', 'Compréhension schémas techniques', 'Production'),
('Sécurité au travail', 'Respect normes sécurité', 'Production');

-- Compétences pour Bureautique
INSERT INTO Competence (libelle, description, categorie) VALUES
('Excel', 'Tableur Microsoft Excel', 'Bureautique'),
('Word', 'Traitement de texte', 'Bureautique'),
('Communication', 'Compétences relationnelles', 'Soft Skills'),
('Organisation', 'Gestion du temps et priorités', 'Soft Skills'),
('Service client', 'Relation avec la clientèle', 'Commercial');

-- Compétences pour Comptabilité
INSERT INTO Competence (libelle, description, categorie) VALUES
('Comptabilité générale', 'Principes comptables de base', 'Comptabilité'),
('Fiscalité', 'Connaissance réglementation fiscale', 'Comptabilité'),
('Audit', 'Contrôle et vérification comptes', 'Comptabilité'),
('Reporting financier', 'Préparation rapports financiers', 'Comptabilité');

-- Compétences pour Jardinage/Ouvrier
INSERT INTO Competence (libelle, description, categorie) VALUES
('Jardinage', 'Entretien espaces verts', 'Ouvrier'),
('Maçonnerie', 'Travaux de construction', 'Ouvrier'),
('Électricité', 'Installation électrique', 'Ouvrier'),
('Plomberie', 'Installation sanitaire', 'Ouvrier');

-- ========================================
-- ASSOCIATION COMPÉTENCES AUX POSTES
-- ========================================

-- Technicien Informatique (id=13)
INSERT INTO Poste_Competence (id_poste, id_competence, niveau_requis, importance) 
SELECT 13, id, 4, 9 FROM Competence WHERE libelle='Java'
UNION ALL SELECT 13, id, 3, 7 FROM Competence WHERE libelle='Python'
UNION ALL SELECT 13, id, 4, 8 FROM Competence WHERE libelle='SQL'
UNION ALL SELECT 13, id, 3, 6 FROM Competence WHERE libelle='Réseau';

-- Chef de Projet IT (id=17)
INSERT INTO Poste_Competence (id_poste, id_competence, niveau_requis, importance) 
SELECT 17, id, 5, 10 FROM Competence WHERE libelle='Java'
UNION ALL SELECT 17, id, 4, 8 FROM Competence WHERE libelle='SQL'
UNION ALL SELECT 17, id, 5, 9 FROM Competence WHERE libelle='Organisation'
UNION ALL SELECT 17, id, 4, 8 FROM Competence WHERE libelle='Communication';

-- Opérateur de Production (id=1)
INSERT INTO Poste_Competence (id_poste, id_competence, niveau_requis, importance) 
SELECT 1, id, 2, 7 FROM Competence WHERE libelle='Conduite engins'
UNION ALL SELECT 1, id, 3, 9 FROM Competence WHERE libelle='Sécurité au travail'
UNION ALL SELECT 1, id, 2, 6 FROM Competence WHERE libelle='Lecture plans';

-- Comptable Assistant (id=10)
INSERT INTO Poste_Competence (id_poste, id_competence, niveau_requis, importance) 
SELECT 10, id, 3, 9 FROM Competence WHERE libelle='Comptabilité générale'
UNION ALL SELECT 10, id, 4, 8 FROM Competence WHERE libelle='Excel'
UNION ALL SELECT 10, id, 2, 6 FROM Competence WHERE libelle='Fiscalité';

-- Secrétaire (id=6)
INSERT INTO Poste_Competence (id_poste, id_competence, niveau_requis, importance) 
SELECT 6, id, 4, 8 FROM Competence WHERE libelle='Word'
UNION ALL SELECT 6, id, 3, 7 FROM Competence WHERE libelle='Excel'
UNION ALL SELECT 6, id, 4, 9 FROM Competence WHERE libelle='Communication'
UNION ALL SELECT 6, id, 4, 8 FROM Competence WHERE libelle='Organisation';

-- ========================================
-- CRÉATION DES VUES
-- ========================================

-- Vue: Détail compétences requises par poste


-- ========================================
-- VÉRIFICATION
-- ========================================

SELECT 'Tables créées avec succès!' as message;
SELECT COUNT(*) as nombre_competences FROM Competence;
SELECT COUNT(*) as associations_poste_competence FROM Poste_Competence;



-- ========================================
-- INSERTION DONNÉES TEST POUR COMPÉTENCES
-- ========================================

-- Supposons que vous avez des candidatures avec des IDs 1, 2, 3, 4, 5
-- Nous allons leur attribuer des compétences évaluées

-- Candidat 1 - Postule pour Technicien Informatique (id_poste=13)
-- Compétences requises : Java(4), Python(3), SQL(4), Réseau(3)
INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
SELECT 1, id, 4, 4, NOW() FROM Competence WHERE libelle='Java'
UNION ALL SELECT 1, id, 3, 3, NOW() FROM Competence WHERE libelle='Python'
UNION ALL SELECT 1, id, 4, 4, NOW() FROM Competence WHERE libelle='SQL'
UNION ALL SELECT 1, id, 3, 3, NOW() FROM Competence WHERE libelle='Réseau'
ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

-- Candidat 2 - Postule pour Technicien Informatique mais manque Java
INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
SELECT 2, id, 2, 2, NOW() FROM Competence WHERE libelle='Java'
UNION ALL SELECT 2, id, 3, 3, NOW() FROM Competence WHERE libelle='Python'
UNION ALL SELECT 2, id, 3, 3, NOW() FROM Competence WHERE libelle='SQL'
UNION ALL SELECT 2, id, 2, 2, NOW() FROM Competence WHERE libelle='Réseau'
ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();


-- DANGER : Tsy mandeha 


-- Candidat 3 - Postule pour Comptable Assistant (id_poste=10)
-- Compétences requises : Comptabilité générale(3), Excel(4), Fiscalité(2)
-- INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
-- SELECT 3, id, 3, 3, NOW() FROM Competence WHERE libelle='Comptabilité générale'
-- UNION ALL SELECT 3, id, 4, 4, NOW() FROM Competence WHERE libelle='Excel'
-- UNION ALL SELECT 3, id, 2, 2, NOW() FROM Competence WHERE libelle='Fiscalité'
-- ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

-- -- Candidat 4 - Postule pour Secrétaire (id_poste=6)
-- -- Compétences requises : Word(4), Excel(3), Communication(4), Organisation(4)
-- INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
-- SELECT 4, id, 4, 4, NOW() FROM Competence WHERE libelle='Word'
-- UNION ALL SELECT 4, id, 3, 3, NOW() FROM Competence WHERE libelle='Excel'
-- UNION ALL SELECT 4, id, 4, 4, NOW() FROM Competence WHERE libelle='Communication'
-- UNION ALL SELECT 4, id, 3, 3, NOW() FROM Competence WHERE libelle='Organisation'
-- ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

-- -- Candidat 5 - Postule pour Chef de Projet IT (id_poste=17) avec lacunes
-- -- Compétences requises : Java(5), SQL(4), Organisation(5), Communication(4)
-- INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
-- SELECT 5, id, 4, 4, NOW() FROM Competence WHERE libelle='Java'
-- UNION ALL SELECT 5, id, 3, 3, NOW() FROM Competence WHERE libelle='SQL'
-- UNION ALL SELECT 5, id, 3, 3, NOW() FROM Competence WHERE libelle='Organisation'
-- UNION ALL SELECT 5, id, 4, 4, NOW() FROM Competence WHERE libelle='Communication'
-- ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

-- ========================================
-- METTRE À JOUR ENTRETIENS EXISTANTS
-- ========================================

-- S'assurer que les entretiens sont publiés pour le matching
UPDATE entretien 
SET statut_publication = 'publie' 
WHERE statut = 'termine' AND statut_publication IS NULL;

-- ========================================
-- VÉRIFICATION
-- ========================================

SELECT 'Données test insérées avec succès!' as message;

-- Afficher les candidats avec compétences
SELECT 
    c.id,
    c.nom,
    c.prenom,
    COUNT(cc.id) as nb_competences_evaluees
FROM candidature c
LEFT JOIN Candidat_Competence cc ON c.id = cc.id_candidature
GROUP BY c.id
ORDER BY c.id;

-- Vérifier le matching
SELECT 
    cand.id,
    cand.nom,
    cand.prenom,
    COUNT(DISTINCT pc.id_competence) as nb_competences_requises,
    COUNT(DISTINCT cc.id_competence) as nb_competences_evaluees
FROM candidature cand
JOIN annonce_emploi a ON cand.id_annonce = a.id
JOIN Poste p ON a.id_poste = p.id
LEFT JOIN Poste_Competence pc ON p.id = pc.id_poste
LEFT JOIN Candidat_Competence cc ON cand.id = cc.id_candidature
GROUP BY cand.id
ORDER BY cand.id
LIMIT 10;
SET FOREIGN_KEY_CHECKS = 1;
