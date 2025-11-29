INSERT INTO Type_Contrat (label) VALUES
('CDI'),
('CDD');

INSERT INTO Statut_Contrat (label) VALUES
('Normal'),
('Démission'),
('Licencie');

INSERT INTO categorie (libelle) VALUES
('Agricole'),
('Non Agricole');

INSERT INTO departement (libelle, fonction) VALUES
('Direction Générale', 1),
('Ressources Humaines', 2),
('Comptabilité et Finance', 3),
('Informatique (IT)', 4),
('Recherche et Développement', 5),
('Commercial et Ventes', 6),
('Marketing', 7),
('Production / Exploitation', 8),
('Logistique et Supply Chain', 9),
('Service Client', 10);

INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
-- Direction Générale (id_departement = 1)
('Directeur Général', 1, 2, 1),
('Assistant de Direction', 2, 2, 1),

-- Ressources Humaines (id_departement = 2)
('Responsable RH', 1, 2, 2),
('Assistant RH', 2, 2, 2),

-- Comptabilité et Finance (id_departement = 3)
('Chef Comptable', 1, 2, 3),
('Comptable', 2, 2, 3),

-- Informatique (IT) (id_departement = 4)
('Responsable IT', 1, 2, 4),
('Développeur', 2, 2, 4),

-- Recherche et Développement (id_departement = 5)
('Chef de Projet R&D', 1, 2, 5),
('Ingénieur R&D', 2, 2, 5),

-- Commercial et Ventes (id_departement = 6)
('Responsable Commercial', 1, 2, 6),
('Commercial', 2, 2, 6),

-- Marketing (id_departement = 7)
('Responsable Marketing', 1, 2, 7),
('Chargé de Marketing', 2, 2, 7),

-- Production / Exploitation (id_departement = 8)
('Chef de Production', 1, 2, 8),
('Opérateur de Production', 2, 2, 8),

-- Logistique et Supply Chain (id_departement = 9)
('Responsable Logistique', 1, 2, 9),
('Agent Logistique', 2, 2, 9),

-- Service Client (id_departement = 10)
('Responsable Service Client', 1, 2, 10),
('Agent Service Client', 2, 2, 10);


INSERT INTO Employe (nom, prenom, contact, photo, cin, date_naissance, email, adresse, genre) VALUES
-- Direction
('Rakoto', 'Jean', '+261 34 12 345 67', 'jean_rakoto.jpg', '101234567891', '1980-05-15', 'jean.rakoto@entreprise.mg', 'Lot IVC 56 Antananarivo', 1),
('Randria', 'Marie', '+261 33 12 345 68', 'marie_randria.jpg', '101234567892', '1985-08-22', 'marie.randria@entreprise.mg', 'Lot VTS 12 Antananarivo', 2),

-- RH
('Rasoa', 'Mialy', '+261 32 12 345 69', 'mialy_rasoa.jpg', '101234567893', '1990-03-10', 'mialy.rasoa@entreprise.mg', 'Ankadifotsy Antananarivo', 2),
('Andriana', 'Tahina', '+261 34 12 345 70', 'tahina_andriana.jpg', '101234567894', '1992-11-30', 'tahina.andriana@entreprise.mg', 'Ivandry Antananarivo', 1),

-- Comptabilité
('Rajaona', 'Henintsoa', '+261 33 12 345 71', 'henintsoa_rajaona.jpg', '101234567895', '1988-07-18', 'henintsoa.rajaona@entreprise.mg', 'Ambohidratrimo', 2),
('Razafy', 'Ando', '+261 32 12 345 72', 'ando_razafy.jpg', '101234567896', '1991-04-25', 'ando.razafy@entreprise.mg', 'Analakely Antananarivo', 1),

-- IT
('Rakotondrabe', 'Faniry', '+261 34 12 345 73', 'faniry_rakotondrabe.jpg', '101234567897', '1993-09-12', 'faniry.rakotondrabe@entreprise.mg', 'Ankorondrano Antananarivo', 2),
('Randrianarisoa', 'Hery', '+261 33 12 345 74', 'hery_randrianarisoa.jpg', '101234567898', '1989-12-05', 'hery.randrianarisoa@entreprise.mg', 'Ambohijatovo Antananarivo', 1),

