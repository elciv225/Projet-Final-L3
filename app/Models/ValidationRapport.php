<?php

namespace App\Models;

class ValidationRapport
{
    private string $utilisateur_id;
    private string $rapport_etudiant_id;
    private string $date_validation;
    private string $commentaire;

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
    public function getDateValidation(): string
    {
        return $this->date_validation;
    }

    /**
     * @param string $date_validation
     */
    public function setDateValidation(string $date_validation): void
    {
        $this->date_validation = $date_validation;
    }

    /**
     * @return string
     */
    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire(string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }
}