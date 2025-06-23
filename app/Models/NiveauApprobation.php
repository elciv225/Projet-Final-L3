<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\HistoriqueApprobation; // Added import
use PDO;

/**
 * Class NiveauApprobation
 *
 * Represents the niveau_approbation table.
 *
 * @package App\Models
 */
class NiveauApprobation extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'niveau_approbation';

    /**
     * @var string The ID of the approval level.
     */
    public string $id;

    /**
     * @var string The label of the approval level.
     */
    public string $libelle;

    /**
     * NiveauApprobation constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