-- R&D
('Razanamalala', 'Sandra', '+261 32 12 345 75', 'sandra_razanamalala.jpg', '101234567899', '1987-06-20', 'sandra.razanamalala@entreprise.mg', 'Andraharo Antananarivo', 2),
('Andrianavalona', 'Tovo', '+261 34 12 345 76', 'tovo_andrianavalona.jpg', '101234567900', '1994-02-14', 'tovo.andrianavalona@entreprise.mg', 'Ambatobe Antananarivo', 1),

-- Commercial
('Rafanomezantsoa', 'Nirina', '+261 33 12 345 77', 'nirina_rafanomezantsoa.jpg', '101234567901', '1986-10-08', 'nirina.rafanomezantsoa@entreprise.mg', 'Anosy Antananarivo', 1),
('Randimbiarison', 'Voahangy', '+261 32 12 345 78', 'voahangy_randimbiarison.jpg', '101234567902', '1990-01-17', 'voahangy.randimbiarison@entreprise.mg', 'Mahamasina Antananarivo', 2),

-- Marketing
('Ralison', 'Mamisoa', '+261 34 12 345 79', 'mamisoa_ralison.jpg', '101234567903', '1984-09-03', 'mamisoa.ralison@entreprise.mg', 'Antsahabe Antananarivo', 2),
('Andriantsoa', 'Feno', '+261 33 12 345 80', 'feno_andriantsoa.jpg', '101234567904', '1995-07-29', 'feno.andriantsoa@entreprise.mg', 'Ambohimanarina', 1),

-- Production
('Razafindrakoto', 'Lala', '+261 32 12 345 81', 'lala_razafindrakoto.jpg', '101234567905', '1983-04-11', 'lala.razafindrakoto@entreprise.mg', 'Ivato Antananarivo', 2),
('Randriamampionona', 'Tafita', '+261 34 12 345 82', 'tafita_randriamampionona.jpg', '101234567906', '1988-08-07', 'tafita.randriamampionona@entreprise.mg', 'Ambohidrapeto', 1),

-- Logistique
('Rajaonarivony', 'Harentsoa', '+261 33 12 345 83', 'harentsoa_rajaonarivony.jpg', '101234567907', '1992-05-19', 'harentsoa.rajaonarivony@entreprise.mg', 'Ankadikely Ilafy', 1),
('Razafimanantsoa', 'Sariaka', '+261 32 12 345 84', 'sariaka_razafimanantsoa.jpg', '101234567908', '1989-03-26', 'sariaka.razafimanantsoa@entreprise.mg', 'Ambohibao', 2),

-- Service Client
('Andrianjafy', 'Miora', '+261 34 12 345 85', 'miora_andrianjafy.jpg', '101234567909', '1991-11-09', 'miora.andrianjafy@entreprise.mg', 'Ambohijanahary', 2),
('Rakotozafy', 'Tsanta', '+261 33 12 345 86', 'tsanta_rakotozafy.jpg', '101234567910', '1993-12-15', 'tsanta.rakotozafy@entreprise.mg', 'Ankadindramamy', 1);


INSERT INTO config_poste (duree_travail, entree, sortie, id_poste) VALUES
-- Direction Générale
(8, '08:00', '17:00', 1),
(8, '08:00', '17:00', 2),

-- Ressources Humaines
(8, '08:00', '17:00', 3),
(8, '08:00', '17:00', 4),

-- Comptabilité et Finance
(8, '08:00', '17:00', 5),
(8, '08:00', '17:00', 6),

-- Informatique (IT)
(8, '08:00', '17:00', 7),
(8, '08:00', '17:00', 8),

-- Recherche et Développement
(8, '08:00', '17:00', 9),
(8, '08:00', '17:00', 10),

-- Commercial et Ventes
(8, '08:00', '17:00', 11),
(8, '08:00', '17:00', 12),

-- Marketing
(8, '08:00', '17:00', 13),
(8, '08:00', '17:00', 14),

-- Production / Exploitation
(8, '08:00', '17:00', 15),
(8, '08:00', '17:00', 16),

-- Logistique et Supply Chain
(8, '08:00', '17:00', 17),
(8, '08:00', '17:00', 18),

-- Service Client
(8, '08:00', '17:00', 19),
(8, '08:00', '17:00', 20);

