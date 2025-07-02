<?php

namespace App\Dao;

use PDO;
use App\Models\Utilisateur;

class UtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'utilisateur', Utilisateur::class, 'id');
    }

    /**
     * Récupère tous les utilisateurs avec les libellés de leur type et groupe.
     * @return array
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "
            SELECT u.id, u.nom, u.prenoms, u.email, tu.libelle as type_user, gu.libelle as groupe
            FROM utilisateur u
            LEFT JOIN type_utilisateur tu ON u.type_utilisateur_id = tu.id
            LEFT JOIN groupe_utilisateur gu ON u.groupe_utilisateur_id = gu.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Crée un compte enseignant en utilisant la procédure stockée.
     * @param array $params
     * @return string|null
     */
    public function creerEnseignantViaProcedure(array $params): ?string
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
     * Crée un compte de personnel administratif en utilisant la procédure stockée.
     * @param array $params
     * @return string|null
     */
    public function creerPersonnelAdminViaProcedure(array $params): ?string
    {
        $sql = "CALL sp_creer_personnel_admin(?, ?, ?, ?, ?, @p_user_id)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['mot_de_passe'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);

        $stmt->execute();
        $stmt->closeCursor();

        $result = $this->pdo->query("SELECT @p_user_id as userId")->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['userId'] : null;
    }

    /**
     * Supprime un ou plusieurs membres du personnel en appelant la procédure stockée.
     * @param array|string $ids
     * @return int
     */
    public function supprimerPersonnel(array|string $ids): int
    {
        if (is_string($ids)) {
            $ids = [$ids];
        }

        if (empty($ids)) {
            return 0;
        }

        try {
            $stmt = $this->pdo->prepare("CALL sp_supprimer_personnel(?)");
            $deletedCount = 0;

            foreach ($ids as $id) {
                if (!empty($id)) {
                    $stmt->execute([$id]);
                    $deletedCount++;
                }
            }
            return $deletedCount;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
