<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\InscriptionEtudiant; // Added import
use PDO;

/**
 * Class NiveauEtude
 *
 * Represents the niveau_etude table.
 *
 * @package App\Models
 */
class NiveauEtude extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'niveau_etude';

    /**
     * @var string The ID of the study level.
     */
    public string $id;

    /**
     * @var string The label of the study level.
     */
    public string $libelle;

    /**
     * NiveauEtude constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
