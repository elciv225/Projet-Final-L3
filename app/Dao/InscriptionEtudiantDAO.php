<?php

namespace App\Dao;

use PDO;
use App\Models\InscriptionEtudiant;

class InscriptionEtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'inscription_etudiant', InscriptionEtudiant::class, 'id');
    }
}