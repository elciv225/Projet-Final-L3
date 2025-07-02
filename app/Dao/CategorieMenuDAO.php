<?php

namespace App\Dao;

use App\Models\CategorieMenu;
use PDO;

class CategorieMenuDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'categorie_menu', CategorieMenu::class, 'id');
    }

    // Aucune méthode spécifique requise pour l'instant autre que celles héritées de DAO.php
    // recupererTous() et recupererParId() sont déjà disponibles.
}
