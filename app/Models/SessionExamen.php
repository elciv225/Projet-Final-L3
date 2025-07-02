<?php

namespace App\Models;

class SessionExamen {
    private string $id; // ex: SESS_NORMALE, SESS_RATTRAPAGE
    private ?string $libelle = null; // ex: Session Normale, Session de Rattrapage

    public function __construct() {}

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getLibelle(): ?string {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void {
        $this->libelle = $libelle;
    }
}
