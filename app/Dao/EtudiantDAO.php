<?php

namespace App\Dao;

use PDO;
use App\Models\Etudiant;

class EtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'etudiant', Etudiant::class, 'id');
    }
}