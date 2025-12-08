INSERT INTO Type_Contrat (label) VALUES
('CDI'),
('CDD'),
('Stage'),
('Interim');

INSERT INTO Statut_Contrat (label) VALUES
('En cours'),
('Résilié'),
('Terminé'),
('En attente');

INSERT INTO Type_Document (label) VALUES
('CV'),
('Lettre de motivation'),
('Contrat signé'),
('Diplôme'),
('Photo d''identité');

-- INSERT INTO conge (libelle, paye, duree, frequence, jour, mois) VALUES
-- ('Congé annuel', 1, 2.5, 1, NULL, NULL),
-- ('Congé maladie', 1, 0, 0, NULL, NULL),  -- durée variable selon cas
-- ('Congé maternité', 1, 14, 0, NULL, NULL),
-- ('Congé sans solde', 0, 0, 0, NULL, NULL),  -- durée variable
-- ('RTT', 1, 1, 1, NULL, NULL);

INSERT INTO conge (libelle, paye, duree, frequence, jour, mois)
VALUES ('Conge normal', 1, 2.5, 1, NULL, NULL);
INSERT INTO conge (libelle, paye, duree, frequence, jour, mois)
VALUES ('Conge du Nouvel An', 1, 1, 2, 1, 1);
INSERT INTO conge (libelle, paye, duree, frequence, jour, mois)
VALUES ('Conge maladie', 0, 1, 5, NULL, NULL);
INSERT INTO conge (libelle, paye, duree, frequence, jour, mois)
VALUES ('Fete nationale', 1, 1, 2, 26, 6);
INSERT INTO conge (libelle, paye, duree, frequence, jour, mois)
VALUES ('Conge exceptionnel', 1, 1, 1, NULL, NULL);

INSERT INTO type_retenu (libelle) VALUES
('CNaPS'),
('OSTIE'),
('IRSA');

INSERT INTO categorie (libelle) VALUES
('Agricoles'),
('Non agricoles');


INSERT INTO departement (libelle, fonction) VALUES
('Direction générale', 1),
('Ressources Humaines', 2),
('Production agricole', 3),
('Commercial', 4);


-- ÉTAPE 2 : Employés et Postes

-- Insertion des 15 employés
INSERT INTO Employe (nom, prenom, contact, photo, cin, date_naissance, email, adresse, genre) VALUES
('Rakoto', 'Jean', '034 12 345 67', 'jean.jpg', '102345678901', '1985-03-15', 'jean.rakoto@entreprise.mg', 'Lot IIA 123 Antananarivo', 1),
('Rasoa', 'Marie', '032 98 765 43', 'marie.jpg', '112345678902', '1990-07-22', 'marie.rasoa@entreprise.mg', 'Analakely 45 Antananarivo', 0),
('Randria', 'Paul', '033 11 223 34', 'paul.jpg', '122345678903', '1988-11-30', 'paul.randria@entreprise.mg', 'Ivandry 78 Antananarivo', 1),
('Razafy', 'Sara', '034 55 667 78', 'sara.jpg', '132345678904', '1992-05-18', 'sara.razafy@entreprise.mg', 'Ambohidratrimo 12', 0),
('Andriana', 'Luc', '032 44 556 67', 'luc.jpg', '142345678905', '1983-09-05', 'luc.andriana@entreprise.mg', 'Ankorondrano 90 Antananarivo', 1),
('Ravo', 'Claire', '033 77 889 90', 'claire.jpg', '152345678906', '1995-01-25', 'claire.ravo@entreprise.mg', 'Ambatobe 34', 0),
('Razak', 'Thomas', '034 22 334 45', 'thomas.jpg', '162345678907', '1980-12-10', 'thomas.razak@entreprise.mg', 'Alarobia 56 Antananarivo', 1),
('Rasoanaivo', 'Elise', '032 66 778 89', 'elise.jpg', '172345678908', '1987-04-12', 'elise.rasoanaivo@entreprise.mg', 'Ambohijanahary 23', 0),
('Rajaona', 'Marc', '033 33 445 56', 'marc.jpg', '182345678909', '1993-08-08', 'marc.rajaona@entreprise.mg', 'Anosy 67 Antananarivo', 1),
('Razafimahefa', 'Sophie', '034 88 990 01', 'sophie.jpg', '192345678910', '1991-06-14', 'sophie.raz@entreprise.mg', 'Mahamasina 89', 0),
('Randriamanana', 'David', '032 99 001 12', 'david.jpg', '202345678911', '1986-02-28', 'david.randria@entreprise.mg', 'Analamahitsy 11', 1),
('Rafalimanana', 'Nathalie', '033 00 112 23', 'nathalie.jpg', '212345678912', '1994-10-03', 'nathalie.raf@entreprise.mg', 'Ambohimanarina 45', 0),
('Razafindrakoto', 'Patrick', '034 66 778 89', 'patrick.jpg', '222345678913', '1989-07-19', 'patrick.raz@entreprise.mg', 'Ankaditapaka 22', 1),
('Raharimanana', 'Christine', '032 77 889 90', 'christine.jpg', '232345678914', '1996-11-11', 'christine.raha@entreprise.mg', 'Ambohidrapeto 33', 0),
('Andrianarisoa', 'Pierre', '033 44 556 67', 'pierre.jpg', '242345678915', '1984-04-25', 'pierre.andria@entreprise.mg', 'Faravohitra 77 Antananarivo', 1);

