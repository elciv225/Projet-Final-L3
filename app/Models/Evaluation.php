<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Ecue;
use PDO;

/**
 * Class Evaluation
 *
 * Represents the evaluation table.
 * Composite PK: (enseignant_id, etudiant_id, ecue_id).
 *
 * @package App\Models
 */
class Evaluation extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'evaluation';

    // Properties representing the composite primary key and other fields
    /**
     * @var string The ID of the teacher (FK to enseignant.utilisateur_id, part of CPK).
     */
    public string $enseignant_id;

    /**
     * @var string The ID of the student (FK to etudiant.utilisateur_id, part of CPK).
     */
    public string $etudiant_id;

    /**
     * @var string The ID of the ECUE (FK to ecue.id, part of CPK).
     */
    public string $ecue_id;

    /**
     * @var float|null The student's grade/score. Note: DDL says TINYINT UNSIGNED, using float for flexibility.
     */
    public ?float $note; // DECIMAL(4,2) maps to float or string

    /**
     * @var string|null The date of the evaluation.
     */
    public ?string $date_evaluation; // Assuming DATE SQL type

    /**
     * Evaluation constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
