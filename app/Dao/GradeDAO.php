<?php

namespace App\Dao;

use PDO;
use App\Models\Grade;

class GradeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'grade', Grade::class, 'id');
    }
}