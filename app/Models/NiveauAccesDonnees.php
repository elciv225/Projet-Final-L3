<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Utilisateur; // Added import
use PDO;

/**
 * Class NiveauAccesDonnees
 *
 * Represents the niveau_acces_donnees table.
 *
 * @package App\Models
 */
class NiveauAccesDonnees extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'niveau_acces_donnees';

    /**
     * @var string The ID of the data access level.
     */
    public string $id;

    /**
     * @var string The label of the data access level.
     */
    public string $libelle;

    /**
     * @var string|null A description of the data access level.
     */
    public ?string $description;

    /**
     * NiveauAccesDonnees constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
