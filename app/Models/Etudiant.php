<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Utilisateur;
use App\Models\InscriptionEtudiant;
use App\Models\StageEffectue;
use App\Models\Evaluation;
use App\Models\DepotRapport;
// Removed CompteRendu import as getComptesRendus is being removed
use App\Models\Messagerie; // Added import
use PDO;

/**
 * Class Etudiant
 *
 * Represents the etudiant table.
 *
 * @package App\Models
 */
class Etudiant extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'etudiant';

    /**
     * @var string The ID of the student. This is also a foreign key to utilisateur.id.
     */
    public string $id_utilisateur;

    // Removed ine, nom, prenom, date_naissance, lieu_naissance, contact properties

    /**
     * Etudiant constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
