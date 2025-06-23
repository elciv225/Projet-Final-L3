<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\InscriptionEtudiant;
// Removed import for StageEffectue
use PDO;

/**
 * Class AnneeAcademique
 *
 * Represents the annee_academique table.
 *
 * @package App\Models
 */
class AnneeAcademique extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'annee_academique';

    /**
     * @var string The ID of the academic year.
     */
    public string $id;

    /**
     * @var string The label of the academic year (e.g., "2023-2024").
     */
    public string $libelle;

    /**
     * @var string The start date of the academic year.
     */
    public string $date_debut;

    /**
     * @var string The end date of the academic year.
     */
    public string $date_fin;

    /**
     * AnneeAcademique constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }
}
