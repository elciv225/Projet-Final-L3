<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\CategorieUtilisateur;
use App\Models\Utilisateur; // Added import
use PDO;

/**
 * Class TypeUtilisateur
 *
 * Represents the type_utilisateur table.
 *
 * @package App\Models
 */
class TypeUtilisateur extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'type_utilisateur';

    /**
     * @var string The ID of the user type.
     */
    public string $id;

    /**
     * @var string The label of the user type.
     */
    public string $libelle;

    /**
     * @var string|null The ID of the CategorieUtilisateur this TypeUtilisateur belongs to.
     */
    public ?string $categorie_utilisateur_id;

    /**
     * TypeUtilisateur constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
