<?php

namespace App\Models;

/**
 * Class ApprobationRapport
 *
 * @package App\Models
 */
class ApprobationRapport
{
    /**
     * @var string
     */
    protected string $table = 'approbation_rapport';

    /**
     * Le personnel administratif ayant approuvé le rapport.
     * @var PersonnelAdministratif
     */
    private PersonnelAdministratif $personnelAdministratif;

    /**
     * Le rapport étudiant approuvé.
     * @var RapportEtudiant
     */
    private RapportEtudiant $rapportEtudiant;

    /**
     * @var string|null Date d'approbation.
     */
    private ?string $dateApprobation; // DDL spécifie DATE

    /**
     * @param PersonnelAdministratif $personnelAdministratif
     * @param RapportEtudiant $rapportEtudiant
     * @param string|null $dateApprobation
     */
    public function __construct(
        PersonnelAdministratif $personnelAdministratif,
        RapportEtudiant $rapportEtudiant,
        ?string $dateApprobation
    ) {
        $this->personnelAdministratif = $personnelAdministratif;
        $this->rapportEtudiant = $rapportEtudiant;
        $this->dateApprobation = $dateApprobation;
    }

    /**
     * @return PersonnelAdministratif
     */
    public function getPersonnelAdministratif(): PersonnelAdministratif
    {
        return $this->personnelAdministratif;
    }

    /**
     * @param PersonnelAdministratif $personnelAdministratif
     */
    public function setPersonnelAdministratif(PersonnelAdministratif $personnelAdministratif): void
    {
        $this->personnelAdministratif = $personnelAdministratif;
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
    public function getDateApprobation(): ?string
    {
        return $this->dateApprobation;
    }

    /**
     * @param string|null $dateApprobation
     */
    public function setDateApprobation(?string $dateApprobation): void
    {
        $this->dateApprobation = $dateApprobation;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