-- Insertion des 5 postes
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Directeur Général', 10, 2, 1),          -- Non agricoles, Direction générale
('Responsable RH', 8, 2, 2),              -- Non agricoles, RH
('Ingénieur agronome', 7, 1, 3),         -- Agricoles, Production agricole
('Commercial produits agricoles', 6, 1, 4), -- Agricoles, Commercial
('Comptable', 5, 2, 1);                  -- Non agricoles, Direction générale

-- ÉTAPE 3 : Configurations et contrats

-- Configuration des postes
INSERT INTO config_poste (duree_travail, entree, sortie, id_poste) VALUES
(8, '08:00', '17:00', 1),  -- Directeur Général
(8, '08:00', '17:00', 2),  -- Responsable RH
(8, '07:30', '16:30', 3),  -- Ingénieur agronome
(8, '08:30', '17:30', 4),  -- Commercial
(8, '08:00', '17:00', 5);  -- Comptable

-- Contrats des employés (au moins 1 par employé)
INSERT INTO contrat_employe (date_debut, date_fin, duree, salaire, id_poste, id_employe, id_statut_contrat, id_type_contrat) VALUES
-- Contrats CDI
('2020-01-15', NULL, NULL, 5000000, 1, 1, 1, 1),   -- Jean Rakoto - Directeur
('2021-03-01', NULL, NULL, 2500000, 2, 2, 1, 1),   -- Marie Rasoa - RH
('2022-06-01', NULL, NULL, 2200000, 3, 3, 1, 1),   -- Paul Randria - Ingénieur agronome
('2022-08-15', NULL, NULL, 1800000, 4, 4, 1, 1),   -- Sara Razafy - Commercial
('2023-01-10', NULL, NULL, 1500000, 5, 5, 1, 1),   -- Luc Andriana - Comptable
('2021-09-01', NULL, NULL, 2300000, 3, 6, 1, 1),   -- Claire Ravo - Ingénieur agronome
('2022-02-15', NULL, NULL, 1900000, 4, 7, 1, 1),   -- Thomas Razak - Commercial
('2023-03-01', NULL, NULL, 2400000, 3, 8, 1, 1),   -- Elise Rasoanaivo - Ingénieur agronome
('2022-11-01', NULL, NULL, 1600000, 5, 9, 1, 1),   -- Marc Rajaona - Comptable
('2023-05-15', NULL, NULL, 1700000, 4, 10, 1, 1),  -- Sophie Razafimahefa - Commercial

-- Contrats CDD
('2023-10-01', '2024-09-30', 12, 1400000, 4, 11, 1, 2),  -- David Randriamanana - Commercial
('2023-11-01', '2024-10-31', 12, 1350000, 5, 12, 1, 2),  -- Nathalie Rafalimanana - Comptable

-- Contrats Stage
('2024-01-15', '2024-06-14', 5, 500000, 3, 13, 1, 3),    -- Patrick Razafindrakoto - Stage ingénieur
('2024-02-01', '2024-07-31', 6, 450000, 2, 14, 1, 3),    -- Christine Raharimanana - Stage RH

-- Contrat Interim
('2024-01-01', '2024-12-31', 12, 1200000, 4, 15, 1, 4);  -- Pierre Andrianarisoa - Commercial

