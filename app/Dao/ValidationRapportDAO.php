<?php

namespace App\Dao;

use PDO;
use App\Models\ValidationRapport;

class ValidationRapportDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'validation_rapport', ValidationRapport::class, 'id');
    }
}