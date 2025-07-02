<?php

namespace App\Dao;

use App\Models\HistoriqueSpecialite;
use PDO;

class HistoriqueSpecialiteDAO extends DAO
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'historique_specialite', HistoriqueSpecialite::class, 'id');
    }
}