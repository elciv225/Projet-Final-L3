<?php

namespace App\Models;
class Etudiant {
    private string $utilisateur_id;
    private ?string $numero_carte = null;
    private ?Utilisateur $utilisateur = null;
    public function __construct() {}
    public function getUtilisateurId(): string { return $this->utilisateur_id; }
    public function setUtilisateurId(string $id): void { $this->utilisateur_id = $id; }
    public function getNumeroCarte(): ?string { return $this->numero_carte; }
    public function setNumeroCarte(?string $num): void { $this->numero_carte = $num; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(Utilisateur $utilisateur): void { $this->utilisateur = $utilisateur; }
}