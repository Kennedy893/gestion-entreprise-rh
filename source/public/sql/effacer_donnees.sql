-- VIDAGE COMPLET DES VUES + TABLES (SAUF la table `utilisateurs`)
-- Exécutez ce script avec précaution — faites une sauvegarde avant.

-- Désactiver les checks FK pour permettre TRUNCATE en toute sécurité
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------
-- SUPPRESSION DES VUES
-- -----------------------
DROP VIEW IF EXISTS v_stats_recrutement;
DROP VIEW IF EXISTS v_candidatures_details;
DROP VIEW IF EXISTS v_postes_libres;
DROP VIEW IF EXISTS v_stats_postes;
DROP VIEW IF EXISTS v_contrats_details;
DROP VIEW IF EXISTS v_candidats_en_evaluation;
DROP VIEW IF EXISTS v_poste_competence_detail;
DROP VIEW IF EXISTS v_candidature_score_matching;
DROP VIEW IF EXISTS v_candidature_competence_matching;
DROP VIEW IF EXISTS v_candidature_competences_deficitaires;
DROP VIEW IF EXISTS v_candidats_mise_en_formation;
DROP VIEW IF EXISTS v_formations_candidats_detail;
DROP VIEW IF EXISTS v_employe_competence_detail;
DROP VIEW IF EXISTS v_employes_performance;
DROP VIEW IF EXISTS v_stats_competences;
DROP VIEW IF EXISTS v_historique_remise_formation_employe;

-- -----------------------
-- TRUNCATE TABLES (liste précise, une par une)
-- -----------------------
TRUNCATE TABLE Evaluation_Formation;
TRUNCATE TABLE Formulaire_Formation;
TRUNCATE TABLE Candidat_Mise_Formation;
TRUNCATE TABLE Historique_Remise_Formation_Employe;
TRUNCATE TABLE Employe_Qualite_Performance;
TRUNCATE TABLE Employe_Competence;
TRUNCATE TABLE Candidat_Competence;
TRUNCATE TABLE Poste_Competence;
TRUNCATE TABLE Niveau_Competence;
TRUNCATE TABLE Competence;

TRUNCATE TABLE avantage;
TRUNCATE TABLE contrat_employe;
TRUNCATE TABLE config_retenu;
TRUNCATE TABLE config_poste;
TRUNCATE TABLE Poste;
TRUNCATE TABLE departement;
TRUNCATE TABLE categorie;
TRUNCATE TABLE data;
TRUNCATE TABLE type_retenu;
TRUNCATE TABLE presence;
TRUNCATE TABLE statut_abscence;
TRUNCATE TABLE absence;
TRUNCATE TABLE conge;

TRUNCATE TABLE Document;
TRUNCATE TABLE Type_Document;

TRUNCATE TABLE Type_Contrat;
TRUNCATE TABLE Employe;
TRUNCATE TABLE Statut_Contrat;

TRUNCATE TABLE annonce_emploi;
TRUNCATE TABLE candidature;
TRUNCATE TABLE entretien;
TRUNCATE TABLE document_candidature;

-- NOTE: la table `utilisateurs` est volontairement conservée (non modifiée)

-- Réactiver les checks FK
SET FOREIGN_KEY_CHECKS = 1;
