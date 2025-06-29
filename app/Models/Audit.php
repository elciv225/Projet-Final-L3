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
     * Le traitement audité.
     * @var Traitement
     */
    private Traitement $traitement;

    /**
     * L'utilisateur ayant effectué le traitement.
     * @var Utilisateur
     */
    private Utilisateur $utilisateur;


    /**
     * @param Traitement $traitement
     * @param Utilisateur $utilisateur
     * @param string|null $description
     * @param string|null $dateTraitement
     * @param int|null $id
     */
    public function __construct(
        Traitement $traitement,
        Utilisateur $utilisateur,
        ?string $description,
        ?string $dateTraitement,
        ?int $id = null // L'ID est généralement défini après l'insertion
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->dateTraitement = $dateTraitement;
        $this->traitement = $traitement;
        $this->utilisateur = $utilisateur;
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
     * @return Traitement
     */
    public function getTraitement(): Traitement
    {
        return $this->traitement;
    }

    /**
     * @param Traitement $traitement
     */
    public function setTraitement(Traitement $traitement): void
    {
        $this->traitement = $traitement;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
