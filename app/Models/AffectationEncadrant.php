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
     * @var string ID de l'enseignant (FK vers enseignant.utilisateur_id, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string ID du rapport étudiant (FK vers rapport_etudiant.id, partie de la CPK).
     */
    private string $rapportEtudiantId;

    /**
     * @var string ID du statut jury (FK vers statut_jury.id, partie de la CPK).
     */
    private string $statutJuryId;

    /**
     * @var string|null Date d'affectation.
     */
    private ?string $dateAffectation; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $rapportEtudiantId
     * @param string $statutJuryId
     * @param string|null $dateAffectation
     */
    public function __construct(string $utilisateurId, string $rapportEtudiantId, string $statutJuryId, ?string $dateAffectation)
    {
        $this->utilisateurId = $utilisateurId;
        $this->rapportEtudiantId = $rapportEtudiantId;
        $this->statutJuryId = $statutJuryId;
        $this->dateAffectation = $dateAffectation;
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
    public function getRapportEtudiantId(): string
    {
        return $this->rapportEtudiantId;
    }

    /**
     * @param string $rapportEtudiantId
     */
    public function setRapportEtudiantId(string $rapportEtudiantId): void
    {
        $this->rapportEtudiantId = $rapportEtudiantId;
    }

    /**
     * @return string
     */
    public function getStatutJuryId(): string
    {
        return $this->statutJuryId;
    }

    /**
     * @param string $statutJuryId
     */
    public function setStatutJuryId(string $statutJuryId): void
    {
        $this->statutJuryId = $statutJuryId;
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
