CREATE OR REPLACE VIEW v_stats_recrutement AS
SELECT 
    COUNT(DISTINCT a.id) as total_annonces,
    COUNT(DISTINCT CASE WHEN a.statut = 'active' THEN a.id END) as annonces_actives,
    COUNT(DISTINCT c.id) as total_candidatures,
    COUNT(DISTINCT CASE WHEN c.statut = 'en_attente' THEN c.id END) as candidatures_attente,
    COUNT(DISTINCT CASE WHEN c.statut = 'accepte' THEN c.id END) as candidatures_acceptees,
    COUNT(DISTINCT CASE WHEN c.statut = 'rejete' THEN c.id END) as candidatures_rejetees,
    COUNT(DISTINCT e.id) as total_entretiens,
    COUNT(DISTINCT CASE WHEN e.statut = 'planifie' THEN e.id END) as entretiens_planifies,
    COUNT(DISTINCT CASE WHEN e.statut = 'termine' THEN e.id END) as entretiens_termines
FROM annonce_emploi a
LEFT JOIN candidature c ON a.id = c.id_annonce
LEFT JOIN entretien e ON c.id = e.id_candidature;

-- Vue pour les détails candidatures avec annonces
DROP VIEW IF EXISTS v_candidatures_details;

-- Vue complète pour les candidatures avec toutes les décisions
CREATE OR REPLACE VIEW v_candidatures_details AS
SELECT 
    c.id,
    c.nom,
    c.prenom,
    c.email,
    c.telephone,
    c.date_naissance,
    c.adresse,
    c.cv_path,
    c.lettre_motivation_path,
    c.diplomes_path,
    c.experience_annees,
    c.note_manager,
    c.note_rh,
    c.score_final,
    c.statut,
    c.date_candidature,
    c.date_entretien,
    c.accepte_essai,
    c.demande_contrat_direct,
    c.qualifications,
    c.competences,
    c.dernier_diplome,
    c.etablissement,
    c.langue_parlee,
    c.statut_entretien,
    c.type_contrat_accorde,
    c.date_debut_travail,
    c.decision_finale,
    c.date_decision,
    c.genre,
    c.id_annonce,
    c.id_manager,
    
    -- Informations de l'annonce
    a.titre AS annonce_titre,
    a.description AS annonce_description,
    a.date_publication,
    a.date_limite,
    
    -- Informations du poste
    p.label AS poste_nom,
    p.valeur AS poste_valeur,
    
    -- Informations de l'entretien (si existe)
    e.id AS entretien_id,
    e.date_entretien AS entretien_date,
    e.lieu AS entretien_lieu,
    e.statut AS entretien_statut,
    e.note_manager AS entretien_note_manager,
    e.note_rh AS entretien_note_rh,
    e.note_finale AS entretien_note_finale,
    e.notation_etoiles AS entretien_notation_etoiles,
    e.decision AS entretien_decision,
    e.date_decision AS entretien_date_decision,
    
    -- Informations du manager
    u.nom AS manager_nom,
    u.prenom AS manager_prenom,
    
    -- CALCUL DU STATUT FINAL
    CASE
        -- Si entretien existe et a une décision
        WHEN e.decision IS NOT NULL THEN e.decision
        -- Sinon si candidature a une decision_finale
        WHEN c.decision_finale IS NOT NULL THEN c.decision_finale
        -- Sinon basé sur le statut
        WHEN c.statut IN ('entretien_termine', 'valide', 'actif') AND c.date_decision IS NOT NULL THEN 'accepte'
        WHEN c.statut IN ('rejete', 'refuse', 'decline') THEN 'rejete'
        ELSE 'en_attente'
    END AS status_computed

FROM candidature c
LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
LEFT JOIN Poste p ON a.id_poste = p.id
LEFT JOIN entretien e ON c.id = e.id_candidature
LEFT JOIN utilisateurs u ON c.id_manager = u.id
ORDER BY c.date_candidature DESC;

-- Ajouter des nouveaux statuts possibles pour les candidatures
-- 'en_attente' : Candidature déposée, en attente de traitement RH
-- 'envoye_manager' : Candidature envoyée au manager par RH
-- 'entretien_planifie' : Date d'entretien définie
-- 'entretien_termine' : Entretien passé, en attente évaluation RH
-- 'accepte' : Candidature acceptée
-- 'rejete' : Candidature rejetée


