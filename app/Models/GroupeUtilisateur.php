<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Utilisateur;
use App\Models\AutorisationAction; // Added import
use PDO;

/**
 * Class GroupeUtilisateur
 *
 * Represents the groupe_utilisateur table.
 *
 * @package App\Models
 */
class GroupeUtilisateur extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'groupe_utilisateur';

    /**
     * @var string The ID of the user group.
     */
    public string $id;

    /**
     * @var string The label of the user group.
     */
    public string $libelle;

    /**
     * @var string|null A description of the user group.
     */
    public ?string $description;

    /**
     * GroupeUtilisateur constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
