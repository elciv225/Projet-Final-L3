<?php

namespace App\Dao;

use App\Models\StatutJury; // Assurez-vous que le modèle StatutJury existe
use PDO;

class StatutJuryDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'statut_jury', StatutJury::class, 'id');
    }
}
