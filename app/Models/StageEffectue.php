<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Etudiant;
use App\Models\Entreprise;
// AnneeAcademique and Enseignant are no longer directly related from this model
use PDO;

/**
 * Class StageEffectue
 *
 * Represents the stage_effectue table (internships).
 * Composite PK: (utilisateur_id, entreprise_id).
 *
 * @package App\Models
 */
class StageEffectue extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'stage_effectue';

    /**
     * @var string|null The ID of the student (FK to etudiant.utilisateur_id, part of composite PK).
     */
    public ?string $utilisateur_id;

    /**
     * @var string|null The ID of the company (FK to entreprise.id, part of composite PK).
     */
    public ?string $entreprise_id;

    /**
     * @var string|null The start date of the internship.
     */
    public ?string $date_debut;

    /**
     * @var string|null The end date of the internship.
     */
    public ?string $date_fin;

    // Removed sujet_stage, id_annee_academique, id_enseignant_encadrant as per strict DDL

    /**
     * StageEffectue constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
