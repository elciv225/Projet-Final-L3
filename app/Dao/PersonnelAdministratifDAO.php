<?php

namespace App\Dao;

use App\Models\PersonnelAdministratif;
use PDO;

class PersonnelAdministratifDAO extends DAO {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'personnel_administratif', PersonnelAdministratif::class, 'utilisateur_id'); }

    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT
                u.id, u.nom, u.prenoms, u.email, u.date_naissance,
                hf.fonction_id, f.libelle as fonction, hf.date_occupation as date_fonction,
                'administratif' as type_personnel_id -- Ajout pour identifier le type
            FROM utilisateur u
            JOIN personnel_administratif pa ON u.id = pa.utilisateur_id
            -- Jointure pour la dernière fonction
            LEFT JOIN (
                SELECT utilisateur_id, fonction_id, date_occupation,
                       ROW_NUMBER() OVER(PARTITION BY utilisateur_id ORDER BY date_occupation DESC) as rn_fonction
                FROM historique_fonction
            ) hf ON u.id = hf.utilisateur_id AND hf.rn_fonction = 1
            LEFT JOIN fonction f ON hf.fonction_id = f.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Ajoute un nouveau personnel administratif et ses informations historiques via une procédure stockée.
     * @param array $params Les paramètres du personnel.
     * @return string|null L'ID du nouveau personnel ou null en cas d'échec.
     */
    public function ajouterViaProcedure(array $params): ?string
    {
        $sql = "CALL sp_ajouter_personnel_administratif(?, ?, ?, ?, ?, ?, ?, @p_utilisateur_id)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['mot_de_passe'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['fonction_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['date_fonction'], PDO::PARAM_STR);

        $stmt->execute();
        $stmt->closeCursor();

        $result = $this->pdo->query("SELECT @p_utilisateur_id as nouvel_id")->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nouvel_id'] : null;
    }

    /**
     * Modifie un personnel administratif et ses informations historiques via une procédure stockée.
     * @param array $params Les paramètres du personnel à modifier.
     * @return bool True si succès.
     */
    public function modifierViaProcedure(array $params): bool
    {
        $sql = "CALL sp_modifier_personnel_administratif(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['id_personnel'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['fonction_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['date_fonction'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Supprime un personnel administratif via une procédure stockée.
     * @param string $id ID du personnel à supprimer.
     * @return bool True si succès.
     */
    public function supprimerViaProcedure(string $id): bool
    {
        $sql = "CALL sp_supprimer_personnel(?)"; // Supposant une procédure générique
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);
        return $stmt->execute();
    }
}