<?php

namespace app\models;

use PDO;

class AnnonceModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createAnnonce($data) {
        $sql = "INSERT INTO annonce_emploi 
                (titre, description, competences_requises, diplomes_requis, experience_min, 
                niveau_responsabilite, autonomie_requise, date_publication, date_limite, 
                statut, id_poste, id_type_contrat, id_manager) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['competences_requises'],
            $data['diplomes_requis'],
            $data['experience_min'],
            $data['niveau_responsabilite'],
            $data['autonomie_requise'],
            $data['date_publication'],
            $data['date_limite'],
            'active',
            $data['id_poste'],
            $data['id_type_contrat'],
            $data['id_manager']
        ]);
    }

    public function getAllAnnonces() {
        $sql = "SELECT a.*, p.label as poste_nom, tc.label as type_contrat_nom, 
                e.nom as manager_nom, e.prenom as manager_prenom,
                c.libelle as categorie_nom, d.libelle as departement_nom
                FROM annonce_emploi a
                JOIN Poste p ON a.id_poste = p.id
                JOIN Type_Contrat tc ON a.id_type_contrat = tc.id
                JOIN Employe e ON a.id_manager = e.id
                JOIN categorie c ON p.id_categorie = c.id
                JOIN departement d ON p.id_departement = d.id
                ORDER BY a.date_publication DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnonceById($id) {
        $sql = "SELECT a.*, p.label as poste_nom, tc.label as type_contrat_nom,
                c.libelle as categorie_nom, d.libelle as departement_nom,
                cp.duree_travail, cp.entree, cp.sortie
                FROM annonce_emploi a
                JOIN Poste p ON a.id_poste = p.id
                JOIN Type_Contrat tc ON a.id_type_contrat = tc.id
                JOIN categorie c ON p.id_categorie = c.id
                JOIN departement d ON p.id_departement = d.id
                LEFT JOIN config_poste cp ON p.id = cp.id_poste
                WHERE a.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatut($id, $statut) {
        $sql = "UPDATE annonce_emploi SET statut = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$statut, $id]);
    }

    public function deleteAnnonce($id) {
        $sql = "DELETE FROM annonce_emploi WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}