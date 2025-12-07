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
   id_type_document INT,
   id_employe INT,
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
   id_document INT,
   id_conge INT,
   FOREIGN KEY(id_document) REFERENCES Document(id),
   FOREIGN KEY(id_conge) REFERENCES conge(id)
);

CREATE TABLE statut_abscence(
   id SERIAL PRIMARY KEY,
   date_statut DATE,
   statut INT,
   id_absence INT,
   FOREIGN KEY(id_absence) REFERENCES absence(id)
);

CREATE TABLE presence(
   id SERIAL PRIMARY KEY,
   date_travail DATE,
   entree TIME,
   sortie TIME,
   montant DECIMAL(15,2),
   id_employe INT,
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
   id_categorie INT,
   id_departement INT,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_departement) REFERENCES departement(id)
);

CREATE TABLE config_poste(
   id SERIAL PRIMARY KEY,
   duree_travail INT,
   entree TIME,
   sortie TIME,
   id_poste INT,
   FOREIGN KEY(id_poste) REFERENCES Poste(id)
);

CREATE TABLE config_retenu(
   id SERIAL PRIMARY KEY,
   pourcentage_entreprise DECIMAL(5,2),
   pourcentage_employer DECIMAL(5,2),
   salaire_min DECIMAL(25,2),
   salaire_max DECIMAL(25,2),
   id_categorie INT,
   id_type_retenu INT,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_type_retenu) REFERENCES type_retenu(id)
);

CREATE TABLE contrat_employe(
   id SERIAL PRIMARY KEY,
   date_debut DATE,
   date_fin DATE,
   duree INT,
   salaire DECIMAL(25,2),
   id_poste INT,
   id_employe INT,
   id_statut_contrat INT,
   id_type_contrat INT,
   FOREIGN KEY(id_poste) REFERENCES Poste(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_statut_contrat) REFERENCES Statut_Contrat(id),
   FOREIGN KEY(id_type_contrat) REFERENCES Type_Contrat(id)
);

CREATE TABLE avantage(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   montant DECIMAL(25,2),
   id_contrat_employe INT,
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

ALTER TABLE absence ADD COLUMN date_demande DATE;



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



CREATE VIEW vue_conge_employe AS
SELECT 
    e.id AS id_employe,
    e.nom,
    e.prenom,
    c.id AS id_conge,
    c.libelle AS type_conge,
    c.paye,
    c.duree,
    a.id AS id_absence,
    a.date_debut,
    a.date_fin,
    ce.id AS id_contrat,
    ce.date_debut AS contrat_debut,
    ce.date_fin AS contrat_fin
FROM absence a
JOIN conge c ON a.id_conge = c.id
JOIN employe e ON a.id_document IN (
    SELECT d.id FROM document d WHERE d.id_employe = e.id
)
JOIN contrat_employe ce ON ce.id_employe = e.id;


CREATE OR REPLACE VIEW vue_contrat_employe AS
SELECT 
    ce.id AS contrat_id,
    e.id AS employe_id,
    e.nom,
    e.prenom,
    e.cin,
    e.date_naissance,
    e.email,
    e.adresse,

    ce.date_debut,
    ce.date_fin,
    ce.duree,
    ce.salaire,

    p.label AS poste,
    c.libelle AS categorie,
    d.libelle AS departement,

    tc.label AS type_contrat,
    sc.label AS statut_contrat
FROM contrat_employe ce
JOIN Employe e ON ce.id_employe = e.id
LEFT JOIN Poste p ON ce.id_poste = p.id
LEFT JOIN categorie c ON p.id_categorie = c.id
LEFT JOIN departement d ON p.id_departement = d.id
LEFT JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
LEFT JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
ORDER BY ce.date_debut DESC;



