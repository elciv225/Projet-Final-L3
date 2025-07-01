<?php

namespace App\Models;
class RapportEtudiant {
    private string $id;
    private ?string $titre = null;
    private ?string $date_rapport = null;
    private ?string $lien_rapport = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): void { $this->titre = $titre; }
    public function getDateRapport(): ?string { return $this->date_rapport; }
    public function setDateRapport(?string $date): void { $this->date_rapport = $date; }
    public function getLienRapport(): ?string { return $this->lien_rapport; }
    public function setLienRapport(?string $lien): void { $this->lien_rapport = $lien; }
}