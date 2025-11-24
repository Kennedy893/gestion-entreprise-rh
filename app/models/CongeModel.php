<?php

namespace app\models;

use Flight;
use PDO;
use Exception;
use DateTime;

class CongeModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function demandeConge($date_debut, $date_fin, $type_conge, $motif, $date_demande)
    {
        try {
            $this->db->beginTransaction();

            // Étape 1 : récupérer l'id_conge correspondant au type_conge
            $stmtConge = $this->db->prepare("
                SELECT id FROM conge WHERE libelle = :type_conge LIMIT 1
            ");
            $stmtConge->execute([':type_conge' => $type_conge]);
            $conge = $stmtConge->fetch(PDO::FETCH_ASSOC);

            if (!$conge) {
                throw new Exception("Aucun congé trouvé pour le type : " . $type_conge);
            }

            $id_conge = $conge['id'];

            // Étape 2 : insérer dans absence
            $stmtAbs = $this->db->prepare("
                INSERT INTO absence (motif, date_debut, date_fin, id_conge, date_demande, id_document)
                VALUES (:motif, :date_debut, :date_fin, :id_conge, :date_demande, 1)
            ");
            $stmtAbs->execute([
                ':motif' => $motif,
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin,
                ':id_conge' => $id_conge,
                ':date_demande' => $date_demande
            ]);

            $id_absence = $this->db->lastInsertId();

            // Étape 3 : insérer le statut de l'absence
            $stmtStatut = $this->db->prepare("
                INSERT INTO statut_abscence (date_statut, statut, id_absence)
                VALUES (NOW(), :statut, :id_absence)
            ");
            $stmtStatut->execute([
                ':statut' => 0, // 0 = en attente
                ':id_absence' => $id_absence
            ]);

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Demande de congé enregistrée avec succès.',
                'id_absence' => $id_absence
            ];

        } 
        catch (Exception $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function listeConge()
    {
        $stmt = $this->db->prepare("
            SELECT 
                nom,
                prenom,
                departement,
                poste,
                motif,
                date_debut,
                date_fin,
                jours_attente,
                fonction,
                id_absence,
                id_employe
            FROM v_absence_en_attente
            ORDER BY jours_attente DESC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listeCongeRH()
    {
        $stmt = $this->db->prepare("
            SELECT 
                nom,
                prenom,
                departement,
                poste,
                motif,
                date_debut,
                date_fin,
                jours_attente,
                fonction,
                id_absence,
                id_employe
            FROM v_absence_en_attente_rh
            ORDER BY jours_attente DESC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCongeByIdAbs($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                nom,
                prenom,
                departement,
                poste,
                motif,
                date_debut,
                date_fin,
                jours_attente,
                fonction,
                id_employe
            FROM v_absence_en_attente
            WHERE id_absence = $id
            ORDER BY jours_attente DESC
        ");
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function issetAutreConge($poste, $id_absence, $date_debut, $date_fin)
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM absence a
            JOIN statut_abscence sa ON sa.id_absence = a.id AND sa.statut = 21
            JOIN Document d ON d.id = a.id_document
            JOIN Employe e ON e.id = d.id_employe
            JOIN contrat_employe ce ON ce.id_employe = e.id AND ce.date_fin IS NULL
            JOIN Poste p ON p.id = ce.id_poste
            WHERE p.label = :poste
            AND a.id = :id_absence
            AND a.date_debut <= :date_fin
            AND a.date_fin >= :date_debut
            LIMIT 1
        ");

        $stmt->execute([
            ':poste' => $poste,
            ':id_absence' => $id_absence,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin
        ]);

        return $stmt->fetch() ? true : false;
    }

    public function hasSoldeSuffisant($id_employe, $date_debut, $date_fin)
    {
        //  Calcul du nombre de jours demandés
        $debut = new \DateTime($date_debut);
        $fin = new \DateTime($date_fin);
        $jours = $fin->diff($debut)->days + 1;

        // Année de la demande
        $annee_demande = (int)$debut->format("Y");

        // Appel à la fonction SQL
        $stmt = $this->db->prepare("SELECT get_solde_disponible(:id, :annee)");
        $stmt->execute([
            ':id' => $id_employe,
            ':annee' => $annee_demande
        ]);

        $solde = $stmt->fetchColumn();

        // Vérification
        return $solde >= $jours;
    }

    public function getAbsenceById($id)
    {
        $stmt = $this->db->prepare("SELECT date_demande, date_debut, date_fin FROM absence WHERE id = $id");

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatut($statut, $id_absence, $date)
    {
        $stmt = $this->db->prepare("INSERT INTO statut_abscence(date_statut, statut, id_absence) VALUES (?, ?, ?)");
        $stmt->execute([$date, $statut, $id_absence]);
    }


    public function updateSolde($annee, $id_employe, $jours_conge)
    {
        // 1. Récupérer tous les soldes de cet employé (ordre décroissant, année courante → anciennes)
        $sql = "SELECT * FROM solde_conge 
                WHERE id_employe = ? 
                ORDER BY annee DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_employe]);
        $soldes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Vérifier total restant
        $totalRestant = 0;
        foreach ($soldes as $s) {
            $totalRestant += $s["jours_restants"];
        }

        if ($totalRestant <= 0) {
            throw new Exception("Solde total épuisé");
        }

        $reste = $jours_conge;

        // 3. Consommer d'abord dans l'année demandée si elle existe
        foreach ($soldes as &$s) {
            if ($s["annee"] == $annee && $reste > 0) {
                $dispo = $s["jours_restants"];
                $used  = min($dispo, $reste);

                // Mise à jour
                $sqlUp = "UPDATE solde_conge 
                        SET jours_conso = jours_conso + ?
                        WHERE id = ?";
                $exec = $this->db->prepare($sqlUp);
                $exec->execute([$used, $s["id"]]);

                $reste -= $used;
            }
        }

        // 4. Si encore des jours à déduire → consommer dans les années précédentes
        if ($reste > 0) {
            foreach ($soldes as &$s) {

                // On saute l'année déjà traitée plus haut
                if ($s["annee"] == $annee) continue;

                if ($reste <= 0) break;

                $dispo = $s["jours_restants"];
                if ($dispo <= 0) continue;

                $used = min($dispo, $reste);

                $sqlUp = "UPDATE solde_conge 
                        SET jours_conso = jours_conso + ?
                        WHERE id = ?";
                $exec = $this->db->prepare($sqlUp);
                $exec->execute([$used, $s["id"]]);

                $reste -= $used;
            }
        }

        // 5. Si pas suffisant → on bloque la validation
        if ($reste > 0) {
            throw new Exception("Impossible de consommer le solde : insuffisant sur toutes les années");
        }
    }

    public function getSoldeCongeAll()
    {
        $stmt = $this->db->prepare("
            SELECT 
                sc.id_employe,
                e.nom,
                e.prenom,
                sc.annee,
                sc.jours_acquis,
                sc.jours_conso,
                sc.jours_restants
            FROM solde_conge sc
            JOIN employe e ON e.id = sc.id_employe
            ORDER BY e.nom ASC, sc.annee ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getSoldeCongeByEmploye($id_employe)
    {
        $stmt = $this->db->prepare("
            SELECT 
                annee,
                id_employe,
                jours_acquis,
                jours_conso,
                jours_restants
            FROM solde_conge
            WHERE id_employe = ?
            ORDER BY annee ASC
        ");

        $stmt->execute([$id_employe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllEmployes()
    {
        $stmt = $this->db->prepare("
            SELECT id, nom, prenom
            FROM employe
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnneeDebutContrat($id_employe)
    {
        $stmt = $this->db->prepare("
            SELECT EXTRACT(YEAR FROM MIN(date_debut)) AS annee_debut
            FROM contrat_employe
            WHERE id_employe = ?
        ");
        $stmt->execute([$id_employe]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['annee_debut'] : null;
    }

    public function getJrsPrisByTypeConge($id_employe, $annee, $type_conge)
    {
        $sql = "
            SELECT COALESCE(SUM( (a.date_fin - a.date_debut) + 1 ), 0) AS total_jours
            FROM absence a
            JOIN conge c ON c.id = a.id_conge
            JOIN Document d ON d.id = a.id_document
            JOIN Employe e ON e.id = d.id_employe

            -- joindre le dernier statut (dernier id) par absence
            JOIN (
                SELECT DISTINCT ON (id_absence) id_absence, statut
                FROM statut_abscence
                ORDER BY id_absence, id DESC
            ) last_sa ON last_sa.id_absence = a.id

            WHERE e.id = :id_employe
            AND c.libelle = :type_conge
            AND last_sa.statut = 11
            AND EXTRACT(YEAR FROM a.date_debut) = :annee
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_employe' => $id_employe,
            ':type_conge' => $type_conge,
            ':annee'      => (int)$annee
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($result['total_jours']) ? (int)$result['total_jours'] : 0;
    }

        


}