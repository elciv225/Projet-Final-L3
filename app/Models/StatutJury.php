<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\AffectationEncadrant; // Added import
use PDO;

/**
 * Class StatutJury
 *
 * Represents the statut_jury table.
 *
 * @package App\Models
 */
class StatutJury extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'statut_jury';

    /**
     * @var string The ID of the jury status.
     */
    public string $id;

    /**
     * @var string The label of the jury status.
     */
    public string $libelle;

    /**
     * StatutJury constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
