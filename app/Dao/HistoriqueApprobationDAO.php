<?php

namespace App\Dao;

use PDO;
use App\Models\HistoriqueApprobation;

class HistoriqueApprobationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'historique_approbation', HistoriqueApprobation::class, 'id');
    }
}