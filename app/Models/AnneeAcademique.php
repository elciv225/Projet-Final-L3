<?php

namespace App\Models;

class AnneeAcademique
{
    protected string $table = 'annee_academique';
    private string $id;
    private string $dateDebut; // DDL spécifie DATE
    private string $dateFin; // DDL spécifie DATE

    /**
     * @param string $id
     * @param string $dateDebut
     * @param string $dateFin
     */
    public function __construct(string $id, string $dateDebut, string $dateFin)
    {
        $this->id = $id;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
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
     * @return string
     */
    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    /**
     * @param string $dateDebut
     */
    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return string
     */
    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    /**
     * @param string $dateFin
     */
    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
