<?php

namespace App\Models;

/**
 * Class AffectationEncadrant
 *
 * @package App\Models
 */
class AffectationEncadrant
{
    /**
     * @var string
     */
    protected string $table = 'affectation_encadrant';

    /**
     * L'enseignant affecté.
     * @var Enseignant
     */
    private Enseignant $enseignant; // anciennement utilisateurId

    /**
     * Le rapport étudiant concerné.
     * @var RapportEtudiant
     */
    private RapportEtudiant $rapportEtudiant;

    /**
     * Le statut de l'enseignant dans le jury pour ce rapport.
     * @var StatutJury
     */
    private StatutJury $statutJury;

    /**
     * @var string|null Date d'affectation.
     */
    private ?string $dateAffectation; // DDL spécifie DATE

    /**
     * @param Enseignant $enseignant
     * @param RapportEtudiant $rapportEtudiant
     * @param StatutJury $statutJury
     * @param string|null $dateAffectation
     */
    public function __construct(
        Enseignant $enseignant,
        RapportEtudiant $rapportEtudiant,
        StatutJury $statutJury,
        ?string $dateAffectation
    ) {
        $this->enseignant = $enseignant;
        $this->rapportEtudiant = $rapportEtudiant;
        $this->statutJury = $statutJury;
        $this->dateAffectation = $dateAffectation;
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
     * @return StatutJury
     */
    public function getStatutJury(): StatutJury
    {
        return $this->statutJury;
    }

    /**
     * @param StatutJury $statutJury
     */
    public function setStatutJury(StatutJury $statutJury): void
    {
        $this->statutJury = $statutJury;
    }

    /**
     * @return string|null
     */
    public function getDateAffectation(): ?string
    {
        return $this->dateAffectation;
    }

    /**
     * @param string|null $dateAffectation
     */
    public function setDateAffectation(?string $dateAffectation): void
    {
        $this->dateAffectation = $dateAffectation;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