INSERT INTO contrat_employe (date_debut, date_fin, duree, salaire, id_poste, id_employe, id_statut_contrat, id_type_contrat) VALUES
-- Direction (CDI, Statut Normal)
('2023-01-15', NULL, NULL, 5000000, 1, 1, 1, 1),
('2023-02-01', NULL, NULL, 2500000, 2, 2, 1, 1),

-- RH (CDI, Statut Normal)
('2023-03-01', NULL, NULL, 1800000, 3, 3, 1, 1),
('2023-03-15', '2024-03-15', 12, 1200000, 4, 4, 1, 2),

-- Comptabilité (CDI, Statut Normal)
('2023-04-01', NULL, NULL, 1600000, 5, 5, 1, 1),
('2023-04-15', '2024-04-15', 12, 1000000, 6, 6, 1, 2),

-- IT (CDI, Statut Normal)
('2023-05-01', NULL, NULL, 2000000, 7, 7, 1, 1),
('2023-05-15', '2024-05-15', 12, 1500000, 8, 8, 1, 2),

-- R&D (CDI, Statut Normal)
('2023-06-01', NULL, NULL, 2200000, 9, 9, 1, 1),
('2023-06-15', '2024-06-15', 12, 1400000, 10, 10, 1, 2),

-- Commercial (CDI, Statut Normal)
('2023-07-01', NULL, NULL, 1700000, 11, 11, 1, 1),
('2023-07-15', '2024-07-15', 12, 1100000, 12, 12, 1, 2),

-- Marketing (CDI, Statut Normal)
('2023-08-01', NULL, NULL, 1600000, 13, 13, 1, 1),
('2023-08-15', '2024-08-15', 12, 1000000, 14, 14, 1, 2),

-- Production (CDI, Statut Normal)
('2023-09-01', NULL, NULL, 1300000, 15, 15, 1, 1),
('2023-09-15', '2024-09-15', 12, 800000, 16, 16, 1, 2),

-- Logistique (CDI, Statut Normal)
('2023-10-01', NULL, NULL, 1400000, 17, 17, 1, 1),
('2023-10-15', '2024-10-15', 12, 900000, 18, 18, 1, 2),

-- Service Client (CDI, Statut Normal)
('2023-11-01', NULL, NULL, 1200000, 19, 19, 1, 1),
('2023-11-15', '2024-11-15', 12, 750000, 20, 20, 1, 2);

INSERT INTO conge (libelle, paye, duree, frequence, jour, mois) VALUES
('Nouvel An', 1, 1.00, 2, 1, 1),
('Fête Nationale', 1, 1.00, 2, 26, 6);

INSERT INTO conge (libelle, paye, duree, frequence, jour, mois) VALUES
('Congé annuel', 1, NULL, NULL, NULL, NULL),
('Congé de maternité', 1, NULL, NULL, NULL, NULL),
('Congé de paternité', 1, NULL, NULL, NULL, NULL),
('Congé de maladie', 1, NULL, NULL, NULL, NULL),
('Congé accident de travail', 1, NULL, NULL, NULL, NULL),
('Congé de mariage', 1, NULL, NULL, NULL, NULL),
('Congé de décès', 1, NULL, NULL, NULL, NULL),
('Congé de naissance', 1, NULL, NULL, NULL, NULL),
('Congé formation', 1, NULL, NULL, NULL, NULL),
('Congé sans solde', 0, NULL, NULL, NULL, NULL);

INSERT INTO Document (chemin, id_type_document, id_employe) VALUES
('/documents/certificat_medical_1.pdf', NULL, 3),
('/documents/certificat_medical_2.pdf', NULL, 7),
('/documents/attestation_formation.pdf', NULL, 5),
('/documents/acte_mariage.pdf', NULL, 12),
('/documents/justificatif_personnel.pdf', NULL, 8),
('/documents/demarche_administrative.pdf', NULL, 15);


INSERT INTO absence (motif, date_debut, date_fin, id_document, id_conge) VALUES
('Congé annuel - Repos', '2025-01-20', '2025-01-22', 6, 3),
('Congé maladie - Grippe', '2025-01-13', '2025-01-14', 1, 6),
('Congé maladie - Consultation', '2025-01-27', '2025-01-27', 2, 6),
('Congé formation professionnelle', '2025-01-15', '2025-01-17', 3, 11),
('Congé sans solde - Raisons personnelles', '2025-01-23', '2025-01-24', 5, 12),
('Congé de mariage', '2025-01-29', '2025-01-31', 4, 8);

