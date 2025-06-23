<?php

namespace App\Dao;

use PDO;
use App\Models\Entreprise;

class EntrepriseDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'entreprise', Entreprise::class, 'id');
    }
}