<?php

namespace App\Dao;

use App\Models\TypeUtilisateur;
use PDO;

class TypeUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'type_utilisateur', TypeUtilisateur::class);
    }

}