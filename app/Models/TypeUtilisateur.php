<?php

namespace App\Models;
class TypeUtilisateur {
    private string $id;
    private ?string $libelle = null;
    private ?string $categorie_utilisateur_id = null;
    private ?CategorieUtilisateur $categorieUtilisateur = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(?string $libelle): void { $this->libelle = $libelle; }
    public function getCategorieUtilisateurId(): ?string { return $this->categorie_utilisateur_id; }
    public function setCategorieUtilisateurId(?string $id): void { $this->categorie_utilisateur_id = $id; }
    public function getCategorieUtilisateur(): ?CategorieUtilisateur { return $this->categorieUtilisateur; }
    public function setCategorieUtilisateur(CategorieUtilisateur $cat): void { $this->categorieUtilisateur = $cat; }
}