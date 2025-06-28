<?php

namespace App\Models;

class Audit
{
    protected string $table = 'audit';
    private ?int $id;
    private ?string $description;
    private ?string $dateTraitement;
    private Traitement $traitement;
    private Utilisateur $utilisateur;


    /**
     * @param int|null $id
     * @param string|null $description
     * @param string|null $dateTraitement
     * @param Traitement $traitement
     * @param Utilisateur $utilisateur
     */
    public function __construct(?int $id, ?string $description, ?string $dateTraitement, Traitement $traitement, Utilisateur $utilisateur)
    {
        $this->id = $id;
        $this->description = $description;
        $this->dateTraitement = $dateTraitement;
        $this->traitement = $traitement;
        $this->utilisateur = $utilisateur;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDateTraitement(): ?string
    {
        return $this->dateTraitement;
    }

    public function setDateTraitement(?string $dateTraitement): void
    {
        $this->dateTraitement = $dateTraitement;
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

}
