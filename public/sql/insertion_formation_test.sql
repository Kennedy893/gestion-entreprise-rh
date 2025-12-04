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
