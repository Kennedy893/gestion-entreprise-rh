CREATE TABLE Type_Contrat(
   id SERIAL,
   label VARCHAR(50),
   PRIMARY KEY(id)
);

CREATE TABLE Employe(
   id SERIAL,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   contact VARCHAR(50),
   photo VARCHAR(100),
   cin VARCHAR(50),
   date_naissance DATE,
   email VARCHAR(50),
   adresse VARCHAR(50),
   genre INT,
   PRIMARY KEY(id)
);

CREATE TABLE Statut_Contrat(
   id SERIAL,
   label VARCHAR(50),
   PRIMARY KEY(id)
);

CREATE TABLE Type_Document(
   id SERIAL,
   label VARCHAR(50),
   PRIMARY KEY(id)
);

CREATE TABLE Document(
   id SERIAL,
   chemin VARCHAR(250),
   id_employe INT,
   id_type_doc INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_type_doc) REFERENCES Type_Document(id)
);

CREATE TABLE conge(
   id SERIAL,
   libelle VARCHAR(150),
   paye INT,
   duree DECIMAL(15,2),
   frequence INT,
   jour INT,
   mois INT,
   PRIMARY KEY(id)
);

CREATE TABLE absence(
   id SERIAL,
   motif VARCHAR(255),
   date_debut DATE,
   date_fin DATE,
   id_document INT,
   id_conge INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_document) REFERENCES Document(id),
   FOREIGN KEY(id_conge) REFERENCES conge(id)
);

CREATE TABLE statut_abscence(
   id SERIAL,
   date_statut DATE,
   statut INT,
   id_absence INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_absence) REFERENCES absence(id)
);

CREATE TABLE presence(
   id SERIAL,
   date_travail DATE,
   entree TIME,
   sortie TIME,
   montant DECIMAL(15,2),
   id_employe INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);

CREATE TABLE type_retenu(
   id SERIAL,
   libelle VARCHAR(255),
   PRIMARY KEY(id)
);

CREATE TABLE data(
   id SERIAL,
   libelle VARCHAR(100),
   valeur DECIMAL(25,2),
   PRIMARY KEY(id)
);

CREATE TABLE categorie(
   id SERIAL,
   libelle VARCHAR(100),
   PRIMARY KEY(id)
);

CREATE TABLE departement(
   id SERIAL,
   libelle VARCHAR(100),
   fonction INT,
   PRIMARY KEY(id)
);

CREATE TABLE Poste(
   id SERIAL,
   label VARCHAR(50),
   valeur INT,
   id_categorie INT,
   id_departement INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_departement) REFERENCES departement(id)
);

CREATE TABLE config_poste(
   id SERIAL,
   duree_travail INT,
   entree TIME,
   sortie TIME,
   id_poste INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_poste) REFERENCES Poste(id)
);

CREATE TABLE config_retenu(
   id SERIAL,
   pourcentage_entreprise DECIMAL(5,2),
   pourcentage_employer DECIMAL(5,2),
   salaire_min DECIMAL(25,2),
   salaire_max DECIMAL(25,2),
   id_categorie INT,
   id_type_retenu INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_type_retenu) REFERENCES type_retenu(id)
);

CREATE TABLE contrat_employe(
   id SERIAL,
   date_debut DATE,
   date_fin DATE,
   duree INT,
   salaire DECIMAL(25,2),
   id_poste INT,
   id_employe INT,
   id_statut_contrat INT,
   id_type_contrat INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_poste) REFERENCES Poste(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_statut_contrat) REFERENCES Statut_Contrat(id),
   FOREIGN KEY(id_type_contrat) REFERENCES Type_Contrat(id)
);

CREATE TABLE avantage(
   id SERIAL,
   libelle VARCHAR(100),
   montant DECIMAL(25,2),
   id_contrat_employe INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_contrat_employe) REFERENCES contrat_employe(id)
);

CREATE TABLE solde_conge(
   id SERIAL,
   annee INT,
   jours_acquis DECIMAL(15,2),
   jours_conso DECIMAL(15,2),
   jours_restants DECIMAL(15,2) GENERATED ALWAYS AS (jours_acquis - jours_conso) STORED,
   id_employe INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);

CREATE TABLE messages (
   id SERIAL PRIMARY KEY,
   id_employe INT,
   sender INT, -- '0' ou '1'
   contenu TEXT,
   date_envoi TIMESTAMP DEFAULT NOW(),
   is_read BOOLEAN DEFAULT FALSE
);