-- Avantages pour certains contrats
INSERT INTO avantage (libelle, montant, id_contrat_employe) VALUES
('Véhicule de fonction', 500000, 1),     -- Directeur
('Logement', 300000, 1),                 -- Directeur
('Téléphone', 100000, 1),                -- Directeur
('Véhicule de fonction', 400000, 2),     -- Responsable RH
('Téléphone', 100000, 2),                -- Responsable RH
('Logement', 200000, 3),                 -- Ingénieur agronome
('Téléphone', 80000, 3),                 -- Ingénieur agronome
('Prime transport', 150000, 4),          -- Commercial
('Téléphone', 80000, 4),                 -- Commercial
('Prime transport', 120000, 5),          -- Comptable
('Téléphone', 60000, 7),                 -- Commercial Thomas
('Prime déplacement', 200000, 3),        -- Ingénieur Paul (terrain)
('Prime transport', 100000, 9),          -- Comptable Marc
('Téléphone', 60000, 10),                -- Commercial Sophie
('Prime transport', 80000, 11);          -- Commercial David (CDD)


-- ÉTAPE 4 : Documents et présences

-- Insertion des documents (1 par employé - CIN seulement)
INSERT INTO Document (chemin, id_type_document, id_employe) VALUES
-- Type Document = 5 (Photo d'identité pour les CIN)
('/documents/cin/cin_jean.jpg', 5, 1),
('/documents/cin/cin_marie.jpg', 5, 2),
('/documents/cin/cin_paul.jpg', 5, 3),
('/documents/cin/cin_sara.jpg', 5, 4),
('/documents/cin/cin_luc.jpg', 5, 5),
('/documents/cin/cin_claire.jpg', 5, 6),
('/documents/cin/cin_thomas.jpg', 5, 7),
('/documents/cin/cin_elise.jpg', 5, 8),
('/documents/cin/cin_marc.jpg', 5, 9),
('/documents/cin/cin_sophie.jpg', 5, 10),
('/documents/cin/cin_david.jpg', 5, 11),
('/documents/cin/cin_nathalie.jpg', 5, 12),
('/documents/cin/cin_patrick.jpg', 5, 13),
('/documents/cin/cin_christine.jpg', 5, 14),
('/documents/cin/cin_pierre.jpg', 5, 15);

-- Insertion des données système
INSERT INTO data (libelle, valeur) VALUES
('SMIG', 200000.00),
('Taux USD', 4500.00),
('Taux EUR', 4800.00),
('Prime ancienneté min', 50000.00),
('Prime ancienneté max', 200000.00),
('Indemnité transport', 50000.00),
('Indemnité repas', 10000.00);

-- Insertion des présences pour décembre 2025 (1er au 5 décembre)
-- Calcul du montant journalier approximatif : salaire mensuel / 21.67 jours
INSERT INTO presence (date_travail, entree, sortie, montant, id_employe) VALUES
-- 1er décembre 2025 (lundi)
('2025-12-01', '08:05', '17:10', 230736.96, 1),  -- Directeur: 5,000,000/21.67
('2025-12-01', '07:58', '17:02', 115368.48, 2),  -- RH: 2,500,000/21.67
('2025-12-01', '07:45', '16:40', 101522.84, 3),  -- Ingénieur: 2,200,000/21.67
('2025-12-01', '08:35', '17:40', 83064.61, 4),   -- Commercial: 1,800,000/21.67
('2025-12-01', '08:02', '17:05', 69220.12, 5),   -- Comptable: 1,500,000/21.67
('2025-12-01', '07:50', '16:35', 106137.52, 6),  -- Ingénieur: 2,300,000/21.67
('2025-12-01', '08:40', '17:45', 87678.82, 7),   -- Commercial: 1,900,000/21.67
('2025-12-01', '07:48', '16:38', 110751.27, 8),  -- Ingénieur: 2,400,000/21.67
('2025-12-01', '08:10', '17:15', 73834.79, 9),   -- Comptable: 1,600,000/21.67
('2025-12-01', '08:32', '17:38', 78449.47, 10),  -- Commercial: 1,700,000/21.67
('2025-12-01', '08:38', '17:42', 64605.44, 11),  -- Commercial CDD: 1,400,000/21.67
('2025-12-01', '08:07', '17:12', 62298.11, 12),  -- Comptable CDD: 1,350,000/21.67
('2025-12-01', '07:55', '16:30', 23073.70, 13),  -- Stage ingénieur: 500,000/21.67
('2025-12-01', '08:03', '17:08', 20766.33, 14),  -- Stage RH: 450,000/21.67
('2025-12-01', '08:36', '17:41', 55376.10, 15),  -- Intérim: 1,200,000/21.67

-- 2 décembre 2025 (mardi)
('2025-12-02', '08:10', '17:15', 230736.96, 1),
('2025-12-02', '08:02', '17:00', 115368.48, 2),
('2025-12-02', '07:42', '16:35', 101522.84, 3),
('2025-12-02', '08:40', '17:45', 83064.61, 4),
('2025-12-02', '08:05', '17:10', 69220.12, 5),
('2025-12-02', '07:48', '16:40', 106137.52, 6),
('2025-12-02', '08:38', '17:40', 87678.82, 7),
('2025-12-02', '07:50', '16:42', 110751.27, 8),
('2025-12-02', '08:15', '17:20', 73834.79, 9),
('2025-12-02', '08:35', '17:42', 78449.47, 10),
('2025-12-02', '08:42', '17:48', 64605.44, 11),
('2025-12-02', '08:12', '17:18', 62298.11, 12),
('2025-12-02', '08:00', '16:35', 23073.70, 13),
('2025-12-02', '08:10', '17:15', 20766.33, 14),
('2025-12-02', '08:38', '17:45', 55376.10, 15),

-- 3 décembre 2025 (mercredi)
('2025-12-03', '08:15', '17:20', 230736.96, 1),
('2025-12-03', '07:55', '16:58', 115368.48, 2),
('2025-12-03', '07:38', '16:32', 101522.84, 3),
('2025-12-03', '08:42', '17:48', 83064.61, 4),
('2025-12-03', '08:08', '17:12', 69220.12, 5),
('2025-12-03', '07:52', '16:45', 106137.52, 6),
('2025-12-03', '08:35', '17:42', 87678.82, 7),
('2025-12-03', '07:45', '16:38', 110751.27, 8),
('2025-12-03', '08:20', '17:25', 73834.79, 9),
('2025-12-03', '08:40', '17:45', 78449.47, 10),
('2025-12-03', '08:45', '17:50', 64605.44, 11),
('2025-12-03', '08:15', '17:20', 62298.11, 12),
('2025-12-03', '08:05', '16:40', 23073.70, 13),
('2025-12-03', '08:12', '17:18', 20766.33, 14),
('2025-12-03', '08:40', '17:48', 55376.10, 15),

-- 4 décembre 2025 (jeudi)
('2025-12-04', '08:12', '17:18', 230736.96, 1),
('2025-12-04', '08:05', '17:10', 115368.48, 2),
('2025-12-04', '07:40', '16:35', 101522.84, 3),
('2025-12-04', '08:38', '17:45', 83064.61, 4),
('2025-12-04', '08:10', '17:15', 69220.12, 5),
('2025-12-04', '07:48', '16:42', 106137.52, 6),
('2025-12-04', '08:42', '17:48', 87678.82, 7),
('2025-12-04', '07:52', '16:45', 110751.27, 8),
('2025-12-04', '08:18', '17:22', 73834.79, 9),
('2025-12-04', '08:35', '17:42', 78449.47, 10),
('2025-12-04', '08:40', '17:45', 64605.44, 11),
('2025-12-04', '08:10', '17:15', 62298.11, 12),
('2025-12-04', '08:02', '16:38', 23073.70, 13),
('2025-12-04', '08:15', '17:20', 20766.33, 14),
('2025-12-04', '08:42', '17:50', 55376.10, 15),

-- 5 décembre 2025 (vendredi)
('2025-12-05', '08:08', '17:12', 230736.96, 1),
('2025-12-05', '08:00', '17:05', 115368.48, 2),
('2025-12-05', '07:35', '16:30', 101522.84, 3),
('2025-12-05', '08:35', '17:40', 83064.61, 4),
('2025-12-05', '08:12', '17:18', 69220.12, 5),
('2025-12-05', '07:45', '16:38', 106137.52, 6),
('2025-12-05', '08:38', '17:45', 87678.82, 7),
('2025-12-05', '07:48', '16:42', 110751.27, 8),
('2025-12-05', '08:15', '17:20', 73834.79, 9),
('2025-12-05', '08:38', '17:45', 78449.47, 10),
('2025-12-05', '08:42', '17:48', 64605.44, 11),
('2025-12-05', '08:08', '17:12', 62298.11, 12),
('2025-12-05', '08:00', '16:35', 23073.70, 13),
('2025-12-05', '08:10', '17:15', 20766.33, 14),
('2025-12-05', '08:38', '17:45', 55376.10, 15);





-- ÉTAPE 5 : Congés, absences et soldes

-- Insertion des soldes de congés pour 2025
INSERT INTO solde_conge (annee, jours_acquis, jours_conso, id_employe) VALUES
(2023, 40.00, 40.00, 1),
(2024, 40.00, 40.00, 1),
(2025, 20.00, 12.50, 1),   -- Directeur
(2025, 22.50, 10.00, 2),   -- RH
(2025, 17.50, 8.00, 3),    -- Ingénieur agronome
(2025, 15.00, 5.50, 4),    -- Commercial
(2025, 12.50, 3.00, 5),    -- Comptable
(2025, 20.00, 15.00, 6),   -- Ingénieur agronome
(2025, 18.00, 7.50, 7),    -- Commercial
(2025, 13.00, 4.00, 8),    -- Ingénieur agronome
(2025, 10.00, 2.50, 9),    -- Comptable
(2025, 8.50, 1.50, 10),    -- Commercial
(2025, 7.00, 0.00, 11),    -- Commercial CDD
(2025, 6.50, 0.00, 12),    -- Comptable CDD
(2025, 3.00, 1.00, 13),    -- Stage ingénieur
(2025, 2.50, 0.50, 14),    -- Stage RH
(2025, 11.00, 3.50, 15);   -- Intérim

-- Insertion des absences (mélange congés et absences maladie)
INSERT INTO absence (motif, date_debut, date_fin, id_document, id_conge, date_demande) VALUES
-- Congés annuels payés
('Congé annuel', '2025-12-15', '2025-12-30', 1, 1, '2025-11-10'),  -- Marie RH
('Vacances familiales', '2025-12-18', '2025-12-24', 1, 1, '2025-12-15'),  -- Paul Ingénieur
('Repos', '2025-12-10', '2025-12-12', 2, 1, '2025-11-01'),  -- Sara Commercial

-- Congés maladie
('Fièvre et grippe', '2025-12-02', '2025-12-03', 2, 3, '2025-12-02'),  -- Marie
('Migraine sévère', '2025-12-04', '2025-12-04', 3, 3, '2025-12-04'),  -- Paul
('Douleurs abdominales', '2025-12-05', '2025-12-06', 4, 3, '2025-12-05'),  -- Sara
('Rhume et toux', '2025-12-03', '2025-12-04', 5, 3, '2025-12-03'),  -- Luc
('Vertiges', '2025-12-01', '2025-12-01', 6, 3, '2025-12-01'),  -- Claire

-- RTT
('RTT', '2025-12-19', '2025-12-19', 1, 5, '2025-12-10'),  -- Marc
('RTT', '2025-12-22', '2025-12-22', NULL, 5, '2025-12-12');  -- Sophie

-- Insertion des statuts d'absence
INSERT INTO statut_abscence (date_statut, statut, id_absence) VALUES
-- Statuts pour les congés annuels
('2025-12-01', 1, 1),  -- Congé Marie validé
('2025-12-06', 0, 2),  -- Congé Paul validé
('2025-12-02', 1, 3),  -- Congé Sara validé

-- Statuts pour les congés maladie
('2025-12-02', 1, 4),  -- Maladie Marie validé
('2025-12-04', 1, 5),  -- Maladie Paul validé
('2025-12-05', 1, 6),  -- Maladie Sara validé
('2025-12-03', 1, 7),  -- Maladie Luc validé
('2025-12-01', 1, 8),  -- Maladie Claire validé

-- Statuts pour absences sans solde
('2025-11-29', 1, 9),  -- RTT Marc validé
('2025-12-02', 1, 10);  -- RTT Sophie validé

-- ÉTAPE 6 : Demandes (sans messages)

-- Insertion des demandes d'attestation
INSERT INTO demande_attestation (type_demande, id_employe, daty, statut) VALUES
(1, 3, '2025-12-01', 1),   -- Paul - Attestation travail - Prêt
(2, 5, '2025-12-02', 1),   -- Luc - Attestation salaire - Prêt
(3, 13, '2025-12-01', 0),  -- Patrick - Attestation stage - En attente
(1, 7, '2025-12-03', 1),   -- Thomas - Attestation travail - Prêt
(2, 10, '2025-12-02', 0),  -- Sophie - Attestation salaire - En attente
(1, 11, '2025-12-04', 2),  -- David - Attestation travail - Refusé
(3, 14, '2025-12-03', 1),  -- Christine - Attestation stage - Prêt
(1, 9, '2025-12-04', 0),   -- Marc - Attestation travail - En attente
(2, 6, '2025-12-05', 1),   -- Claire - Attestation salaire - Prêt
(1, 12, '2025-12-01', 1);  -- Nathalie - Attestation travail - Prêt

-- Insertion des demandes de remboursement
INSERT INTO demande_remboursement (motif, montant, fichier, id_employe, daty, statut) VALUES
('Frais médicaux consultation', 50000.00, '/documents/remboursement/consult_jean.pdf', 1, '2025-12-01', 1),
('Achat médicaments', 25000.00, '/documents/remboursement/medic_marie.pdf', 2, '2025-12-02', 0),
('Déplacement professionnel', 120000.00, '/documents/remboursement/deplacement_paul.pdf', 3, '2025-12-01', 1),
('Frais dentaires', 80000.00, '/documents/remboursement/dentiste_sara.pdf', 4, '2025-12-03', 2),
('Formation en ligne', 45000.00, '/documents/remboursement/formation_luc.pdf', 5, '2025-12-02', 1),
('Achat équipement sécurité', 75000.00, '/documents/remboursement/equipement_claire.pdf', 6, '2025-12-04', 0),
('Frais optique lunettes', 95000.00, '/documents/remboursement/lunettes_thomas.pdf', 7, '2025-12-03', 1),
('Déplacement terrain agricole', 150000.00, '/documents/remboursement/terrain_elise.pdf', 8, '2025-12-01', 1),
('Frais pharmacie', 35000.00, '/documents/remboursement/pharma_marc.pdf', 9, '2025-12-05', 0),
('Formation commerciale', 60000.00, '/documents/remboursement/formation_sophie.pdf', 10, '2025-12-04', 1);

-- ÉTAPE 7 : Configuration des retenues

-- Insertion des configurations de retenues
INSERT INTO config_retenu (pourcentage_entreprise, pourcentage_employer, salaire_min, salaire_max, id_categorie, id_type_retenu) VALUES
-- CNaPS pour Agricoles
(13.00, 1.00, 200000.00, 2000000.00, 1, 1),
(13.00, 1.00, 2000001.00, 5000000.00, 1, 1),

-- CNaPS pour Non agricoles
(13.00, 1.00, 200000.00, 2000000.00, 2, 1),
(13.00, 1.00, 2000001.00, 5000000.00, 2, 1),

-- OSTIE pour Agricoles
(5.00, 2.00, 200000.00, 2000000.00, 1, 2),
(5.00, 2.00, 2000001.00, 5000000.00, 1, 2),

-- OSTIE pour Non agricoles
(5.00, 2.00, 200000.00, 2000000.00, 2, 2),
(5.00, 2.00, 2000001.00, 5000000.00, 2, 2),

-- IRSA pour Agricoles (tranches)
(0.00, 0.00, 200000.00, 350000.00, 1, 3),
(5.00, 0.00, 350001.00, 400000.00, 1, 3),
(10.00, 0.00, 400001.00, 500000.00, 1, 3),
(15.00, 0.00, 500001.00, 600000.00, 1, 3),
(20.00, 0.00, 600001.00, 2000000.00, 1, 3),

-- IRSA pour Non agricoles (tranches)
(0.00, 0.00, 200000.00, 350000.00, 2, 3),
(5.00, 0.00, 350001.00, 400000.00, 2, 3),
(10.00, 0.00, 400001.00, 500000.00, 2, 3),
(15.00, 0.00, 500001.00, 600000.00, 2, 3),
(20.00, 0.00, 600001.00, 2000000.00, 2, 3);