<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Enseignant;
use App\Models\RapportEtudiant;
use PDO;

/**
 * Class ValidationRapport
 *
 * Represents the validation_rapport table.
 * Composite PK: (utilisateur_id, rapport_etudiant_id).
 *
 * @package App\Models
 */
class ValidationRapport extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'validation_rapport';

    /**
     * @var string The ID of the validating user (FK to enseignant.utilisateur_id, part of CPK).
     */
    public string $utilisateur_id;

    /**
     * @var string The ID of the student report (FK to rapport_etudiant.id, part of CPK).
     */
    public string $rapport_etudiant_id;

    /**
     * @var string|null The date of validation.
     */
    public ?string $date_validation; // DDL specifies DATE

    /**
     * @var string|null Comments regarding the validation.
     */
    public ?string $commentaire;     // DDL specifies VARCHAR(255)

    /**
     * ValidationRapport constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
