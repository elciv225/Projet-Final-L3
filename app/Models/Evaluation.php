<?php

namespace App\Models;

/**
 * Class Evaluation
 *
 * @package App\Models
 */
class Evaluation
{
    /**
     * @var string
     */
    protected string $table = 'evaluation';

    /**
     * L'enseignant qui a donné l'évaluation.
     * @var Enseignant
     */
    private Enseignant $enseignant;

    /**
     * L'étudiant qui a été évalué.
     * @var Etudiant
     */
    private Etudiant $etudiant;

    /**
     * L'ECUE pour laquelle l'évaluation a été donnée.
     * @var Ecue
     */
    private Ecue $ecue;

    /**
     * @var string|null La date de l'évaluation.
     */
    private ?string $dateEvaluation; // DDL spécifie DATE

    /**
     * @var int|null La note de l'étudiant.
     */
    private ?int $note; // DDL spécifie SMALLINT (SQL d'origine), le modèle PHP avait TINYINT UNSIGNED

    /**
     * @param Enseignant $enseignant
     * @param Etudiant $etudiant
     * @param Ecue $ecue
     * @param string|null $dateEvaluation
     * @param int|null $note
     */
    public function __construct(
        Enseignant $enseignant,
        Etudiant $etudiant,
        Ecue $ecue,
        ?string $dateEvaluation,
        ?int $note
    ) {
        $this->enseignant = $enseignant;
        $this->etudiant = $etudiant;
        $this->ecue = $ecue;
        $this->dateEvaluation = $dateEvaluation;
        $this->note = $note;
    }

    /**
     * @return Enseignant
     */
    public function getEnseignant(): Enseignant
    {
        return $this->enseignant;
    }

    /**
     * @param Enseignant $enseignant
     */
    public function setEnseignant(Enseignant $enseignant): void
    {
        $this->enseignant = $enseignant;
    }

    /**
     * @return Etudiant
     */
    public function getEtudiant(): Etudiant
    {
        return $this->etudiant;
    }

    /**
     * @param Etudiant $etudiant
     */
    public function setEtudiant(Etudiant $etudiant): void
    {
        $this->etudiant = $etudiant;
    }

    /**
     * @return Ecue
     */
    public function getEcue(): Ecue
    {
        return $this->ecue;
    }

    /**
     * @param Ecue $ecue
     */
    public function setEcue(Ecue $ecue): void
    {
        $this->ecue = $ecue;
    }

    /**
     * @return string|null
     */
    public function getDateEvaluation(): ?string
    {
        return $this->dateEvaluation;
    }

    /**
     * @param string|null $dateEvaluation
     */
    public function setDateEvaluation(?string $dateEvaluation): void
    {
        $this->dateEvaluation = $dateEvaluation;
    }

    /**
     * @return int|null
     */
    public function getNote(): ?int
    {
        return $this->note;
    }

    /**
     * @param int|null $note
     */
    public function setNote(?int $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
