<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Utilisateur;
// Removed StageEffectue import as getStagesEncadres is being removed
use App\Models\Evaluation;
use App\Models\ValidationRapport;
use App\Models\AffectationEncadrant;
use App\Models\HistoriqueFonction;
use App\Models\HistoriqueGrade;
use App\Models\RemiseCompteRendu;
use App\Models\Messagerie; // Added import
use PDO;

/**
 * Class Enseignant
 *
 * Represents the enseignant table.
 *
 * @package App\Models
 */
class Enseignant extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'enseignant';

    /**
     * @var string The ID of the teacher. This is also a foreign key to utilisateur.id.
     */
    public string $id_utilisateur;

    // Removed nom, prenom, contact, specialite, id_grade properties

    /**
     * Enseignant constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
