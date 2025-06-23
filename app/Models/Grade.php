<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\HistoriqueGrade; // Added import
use PDO;

/**
 * Class Grade
 *
 * Represents the grade table.
 *
 * @package App\Models
 */
class Grade extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'grade';

    /**
     * @var string The ID of the grade.
     */
    public string $id;

    /**
     * @var string The label of the grade.
     */
    public string $libelle;

    /**
     * Grade constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
