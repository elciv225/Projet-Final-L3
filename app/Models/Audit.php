<?php

namespace App\Models;

/**
 * Class Audit
 *
 * @package App\Models
 */
class Audit
{
    /**
     * @var string
     */
    protected string $table = 'audit';

    /**
     * @var int|null L'ID de l'entrée d'audit (auto-incrémenté).
     */
    private ?int $id;

    /**
     * @var string|null Description de l'audit.
     */
    private ?string $description; // DDL spécifie VARCHAR(255)

    /**
     * @var string|null Date et heure du traitement.
     */
    private ?string $dateTraitement; // DDL spécifie TIMESTAMP

    /**
     * @var string L'ID du traitement (FK).
     */
    private string $traitementId;

    /**
     * @var string L'ID de l'utilisateur (FK).
     */
    private string $utilisateurId;


    /**
     * @param int|null $id
     * @param string|null $description
     * @param string|null $dateTraitement
     * @param string $traitementId
     * @param string $utilisateurId
     */
    public function __construct(?int $id, ?string $description, ?string $dateTraitement, string $traitementId, string $utilisateurId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->dateTraitement = $dateTraitement;
        $this->traitementId = $traitementId;
        $this->utilisateurId = $utilisateurId;
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDateTraitement(): ?string
    {
        return $this->dateTraitement;
    }

    /**
     * @param string|null $dateTraitement
     */
    public function setDateTraitement(?string $dateTraitement): void
    {
        $this->dateTraitement = $dateTraitement;
    }

    /**
     * @return string
     */
    public function getTraitementId(): string
    {
        return $this->traitementId;
    }

    /**
     * @param string $traitementId
     */
    public function setTraitementId(string $traitementId): void
    {
        $this->traitementId = $traitementId;
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
    public function getTable(): string
    {
        return $this->table;
    }
}
