<?php

namespace App\Dao;

use App\Models\Grade;
use PDO;

class GradeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'grade',Grade::class);
    }
}