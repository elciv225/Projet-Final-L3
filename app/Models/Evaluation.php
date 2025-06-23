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
     * @var string L'ID de l'enseignant (FK, partie de la CPK).
     */
    private string $enseignantId;

    /**
     * @var string L'ID de l'étudiant (FK, partie de la CPK).
     */
    private string $etudiantId;

    /**
     * @var string L'ID de l'ECUE (FK, partie de la CPK).
     */
    private string $ecueId;

    /**
     * @var string|null La date de l'évaluation.
     */
    private ?string $dateEvaluation; // DDL spécifie DATE

    /**
     * @var int|null La note de l'étudiant.
     */
    private ?int $note; // DDL spécifie TINYINT UNSIGNED

    /**
     * @param string $enseignantId
     * @param string $etudiantId
     * @param string $ecueId
     * @param string|null $dateEvaluation
     * @param int|null $note
     */
    public function __construct(string $enseignantId, string $etudiantId, string $ecueId, ?string $dateEvaluation, ?int $note)
    {
        $this->enseignantId = $enseignantId;
        $this->etudiantId = $etudiantId;
        $this->ecueId = $ecueId;
        $this->dateEvaluation = $dateEvaluation;
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getEnseignantId(): string
    {
        return $this->enseignantId;
    }

    /**
     * @param string $enseignantId
     */
    public function setEnseignantId(string $enseignantId): void
    {
        $this->enseignantId = $enseignantId;
    }

    /**
     * @return string
     */
    public function getEtudiantId(): string
    {
        return $this->etudiantId;
    }

    /**
     * @param string $etudiantId
     */
    public function setEtudiantId(string $etudiantId): void
    {
        $this->etudiantId = $etudiantId;
    }

    /**
     * @return string
     */
    public function getEcueId(): string
    {
        return $this->ecueId;
    }

    /**
     * @param string $ecueId
     */
    public function setEcueId(string $ecueId): void
    {
        $this->ecueId = $ecueId;
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
