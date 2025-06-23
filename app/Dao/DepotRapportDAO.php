<?php

namespace App\Dao;

use PDO;
use App\Models\DepotRapport;

class DepotRapportDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'depot_rapport', DepotRapport::class, 'id');
    }
}