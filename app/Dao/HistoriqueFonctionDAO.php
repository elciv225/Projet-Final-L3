<?php

namespace App\Dao;

use PDO;
use App\Models\HistoriqueFonction;

class HistoriqueFonctionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'historique_fonction', HistoriqueFonction::class, 'id');
    }
}