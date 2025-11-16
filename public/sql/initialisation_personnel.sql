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