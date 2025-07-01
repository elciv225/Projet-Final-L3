<?php

namespace App\Dao;

use PDO;
use App\Models\AutorisationAction;

class AutorisationActionDAO extends DAO
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'autorisation_action', AutorisationAction::class, '');
    }
}