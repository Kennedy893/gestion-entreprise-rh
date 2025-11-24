CREATE DATABASE gestion_entreprise_rh;
\c gestion_entreprise_rh;

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

