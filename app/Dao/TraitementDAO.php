<?php

namespace App\Dao;

use PDO;
use App\Models\Traitement;

class TraitementDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'traitement', Traitement::class, 'id');
    }
}