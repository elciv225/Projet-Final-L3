<?php

namespace App\Dao;

use PDO;
use App\Models\RapportEtudiant;

class RapportEtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'rapport_etudiant', RapportEtudiant::class, 'id');
    }
}