CREATE TABLE demande_attestation(
   id SERIAL PRIMARY KEY,
   type_demande INT,
   id_employe INT,
   daty DATE,
   statut INT,
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);

CREATE TABLE demande_remboursement(
   id SERIAL,
   motif VARCHAR(100),
   montant DECIMAL(15,2),
   fichier VARCHAR(200),
   id_employe INT NOT NULL,
   daty DATE,
   statut INT,
   PRIMARY KEY(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);




-- ---------------------------------------------------------------

-- VIEWS------

CREATE OR REPLACE VIEW v_absence_en_attente AS
SELECT 
    e.nom,
    e.prenom,
    e.email,
    a.motif,
    a.date_debut,
    a.date_fin,
    sa.date_statut,
    a.id AS id_absence,
    (CURRENT_DATE - sa.date_statut) AS jours_attente,
    p.label AS poste,
    dept.libelle AS departement,
    dept.fonction AS fonction,
    e.id AS id_employe

FROM absence a

-- Dernier statut par absence
JOIN (
    SELECT sa1.*
    FROM statut_abscence sa1
    JOIN (
        SELECT id_absence, MAX(date_statut) AS max_date
        FROM statut_abscence
        GROUP BY id_absence
    ) AS last_sa
    ON sa1.id_absence = last_sa.id_absence
    AND sa1.date_statut = last_sa.max_date
) sa ON sa.id_absence = a.id

JOIN Document d ON d.id = a.id_document
JOIN Employe e ON e.id = d.id_employe

JOIN contrat_employe ce ON ce.id_employe = e.id
                         AND ce.date_fin IS NULL

JOIN Poste p ON p.id = ce.id_poste
JOIN departement dept ON dept.id = p.id_departement

WHERE sa.statut = 0 
  AND a.id_conge IS NOT NULL

ORDER BY sa.date_statut DESC;



-- ----


CREATE OR REPLACE VIEW v_absence_en_attente_rh AS
SELECT 
    e.nom,
    e.prenom,
    e.email,
    a.motif,
    a.date_debut,
    a.date_fin,
    sa.date_statut,
    a.id AS id_absence,
    (CURRENT_DATE - sa.date_statut) AS jours_attente,
    p.label AS poste,
    dept.libelle AS departement,
    dept.fonction AS fonction,
    e.id AS id_employe
FROM absence a

-- On récupère UNE SEULE ligne par absence : la DERNIÈRE par ID
JOIN (
    SELECT DISTINCT ON (id_absence)
           id_absence, statut, date_statut
    FROM statut_abscence
    ORDER BY id_absence, id DESC  -- 🔥 C’EST ICI LE CHANGEMENT !
) sa ON sa.id_absence = a.id

JOIN Document d ON d.id = a.id_document
JOIN Employe e ON e.id = d.id_employe
JOIN contrat_employe ce ON ce.id_employe = e.id
                         AND ce.date_fin IS NULL
JOIN Poste p ON p.id = ce.id_poste
JOIN departement dept ON dept.id = p.id_departement

WHERE sa.statut = 1  -- le dernier statut = 1
  AND a.id_conge IS NOT NULL

ORDER BY sa.date_statut DESC;



UPDATE absence SET id_document=1;

-- --------------------------------\
-- FONCTION
CREATE OR REPLACE FUNCTION get_solde_disponible(
    p_id_employe INT,
    p_annee_demande INT
)
RETURNS NUMERIC(10,2)
LANGUAGE plpgsql
AS $$
DECLARE
    date_embauche DATE;
    annee_embauche INT;
    groupe INT;
    annee_min INT;
    annee_max INT;
    solde_total NUMERIC(10,2);
BEGIN
    -- 1) récupérer la date de début du premier contrat de l'employé
    SELECT MIN(date_debut)
    INTO date_embauche
    FROM contrat_employe
    WHERE id_employe = p_id_employe;

    IF date_embauche IS NULL THEN
        RETURN 0;
    END IF;

    annee_embauche := EXTRACT(YEAR FROM date_embauche)::INT;

    -- 2) calculer le groupe de 3 ans
    groupe := FLOOR((p_annee_demande - annee_embauche) / 3);

    -- 3) bornes de la fenêtre de 3 ans
    annee_min := annee_embauche + groupe * 3;
    annee_max := annee_min + 2;

    -- 4) somme des jours restants dans cette fenêtre
    SELECT COALESCE(SUM(jours_restants), 0)
    INTO solde_total
    FROM solde_conge
    WHERE id_employe = p_id_employe
      AND annee BETWEEN annee_min AND annee_max;

    RETURN solde_total;
