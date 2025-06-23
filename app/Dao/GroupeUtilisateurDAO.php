<?php

namespace App\Dao;

use PDO;
use App\Models\GroupeUtilisateur;

class GroupeUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'groupe_utilisateur', GroupeUtilisateur::class, 'id');
    }
}