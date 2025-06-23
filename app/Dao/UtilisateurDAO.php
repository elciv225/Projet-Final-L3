<?php

namespace App\Dao;

use PDO;
use App\Models\Utilisateur;

class UtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'utilisateur', Utilisateur::class, 'id');
    }
}