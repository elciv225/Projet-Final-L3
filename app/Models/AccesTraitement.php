<?php

namespace App\Models;

class AccesTraitement
{
    protected string $table = 'acces_traitement';
    protected Traitement $traitement;
    protected Utilisateur $utilisateur;
    protected ?string $dateAccesion;
    protected ?string $heureAccesion;

    /**
     * @param Traitement $traitement
     * @param Utilisateur $utilisateur
     * @param string|null $dateAccesion
     * @param string|null $heureAccesion
     */
    public function __construct(Traitement $traitement, Utilisateur $utilisateur, ?string $dateAccesion, ?string $heureAccesion)
    {
        $this->traitement = $traitement;
        $this->utilisateur = $utilisateur;
        $this->dateAccesion = $dateAccesion;
        $this->heureAccesion = $heureAccesion;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function getTraitement(): Traitement
    {
        return $this->traitement;
    }

    public function setTraitement(Traitement $traitement): void
    {
        $this->traitement = $traitement;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function getHeureAccesion(): ?string
    {
        return $this->heureAccesion;
    }

    public function setHeureAccesion(?string $heureAccesion): void
    {
        $this->heureAccesion = $heureAccesion;
    }


}