END;
$$;


-- -------------------------------------------------------------------

------ DONNEES ------

-- EMPLOYES
INSERT INTO Employe (nom, prenom, contact, photo, cin, date_naissance, email, adresse, genre)
VALUES
('Rakoto', 'Jean', '0321123344', 'photos/jean.jpg', '101010101010', '1990-05-12', 'jean.rakoto@gmail.com', 'Antananarivo', 1),
('Randria', 'Sarah', '0345678899', 'photos/sarah.jpg', '202020202020', '1993-08-25', 'sarah.randria@gmail.com', 'Antsirabe', 2),
('Rasoanaivo', 'Hery', '0339988776', 'photos/hery.jpg', '303030303030', '1987-03-03', 'hery.raso@gmail.com', 'Toamasina', 1),
('Andriam', 'Lova', '0328899001', 'photos/lova.jpg', '404040404040', '1995-12-09', 'lova.andriam@gmail.com', 'Fianarantsoa', 2),
('Rakotondr', 'Tiana', '0345566778', 'photos/tiana.jpg', '505050505050', '1989-07-17', 'tiana.rkt@gmail.com', 'Mahajanga', 1);

-- TYPE DOCUMENT
INSERT INTO Type_Document (label)
VALUES
('Contrat de travail'),
('Certificat médical'),
('Lettre de motivation'),
('Attestation de présence'),
('Justificatif absence');

-- DOCUMENT
INSERT INTO Document (chemin, id_employe, id_type_doc)
VALUES
('docs/contrat_jean.pdf', 1, 1),
('docs/certif_sarah.pdf', 2, 2),
('docs/absence_hery.pdf', 3, 5),
('docs/contrat_lova.pdf', 4, 1),
('docs/certif_tiana.pdf', 5, 2),
('docs/attestation_sarah.pdf', 2, 4),
('docs/justif_lova.pdf', 4, 5);

-- CONGE
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

-- CATEGORIE
INSERT INTO categorie (libelle) VALUES
('Cadre'),
('Agent de maîtrise'),
('Employé'),
('Ouvrier');

-- DEPARTEMENT
INSERT INTO departement (libelle, fonction) VALUES
('Ressources Humaines', 1),
('Informatique', 2),
('Finance', 3),
('Logistique', 4);

-- POSTE
INSERT INTO Poste (label, valeur, id_categorie, id_departement) VALUES
('Directeur RH',          1, 1, 1),  -- Cadre, RH
('Développeur Backend',   2, 3, 2),  -- Employé, Informatique
('Comptable',             3, 3, 3),  -- Employé, Finance
('Chef de stock',         4, 2, 4);  -- Agent de maîtrise, Logistique

-- TYPE DE CONTRAT
INSERT INTO Type_Contrat (label) VALUES
('CDI'),
('CDD'),
('Stage');

-- STATUT DE CONTRAT
INSERT INTO Statut_Contrat (label) VALUES
('Actif'),
('Suspendu'),
('Terminé');

-- CONTRAT EMPLOYE 
INSERT INTO contrat_employe (date_debut, date_fin, duree, salaire, id_poste, id_employe, id_statut_contrat, id_type_contrat)
VALUES ('2023-01-01', NULL, NULL, 1500000, 1, 1, 1, 1);

-- SOLDE
INSERT INTO solde_conge (annee, jours_acquis, jours_conso, id_employe)
VALUES 
(2023, 40.00, 40.00, 1),
(2024, 40.00, 40.00, 1),
(2025, 40.00, 39.00, 1);

INSERT INTO messages (id_employe, sender, contenu) VALUES (1, 0, 'Bonjour, j’aimerais demander un congé.');

INSERT INTO messages (id_employe, sender, contenu) VALUES (1, 1, 'Bonjour, veuillez remplir le formulaire de demande.');

INSERT INTO messages (id_employe, sender, contenu, is_read)VALUES (1, 1, 'Message lu automatiquement.', TRUE);



-- --------------------------------------------------------------

-- Update --------------------
UPDATE absence SET id_document=1;

ALTER TABLE absence ADD COLUMN date_demande DATE;