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
     * @var string L'ID de l'étudiant qui a soumis (FK vers etudiant.utilisateur_id, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID du rapport étudiant (FK vers rapport_etudiant.id, partie de la CPK).
     */
    private string $rapportEtudiantId;

    /**
     * @var string|null La date de soumission.
     */
    private ?string $dateDepot; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $rapportEtudiantId
     * @param string|null $dateDepot
     */
    public function __construct(string $utilisateurId, string $rapportEtudiantId, ?string $dateDepot)
    {
        $this->utilisateurId = $utilisateurId;
        $this->rapportEtudiantId = $rapportEtudiantId;
        $this->dateDepot = $dateDepot;
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
