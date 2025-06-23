<?php

namespace App\Dao;

use PDO;
use App\Models\RemiseCompteRendu;

class RemiseCompteRenduDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'remise_compte_rendu', RemiseCompteRendu::class, 'id');
    }
}