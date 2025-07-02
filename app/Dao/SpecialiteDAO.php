<?php

namespace App\Dao;

use App\Models\Specialite;
use PDO;

class SpecialiteDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'specialite', Specialite::class, 'id');
    }

    // Aucune méthode spécifique requise pour l'instant autre que celles héritées de DAO.php
}
