<?php

namespace App\Models;
class CategorieMenu {
    private string $id;
    private ?string $libelle = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(?string $libelle): void { $this->libelle = $libelle; }
}