-- Vue pour les postes libres avec détails
CREATE OR REPLACE VIEW v_postes_libres AS
SELECT 
    p.id,
    p.label,
    p.valeur,
    p.id_categorie,
    p.id_departement,
    c.libelle as categorie_nom,
    d.libelle as departement_nom,
    -- Compter le nombre d'annonces actives pour ce poste
    COUNT(DISTINCT CASE WHEN ae.statut = 'active' THEN ae.id END) as annonces_actives,
    -- Compter le nombre d'employés actuellement sur ce poste
    COUNT(DISTINCT CASE WHEN ce.id_statut_contrat = 1 THEN ce.id_employe END) as employes_actifs,
    -- Calcul du nombre de postes libres (valeur - employés actifs - annonces actives)
    (p.valeur - 
     COUNT(DISTINCT CASE WHEN ce.id_statut_contrat = 1 THEN ce.id_employe END) -
     COUNT(DISTINCT CASE WHEN ae.statut = 'active' THEN ae.id END)
    ) as postes_disponibles,
    -- Statut du poste
    CASE 
        WHEN (p.valeur - 
              COUNT(DISTINCT CASE WHEN ce.id_statut_contrat = 1 THEN ce.id_employe END) -
              COUNT(DISTINCT CASE WHEN ae.statut = 'active' THEN ae.id END)
             ) > 0 THEN 'disponible'
        ELSE 'complet'
    END as statut_disponibilite
FROM Poste p
JOIN categorie c ON p.id_categorie = c.id
JOIN departement d ON p.id_departement = d.id
LEFT JOIN annonce_emploi ae ON p.id = ae.id_poste
LEFT JOIN contrat_employe ce ON p.id = ce.id_poste
GROUP BY p.id, p.label, p.valeur, p.id_categorie, p.id_departement, c.libelle, d.libelle;

-- Vue pour les statistiques des postes
CREATE OR REPLACE VIEW v_stats_postes AS
SELECT 
    COUNT(*) as total_postes,
    SUM(valeur) as total_capacite,
    SUM(CASE WHEN (
        valeur - 
        (SELECT COUNT(DISTINCT ce.id_employe) 
         FROM contrat_employe ce 
         WHERE ce.id_poste = Poste.id AND ce.id_statut_contrat = 1) -
        (SELECT COUNT(DISTINCT ae.id) 
         FROM annonce_emploi ae 
         WHERE ae.id_poste = Poste.id AND ae.statut = 'active')
    ) > 0 THEN 1 ELSE 0 END) as postes_avec_places_libres,
    SUM(valeur - 
        (SELECT COUNT(DISTINCT ce.id_employe) 
         FROM contrat_employe ce 
         WHERE ce.id_poste = Poste.id AND ce.id_statut_contrat = 1) -
        (SELECT COUNT(DISTINCT ae.id) 
         FROM annonce_emploi ae 
         WHERE ae.id_poste = Poste.id AND ae.statut = 'active')
    ) as total_places_libres
FROM Poste;

-- Vue pour les contrats avec détails employés
CREATE OR REPLACE VIEW v_contrats_details AS
SELECT 
    ce.id,
    ce.date_debut,
    ce.date_fin,
    ce.duree,
    ce.salaire,
    ce.id_poste,
    ce.id_employe,
    ce.id_statut_contrat,
    ce.id_type_contrat,
    e.nom as employe_nom,
    e.prenom as employe_prenom,
    e.email as employe_email,
    e.contact as employe_contact,
    e.cin as employe_cin,
    e.genre as employe_genre,
    p.label as poste_nom,
    c.libelle as categorie_nom,
    d.libelle as departement_nom,
    tc.label as type_contrat_nom,
    sc.label as statut_contrat_nom,
    -- Informations candidature si disponibles
    cand.id as candidature_id,
    cand.email as candidature_email
FROM contrat_employe ce
JOIN Employe e ON ce.id_employe = e.id
JOIN Poste p ON ce.id_poste = p.id
JOIN categorie c ON p.id_categorie = c.id
JOIN departement d ON p.id_departement = d.id
JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
LEFT JOIN candidature cand ON e.email = cand.email
ORDER BY ce.date_debut DESC;

-- Vue pour les candidats en attente d'évaluation
CREATE OR REPLACE VIEW v_candidats_en_evaluation AS
SELECT 
    c.id,
    c.nom,
    c.prenom,
    c.email,
    c.telephone,
    c.genre,
    c.statut,
    c.date_candidature,
    a.titre as annonce_titre,
    p.label as poste_nom,
    e.id as entretien_id,
    e.statut as entretien_statut,
    e.note_manager,
    e.note_rh,
    CASE 
        WHEN e.statut = 'termine' AND e.note_manager IS NOT NULL AND e.note_rh IS NULL THEN 1
        ELSE 0
    END as en_attente_evaluation_rh,
    CASE 
        WHEN e.statut = 'planifie' OR (e.statut = 'termine' AND e.note_manager IS NULL) THEN 1
        ELSE 0
    END as en_attente_entretien_manager
FROM candidature c
LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
LEFT JOIN Poste p ON a.id_poste = p.id
LEFT JOIN entretien e ON c.id = e.id_candidature
WHERE c.statut IN ('envoye_manager', 'entretien_planifie', 'entretien_termine')
  AND c.decision_finale IS NULL
ORDER BY c.date_candidature DESC;