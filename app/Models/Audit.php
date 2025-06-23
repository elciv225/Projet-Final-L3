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
    private ?string $description; // DDL spécifie TEXT

    /**
     * @var string|null Date et heure de l'action.
     */
    private ?string $dateAction; // DDL spécifie DATETIME

    /**
     * @var string L'ID de l'action (FK).
     */
    private string $actionId;

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
     * @param string|null $dateAction
     * @param string $actionId
     * @param string $traitementId
     * @param string $utilisateurId
     */
    public function __construct(?int $id, ?string $description, ?string $dateAction, string $actionId, string $traitementId, string $utilisateurId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->dateAction = $dateAction;
        $this->actionId = $actionId;
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
    public function getDateAction(): ?string
    {
        return $this->dateAction;
    }

    /**
     * @param string|null $dateAction
     */
    public function setDateAction(?string $dateAction): void
    {
        $this->dateAction = $dateAction;
    }

    /**
     * @return string
     */
    public function getActionId(): string
    {
        return $this->actionId;
    }

    /**
     * @param string $actionId
     */
    public function setActionId(string $actionId): void
    {
        $this->actionId = $actionId;
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
