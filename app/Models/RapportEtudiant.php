<?php

namespace App\Models;

/**
 * Class RapportEtudiant
 *
 * @package App\Models
 */
class RapportEtudiant
{
    /**
     * @var string
     */
    protected string $table = 'rapport_etudiant';

    /**
     * @var string L'ID du rapport étudiant.
     */
    private string $id;

    /**
     * @var string|null Le titre du rapport.
     */
    private ?string $titre;

    /**
     * @var string|null La date du rapport.
     */
    private ?string $dateRapport; // DDL spécifie DATE

    /**
     * @var string|null Le thème du mémoire.
     */
    private ?string $themeMemoire;

    /**
     * @param string $id
     * @param string|null $titre
     * @param string|null $dateRapport
     * @param string|null $themeMemoire
     */
    public function __construct(string $id, ?string $titre, ?string $dateRapport, ?string $themeMemoire)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->dateRapport = $dateRapport;
        $this->themeMemoire = $themeMemoire;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string|null
     */
    public function getDateRapport(): ?string
    {
        return $this->dateRapport;
    }

    /**
     * @param string|null $dateRapport
     */
    public function setDateRapport(?string $dateRapport): void
    {
        $this->dateRapport = $dateRapport;
    }

    /**
     * @return string|null
     */
    public function getThemeMemoire(): ?string
    {
        return $this->themeMemoire;
    }

    /**
     * @param string|null $themeMemoire
     */
    public function setThemeMemoire(?string $themeMemoire): void
    {
        $this->themeMemoire = $themeMemoire;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
