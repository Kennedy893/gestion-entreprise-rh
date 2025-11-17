<?php

namespace app\models;

use Flight;
use PDO;
use Exception;

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
                INSERT INTO absence (motif, date_debut, date_fin, id_conge, date_demande)
                VALUES (:motif, :date_debut, :date_fin, :id_conge)
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

    public function updateSolde($annee, $id_employe, $jours_conso)
    {
        $stmt = $this->db->prepare("UPDATE solde_conge SET jours_conso = jours_conso - ? WHERE id_employe = ? AND annee = ?");
        $stmt->execute([$jours_conso, $id_employe, $annee]);
    }


}