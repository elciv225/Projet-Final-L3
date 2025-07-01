<?php

namespace App\Models;
class Ue {
    private string $id;
    private ?string $libelle = null;
    private ?int $credit = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(?string $libelle): void { $this->libelle = $libelle; }
    public function getCredit(): ?int { return $this->credit; }
    public function setCredit(?int $credit): void { $this->credit = $credit; }
}