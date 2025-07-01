<?php

namespace App\Dao;

use PDO;

class Specialite
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'specialite', Specialite::class, 'id');
    }
}