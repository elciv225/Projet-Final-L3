<?php

namespace App\Models;

class HistoriqueApprobation
{
    private string $niveau_approbation_id;
    private string $compte_rendu_id;
    private string $date_approbation;

    /**
     * @return string
     */
    public function getNiveauApprobationId(): string
    {
        return $this->niveau_approbation_id;
    }

    /**
     * @param string $niveau_approbation_id
     */
    public function setNiveauApprobationId(string $niveau_approbation_id): void
    {
        $this->niveau_approbation_id = $niveau_approbation_id;
    }

    /**
     * @return string
     */
    public function getCompteRenduId(): string
    {
        return $this->compte_rendu_id;
    }

    /**
     * @param string $compte_rendu_id
     */
    public function setCompteRenduId(string $compte_rendu_id): void
    {
        $this->compte_rendu_id = $compte_rendu_id;
    }

    /**
     * @return string
     */
    public function getDateApprobation(): string
    {
        return $this->date_approbation;
    }

    /**
     * @param string $date_approbation
     */
    public function setDateApprobation(string $date_approbation): void
    {
        $this->date_approbation = $date_approbation;
    }
}