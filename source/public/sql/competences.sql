-- ========================================
-- SUPPRESSION DES TABLES EXISTANTES
-- ========================================

SET FOREIGN_KEY_CHECKS = 0;

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

-- Candidat 3 - Postule pour Comptable Assistant (id_poste=10)
-- Compétences requises : Comptabilité générale(3), Excel(4), Fiscalité(2)
INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
SELECT 3, id, 3, 3, NOW() FROM Competence WHERE libelle='Comptabilité générale'
UNION ALL SELECT 3, id, 4, 4, NOW() FROM Competence WHERE libelle='Excel'
UNION ALL SELECT 3, id, 2, 2, NOW() FROM Competence WHERE libelle='Fiscalité'
ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

-- Candidat 4 - Postule pour Secrétaire (id_poste=6)
-- Compétences requises : Word(4), Excel(3), Communication(4), Organisation(4)
INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
SELECT 4, id, 4, 4, NOW() FROM Competence WHERE libelle='Word'
UNION ALL SELECT 4, id, 3, 3, NOW() FROM Competence WHERE libelle='Excel'
UNION ALL SELECT 4, id, 4, 4, NOW() FROM Competence WHERE libelle='Communication'
UNION ALL SELECT 4, id, 3, 3, NOW() FROM Competence WHERE libelle='Organisation'
ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

-- Candidat 5 - Postule pour Chef de Projet IT (id_poste=17) avec lacunes
-- Compétences requises : Java(5), SQL(4), Organisation(5), Communication(4)
INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare, niveau_evalue, date_evaluation)
SELECT 5, id, 4, 4, NOW() FROM Competence WHERE libelle='Java'
UNION ALL SELECT 5, id, 3, 3, NOW() FROM Competence WHERE libelle='SQL'
UNION ALL SELECT 5, id, 3, 3, NOW() FROM Competence WHERE libelle='Organisation'
UNION ALL SELECT 5, id, 4, 4, NOW() FROM Competence WHERE libelle='Communication'
ON DUPLICATE KEY UPDATE niveau_evalue = VALUES(niveau_evalue), date_evaluation = NOW();

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