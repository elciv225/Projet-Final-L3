<?php

namespace App\Dao;

use PDO;
use App\Models\StatutJury;

class StatutJuryDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'statut_jury', StatutJury::class, 'id');
    }
}