<?php

namespace App\Dao;

use App\Models\Enseignant;
use PDO;

class EnseignantDAO extends DAO {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'enseignant', Enseignant::class, 'utilisateur_id');
    }

    /**
     * Récupère tous les enseignants avec les détails de leur dernier grade, spécialité et fonction.
     * @return array
     */
    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT 
                u.id, u.nom, u.prenoms, u.email, u.date_naissance,
                hg.grade_id, g.libelle as grade,
                hs.specialite_id, s.libelle as specialite,
                hf.fonction_id, f.libelle as fonction
            FROM utilisateur u
            JOIN enseignant e ON u.id = e.utilisateur_id
            -- Jointure pour le dernier grade
            LEFT JOIN (
                SELECT utilisateur_id, MAX(date_grade) as max_date 
                FROM historique_grade 
                GROUP BY utilisateur_id
            ) hg_max ON u.id = hg_max.utilisateur_id
            LEFT JOIN historique_grade hg ON hg.utilisateur_id = hg_max.utilisateur_id AND hg.date_grade = hg_max.max_date
            LEFT JOIN grade g ON hg.grade_id = g.id
            -- Jointure pour la dernière spécialité (CORRIGÉ)
            LEFT JOIN (
                SELECT utilisateur_id, MAX(date_occupation) as max_date 
                FROM historique_specialite 
                GROUP BY utilisateur_id
            ) hs_max ON u.id = hs_max.utilisateur_id
            LEFT JOIN historique_specialite hs ON hs.utilisateur_id = hs_max.utilisateur_id AND hs.date_occupation = hs_max.max_date
            LEFT JOIN specialite s ON hs.specialite_id = s.id
            -- Jointure pour la dernière fonction
            LEFT JOIN (
                SELECT utilisateur_id, MAX(date_occupation) as max_date 
                FROM historique_fonction 
                GROUP BY utilisateur_id
            ) hf_max ON u.id = hf_max.utilisateur_id
            LEFT JOIN historique_fonction hf ON hf_max.utilisateur_id = hf.utilisateur_id AND hf_max.max_date = hf.date_occupation
            LEFT JOIN fonction f ON hf.fonction_id = f.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Crée un compte enseignant en utilisant la procédure stockée.
     * @param array $params
     * @return string|null
     */
    public function creerViaProcedure(array $params): ?string
    {
        $sql = "CALL sp_creer_enseignant(?, ?, ?, ?, ?, ?, ?, ?, @p_user_id)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['mot_de_passe'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['grade_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['specialite_id'], PDO::PARAM_STR);
        $stmt->bindParam(8, $params['fonction_id'], PDO::PARAM_STR);

        $stmt->execute();
        $stmt->closeCursor();

        $result = $this->pdo->query("SELECT @p_user_id as userId")->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['userId'] : null;
    }

    /**
     * Modifie un enseignant en utilisant la procédure stockée.
     * @param array $params
     * @return bool
     */
    public function modifierViaProcedure(array $params): bool
    {
        $sql = "CALL sp_modifier_enseignant(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['id_utilisateur'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['grade_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['specialite_id'], PDO::PARAM_STR);
        $stmt->bindParam(8, $params['fonction_id'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}
