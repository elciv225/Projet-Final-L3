<?php

namespace App\Models;

/**
 * Class EtatSauvegarde
 *
 * Représente l'état d'un enregistrement sauvegardé à un instant T.
 * Utilisé pour la restauration potentielle de données.
 *
 * @package App\Models
 */
class EtatSauvegarde
{
    /**
     * Nom de la table correspondante en base de données.
     * @var string
     */
    protected string $table = 'etat_sauvegarde';

    /**
     * L'identifiant unique de la sauvegarde (Clé primaire auto-incrémentée).
     * @var int|null
     */
    private ?int $id;

    /**
     * Le nom de la table dont l'enregistrement a été sauvegardé.
     * @var string
     */
    private string $nomTable;

    /**
     * L'identifiant de l'enregistrement original qui a été sauvegardé.
     * @var string
     */
    private string $enregistrementId;

    /**
     * Les données de l'enregistrement sauvegardées, au format JSON.
     * Devrait être un string contenant du JSON valide.
     * @var string
     */
    private string $donnees; // JSON

    /**
     * La date et l'heure de la sauvegarde.
     * Format: Timestamp (par exemple, YYYY-MM-DD HH:MM:SS).
     * @var string|null
     */
    private ?string $dateSauvegarde;

    /**
     * Le traitement qui a potentiellement déclenché cette sauvegarde (optionnel).
     * @var Traitement|null
     */
    private ?Traitement $traitement;

    /**
     * L'utilisateur qui a potentiellement déclenché cette sauvegarde (optionnel).
     * @var Utilisateur|null
     */
    private ?Utilisateur $utilisateur;

    /**
     * Constructeur de la classe EtatSauvegarde.
     *
     * @param string $nomTable Nom de la table de l'enregistrement sauvegardé.
     * @param string $enregistrementId ID de l'enregistrement sauvegardé.
     * @param string $donnees Données JSON de l'enregistrement.
     * @param string|null $dateSauvegarde Date de la sauvegarde.
     * @param Traitement|null $traitement Traitement lié (optionnel).
     * @param Utilisateur|null $utilisateur Utilisateur lié (optionnel).
     * @param int|null $id ID de la sauvegarde (optionnel).
     */
    public function __construct(
        string $nomTable,
        string $enregistrementId,
        string $donnees,
        ?string $dateSauvegarde = null,
        ?Traitement $traitement = null,
        ?Utilisateur $utilisateur = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->nomTable = $nomTable;
        $this->enregistrementId = $enregistrementId;
        $this->donnees = $donnees;
        $this->dateSauvegarde = $dateSauvegarde;
        $this->traitement = $traitement;
        $this->utilisateur = $utilisateur;
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNomTable(): string
    {
        return $this->nomTable;
    }

    public function setNomTable(string $nomTable): void
    {
        $this->nomTable = $nomTable;
    }

    public function getEnregistrementId(): string
    {
        return $this->enregistrementId;
    }

    public function setEnregistrementId(string $enregistrementId): void
    {
        $this->enregistrementId = $enregistrementId;
    }

    public function getDonnees(): string
    {
        return $this->donnees;
    }

    public function setDonnees(string $donnees): void
    {
        $this->donnees = $donnees;
    }

    public function getDonneesAsArray(): ?array
    {
        return json_decode($this->donnees, true);
    }

    public function getDonneesAsObject(): ?object
    {
        return json_decode($this->donnees);
    }

    public function getDateSauvegarde(): ?string
    {
        return $this->dateSauvegarde;
    }

    public function setDateSauvegarde(?string $dateSauvegarde): void
    {
        $this->dateSauvegarde = $dateSauvegarde;
    }

    public function getTraitement(): ?Traitement
    {
        return $this->traitement;
    }

    public function setTraitement(?Traitement $traitement): void
    {
        $this->traitement = $traitement;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function getTable(): string
    {
        return $this->table;
    }
}
