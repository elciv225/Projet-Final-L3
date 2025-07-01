<?php

namespace App\Dao;

use PDO;
use App\Models\TraitementAction;


class TraitementActionDAO extends DAO
{
    public function __construct(PDO $pdo, TraitementDAO $traitementDAO, ActionDAO $actionDAO)
    {
        parent::__construct($pdo, 'traitement_action', TraitementAction::class, '');
    }
}
