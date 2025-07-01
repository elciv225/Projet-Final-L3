<?php

namespace App\Models;
class Menu {
    private string $id;
    private ?string $categorie_menu_id = null;
    private ?string $libelle = null;
    private ?string $vue = null;
    private ?CategorieMenu $categorieMenu = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getCategorieMenuId(): ?string { return $this->categorie_menu_id; }
    public function setCategorieMenuId(?string $id): void { $this->categorie_menu_id = $id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(?string $libelle): void { $this->libelle = $libelle; }
    public function getVue(): ?string { return $this->vue; }
    public function setVue(?string $vue): void { $this->vue = $vue; }
    public function getCategorieMenu(): ?CategorieMenu { return $this->categorieMenu; }
    public function setCategorieMenu(CategorieMenu $categorieMenu): void { $this->categorieMenu = $categorieMenu; }
}