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
     * @var string L'ID de l'utilisateur validateur (FK vers enseignant.utilisateur_id, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID du rapport étudiant (FK vers rapport_etudiant.id, partie de la CPK).
     */
    private string $rapportEtudiantId;

    /**
     * @var string|null La date de validation.
     */
    private ?string $dateValidation; // DDL spécifie DATE

    /**
     * @var string|null Commentaires concernant la validation.
     */
    private ?string $commentaire;     // DDL spécifie TEXT

    /**
     * @param string $utilisateurId
     * @param string $rapportEtudiantId
     * @param string|null $dateValidation
     * @param string|null $commentaire
     */
    public function __construct(string $utilisateurId, string $rapportEtudiantId, ?string $dateValidation, ?string $commentaire)
    {
        $this->utilisateurId = $utilisateurId;
        $this->rapportEtudiantId = $rapportEtudiantId;
        $this->dateValidation = $dateValidation;
        $this->commentaire = $commentaire;
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
