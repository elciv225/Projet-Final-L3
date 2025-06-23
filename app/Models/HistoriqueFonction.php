<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Enseignant;
use App\Models\Fonction;
use PDO;

/**
 * Class HistoriqueFonction
 *
 * Represents the historique_fonction table.
 * Composite PK: (utilisateur_id, fonction_id, date_occupation).
 *
 * @package App\Models
 */
class HistoriqueFonction extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'historique_fonction';

    /**
     * @var string The ID of the enseignant (FK to enseignant.utilisateur_id, part of CPK).
     */
    public string $utilisateur_id;

    /**
     * @var string The ID of the function (FK to fonction.id, part of CPK).
     */
    public string $fonction_id;

    /**
     * @var string The date of occupation (part of CPK).
     */
    public string $date_occupation; // DDL specifies DATE

    /**
     * HistoriqueFonction constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
