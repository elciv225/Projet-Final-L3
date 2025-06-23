<?php

namespace App\Dao;

use PDO;
use App\Models\Audit;

class AuditDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'audit', Audit::class, 'id');
    }
}