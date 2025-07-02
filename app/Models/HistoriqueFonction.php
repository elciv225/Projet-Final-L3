<?php

namespace App\Models;

class HistoriqueFonction {
    private string $utilisateur_id;
    private string $fonction_id;
    private ?string $date_occupation = null;
    public function __construct() {}
    public function getUtilisateurId(): string { return $this->utilisateur_id; }
    public function setUtilisateurId(string $id): void { $this->utilisateur_id = $id; }
    public function getFonctionId(): string { return $this->fonction_id; }
    public function setFonctionId(string $id): void { $this->fonction_id = $id; }
    public function getDateOccupation(): ?string { return $this->date_occupation; }
    public function setDateOccupation(?string $date): void { $this->date_occupation = $date; }
}