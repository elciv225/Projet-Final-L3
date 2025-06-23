<?php

namespace App\Models;

/**
 * Class HistoriqueFonction
 *
 * @package App\Models
 */
class HistoriqueFonction
{
    /**
     * @var string
     */
    protected string $table = 'historique_fonction';

    /**
     * @var string L'ID de l'enseignant (FK, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID de la fonction (FK, partie de la CPK).
     */
    private string $fonctionId;

    /**
     * @var string|null La date d'occupation.
     */
    private ?string $dateOccupation; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $fonctionId
     * @param string|null $dateOccupation
     */
    public function __construct(string $utilisateurId, string $fonctionId, ?string $dateOccupation)
    {
        $this->utilisateurId = $utilisateurId;
        $this->fonctionId = $fonctionId;
        $this->dateOccupation = $dateOccupation;
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
    public function getFonctionId(): string
    {
        return $this->fonctionId;
    }

    /**
     * @param string $fonctionId
     */
    public function setFonctionId(string $fonctionId): void
    {
        $this->fonctionId = $fonctionId;
    }

    /**
     * @return string|null
     */
    public function getDateOccupation(): ?string
    {
        return $this->dateOccupation;
    }

    /**
     * @param string|null $dateOccupation
     */
    public function setDateOccupation(?string $dateOccupation): void
    {
        $this->dateOccupation = $dateOccupation;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
