<?php

namespace App\Models;

class Audit
{
    private int $id;
    private string $description;
    private string $date_traitement;
    private string $traitement_id;
    private string $utilisateur_id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDateTraitement(): string
    {
        return $this->date_traitement;
    }

    /**
     * @param string $date_traitement
     */
    public function setDateTraitement(string $date_traitement): void
    {
        $this->date_traitement = $date_traitement;
    }

    /**
     * @return string
     */
    public function getTraitementId(): string
    {
        return $this->traitement_id;
    }

    /**
     * @param string $traitement_id
     */
    public function setTraitementId(string $traitement_id): void
    {
        $this->traitement_id = $traitement_id;
    }

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
}