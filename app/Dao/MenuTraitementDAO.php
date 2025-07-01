<?php

namespace App\Dao;

use PDO;
use App\Models\MenuTraitement;

class MenuTraitementDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'menu_traitement', MenuTraitement::class, '');
    }
}