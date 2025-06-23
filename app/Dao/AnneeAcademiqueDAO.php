<?php

namespace App\Dao;

use PDO;
use App\Models\AnneeAcademique;

class AnneeAcademiqueDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'annee_academique', AnneeAcademique::class, 'id');
    }
}