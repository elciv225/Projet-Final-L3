<?php

namespace App\Dao;

use PDO;
use App\Models\CategorieUtilisateur;

class CategorieUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'categorie_utilisateur', CategorieUtilisateur::class, 'id');
    }
}