<?php

namespace App\Models;

/**
 * Class AffectationEncadrant
 *
 * Represents the affectation_encadrant table.
 * Composite PK: (utilisateur_id, rapport_etudiant_id, statut_jury_id).
 *
 * @package App\Models
 */
class AffectationEncadrant
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'affectation_encadrant';

    /**
     * @var string The ID of the enseignant (FK to enseignant.utilisateur_id, part of CPK).
     */
    private string $utilisateur_id;

    /**
     * @var string The ID of the student report (FK to rapport_etudiant.id, part of CPK).
     */
    private string $rapport_etudiant_id;

    /**
     * @var string The ID of the statut_jury (FK to statut_jury.id, part of CPK).
     */
    private string $statut_jury_id;

    /**
     * @var string|null The date of assignment.
     */
    private ?string $date_affectation; // DDL specifies DATE

    /**
     * @param string $table
     * @param string $utilisateur_id
     * @param string $rapport_etudiant_id
     * @param string $statut_jury_id
     * @param string|null $date_affectation
     */
    public function __construct(string $table, string $utilisateur_id, string $rapport_etudiant_id, string $statut_jury_id, ?string $date_affectation)
    {
        $this->table = $table;
        $this->utilisateur_id = $utilisateur_id;
        $this->rapport_etudiant_id = $rapport_etudiant_id;
        $this->statut_jury_id = $statut_jury_id;
        $this->date_affectation = $date_affectation;
    }


}
