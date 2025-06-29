<?php

namespace App\Models;

/**
 * Class ValidationRapport
 *
 * @package App\Models
 */
class ValidationRapport
{
    /**
     * @var string
     */
    protected string $table = 'validation_rapport';

    /**
     * L'enseignant validateur.
     * @var Enseignant
     */
    private Enseignant $enseignant;

    /**
     * Le rapport étudiant validé.
     * @var RapportEtudiant
     */
    private RapportEtudiant $rapportEtudiant;

    /**
     * @var string|null La date de validation.
     */
    private ?string $dateValidation; // DDL spécifie DATE

    /**
     * @var string|null Commentaires concernant la validation.
     */
    private ?string $commentaire;     // DDL spécifie TEXT

    /**
     * @param Enseignant $enseignant
     * @param RapportEtudiant $rapportEtudiant
     * @param string|null $dateValidation
     * @param string|null $commentaire
     */
    public function __construct(
        Enseignant $enseignant,
        RapportEtudiant $rapportEtudiant,
        ?string $dateValidation,
        ?string $commentaire
    ) {
        $this->enseignant = $enseignant;
        $this->rapportEtudiant = $rapportEtudiant;
        $this->dateValidation = $dateValidation;
        $this->commentaire = $commentaire;
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
     * @return string|null
     */
    public function getDateValidation(): ?string
    {
        return $this->dateValidation;
    }

    /**
     * @param string|null $dateValidation
     */
    public function setDateValidation(?string $dateValidation): void
    {
        $this->dateValidation = $dateValidation;
    }

    /**
     * @return string|null
     */
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    /**
     * @param string|null $commentaire
     */
    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
