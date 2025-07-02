<?php

namespace App\Dao;

use PDO;
use App\Models\Enseignant;

class EnseignantDAO extends DAO {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'enseignant', Enseignant::class, 'utilisateur_id'); }

    public function recupererTousAvecDetails(): array {
        $sql = "
            SELECT u.id, u.nom, u.prenoms, u.email, g.libelle as grade, f.libelle as fonction
            FROM utilisateur u
            JOIN enseignant en ON u.id = en.utilisateur_id
            LEFT JOIN (
                SELECT utilisateur_id, MAX(date_grade) as max_date FROM historique_grade GROUP BY utilisateur_id
            ) hg_max ON u.id = hg_max.utilisateur_id
            LEFT JOIN historique_grade hg ON hg_max.utilisateur_id = hg.utilisateur_id AND hg_max.max_date = hg.date_grade
            LEFT JOIN grade g ON hg.grade_id = g.id
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