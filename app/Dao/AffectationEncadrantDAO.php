<?php

namespace App\Dao;

use PDO;
use App\Models\AffectationEncadrant;

class AffectationEncadrantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'affectation_encadrant', AffectationEncadrant::class, 'id');
    }
}