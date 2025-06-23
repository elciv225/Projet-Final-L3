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
     * @var string ID de l'utilisateur approbateur (FK vers personnel_administratif.utilisateur_id, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string ID du rapport étudiant (FK vers rapport_etudiant.id, partie de la CPK).
     */
    private string $rapportEtudiantId;

    /**
     * @var string|null Date d'approbation.
     */
    private ?string $dateApprobation; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $rapportEtudiantId
     * @param string|null $dateApprobation
     */
    public function __construct(string $utilisateurId, string $rapportEtudiantId, ?string $dateApprobation)
    {
        $this->utilisateurId = $utilisateurId;
        $this->rapportEtudiantId = $rapportEtudiantId;
        $this->dateApprobation = $dateApprobation;
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
