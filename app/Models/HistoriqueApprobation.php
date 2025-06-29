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
     * Le niveau d'approbation.
     * @var NiveauApprobation
     */
    private NiveauApprobation $niveauApprobation;

    /**
     * Le compte rendu concerné.
     * @var CompteRendu
     */
    private CompteRendu $compteRendu;

    /**
     * @var string|null La date d'approbation (format TIMESTAMP).
     */
    private ?string $dateApprobation;

    /**
     * @param NiveauApprobation $niveauApprobation
     * @param CompteRendu $compteRendu
     * @param string|null $dateApprobation
     */
    public function __construct(
        NiveauApprobation $niveauApprobation,
        CompteRendu $compteRendu,
        ?string $dateApprobation
    ) {
        $this->niveauApprobation = $niveauApprobation;
        $this->compteRendu = $compteRendu;
        $this->dateApprobation = $dateApprobation;
    }

    /**
     * @return NiveauApprobation
     */
    public function getNiveauApprobation(): NiveauApprobation
    {
        return $this->niveauApprobation;
    }

    /**
     * @param NiveauApprobation $niveauApprobation
     */
    public function setNiveauApprobation(NiveauApprobation $niveauApprobation): void
    {
        $this->niveauApprobation = $niveauApprobation;
    }

    /**
     * @return CompteRendu
     */
    public function getCompteRendu(): CompteRendu
    {
        return $this->compteRendu;
    }

    /**
     * @param CompteRendu $compteRendu
     */
    public function setCompteRendu(CompteRendu $compteRendu): void
    {
        $this->compteRendu = $compteRendu;
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
