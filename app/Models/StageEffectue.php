<?php

namespace App\Models;

/**
 * Class StageEffectue
 *
 * @package App\Models
 */
class StageEffectue
{
    /**
     * @var string
     */
    protected string $table = 'stage_effectue';

    /**
     * @var string L'ID de l'étudiant (FK, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID de l'entreprise (FK, partie de la CPK).
     */
    private string $entrepriseId;

    /**
     * @var string|null La date de début du stage.
     */
    private ?string $dateDebut; // DDL spécifie DATE

    /**
     * @var string|null La date de fin du stage.
     */
    private ?string $dateFin; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $entrepriseId
     * @param string|null $dateDebut
     * @param string|null $dateFin
     */
    public function __construct(string $utilisateurId, string $entrepriseId, ?string $dateDebut, ?string $dateFin)
    {
        $this->utilisateurId = $utilisateurId;
        $this->entrepriseId = $entrepriseId;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
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
    public function getEntrepriseId(): string
    {
        return $this->entrepriseId;
    }

    /**
     * @param string $entrepriseId
     */
    public function setEntrepriseId(string $entrepriseId): void
    {
        $this->entrepriseId = $entrepriseId;
    }

    /**
     * @return string|null
     */
    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    /**
     * @param string|null $dateDebut
     */
    public function setDateDebut(?string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return string|null
     */
    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    /**
     * @param string|null $dateFin
     */
    public function setDateFin(?string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
