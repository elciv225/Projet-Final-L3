<?php


namespace App\Models;
class AnneeAcademique {
    private string $id;
    private ?string $date_debut = null;
    private ?string $date_fin = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getDateDebut(): ?string { return $this->date_debut; }
    public function setDateDebut(?string $date_debut): void { $this->date_debut = $date_debut; }
    public function getDateFin(): ?string { return $this->date_fin; }
    public function setDateFin(?string $date_fin): void { $this->date_fin = $date_fin; }
}