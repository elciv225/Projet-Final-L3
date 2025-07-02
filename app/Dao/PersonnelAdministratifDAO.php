<?php

namespace App\Dao;

use App\Models\PersonnelAdministratif;
use PDO;

class PersonnelAdministratifDAO extends DAO {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'personnel_administratif', PersonnelAdministratif::class, 'utilisateur_id');
    }

    /**
     * Récupère tous les membres du personnel administratif avec leurs détails.
     * @return array
     */
    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT u.id, u.nom, u.prenoms, u.email, u.date_naissance
            FROM utilisateur u
            JOIN personnel_administratif pa ON u.id = pa.utilisateur_id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Modifie un membre du personnel administratif via la procédure stockée.
     * @param array $params
     * @return bool
     */
    public function modifierViaProcedure(array $params): bool
    {
        $sql = "CALL sp_modifier_personnel_admin(?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['id_utilisateur'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}
