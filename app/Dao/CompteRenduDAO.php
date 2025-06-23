<?php

namespace App\Dao;

use PDO;
use App\Models\CompteRendu;

class CompteRenduDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'compte_rendu', CompteRendu::class, 'id');
    }
}