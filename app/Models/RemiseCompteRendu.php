<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Enseignant;
use App\Models\CompteRendu;
use PDO;

/**
 * Class RemiseCompteRendu
 *
 * Represents the remise_compte_rendu table.
 * Composite PK: (utilisateur_id, compte_rendu_id).
 *
 * @package App\Models
 */
class RemiseCompteRendu extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'remise_compte_rendu';

    /**
     * @var string The ID of the enseignant (FK to enseignant.utilisateur_id, part of CPK).
     */
    public string $utilisateur_id;

    /**
     * @var string The ID of the compte rendu (FK to compte_rendu.id, part of CPK).
     */
    public string $compte_rendu_id;

    /**
     * @var string|null The date of submission.
     */
    public ?string $date_rendu; // DDL specifies DATE

    /**
     * RemiseCompteRendu constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
