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
     * L'enseignant concerné.
     * @var Enseignant
     */
    private Enseignant $enseignant;

    /**
     * La fonction occupée.
     * @var Fonction
     */
    private Fonction $fonction;

    /**
     * @var string|null La date d'occupation.
     */
    private ?string $dateOccupation; // DDL spécifie DATE

    /**
     * @param Enseignant $enseignant
     * @param Fonction $fonction
     * @param string|null $dateOccupation
     */
    public function __construct(
        Enseignant $enseignant,
        Fonction $fonction,
        ?string $dateOccupation
    ) {
        $this->enseignant = $enseignant;
        $this->fonction = $fonction;
        $this->dateOccupation = $dateOccupation;
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
     * @return Fonction
     */
    public function getFonction(): Fonction
    {
        return $this->fonction;
    }

    /**
     * @param Fonction $fonction
     */
    public function setFonction(Fonction $fonction): void
    {
        $this->fonction = $fonction;
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
