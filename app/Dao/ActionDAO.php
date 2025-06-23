<?php

namespace App\Dao;

use PDO;
use App\Models\Action;

class ActionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'action', Action::class, 'id');
    }
}