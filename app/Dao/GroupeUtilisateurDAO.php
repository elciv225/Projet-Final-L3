<?php

namespace App\Dao;

use App\Models\GroupeUtilisateur;
use PDO;

class GroupeUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'groupe_utilisateur', GroupeUtilisateur::class, 'id');
    }
}