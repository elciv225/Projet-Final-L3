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
     * L'ID du traitement qui a potentiellement déclenché cette sauvegarde (optionnel).
     * @var string|null
     */
    private ?string $traitementId;

    /**
     * L'ID de l'utilisateur qui a potentiellement déclenché cette sauvegarde (optionnel).
     * @var string|null
     */
    private ?string $utilisateurId;

    /**
     * Constructeur de la classe EtatSauvegarde.
     *
     * @param string $nomTable Nom de la table de l'enregistrement sauvegardé.
     * @param string $enregistrementId ID de l'enregistrement sauvegardé.
     * @param string $donnees Données JSON de l'enregistrement.
     * @param string|null $dateSauvegarde Date de la sauvegarde (optionnel, défaut à maintenant si non fourni par la DB).
     * @param string|null $traitementId ID du traitement lié (optionnel).
     * @param string|null $utilisateurId ID de l'utilisateur lié (optionnel).
     * @param int|null $id ID de la sauvegarde (optionnel, pour instanciation depuis la DB).
     */
    public function __construct(
        string $nomTable,
        string $enregistrementId,
        string $donnees, // S'assurer que c'est une chaîne JSON valide lors de l'hydratation/création
        ?string $dateSauvegarde = null,
        ?string $traitementId = null,
        ?string $utilisateurId = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->nomTable = $nomTable;
        $this->enregistrementId = $enregistrementId;
        $this->donnees = $donnees;
        $this->dateSauvegarde = $dateSauvegarde;
        $this->traitementId = $traitementId;
        $this->utilisateurId = $utilisateurId;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNomTable(): string
    {
        return $this->nomTable;
    }

    /**
     * @param string $nomTable
     */
    public function setNomTable(string $nomTable): void
    {
        $this->nomTable = $nomTable;
    }

    /**
     * @return string
     */
    public function getEnregistrementId(): string
    {
        return $this->enregistrementId;
    }

    /**
     * @param string $enregistrementId
     */
    public function setEnregistrementId(string $enregistrementId): void
    {
        $this->enregistrementId = $enregistrementId;
    }

    /**
     * Retourne les données JSON sous forme de chaîne.
     * @return string
     */
    public function getDonnees(): string
    {
        return $this->donnees;
    }

    /**
     * Définit les données JSON.
     * @param string $donnees Doit être une chaîne JSON valide.
     */
    public function setDonnees(string $donnees): void
    {
        $this->donnees = $donnees;
    }

    /**
     * Tente de décoder les données JSON en tableau associatif PHP.
     * @return array|null Renvoie un tableau si le JSON est valide, null sinon.
     */
    public function getDonneesAsArray(): ?array
    {
        return json_decode($this->donnees, true);
    }

    /**
     * Tente de décoder les données JSON en objet PHP.
     * @return object|null Renvoie un objet si le JSON est valide, null sinon.
     */
    public function getDonneesAsObject(): ?object
    {
        return json_decode($this->donnees);
    }

    /**
     * @return string|null
     */
    public function getDateSauvegarde(): ?string
    {
        return $this->dateSauvegarde;
    }

    /**
     * @param string|null $dateSauvegarde
     */
    public function setDateSauvegarde(?string $dateSauvegarde): void
    {
        $this->dateSauvegarde = $dateSauvegarde;
    }

    /**
     * @return string|null
     */
    public function getTraitementId(): ?string
    {
        return $this->traitementId;
    }

    /**
     * @param string|null $traitementId
     */
    public function setTraitementId(?string $traitementId): void
    {
        $this->traitementId = $traitementId;
    }

    /**
     * @return string|null
     */
    public function getUtilisateurId(): ?string
    {
        return $this->utilisateurId;
    }

    /**
     * @param string|null $utilisateurId
     */
    public function setUtilisateurId(?string $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    // TODO: Ajouter les méthodes pour récupérer les objets Traitement et Utilisateur associés si nécessaire
    // public function traitement(): ?Traitement { /* ... */ }
    // public function utilisateur(): ?Utilisateur { /* ... */ }
}
