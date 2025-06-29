<?php

namespace App\Dao;

use PDO;
use App\Models\Enseignant;

class EnseignantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'enseignant', Enseignant::class, 'utilisateur_id');
    }
}