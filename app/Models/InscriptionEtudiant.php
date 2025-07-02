<?php

namespace App\Models;

class InscriptionEtudiant {
    private ?int $id = null; // Auto-incrementing primary key
    private string $utilisateur_id; // Foreign key to Utilisateur
    private string $annee_academique_id; // Foreign key to AnneeAcademique
    private string $niveau_etude_id; // Foreign key to NiveauEtude
    private ?string $date_inscription = null; // Date of inscription

    // Related objects (optional, for hydration)
    private ?Utilisateur $utilisateur = null;
    private ?AnneeAcademique $anneeAcademique = null;
    private ?NiveauEtude $niveauEtude = null;

    public function __construct() {}

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getUtilisateurId(): string { return $this->utilisateur_id; }
    public function getAnneeAcademiqueId(): string { return $this->annee_academique_id; }
    public function getNiveauEtudeId(): string { return $this->niveau_etude_id; }
    public function getDateInscription(): ?string { return $this->date_inscription; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function getAnneeAcademique(): ?AnneeAcademique { return $this->anneeAcademique; }
    public function getNiveauEtude(): ?NiveauEtude { return $this->niveauEtude; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setUtilisateurId(string $utilisateur_id): void { $this->utilisateur_id = $utilisateur_id; }
    public function setAnneeAcademiqueId(string $annee_academique_id): void { $this->annee_academique_id = $annee_academique_id; }
    public function setNiveauEtudeId(string $niveau_etude_id): void { $this->niveau_etude_id = $niveau_etude_id; }
    public function setDateInscription(?string $date_inscription): void { $this->date_inscription = $date_inscription; }
    public function setUtilisateur(Utilisateur $utilisateur): void { $this->utilisateur = $utilisateur; }
    public function setAnneeAcademique(AnneeAcademique $anneeAcademique): void { $this->anneeAcademique = $anneeAcademique; }
    public function setNiveauEtude(NiveauEtude $niveauEtude): void { $this->niveauEtude = $niveauEtude; }
}
