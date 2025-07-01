<?php

namespace App\Dao;

use PDO;
use App\Models\PersonnelAdministratif;

class PersonnelAdministratifDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'personnel_administratif', PersonnelAdministratif::class, 'utilisateur_id');
    }
}