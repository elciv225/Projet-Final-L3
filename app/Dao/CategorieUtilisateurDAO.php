<?php

namespace App\Dao;

use App\Models\CategorieUtilisateur; // Assurez-vous que le modèle CategorieUtilisateur existe
use PDO;

class CategorieUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'categorie_utilisateur', CategorieUtilisateur::class, 'id');
    }
}
