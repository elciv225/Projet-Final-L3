<?php

namespace App\Dao;
use App\Models\NiveauEtude;
use PDO;
class NiveauEtudeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'niveau_etude', NiveauEtude::class, 'id');
    }
}