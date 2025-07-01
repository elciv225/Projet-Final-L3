<?php

namespace App\Dao;

use PDO;

class HistoriqueSpecialite extends DAO
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'historique_specialite', HistoriqueGrade::class, 'id');
    }
}