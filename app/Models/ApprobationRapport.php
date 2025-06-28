<?php

namespace App\Models;

/**
 * Class ApprobationRapport
 *
 * @package App\Models
 */
class ApprobationRapport
{
    protected string $table = 'approbation_rapport';
    private Utilisateur $utilisateur;
    private RapportEtudiant $rapportEtudiant;
    private ?string $dateApprobation; // DDL spécifie DATE

    /**
     * @param Utilisateur $utilisateur
     * @param RapportEtudiant $rapportEtudiant
     * @param string|null $dateApprobation
     */
    public function __construct(Utilisateur $utilisateur, RapportEtudiant $rapportEtudiant, ?string $dateApprobation)
    {
        $this->utilisateur = $utilisateur;
        $this->rapportEtudiant = $rapportEtudiant;
        $this->dateApprobation = $dateApprobation;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function getRapportEtudiant(): RapportEtudiant
    {
        return $this->rapportEtudiant;
    }

    public function setRapportEtudiant(RapportEtudiant $rapportEtudiant): void
    {
        $this->rapportEtudiant = $rapportEtudiant;
    }

    public function getDateApprobation(): ?string
    {
        return $this->dateApprobation;
    }

    public function setDateApprobation(?string $dateApprobation): void
    {
        $this->dateApprobation = $dateApprobation;
    }


}
