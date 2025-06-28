<?php

namespace App\Dao;

use PDO;
use App\Models\MenuTraitement;

class MenuTraitementDAO extends DAO
{
    private MenuDAO $menuDAO;
    private TraitementDAO $traitementDAO;

    public function __construct(PDO $pdo, MenuDAO $menuDAO, TraitementDAO $traitementDAO)
    {
        // Clé primaire composite, donc la clé primaire simple de la classe DAO parente n'est pas directement applicable.
        parent::__construct($pdo, 'menu_traitement', MenuTraitement::class, '');
        $this->menuDAO = $menuDAO;
        $this->traitementDAO = $traitementDAO;
    }
}