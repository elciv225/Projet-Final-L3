<?php

namespace App\Dao;

use PDO;
use App\Models\NiveauAccesDonnees;

class NiveauAccesDonneesDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'niveau_acces_donnees', NiveauAccesDonnees::class, 'id');
    }
}