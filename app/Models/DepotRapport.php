<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Etudiant;
use App\Models\RapportEtudiant;
use PDO;

/**
 * Class DepotRapport
 *
 * Represents the depot_rapport table (report submissions).
 * Composite PK: (utilisateur_id, rapport_etudiant_id).
 *
 * @package App\Models
 */
class DepotRapport extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'depot_rapport';

    /**
     * @var string The ID of the student who submitted (FK to etudiant.utilisateur_id, part of CPK).
     */
    public string $utilisateur_id;
    
    /**
     * @var string The ID of the student report (FK to rapport_etudiant.id, part of CPK).
     */
    public string $rapport_etudiant_id;

    /**
     * @var string|null The date of submission.
     */
    public ?string $date_depot; // DDL specifies DATE

    /**
     * DepotRapport constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    }
