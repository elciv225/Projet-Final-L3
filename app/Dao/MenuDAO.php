<?php

namespace App\Dao;

use PDO;
use App\Models\Menu;

class MenuDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'menu', Menu::class, 'id');
    }

}