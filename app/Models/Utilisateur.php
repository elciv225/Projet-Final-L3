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

    /**
     * @var string L'ID de l'utilisateur.
     */
    private string $id;

    /**
     * @var string|null Le nom de l'utilisateur.
     */
    private ?string $nom;

    /**
     * @var string|null Les prénoms de l'utilisateur.
     */
    private ?string $prenoms;

    /**
     * @var string|null L'adresse email de l'utilisateur.
     */
    private ?string $email;

    /**
     * @var string|null Le login de l'utilisateur.
     */
    private ?string $login;

    /**
     * @var string|null Le mot de passe hashé de l'utilisateur.
     */
    private ?string $motDePasse;

    /**
     * @var string|null Le chemin ou l'URL de la photo de l'utilisateur.
     */
    private ?string $photo;

    /**
     * @var string|null La date de naissance de l'utilisateur.
     */
    private ?string $dateNaissance; // DDL spécifie DATE

    /**
     * @var string L'ID du groupe utilisateur (FK).
     */
    private string $groupeUtilisateurId; // DDL spécifie NOT NULL

    /**
     * @var string L'ID du type d'utilisateur (FK).
     */
    private string $typeUtilisateurId; // DDL spécifie NOT NULL

    /**
     * @var string L'ID du niveau d'accès aux données (FK).
     */
    private string $niveauAccesDonneesId; // DDL spécifie NOT NULL

    /**
     * @param string $id
     * @param string|null $nom
     * @param string|null $prenoms
     * @param string|null $email
     * @param string|null $login
     * @param string|null $motDePasse
     * @param string|null $photo
     * @param string|null $dateNaissance
     * @param string $groupeUtilisateurId
     * @param string $typeUtilisateurId
     * @param string $niveauAccesDonneesId
     */
    public function __construct(
        string $id,
        ?string $nom,
        ?string $prenoms,
        ?string $email,
        ?string $login,
        ?string $motDePasse,
        ?string $photo,
        ?string $dateNaissance,
        string $groupeUtilisateurId,
        string $typeUtilisateurId,
        string $niveauAccesDonneesId
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenoms = $prenoms;
        $this->email = $email;
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        $this->photo = $photo;
        $this->dateNaissance = $dateNaissance;
        $this->groupeUtilisateurId = $groupeUtilisateurId;
        $this->typeUtilisateurId = $typeUtilisateurId;
        $this->niveauAccesDonneesId = $niveauAccesDonneesId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    /**
     * @param string|null $prenoms
     */
    public function setPrenoms(?string $prenoms): void
    {
        $this->prenoms = $prenoms;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string|null
     */
    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    /**
     * @param string|null $motDePasse
     */
    public function setMotDePasse(?string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return string|null
     */
    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    /**
     * @param string|null $dateNaissance
     */
    public function setDateNaissance(?string $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @return string
     */
    public function getGroupeUtilisateurId(): string
    {
        return $this->groupeUtilisateurId;
    }

    /**
     * @param string $groupeUtilisateurId
     */
    public function setGroupeUtilisateurId(string $groupeUtilisateurId): void
    {
        $this->groupeUtilisateurId = $groupeUtilisateurId;
    }

    /**
     * @return string
     */
    public function getTypeUtilisateurId(): string
    {
        return $this->typeUtilisateurId;
    }

    /**
     * @param string $typeUtilisateurId
     */
    public function setTypeUtilisateurId(string $typeUtilisateurId): void
    {
        $this->typeUtilisateurId = $typeUtilisateurId;
    }

    /**
     * @return string
     */
    public function getNiveauAccesDonneesId(): string
    {
        return $this->niveauAccesDonneesId;
    }

    /**
     * @param string $niveauAccesDonneesId
     */
    public function setNiveauAccesDonneesId(string $niveauAccesDonneesId): void
    {
        $this->niveauAccesDonneesId = $niveauAccesDonneesId;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
