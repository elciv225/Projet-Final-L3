<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\NiveauApprobation;
use App\Models\CompteRendu;
use PDO;

/**
 * Class HistoriqueApprobation
 *
 * Represents the historique_approbation table.
 * Composite PK: (niveau_approbation_id, compte_rendu_id, date_approbation).
 *
 * @package App\Models
 */
class HistoriqueApprobation extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'historique_approbation';

    /**
     * @var string The ID of the niveau_approbation (FK, part of CPK).
     */
    public string $niveau_approbation_id;

    /**
     * @var string The ID of the compte_rendu (FK, part of CPK).
     */
    public string $compte_rendu_id;

    /**
     * @var string The date of approval (part of CPK).
     */
    public string $date_approbation; // DDL specifies DATE

    /**
     * HistoriqueApprobation constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
