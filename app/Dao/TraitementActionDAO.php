<?php

namespace App\Dao;

use PDO;
use App\Models\TraitementAction;

class TraitementActionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'traitement_action', TraitementAction::class); // Clé primaire vide pour l'instant
    }

}