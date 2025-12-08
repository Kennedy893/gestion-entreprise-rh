-- PostgreSQL: réinitialise puis génère les présences 2025 (lundi→samedi) selon contrat_employe et config_poste

TRUNCATE TABLE presence RESTART IDENTITY;

WITH dates AS (
  SELECT d::date AS d
  FROM generate_series(date '2025-01-01', date '2025-12-31', interval '1 day') AS gs(d)
),
workdays AS (
  -- isodow: 1=lundi ... 7=dimanche -> on garde 1..6
  SELECT d FROM dates WHERE date_part('isodow', d) <= 6
),
presence_base AS (
  SELECT
    w.d AS date_travail,
    e.id AS id_employe,
    cp.entree::time  AS entree_base,
    cp.sortie::time  AS sortie_base,
    ce.salaire
  FROM employe e
  JOIN contrat_employe ce ON ce.id_employe = e.id
  JOIN config_poste cp     ON cp.id_poste    = ce.id_poste
  JOIN workdays w
    ON w.d >= ce.date_debut
   AND (ce.date_fin IS NULL OR w.d <= ce.date_fin)
)
INSERT INTO presence (date_travail, entree, sortie, montant, id_employe)
SELECT
  pb.date_travail,
  (
    (pb.date_travail::timestamp + pb.entree_base)
    + (( (abs((('x'||substr(md5(pb.id_employe::text||pb.date_travail::text||'in'),1,8))::bit(32)::int)) % 16) - 5 )::text || ' minutes')::interval
  )::time AS entree,
  (
    (pb.date_travail::timestamp + pb.sortie_base)
    + (( (abs((('x'||substr(md5(pb.id_employe::text||pb.date_travail::text||'out'),1,8))::bit(32)::int)) % 16) - 5 )::text || ' minutes')::interval
  )::time AS sortie,
  ROUND(pb.salaire::numeric / 21.67, 2) AS montant,
  pb.id_employe
FROM presence_base pb;