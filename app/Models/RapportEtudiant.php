
<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\DepotRapport;
use App\Models\ApprobationRapport;
use App\Models\ValidationRapport;
use App\Models\AffectationEncadrant; // Added import
use PDO;

/**
 * Class RapportEtudiant
 *
 * Represents a student report in the rapport_etudiant table.
 *
 * @package App\Models
 */
class RapportEtudiant extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'rapport_etudiant';

    /**
     * @var int|null The ID of the report.
     */
    public ?int $id;

    /**
     * @var string|null The title of the report.
     */
    public ?string $titre;

    /**
     * @var string|null The date of the report.
     */
    public ?string $date_rapport;

    /**
     * @var string|null The theme of the thesis.
     */
    public ?string $theme_memoire;

    /**
     * RapportEtudiant constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
