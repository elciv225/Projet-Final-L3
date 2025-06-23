<?php

namespace App\Models;

/**
 * Class HistoriqueApprobation
 *
 * @package App\Models
 */
class HistoriqueApprobation
{
    /**
     * @var string
     */
    protected string $table = 'historique_approbation';

    /**
     * @var string L'ID du niveau d'approbation (FK, partie de la CPK).
     */
    private string $niveauApprobationId;

    /**
     * @var string L'ID du compte rendu (FK, partie de la CPK).
     */
    private string $compteRenduId;

    /**
     * @var string|null La date d'approbation.
     */
    private ?string $dateApprobation; // DDL spécifie DATETIME

    /**
     * @param string $niveauApprobationId
     * @param string $compteRenduId
     * @param string|null $dateApprobation
     */
    public function __construct(string $niveauApprobationId, string $compteRenduId, ?string $dateApprobation)
    {
        $this->niveauApprobationId = $niveauApprobationId;
        $this->compteRenduId = $compteRenduId;
        $this->dateApprobation = $dateApprobation;
    }

    /**
     * @return string
     */
    public function getNiveauApprobationId(): string
    {
        return $this->niveauApprobationId;
    }

    /**
     * @param string $niveauApprobationId
     */
    public function setNiveauApprobationId(string $niveauApprobationId): void
    {
        $this->niveauApprobationId = $niveauApprobationId;
    }

    /**
     * @return string
     */
    public function getCompteRenduId(): string
    {
        return $this->compteRenduId;
    }

    /**
     * @param string $compteRenduId
     */
    public function setCompteRenduId(string $compteRenduId): void
    {
        $this->compteRenduId = $compteRenduId;
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
