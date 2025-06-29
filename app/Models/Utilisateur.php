<?php

namespace App\Models;

/**
 * Class Utilisateur
 *
 * @package App\Models
 */
class Utilisateur
{
    /**
     * @var string
     */
    protected string $table = 'utilisateur';

    private string $id;
    private ?string $nom;
    private ?string $prenoms;
    private ?string $email;
    private ?string $login;
    private ?string $motDePasse; // Devrait rester hashé et géré avec précaution
    private ?string $photo;
    private ?string $dateNaissance; // Format YYYY-MM-DD

    private GroupeUtilisateur $groupeUtilisateur;
    private TypeUtilisateur $typeUtilisateur;
    private NiveauAccesDonnees $niveauAccesDonnees;

    public function __construct(
        string $id,
        GroupeUtilisateur $groupeUtilisateur,
        TypeUtilisateur $typeUtilisateur,
        NiveauAccesDonnees $niveauAccesDonnees,
        ?string $nom,
        ?string $prenoms,
        ?string $email,
        ?string $login,
        ?string $motDePasse,
        ?string $photo,
        ?string $dateNaissance
    ) {
        $this->id = $id;
        $this->groupeUtilisateur = $groupeUtilisateur;
        $this->typeUtilisateur = $typeUtilisateur;
        $this->niveauAccesDonnees = $niveauAccesDonnees;
        $this->nom = $nom;
        $this->prenoms = $prenoms;
        $this->email = $email;
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        $this->photo = $photo;
        $this->dateNaissance = $dateNaissance;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function getPrenoms(): ?string { return $this->prenoms; }
    public function getEmail(): ?string { return $this->email; }
    public function getLogin(): ?string { return $this->login; }
    public function getMotDePasse(): ?string { return $this->motDePasse; }
    public function getPhoto(): ?string { return $this->photo; }
    public function getDateNaissance(): ?string { return $this->dateNaissance; }
    public function getGroupeUtilisateur(): GroupeUtilisateur { return $this->groupeUtilisateur; }
    public function getTypeUtilisateur(): TypeUtilisateur { return $this->typeUtilisateur; }
    public function getNiveauAccesDonnees(): NiveauAccesDonnees { return $this->niveauAccesDonnees; }

    // Setters
    public function setId(string $id): void { $this->id = $id; }
    public function setNom(?string $nom): void { $this->nom = $nom; }
    public function setPrenoms(?string $prenoms): void { $this->prenoms = $prenoms; }
    public function setEmail(?string $email): void { $this->email = $email; }
    public function setLogin(?string $login): void { $this->login = $login; }
    public function setMotDePasse(?string $motDePasse): void { $this->motDePasse = $motDePasse; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }
    public function setDateNaissance(?string $dateNaissance): void { $this->dateNaissance = $dateNaissance; }
    public function setGroupeUtilisateur(GroupeUtilisateur $groupeUtilisateur): void { $this->groupeUtilisateur = $groupeUtilisateur; }
    public function setTypeUtilisateur(TypeUtilisateur $typeUtilisateur): void { $this->typeUtilisateur = $typeUtilisateur; }
    public function setNiveauAccesDonnees(NiveauAccesDonnees $niveauAccesDonnees): void { $this->niveauAccesDonnees = $niveauAccesDonnees; }

    public function getTable(): string
    {
        return $this->table;
    }
}
