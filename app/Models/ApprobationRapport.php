<?php

namespace App\Models;

class ApprobationRapport
{
    private string $utilisateur_id;
    private string $rapport_etudiant_id;
    private string $date_approbation;

    /**
     * @return string
     */
    public function getUtilisateurId(): string
    {
        return $this->utilisateur_id;
    }

    /**
     * @param string $utilisateur_id
     */
    public function setUtilisateurId(string $utilisateur_id): void
    {
        $this->utilisateur_id = $utilisateur_id;
    }

    /**
     * @return string
     */
    public function getRapportEtudiantId(): string
    {
        return $this->rapport_etudiant_id;
    }

    /**
     * @param string $rapport_etudiant_id
     */
    public function setRapportEtudiantId(string $rapport_etudiant_id): void
    {
        $this->rapport_etudiant_id = $rapport_etudiant_id;
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