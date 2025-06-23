<?php

namespace App\Models;

/**
 * Class InscriptionEtudiant
 *
 * @package App\Models
 */
class InscriptionEtudiant
{
    /**
     * @var string
     */
    protected string $table = 'inscription_etudiant';

    /**
     * @var string L'ID de l'utilisateur/étudiant (FK, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID du niveau d'étude (FK, partie de la CPK).
     */
    private string $niveauEtudeId;

    /**
     * @var string L'ID de l'année académique (FK, partie de la CPK).
     */
    private string $anneeAcademiqueId;

    /**
     * @var string|null La date d'inscription.
     */
    private ?string $dateInscription; // DDL spécifie DATE

    /**
     * @var int|null Le montant de l'inscription.
     */
    private ?int $montant; // DDL spécifie INT

    /**
     * @param string $utilisateurId
     * @param string $niveauEtudeId
     * @param string $anneeAcademiqueId
     * @param string|null $dateInscription
     * @param int|null $montant
     */
    public function __construct(string $utilisateurId, string $niveauEtudeId, string $anneeAcademiqueId, ?string $dateInscription, ?int $montant)
    {
        $this->utilisateurId = $utilisateurId;
        $this->niveauEtudeId = $niveauEtudeId;
        $this->anneeAcademiqueId = $anneeAcademiqueId;
        $this->dateInscription = $dateInscription;
        $this->montant = $montant;
    }

    /**
     * @return string
     */
    public function getUtilisateurId(): string
    {
        return $this->utilisateurId;
    }

    /**
     * @param string $utilisateurId
     */
    public function setUtilisateurId(string $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    /**
     * @return string
     */
    public function getNiveauEtudeId(): string
    {
        return $this->niveauEtudeId;
    }

    /**
     * @param string $niveauEtudeId
     */
    public function setNiveauEtudeId(string $niveauEtudeId): void
    {
        $this->niveauEtudeId = $niveauEtudeId;
    }

    /**
     * @return string
     */
    public function getAnneeAcademiqueId(): string
    {
        return $this->anneeAcademiqueId;
    }

    /**
     * @param string $anneeAcademiqueId
     */
    public function setAnneeAcademiqueId(string $anneeAcademiqueId): void
    {
        $this->anneeAcademiqueId = $anneeAcademiqueId;
    }

    /**
     * @return string|null
     */
    public function getDateInscription(): ?string
    {
        return $this->dateInscription;
    }

    /**
     * @param string|null $dateInscription
     */
    public function setDateInscription(?string $dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return int|null
     */
    public function getMontant(): ?int
    {
        return $this->montant;
    }

    /**
     * @param int|null $montant
     */
    public function setMontant(?int $montant): void
    {
        $this->montant = $montant;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
