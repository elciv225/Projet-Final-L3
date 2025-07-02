<?php

namespace App\Dao;

use App\Models\Action; // Assurez-vous que le modèle Action existe
use PDO;

class ActionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'action', Action::class, 'id');
    }
}
