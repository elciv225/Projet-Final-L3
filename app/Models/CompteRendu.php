<?php

namespace App\Models;

/**
 * Class CompteRendu
 *
 * @package App\Models
 */
class CompteRendu
{
    /**
     * @var string
     */
    protected string $table = 'compte_rendu';

    /**
     * @var string L'ID du compte rendu.
     */
    private string $id;

    /**
     * @var string|null Le titre du compte rendu.
     */
    private ?string $titre;

    /**
     * @var string|null La date du rapport.
     */
    private ?string $dateRapport; // DDL spécifie DATE

    /**
     * @param string $id
     * @param string|null $titre
     * @param string|null $dateRapport
     */
    public function __construct(string $id, ?string $titre, ?string $dateRapport)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->dateRapport = $dateRapport;
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
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
