<?php

namespace App\Dao;

use PDO;
use App\Models\Fonction;

class FonctionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'fonction', Fonction::class, 'id');
    }
}