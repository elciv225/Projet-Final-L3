<?php

namespace App\Dao;

use PDO;
use App\Models\ApprobationRapport;

class ApprobationRapportDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'approbation_rapport', ApprobationRapport::class, 'id');
    }
}