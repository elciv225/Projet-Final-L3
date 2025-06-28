<?php

namespace App\Models;

class Menu
{
    protected string $table = 'menu';
    private string $id;
    private string $libelle;
    private string $vue;
    private ?string $icone; // Ajout de l'icône, nullable

    public function __construct(string $id, string $libelle, string $vue, ?string $icone = null)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->vue = $vue;
        $this->icone = $icone;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getVue(): string
    {
        return $this->vue;
    }

    public function setVue(string $vue): void
    {
        $this->vue = $vue;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): void
    {
        $this->icone = $icone;
    }



}