<?php

namespace App\Dao;

use PDO;
use App\Models\AuditAction;

class AuditActionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'audit_action', AuditAction::class, 'id');
    }

}
