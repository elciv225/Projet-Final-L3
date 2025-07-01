<?php

namespace App\Models;
class CompteRendu {
    private string $id;
    private ?string $titre = null;
    private ?string $date_rapport = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): void { $this->titre = $titre; }
    public function getDateRapport(): ?string { return $this->date_rapport; }
    public function setDateRapport(?string $date): void { $this->date_rapport = $date; }
}
