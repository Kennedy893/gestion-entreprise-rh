-- Insertion de données pour 3 employés et toutes les tables liées

-- Types de contrat
INSERT INTO Type_Contrat(label) VALUES
 ('CDI'), ('CDD'), ('Stage');

-- Statuts de contrat
INSERT INTO Statut_Contrat(label) VALUES
 ('Actif'), ('En attente'), ('Terminé');

-- Catégories
INSERT INTO categorie(libelle) VALUES
 ('Cadre'), ('Employé'), ('Ouvrier');

-- Départements
INSERT INTO departement(libelle, fonction) VALUES
 ('Informatique', 1),
 ('Comptabilité', 2),
 ('Ressources Humaines', 3);

-- Postes
INSERT INTO Poste(label, valeur, id_categorie, id_departement) VALUES
 ('Développeur', 1, 1, 1),
 ('Comptable', 1, 2, 2),
 ('Assistant RH', 1, 2, 3);

-- Configuration des postes
INSERT INTO config_poste(duree_travail, entree, sortie, id_poste) VALUES
 (8, '08:00', '16:00', 1),
 (8, '08:00', '16:00', 2),
 (8, '08:00', '16:00', 3);

-- Employés
INSERT INTO Employe(nom, prenom, contact, photo, cin, date_naissance, email, adresse, genre)
VALUES
 ('Randria', 'Tiana', '0320000001', 'tiana.jpg', '112233445', '1990-05-12', 'tiana@mail.com', 'Antananarivo', 1),
 ('Rakoto', 'Mina', '0320000002', 'mina.jpg', '223344556', '1995-08-20', 'mina@mail.com', 'Toamasina', 2),
 ('Rasoana', 'Lova', '0320000003', 'lova.jpg', '334455667', '1992-02-01', 'lova@mail.com', 'Fianarantsoa', 1);

-- Types de documents
INSERT INTO Type_Document(label) VALUES
 ('CIN'), ('Contrat'), ('Certificat de Résidence');

-- Documents des employés
INSERT INTO Document(chemin, id_type_document, id_employe) VALUES
 ('/docs/tiana_cin.pdf', 1, 1),
 ('/docs/mina_contrat.pdf', 2, 2),
 ('/docs/lova_residence.pdf', 3, 3);

-- Types retenues
INSERT INTO type_retenu(libelle) VALUES
 ('CNAPS'), ('OSTIE');

-- Config Retenu
INSERT INTO config_retenu(pourcentage_entreprise, pourcentage_employer, salaire_min, salaire_max, id_categorie, id_type_retenu) VALUES
 (5.0, 1.0, 200000, 2000000, 1, 1),
 (4.0, 1.0, 200000, 2000000, 2, 2);

-- Contrats employés
INSERT INTO contrat_employe(date_debut, date_fin, duree, salaire, id_poste, id_employe, id_statut_contrat, id_type_contrat) VALUES
 ('2024-01-01', '2026-01-01', 24, 1500000, 1, 1, 1, 1),
 ('2024-06-01', '2025-06-01', 12, 800000, 2, 2, 1, 2),
 ('2024-02-15', '2024-08-15', 6, 600000, 3, 3, 1, 3);

UPDATE contrat_employe set id_statut_contrat = 2 where id_employe=1;
-- Avantages
INSERT INTO avantage(libelle, montant, id_contrat_employe) VALUES
 ('Prime de transport', 50000, 1),
 ('Panier repas', 30000, 2),
 ('Indemnité divers', 25000, 3);

-- Congés
INSERT INTO conge(libelle, paye, duree, frequence, jour, mois) VALUES
 ('Annuel', 1, 30, 1, 1, 12),
 ('Maladie', 1, 15, 2, 1, 6);

-- Absences
INSERT INTO absence(motif, date_debut, date_fin, id_document, id_conge) VALUES
 ('Maladie', '2024-03-01', '2024-03-05', 1, 2),
 ('Vacances', '2024-04-10', '2024-04-20', 2, 1);

-- Statuts Absence
INSERT INTO statut_abscence(date_statut, statut, id_absence) VALUES
 ('2024-03-02', 1, 1),
 ('2024-04-11', 1, 2);

-- Présences
INSERT INTO presence(date_travail, entree, sortie, montant, id_employe) VALUES
 ('2024-03-10', '08:00', '16:00', 50000, 1),
 ('2024-03-10', '08:05', '16:10', 45000, 2),
 ('2024-03-10', '08:00', '16:00', 40000, 3);




--plus
INSERT INTO Employe(nom, prenom, contact, photo, cin, date_naissance, email, adresse, genre)
VALUES ('Andry', 'Solo', '0320000004', 'andry.jpg', '445566778', '1990-11-25', 'andry@mail.com', 'Mahajanga', 1);


INSERT INTO Document(chemin, id_type_document, id_employe)
VALUES ('/docs/andry_cin.pdf', 1, 4);


INSERT INTO contrat_employe(date_debut, date_fin, duree, salaire, id_poste, id_employe, id_statut_contrat, id_type_contrat)
VALUES ('2025-01-01', '2027-01-01', 24, 1200000, 1, 4, 1, 1);


INSERT INTO avantage(libelle, montant, id_contrat_employe)
VALUES ('Prime de performance', 40000, 4);