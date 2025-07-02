<?php

namespace App\Dao;

use App\Models\PersonnelAdministratif;
use PDO;

class PersonnelAdministratifDAO extends DAO {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'personnel_administratif', PersonnelAdministratif::class, 'utilisateur_id'); }

    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT u.id, u.nom, u.prenoms, u.email, f.libelle as fonction
            FROM utilisateur u
            JOIN personnel_administratif pa ON u.id = pa.utilisateur_id
            LEFT JOIN (
                SELECT utilisateur_id, MAX(date_occupation) as max_date FROM historique_fonction GROUP BY utilisateur_id
            ) hf_max ON u.id = hf_max.utilisateur_id
            LEFT JOIN historique_fonction hf ON hf_max.utilisateur_id = hf.utilisateur_id AND hf_max.max_date = hf.date_occupation
            LEFT JOIN fonction f ON hf.fonction_id = f.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }
}