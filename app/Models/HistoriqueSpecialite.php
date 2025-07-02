<?php

namespace App\Models;

class HistoriqueSpecialite {
    private string $utilisateur_id;
    private string $specialite_id;
    private ?string $date_occupation = null;
    public function __construct() {}
    public function getUtilisateurId(): string { return $this->utilisateur_id; }
    public function setUtilisateurId(string $id): void { $this->utilisateur_id = $id; }
    public function getSpecialiteId(): string { return $this->specialite_id; }
    public function setSpecialiteId(string $id): void { $this->specialite_id = $id; }
    public function getDateOccupation(): ?string { return $this->date_occupation; }
    public function setDateOccupation(?string $date): void { $this->date_occupation = $date; }
}