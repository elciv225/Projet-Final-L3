<?php

namespace App\Models;
class Enseignant {
    private string $utilisateur_id;
    private ?Utilisateur $utilisateur = null;
    public function __construct() {}
    public function getUtilisateurId(): string { return $this->utilisateur_id; }
    public function setUtilisateurId(string $id): void { $this->utilisateur_id = $id; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(Utilisateur $utilisateur): void { $this->utilisateur = $utilisateur; }
}