<?php

namespace App\Models;

/**
 * Class RemiseCompteRendu
 *
 * @package App\Models
 */
class RemiseCompteRendu
{
    /**
     * @var string
     */
    protected string $table = 'remise_compte_rendu';

    /**
     * L'enseignant ayant remis le compte rendu.
     * @var Enseignant
     */
    private Enseignant $enseignant;

    /**
     * Le compte rendu remis.
     * @var CompteRendu
     */
    private CompteRendu $compteRendu;

    /**
     * @var string|null La date de remise.
     */
    private ?string $dateRendu; // DDL spécifie DATE

    /**
     * @param Enseignant $enseignant
     * @param CompteRendu $compteRendu
     * @param string|null $dateRendu
     */
    public function __construct(
        Enseignant $enseignant,
        CompteRendu $compteRendu,
        ?string $dateRendu
    ) {
        $this->enseignant = $enseignant;
        $this->compteRendu = $compteRendu;
        $this->dateRendu = $dateRendu;
    }

    /**
     * @return Enseignant
     */
    public function getEnseignant(): Enseignant
    {
        return $this->enseignant;
    }

    /**
     * @param Enseignant $enseignant
     */
    public function setEnseignant(Enseignant $enseignant): void
    {
        $this->enseignant = $enseignant;
    }

    /**
     * @return CompteRendu
     */
    public function getCompteRendu(): CompteRendu
    {
        return $this->compteRendu;
    }

    /**
     * @param CompteRendu $compteRendu
     */
    public function setCompteRendu(CompteRendu $compteRendu): void
    {
        $this->compteRendu = $compteRendu;
    }

    /**
     * @return string|null
     */
    public function getDateRendu(): ?string
    {
        return $this->dateRendu;
    }

    /**
     * @param string|null $dateRendu
     */
    public function setDateRendu(?string $dateRendu): void
    {
        $this->dateRendu = $dateRendu;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
