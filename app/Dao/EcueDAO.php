<?php

namespace App\Dao;

use PDO;
use App\Models\Ecue;

class EcueDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'ecue', Ecue::class, 'id');
    }
}