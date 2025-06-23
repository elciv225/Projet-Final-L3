<?php

namespace App\Dao;

use PDO;
use App\Models\NiveauEtude;

class NiveauEtudeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'niveau_etude', NiveauEtude::class, 'id');
    }
}