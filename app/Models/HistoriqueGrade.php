<?php

namespace App\Models;

/**
 * Class HistoriqueGrade
 *
 * @package App\Models
 */
class HistoriqueGrade
{
    /**
     * @var string
     */
    protected string $table = 'historique_grade';

    /**
     * L'enseignant concerné.
     * @var Enseignant
     */
    private Enseignant $enseignant;

    /**
     * Le grade obtenu.
     * @var Grade
     */
    private Grade $grade;

    /**
     * @var string|null La date d'obtention du grade.
     */
    private ?string $dateGrade; // DDL spécifie DATE

    /**
     * @param Enseignant $enseignant
     * @param Grade $grade
     * @param string|null $dateGrade
     */
    public function __construct(
        Enseignant $enseignant,
        Grade $grade,
        ?string $dateGrade
    ) {
        $this->enseignant = $enseignant;
        $this->grade = $grade;
        $this->dateGrade = $dateGrade;
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
     * @return Grade
     */
    public function getGrade(): Grade
    {
        return $this->grade;
    }

    /**
     * @param Grade $grade
     */
    public function setGrade(Grade $grade): void
    {
        $this->grade = $grade;
    }

    /**
     * @return string|null
     */
    public function getDateGrade(): ?string
    {
        return $this->dateGrade;
    }

    /**
     * @param string|null $dateGrade
     */
    public function setDateGrade(?string $dateGrade): void
    {
        $this->dateGrade = $dateGrade;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
