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
     * @var string L'ID de l'enseignant (FK, partie de la CPK).
     */
    private string $utilisateurId;

    /**
     * @var string L'ID du compte rendu (FK, partie de la CPK).
     */
    private string $compteRenduId;

    /**
     * @var string|null La date de remise.
     */
    private ?string $dateRendu; // DDL spécifie DATE

    /**
     * @param string $utilisateurId
     * @param string $compteRenduId
     * @param string|null $dateRendu
     */
    public function __construct(string $utilisateurId, string $compteRenduId, ?string $dateRendu)
    {
        $this->utilisateurId = $utilisateurId;
        $this->compteRenduId = $compteRenduId;
        $this->dateRendu = $dateRendu;
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
    public function getCompteRenduId(): string
    {
        return $this->compteRenduId;
    }

    /**
     * @param string $compteRenduId
     */
    public function setCompteRenduId(string $compteRenduId): void
    {
        $this->compteRenduId = $compteRenduId;
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
