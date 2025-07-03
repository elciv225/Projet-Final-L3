<?php

namespace App\Models;

class Evaluation
{
    private string $enseignant_id;
    private string $etudiant_id;
    private string $ecue_id;
    private string $date_evaluation;
    private int $note;

    /**
     * @return string
     */
    public function getEnseignantId(): string
    {
        return $this->enseignant_id;
    }

    /**
     * @param string $enseignant_id
     */
    public function setEnseignantId(string $enseignant_id): void
    {
        $this->enseignant_id = $enseignant_id;
    }

    /**
     * @return string
     */
    public function getEtudiantId(): string
    {
        return $this->etudiant_id;
    }

    /**
     * @param string $etudiant_id
     */
    public function setEtudiantId(string $etudiant_id): void
    {
        $this->etudiant_id = $etudiant_id;
    }

    /**
     * @return string
     */
    public function getEcueId(): string
    {
        return $this->ecue_id;
    }

    /**
     * @param string $ecue_id
     */
    public function setEcueId(string $ecue_id): void
    {
        $this->ecue_id = $ecue_id;
    }

    /**
     * @return string
     */
    public function getDateEvaluation(): string
    {
        return $this->date_evaluation;
    }

    /**
     * @param string $date_evaluation
     */
    public function setDateEvaluation(string $date_evaluation): void
    {
        $this->date_evaluation = $date_evaluation;
    }

    /**
     * @return int
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote(int $note): void
    {
        $this->note = $note;
    }
}