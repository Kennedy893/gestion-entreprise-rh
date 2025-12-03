-- === Nettoyage sécurisé (supprime les objets précédents) ===
SET FOREIGN_KEY_CHECKS = 0;

DROP TRIGGER IF EXISTS after_questionnaire_submit;
DROP VIEW IF EXISTS v_formulaires_formation_complets;
DROP TABLE IF EXISTS Questionnaire_Formation_Candidat;

SET FOREIGN_KEY_CHECKS = 1;

-- === Créer la table Questionnaire_Formation_Candidat ===
-- Utilise BIGINT UNSIGNED pour correspondre à SERIAL (qui est souvent BIGINT UNSIGNED)
CREATE TABLE IF NOT EXISTS Questionnaire_Formation_Candidat (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_formulaire_formation BIGINT UNSIGNED NOT NULL,
    id_candidature BIGINT UNSIGNED NOT NULL,

    -- Questions
    q1_contenu_attentes ENUM('oui','partiellement','non') NOT NULL,
    q2_qualite_pedagogique TINYINT NOT NULL CHECK (q2_qualite_pedagogique BETWEEN 1 AND 5),
    q3_objectifs_atteints ENUM('oui','partiellement','non') NOT NULL,
    q4_ameliorations TEXT,
    q5_formation_complementaire ENUM('oui','non') NOT NULL,

    -- Métadonnées
    date_soumission TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('soumis','evalue') DEFAULT 'soumis',

    -- Contraintes FK
    CONSTRAINT fk_qfc_ff FOREIGN KEY (id_formulaire_formation) REFERENCES Formulaire_Formation(id) ON DELETE CASCADE,
    CONSTRAINT fk_qfc_cand FOREIGN KEY (id_candidature) REFERENCES candidature(id) ON DELETE CASCADE,
    UNIQUE KEY unique_questionnaire (id_formulaire_formation)
) ENGINE=InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- === S'assurer que Formulaire_Formation contient les colonnes de tracking (ajoute si manquant) ===
ALTER TABLE Formulaire_Formation
    ADD COLUMN IF NOT EXISTS questionnaire_complete BOOLEAN DEFAULT FALSE AFTER date_submission,
    ADD COLUMN IF NOT EXISTS date_questionnaire_complete TIMESTAMP NULL AFTER questionnaire_complete;

-- === Recréer la vue (utilisant LEFT JOIN sur la table nouvellement créée) ===
CREATE OR REPLACE VIEW v_formulaires_formation_complets AS
SELECT 
    ff.*,
    cmf.id_candidature,
    cmf.statut AS statut_mise_formation,
    c.nom AS candidat_nom,
    c.prenom AS candidat_prenom,
    c.email AS candidat_email,
    qfc.id AS questionnaire_id,
    qfc.statut AS questionnaire_statut,
    qfc.date_soumission AS questionnaire_date_soumission
FROM Formulaire_Formation ff
JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
JOIN candidature c ON cmf.id_candidature = c.id
LEFT JOIN Questionnaire_Formation_Candidat qfc ON ff.id = qfc.id_formulaire_formation
ORDER BY ff.date_submission DESC;

-- === Trigger : marquer formulaire complet après insertion d'un questionnaire ===
DELIMITER $$
CREATE TRIGGER after_questionnaire_submit
AFTER INSERT ON Questionnaire_Formation_Candidat
FOR EACH ROW
BEGIN
    -- marquer le formulaire comme complété
    UPDATE Formulaire_Formation
    SET questionnaire_complete = TRUE,
        date_questionnaire_complete = NOW()
    WHERE id = NEW.id_formulaire_formation;
END$$
DELIMITER ;

-- === Indexes pour performance ===
CREATE INDEX IF NOT EXISTS idx_questionnaire_formulaire ON Questionnaire_Formation_Candidat (id_formulaire_formation);
CREATE INDEX IF NOT EXISTS idx_questionnaire_candidature ON Questionnaire_Formation_Candidat (id_candidature);
CREATE INDEX IF NOT EXISTS idx_ff_questionnaire ON Formulaire_Formation (questionnaire_complete);
