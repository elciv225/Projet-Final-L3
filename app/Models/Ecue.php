<?php

namespace App\Models;
class Ecue {
    private string $id;
    private ?string $libelle = null;
    private ?int $credit = null;
    private ?string $ue_id = null;
    private ?Ue $ue = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(?string $libelle): void { $this->libelle = $libelle; }
    public function getCredit(): ?int { return $this->credit; }
    public function setCredit(?int $credit): void { $this->credit = $credit; }
    public function getUeId(): ?string { return $this->ue_id; }
    public function setUeId(?string $ue_id): void { $this->ue_id = $ue_id; }
    public function getUe(): ?Ue { return $this->ue; }
    public function setUe(Ue $ue): void { $this->ue = $ue; }
}