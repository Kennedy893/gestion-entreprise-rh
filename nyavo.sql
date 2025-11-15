TURNOVER
SELECT COUNT(*) FROM contrat_employe where id_poste = 1 and date_debut ='' and date_fin = '';

ABCENTISISME
SELECT COUTN(*) FROM absence;

ANCIENNETE MOYENNE
SELECT AVG(SELECT EXTRACT(YEAR FROM AGE(date_fin, date_debut)) AS difference_annee FROM contrat_employe); 
SELECT TIMESTAMPDIFF(YEAR, date_debut, date_fin) AS difference_annee FROM contrat_employe;
;


SELECT 
    AVG(EXTRACT(YEAR FROM AGE(
        DATE '2025-01-01',  -- la date de référence
        date_debut
    ))) AS anciennete_moyenne
FROM contrat_employe
WHERE (date_fin IS NULL OR date_fin >= DATE '2025-01-01');


AGE : 18 ans firy tal ity anne iity avoka janvie fevrier mars
Fin de contrat  : calculer la anne ou mois ou le jour entre la date_debut et dureer 
conger compter le conger dans l'absnece 
    