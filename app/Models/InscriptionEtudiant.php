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
     * L'étudiant concerné par l'inscription.
     * @var Etudiant
     */
    private Etudiant $etudiant;

    /**
     * Le niveau d'étude pour lequel l'étudiant s'inscrit.
     * @var NiveauEtude
     */
    private NiveauEtude $niveauEtude;

    /**
     * L'année académique de l'inscription.
     * @var AnneeAcademique
     */
    private AnneeAcademique $anneeAcademique;

    /**
     * @var string|null La date d'inscription.
     */
    private ?string $dateInscription; // DDL spécifie DATE

    /**
     * @var int|null Le montant de l'inscription.
     */
    private ?int $montant; // DDL spécifie INT

    /**
     * @param Etudiant $etudiant
     * @param NiveauEtude $niveauEtude
     * @param AnneeAcademique $anneeAcademique
     * @param string|null $dateInscription
     * @param int|null $montant
     */
    public function __construct(
        Etudiant $etudiant,
        NiveauEtude $niveauEtude,
        AnneeAcademique $anneeAcademique,
        ?string $dateInscription,
        ?int $montant
    ) {
        $this->etudiant = $etudiant;
        $this->niveauEtude = $niveauEtude;
        $this->anneeAcademique = $anneeAcademique;
        $this->dateInscription = $dateInscription;
        $this->montant = $montant;
    }

    /**
     * @return Etudiant
     */
    public function getEtudiant(): Etudiant
    {
        return $this->etudiant;
    }

    /**
     * @param Etudiant $etudiant
     */
    public function setEtudiant(Etudiant $etudiant): void
    {
        $this->etudiant = $etudiant;
    }

    /**
     * @return NiveauEtude
     */
    public function getNiveauEtude(): NiveauEtude
    {
        return $this->niveauEtude;
    }

    /**
     * @param NiveauEtude $niveauEtude
     */
    public function setNiveauEtude(NiveauEtude $niveauEtude): void
    {
        $this->niveauEtude = $niveauEtude;
    }

    /**
     * @return AnneeAcademique
     */
    public function getAnneeAcademique(): AnneeAcademique
    {
        return $this->anneeAcademique;
    }

    /**
     * @param AnneeAcademique $anneeAcademique
     */
    public function setAnneeAcademique(AnneeAcademique $anneeAcademique): void
    {
        $this->anneeAcademique = $anneeAcademique;
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
