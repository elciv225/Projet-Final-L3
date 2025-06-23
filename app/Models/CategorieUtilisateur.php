<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\TypeUtilisateur; // Added import
use PDO;

/**
 * Class CategorieUtilisateur
 *
 * Represents the categorie_utilisateur table.
 *
 * @package App\Models
 */
class CategorieUtilisateur extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'categorie_utilisateur';

    /**
     * @var string The ID of the user category.
     */
    public string $id;

    /**
     * @var string The label of the user category.
     */
    public string $libelle;

    /**
     * CategorieUtilisateur constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    }
