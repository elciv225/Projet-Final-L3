<?php

namespace App\Dao;

use PDO;
use App\Models\Ue;

class UeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'ue', Ue::class, 'id');
    }
}