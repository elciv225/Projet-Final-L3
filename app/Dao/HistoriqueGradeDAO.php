<?php

namespace App\Dao;

use PDO;
use App\Models\HistoriqueGrade;

class HistoriqueGradeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'historique_grade', HistoriqueGrade::class, 'id');
    }
}