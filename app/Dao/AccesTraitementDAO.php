<?php

namespace App\Dao;

use PDO;
use App\Models\AccesTraitement;

class AccesTraitementDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'acces_traitement', AccesTraitement::class, 'id');
    }
}