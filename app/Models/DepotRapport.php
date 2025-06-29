<?php

namespace App\Models;

/**
 * Class DepotRapport
 *
 * @package App\Models
 */
class DepotRapport
{
    /**
     * @var string
     */
    protected string $table = 'depot_rapport';

    /**
     * L'étudiant qui a soumis le rapport.
     * @var Etudiant
     */
    private Etudiant $etudiant;

    /**
     * Le rapport étudiant soumis.
     * @var RapportEtudiant
     */
    private RapportEtudiant $rapportEtudiant;

    /**
     * @var string|null La date de soumission.
     */
    private ?string $dateDepot; // DDL spécifie DATE

    /**
     * @param Etudiant $etudiant
     * @param RapportEtudiant $rapportEtudiant
     * @param string|null $dateDepot
     */
    public function __construct(
        Etudiant $etudiant,
        RapportEtudiant $rapportEtudiant,
        ?string $dateDepot
    ) {
        $this->etudiant = $etudiant;
        $this->rapportEtudiant = $rapportEtudiant;
        $this->dateDepot = $dateDepot;
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
     * @return RapportEtudiant
     */
    public function getRapportEtudiant(): RapportEtudiant
    {
        return $this->rapportEtudiant;
    }

    /**
     * @param RapportEtudiant $rapportEtudiant
     */
    public function setRapportEtudiant(RapportEtudiant $rapportEtudiant): void
    {
        $this->rapportEtudiant = $rapportEtudiant;
    }

    /**
     * @return string|null
     */
    public function getDateDepot(): ?string
    {
        return $this->dateDepot;
    }

    /**
     * @param string|null $dateDepot
     */
    public function setDateDepot(?string $dateDepot): void
    {
        $this->dateDepot = $dateDepot;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
