--pointage
--entree
INSERT INTO presence (date_travail, entree, sortie, montant, id_employe) VALUES
(?, ? , NULL , NULL, ?);
--sortie
UPDATE presence SET sortie = ? , montant = ? WHERE date_travail = ? AND id_employe = ? AND entree = ?;

--feuille de presence mensuelle
SELECT date_travail, entree, sortie, id_employe FROM presence
WHERE EXTRACT(MONTH FROM date_travail) = ? AND EXTRACT(YEAR FROM date_travail) = ? AND id_employe = ?;

SELECT date_travail, entree, sortie, id_employe
FROM presence
WHERE date_travail BETWEEN date_trunc('week', ?::date) AND date_trunc('week', ?::date) + INTERVAL '6 days'
  AND id_employe = ?;
