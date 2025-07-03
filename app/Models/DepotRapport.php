<?php

namespace App\Models;

class DepotRapport
{
    private string $utilisateur_id;
    private string $rapport_etudiant_id;
    private string $date_depot;

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
    public function getDateDepot(): string
    {
        return $this->date_depot;
    }

    /**
     * @param string $date_depot
     */
    public function setDateDepot(string $date_depot): void
    {
        $this->date_depot = $date_depot;
    }
}