<?php
// Fichier : app/controllers/ValidationController.php
namespace app\controllers;

use Flight;

class ValidationController {

    /**
     * Endpoint JSON - renvoie tous les candidats avec leur statut calculé
     */
    public function candidatsRecus() {
        $db = Flight::db();

        $sql = "
            SELECT
                c.id,
                c.nom,
                c.prenom,
                c.email,
                c.telephone,
                c.genre,
                c.date_candidature,
                c.statut,
                c.decision_finale,
                c.date_decision,
                c.type_contrat_accorde,
                c.date_debut_travail,
                a.titre AS annonce_titre,
                a.id AS annonce_id,
                p.label AS poste,
                e.id AS entretien_id,
                e.decision AS entretien_decision,
                e.date_decision AS entretien_date_decision,
                e.note_finale AS entretien_note_finale,
                
                -- Calcul du statut final selon priorité stricte
                CASE
                    -- Priorité 1: Décision d'entretien (la plus fiable)
                    WHEN e.decision IS NOT NULL AND LOWER(TRIM(e.decision)) IN ('accepte', 'accepté', 'accepted', 'accepter', 'reçu', 'recu') 
                        THEN 'recu'
                    WHEN e.decision IS NOT NULL AND LOWER(TRIM(e.decision)) IN ('rejete', 'rejeté', 'rejet', 'rejected', 'refuse', 'refusé', 'decline') 
                        THEN 'rejete'
                    
                    -- Priorité 2: Décision finale candidature
                    WHEN c.decision_finale IS NOT NULL AND LOWER(TRIM(c.decision_finale)) IN ('accepte', 'accepté', 'accepted', 'accepter', 'reçu', 'recu') 
                        THEN 'recu'
                    WHEN c.decision_finale IS NOT NULL AND LOWER(TRIM(c.decision_finale)) IN ('rejete', 'rejeté', 'rejet', 'rejected', 'refuse', 'refusé', 'decline') 
                        THEN 'rejete'
                    
                    -- Priorité 3: Combinaison statut + date_decision
                    WHEN c.date_decision IS NOT NULL 
                         AND LOWER(TRIM(c.statut)) NOT IN ('en_attente', 'nouveau', 'en_cours', 'entretien_planifie')
                         AND LOWER(TRIM(c.statut)) IN ('accepte', 'accepté', 'valide', 'actif', 'entretien_termine')
                        THEN 'recu'
                    WHEN LOWER(TRIM(c.statut)) IN ('rejete', 'rejeté', 'refuse', 'refusé', 'decline', 'annule') 
                        THEN 'rejete'
                    
                    -- Par défaut: en attente
                    ELSE 'attente'
                END AS status_computed
                
            FROM candidature c
            LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
            LEFT JOIN Poste p ON a.id_poste = p.id
            LEFT JOIN entretien e ON c.id = e.id_candidature
            ORDER BY 
                COALESCE(e.date_decision, c.date_decision, c.date_candidature) DESC
            LIMIT 500
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Formater les dates pour JSON
        $rows = array_map(function($r) {
            if (!empty($r['date_decision'])) {
                $r['date_decision'] = date('Y-m-d', strtotime($r['date_decision']));
            }
            if (!empty($r['entretien_date_decision'])) {
                $r['entretien_date_decision'] = date('Y-m-d', strtotime($r['entretien_date_decision']));
            }
            if (!empty($r['date_candidature'])) {
                $r['date_candidature'] = date('Y-m-d', strtotime($r['date_candidature']));
            }
            return $r;
        }, $rows);

        Flight::json($rows);
    }

    /**
     * Page RH : résultats candidats avec filtres
     */
    public function resultatsCandidatsPage() {
        $db = Flight::db();

        // Récupérer le filtre optionnel
        $filter = Flight::request()->query->filter ?? 'all';

        $sql = "
            SELECT
                c.id,
                c.nom,
                c.prenom,
                c.email,
                c.telephone,
                c.genre,
                c.statut,
                c.decision_finale,
                c.date_decision,
                a.titre AS annonce_titre,
                e.decision AS entretien_decision,
                e.date_decision AS entretien_date_decision,
                
                -- Calcul du statut final
                CASE
                    WHEN LOWER(TRIM(COALESCE(e.decision, ''))) IN ('accepte', 'accepté', 'accepted', 'accepter', 'reçu', 'recu') 
                        THEN 'recu'
                    WHEN LOWER(TRIM(COALESCE(e.decision, ''))) IN ('rejete', 'rejeté', 'rejet', 'rejected', 'refuse', 'refusé', 'decline') 
                        THEN 'rejete'
                    WHEN LOWER(TRIM(COALESCE(c.decision_finale, ''))) IN ('accepte', 'accepté', 'accepted', 'accepter', 'reçu', 'recu') 
                        THEN 'recu'
                    WHEN LOWER(TRIM(COALESCE(c.decision_finale, ''))) IN ('rejete', 'rejeté', 'rejet', 'rejected', 'refuse', 'refusé', 'decline') 
                        THEN 'rejete'
                    WHEN LOWER(TRIM(COALESCE(c.statut, ''))) IN ('accepte', 'accepté', 'accepted', 'valide', 'actif') 
                         AND c.date_decision IS NOT NULL 
                        THEN 'recu'
                    WHEN LOWER(TRIM(COALESCE(c.statut, ''))) IN ('rejete', 'rejeté', 'refuse', 'refusé', 'decline') 
                        THEN 'rejete'
                    ELSE 'attente'
                END AS status_computed
                
            FROM candidature c
            LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
            LEFT JOIN entretien e ON c.id = e.id_candidature
        ";

        // Ajouter filtre si nécessaire
        if ($filter === 'recu') {
            $sql .= " HAVING status_computed = 'recu'";
        } elseif ($filter === 'rejete') {
            $sql .= " HAVING status_computed = 'rejete'";
        } elseif ($filter === 'attente') {
            $sql .= " HAVING status_computed = 'attente'";
        }

        $sql .= " ORDER BY COALESCE(e.date_decision, c.date_decision, c.date_candidature) DESC LIMIT 1000";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $candidats = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        Flight::render('rh/candidats_resultats', [
            'candidats' => $candidats,
            'page_title' => 'Résultats candidats',
            'current_filter' => $filter
        ]);
    }
}