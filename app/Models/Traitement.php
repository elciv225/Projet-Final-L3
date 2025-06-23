<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Audit;
use App\Models\AccesTraitement;
use App\Models\AutorisationAction; // Added import
use PDO;

/**
 * Class Traitement
 *
 * Represents the traitement table.
 *
 * @package App\Models
 */
class Traitement extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'traitement';

    /**
     * @var string The ID of the treatment.
     */
    public string $id;

    /**
     * @var string The label of the treatment.
     */
    public string $libelle;

    /**
     * @var string|null A description of the treatment.
     */
    public ?string $description;

    /**
     * Traitement constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
