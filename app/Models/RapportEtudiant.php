<?php

namespace App\Models;

class RapportEtudiant
{
    private string $id;
    private string $titre;
    private string $date_rapport;
    private string $lien_rapport;

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
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getDateRapport(): string
    {
        return $this->date_rapport;
    }

    /**
     * @param string $date_rapport
     */
    public function setDateRapport(string $date_rapport): void
    {
        $this->date_rapport = $date_rapport;
    }

    /**
     * @return string
     */
    public function getLienRapport(): string
    {
        return $this->lien_rapport;
    }

    /**
     * @param string $lien_rapport
     */
    public function setLienRapport(string $lien_rapport): void
    {
        $this->lien_rapport = $lien_rapport;
    }
}