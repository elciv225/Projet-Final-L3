<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\PersonnelAdministratif;
use App\Models\RapportEtudiant;
use PDO;

/**
 * Class ApprobationRapport
 *
 * Represents the approbation_rapport table.
 * Composite PK: (utilisateur_id, rapport_etudiant_id).
 *
 * @package App\Models
 */
class ApprobationRapport extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'approbation_rapport';

    /**
     * @var string The ID of the approving user (FK to personnel_administratif.utilisateur_id, part of CPK).
     */
    public string $utilisateur_id;

    /**
     * @var string The ID of the student report (FK to rapport_etudiant.id, part of CPK).
     */
    public string $rapport_etudiant_id;

    /**
     * @var string|null The date of approval.
     */
    public ?string $date_approbation; // DDL specifies DATE

    /**
     * ApprobationRapport constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

   
}
