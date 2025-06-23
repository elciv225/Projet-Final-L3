<?php

namespace App\Dao;

use PDO;
use App\Models\TypeUtilisateur;

class TypeUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'type_utilisateur', TypeUtilisateur::class, 'id');
    }
}