<?php

namespace App\Dao;

use PDO;
use App\Models\NiveauApprobation;

class NiveauApprobationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'niveau_approbation', NiveauApprobation::class, 'id');
    }
}