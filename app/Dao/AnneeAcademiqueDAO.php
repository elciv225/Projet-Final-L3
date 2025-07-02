<?php

namespace App\Dao;

use App\Models\AnneeAcademique;
use PDO;
class AnneeAcademiqueDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'annee_academique', AnneeAcademique::class, 'id');
    }
}