TURNOVER
SELECT COUNT(*) FROM contrat_employe where id_poste = 1 and date_debut ='' and date_fin = '';

ABCENTISISME
SELECT COUTN(*) FROM absence;

ANCIENNETE MOYENNE
SELECT 
    AVG(EXTRACT(YEAR FROM AGE(
        DATE '2025-01-01',  -- la date de référence
        date_debut
    ))) AS anciennete_moyenne
FROM contrat_employe;


AGE : 18 ans firy tal ity anne iity avoka janvie fevrier mars
Fin de contrat  : calculer la anne ou mois ou le jour entre la date_debut et dureer 
conger compter le conger dans l'absnece 

j'entre 18 il vas afficher une statistique des employer de 18ans dans le contrat_employe et date_fin <=2999
    