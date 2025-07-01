<?php

namespace App\Dao;

use PDO;
use App\Models\Evaluation;

class EvaluationDAO extends DAO
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'evaluation', Evaluation::class, '');
    }
}