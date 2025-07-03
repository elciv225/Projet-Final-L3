<?php

namespace App\Models;

class AffectationEncadrant
{
    private string $utilisateur_id;
    private string $rapport_etudiant_id;
    private string $statut_jury_id;
    private string $date_affectation;

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
    public function getStatutJuryId(): string
    {
        return $this->statut_jury_id;
    }

    /**
     * @param string $statut_jury_id
     */
    public function setStatutJuryId(string $statut_jury_id): void
    {
        $this->statut_jury_id = $statut_jury_id;
    }

    /**
     * @return string
     */
    public function getDateAffectation(): string
    {
        return $this->date_affectation;
    }

    /**
     * @param string $date_affectation
     */
    public function setDateAffectation(string $date_affectation): void
    {
        $this->date_affectation = $date_affectation;
    }
}