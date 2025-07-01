<?php

namespace App\Models;
class Utilisateur {
    private string $id;
    private ?string $nom = null;
    private ?string $prenoms = null;
    private ?string $email = null;
    private ?string $login = null;
    private ?string $mot_de_passe = null;
    private ?string $date_naissance = null;
    private ?string $groupe_utilisateur_id = null;
    private ?string $type_utilisateur_id = null;

    private ?GroupeUtilisateur $groupeUtilisateur = null;
    private ?TypeUtilisateur $typeUtilisateur = null;
    public function __construct() {}
    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): void { $this->nom = $nom; }
    public function getPrenoms(): ?string { return $this->prenoms; }
    public function setPrenoms(?string $prenoms): void { $this->prenoms = $prenoms; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): void { $this->email = $email; }
    public function getLogin(): ?string { return $this->login; }
    public function setLogin(?string $login): void { $this->login = $login; }
    public function getMotDePasse(): ?string { return $this->mot_de_passe; }
    public function setMotDePasse(?string $mot_de_passe): void { $this->mot_de_passe = $mot_de_passe; }
    public function getDateNaissance(): ?string { return $this->date_naissance; }
    public function setDateNaissance(?string $date): void { $this->date_naissance = $date; }
    public function getGroupeUtilisateurId(): ?string { return $this->groupe_utilisateur_id; }
    public function setGroupeUtilisateurId(?string $id): void { $this->groupe_utilisateur_id = $id; }
    public function getTypeUtilisateurId(): ?string { return $this->type_utilisateur_id; }
    public function setTypeUtilisateurId(?string $id): void { $this->type_utilisateur_id = $id; }
    public function getGroupeUtilisateur(): ?GroupeUtilisateur { return $this->groupeUtilisateur; }
    public function setGroupeUtilisateur(GroupeUtilisateur $groupe): void { $this->groupeUtilisateur = $groupe; }
    public function getTypeUtilisateur(): ?TypeUtilisateur { return $this->typeUtilisateur; }
    public function setTypeUtilisateur(TypeUtilisateur $type): void { $this->typeUtilisateur = $type; }

}