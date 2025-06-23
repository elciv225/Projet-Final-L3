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
     * @var string L'ID de l'enseignant (FK, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID du grade (FK, partie de la CPK).
     */
    private string $gradeId;

    /**
     * @var string|null La date d'obtention du grade.
     */
    private ?string $dateGrade; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $gradeId
     * @param string|null $dateGrade
     */
    public function __construct(string $utilisateurId, string $gradeId, ?string $dateGrade)
    {
        $this->utilisateurId = $utilisateurId;
        $this->gradeId = $gradeId;
        $this->dateGrade = $dateGrade;
    }

    /**
     * @return string
     */
    public function getUtilisateurId(): string
    {
        return $this->utilisateurId;
    }

    /**
     * @param string $utilisateurId
     */
    public function setUtilisateurId(string $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    /**
     * @return string
     */
    public function getGradeId(): string
    {
        return $this->gradeId;
    }

    /**
     * @param string $gradeId
     */
    public function setGradeId(string $gradeId): void
    {
        $this->gradeId = $gradeId;
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
