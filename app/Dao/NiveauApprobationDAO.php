<?php

namespace App\Dao;

use App\Models\NiveauApprobation; // Assurez-vous que le modèle NiveauApprobation existe
use PDO;

class NiveauApprobationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'niveau_approbation', NiveauApprobation::class, 'id');
    }
}
