<?php

namespace App\Dao;

use App\Models\Traitement; // Assurez-vous que le modèle Traitement existe
use PDO;

class TraitementDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'traitement', Traitement::class, 'id');
    }
}
