<?php

namespace App\Dao;

use App\Models\Fonction;
use PDO;
class FonctionDAO extends DAO
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'fonction',Fonction::class);
    }

}