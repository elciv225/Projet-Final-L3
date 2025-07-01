<?php

namespace App\Models;

class HistoriqueSpecialite
{
    /**
     * @var string
     */
    protected string $table = 'historique_specialite';

    /**
     * L'enseignant concerné.
     * @var Enseignant
     */
    private Enseignant $enseignant;

    /**
     * Le specialite obtenu.
     * @var Specialite
     */
    private Specialite $specialite;

    /**
     * @var string|null La date d'obtention du specialite.
     */
    private ?string $dateSpecialite; // DDL spécifie DATE

    /**
     * @param Enseignant $enseignant
     * @param Specialite $specialite
     * @param string|null $dateSpecialite
     */
    public function __construct(
        Enseignant $enseignant,
        Specialite      $specialite,
        ?string    $dateSpecialite
    )
    {
        $this->enseignant = $enseignant;
        $this->specialite = $specialite;
        $this->dateSpecialite = $dateSpecialite;
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
     * @return specialite
     */
    public function getspecialite(): specialite
    {
        return $this->specialite;
    }

    /**
     * @param specialite $specialite
     */
    public function setspecialite(specialite $specialite): void
    {
        $this->specialite = $specialite;
    }

    /**
     * @return string|null
     */
    public function getdateSpecialite(): ?string
    {
        return $this->dateSpecialite;
    }

    /**
     * @param string|null $dateSpecialite
     */
    public function setdateSpecialite(?string $dateSpecialite): void
    {
        $this->dateSpecialite = $dateSpecialite;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}