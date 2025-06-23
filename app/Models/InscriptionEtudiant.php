<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Etudiant;
use App\Models\NiveauEtude;
use App\Models\AnneeAcademique;
use PDO;

/**
 * Class InscriptionEtudiant
 *
 * Represents the inscription_etudiant table (student enrollment).
 * This table has a composite primary key (id_etudiant, id_annee_academique, id_niveau_etude).
 * The BaseModel may need adjustments to handle composite keys for find, update, delete.
 *
 * @package App\Models
 */
class InscriptionEtudiant extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'inscription_etudiant';

    /**
     * @var string The ID of the student (part of composite PK).
     */
    public string $id_etudiant;

    /**
     * @var string The ID of the academic year (part of composite PK).
     */
    public string $id_annee_academique;

    /**
     * @var string The ID of the study level (part of composite PK).
     */
    public string $id_niveau_etude;

    /**
     * @var string|null The date of enrollment.
     */
    public ?string $date_inscription; // Assuming DATE SQL type

    /**
     * @var string|null The status of the enrollment.
     */
    public ?string $statut_inscription;


    /**
     * InscriptionEtudiant constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
