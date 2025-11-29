TURNOVER
SELECT COUNT(*) FROM contrat_employe where id_poste = 1 and date_debut ='' and date_fin = '';

ABCENTISISME
SELECT COUTN(*) FROM absence;

ANCIENNETE MOYENNE
SELECT 
    AVG(EXTRACT(YEAR FROM AGE(
        DATE '2027-01-01',  -- la date de référence
        date_debut
    ))) AS anciennete_moyenne
FROM contrat_employe where date_fin = '2027-01-01';


AGE : 18 ans firy tal ity anne iity avoka janvie fevrier mars
Fin de contrat  : calculer la anne ou mois ou le jour entre la date_debut et dureer 
conger compter le conger dans l'absnece 

j'entre 18 il vas afficher une statistique des employer de 18ans dans le contrat_employe et date_fin <=2999
    
      SELECT COUNT(*) as count_employees
            FROM contrat_employe ce
            JOIN employe e ON ce.id_employe = e.id
            WHERE EXTRACT(YEAR FROM AGE(CURRENT_DATE, e.date_naissance)) = 35
            AND ce.date_fin <= '2999-12-31';


UPDATE contrat_employe SET date_fin='2027-06-05' where id_employe=2;


SELECT * FROM contrat_employe ce JOIN employe e ON ce.id_employe = e.id 
        JOIN poste p ON ce.id_poste = p.id WHERE '2025-11-28' BETWEEN date_debut AND date_fin AND id_statut_contrat = 2


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
