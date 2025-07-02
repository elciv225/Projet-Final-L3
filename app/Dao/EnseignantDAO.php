<?php

namespace App\Dao;

use PDO;
use App\Models\Enseignant;

class EnseignantDAO extends DAO {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'enseignant', Enseignant::class, 'utilisateur_id'); }

    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT
                u.id, u.nom, u.prenoms, u.email, u.date_naissance,
                hg.grade_id, g.libelle as grade, hg.date_grade,
                hf.fonction_id, f.libelle as fonction, hf.date_occupation as date_fonction,
                hs.specialite_id, s.libelle as specialite, hs.date_specialite,
                'enseignant' as type_personnel_id -- Ajout pour identifier le type
            FROM utilisateur u
            JOIN enseignant en ON u.id = en.utilisateur_id
            -- Jointure pour le dernier grade
            LEFT JOIN (
                SELECT utilisateur_id, grade_id, date_grade,
                       ROW_NUMBER() OVER(PARTITION BY utilisateur_id ORDER BY date_grade DESC) as rn_grade
                FROM historique_grade
            ) hg ON u.id = hg.utilisateur_id AND hg.rn_grade = 1
            LEFT JOIN grade g ON hg.grade_id = g.id
            -- Jointure pour la dernière fonction
            LEFT JOIN (
                SELECT utilisateur_id, fonction_id, date_occupation,
                       ROW_NUMBER() OVER(PARTITION BY utilisateur_id ORDER BY date_occupation DESC) as rn_fonction
                FROM historique_fonction
            ) hf ON u.id = hf.utilisateur_id AND hf.rn_fonction = 1
            LEFT JOIN fonction f ON hf.fonction_id = f.id
            -- Jointure pour la dernière spécialité
            LEFT JOIN (
                SELECT utilisateur_id, specialite_id, date_specialite,
                       ROW_NUMBER() OVER(PARTITION BY utilisateur_id ORDER BY date_specialite DESC) as rn_specialite
                FROM historique_specialite
            ) hs ON u.id = hs.utilisateur_id AND hs.rn_specialite = 1
            LEFT JOIN specialite s ON hs.specialite_id = s.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Ajoute un nouvel enseignant et ses informations historiques via une procédure stockée.
     * @param array $params Les paramètres de l'enseignant.
     * @return string|null L'ID du nouvel enseignant ou null en cas d'échec.
     */
    public function ajouterViaProcedure(array $params): ?string
    {
        $sql = "CALL sp_ajouter_enseignant(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_utilisateur_id)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['mot_de_passe'], PDO::PARAM_STR); // Assurez-vous de hasher avant si besoin
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['grade_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['date_grade'], PDO::PARAM_STR);
        $stmt->bindParam(8, $params['fonction_id'], PDO::PARAM_STR);
        $stmt->bindParam(9, $params['date_fonction'], PDO::PARAM_STR);
        $stmt->bindParam(10, $params['specialite_id'], PDO::PARAM_STR);
        $stmt->bindParam(11, $params['date_specialite'], PDO::PARAM_STR);

        $stmt->execute();
        $stmt->closeCursor();

        $result = $this->pdo->query("SELECT @p_utilisateur_id as nouvel_id")->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nouvel_id'] : null;
    }

    /**
     * Modifie un enseignant et ses informations historiques via une procédure stockée.
     * @param array $params Les paramètres de l'enseignant à modifier.
     * @return bool True si succès.
     */
    public function modifierViaProcedure(array $params): bool
    {
        $sql = "CALL sp_modifier_enseignant(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['id_personnel'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['grade_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['date_grade'], PDO::PARAM_STR);
        $stmt->bindParam(8, $params['fonction_id'], PDO::PARAM_STR);
        $stmt->bindParam(9, $params['date_fonction'], PDO::PARAM_STR);
        $stmt->bindParam(10, $params['specialite_id'], PDO::PARAM_STR);
        $stmt->bindParam(11, $params['date_specialite'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Supprime un enseignant via une procédure stockée.
     * @param string $id ID de l'enseignant à supprimer.
     * @return bool True si succès.
     */
    public function supprimerViaProcedure(string $id): bool
    {
        $sql = "CALL sp_supprimer_personnel(?)"; // Supposant une procédure générique
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);
        // La procédure doit gérer la suppression en cascade ou retourner une erreur si des dépendances existent.
        // Le retour true ici signifie que la procédure s'est exécutée sans erreur PDO.
        // La procédure elle-même pourrait avoir une logique pour retourner si la suppression a eu lieu.
        return $stmt->execute();
    }
}