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
     * L'étudiant ayant effectué le stage.
     * @var Etudiant
     */
    private Etudiant $etudiant;

    /**
     * L'entreprise où le stage a été effectué.
     * @var Entreprise
     */
    private Entreprise $entreprise;

    /**
     * @var string|null La date de début du stage.
     */
    private ?string $dateDebut; // DDL spécifie DATE

    /**
     * @var string|null La date de fin du stage.
     */
    private ?string $dateFin; // DDL spécifie DATE

    /**
     * @param Etudiant $etudiant
     * @param Entreprise $entreprise
     * @param string|null $dateDebut
     * @param string|null $dateFin
     */
    public function __construct(
        Etudiant $etudiant,
        Entreprise $entreprise,
        ?string $dateDebut,
        ?string $dateFin
    ) {
        $this->etudiant = $etudiant;
        $this->entreprise = $entreprise;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
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
     * @return Entreprise
     */
    public function getEntreprise(): Entreprise
    {
        return $this->entreprise;
    }

    /**
     * @param Entreprise $entreprise
     */
    public function setEntreprise(Entreprise $entreprise): void
    {
        $this->entreprise = $entreprise;
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
