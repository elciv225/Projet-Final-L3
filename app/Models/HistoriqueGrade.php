<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Enseignant;
use App\Models\Grade;
use PDO;

/**
 * Class HistoriqueGrade
 *
 * Represents the historique_grade table.
 * Composite PK: (utilisateur_id, grade_id, date_grade).
 *
 * @package App\Models
 */
class HistoriqueGrade extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'historique_grade';

    /**
     * @var string The ID of the enseignant (FK to enseignant.utilisateur_id, part of CPK).
     */
    public string $utilisateur_id;

    /**
     * @var string The ID of the grade (FK to grade.id, part of CPK).
     */
    public string $grade_id;

    /**
     * @var string The date the grade was obtained/assigned (part of CPK).
     */
    public string $date_grade; // DDL specifies DATE

    /**
     * HistoriqueGrade constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
