<?php

namespace App\Models;

/**
 * Class AuditAction
 *
 * Représente le log d'une action spécifique exécutée dans le cadre d'un audit.
 *
 * @package App\Models
 */
class AuditAction
{
    /**
     * Nom de la table correspondante en base de données.
     * @var string
     */
    protected string $table = 'audit_action';

    /**
     * L'identifiant unique de l'enregistrement d'audit d'action (Clé primaire auto-incrémentée).
     * @var int|null
     */
    private ?int $id;

    /**
     * L'ID de l'enregistrement d'audit parent auquel cette action est liée (Clé étrangère).
     * @var int
     */
    private int $auditId;

    /**
     * L'ID de l'action qui a été exécutée (Clé étrangère).
     * @var string
     */
    private string $actionId;

    /**
     * L'ordre d'exécution de l'action, si applicable.
     * @var int|null
     */
    private ?int $ordre;

    /**
     * L'heure à laquelle l'action a été exécutée.
     * Format: Timestamp (par exemple, YYYY-MM-DD HH:MM:SS).
     * @var string|null
     */
    private ?string $heureExecution;

    /**
     * Le statut de l'exécution de l'action ('SUCCES' ou 'ECHEC').
     * @var string
     */
    private string $statut;

    /**
     * Un message optionnel fournissant plus de détails sur l'exécution de l'action.
     * @var string|null
     */
    private ?string $message;

    /**
     * Constructeur de la classe AuditAction.
     *
     * @param int $auditId L'ID de l'audit parent.
     * @param string $actionId L'ID de l'action exécutée.
     * @param string $statut Le statut de l'exécution ('SUCCES', 'ECHEC').
     * @param int|null $ordre L'ordre d'exécution (optionnel).
     * @param string|null $heureExecution L'heure d'exécution (optionnel, défaut à maintenant si non fourni par la DB).
     * @param string|null $message Message additionnel (optionnel).
     * @param int|null $id L'ID de l'enregistrement (optionnel, pour instanciation depuis la DB).
     */
    public function __construct(
        int $auditId,
        string $actionId,
        string $statut,
        ?int $ordre = null,
        ?string $heureExecution = null,
        ?string $message = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->auditId = $auditId;
        $this->actionId = $actionId;
        $this->statut = $statut;
        $this->ordre = $ordre;
        $this->heureExecution = $heureExecution;
        $this->message = $message;
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
     * @return int
     */
    public function getAuditId(): int
    {
        return $this->auditId;
    }

    /**
     * @param int $auditId
     */
    public function setAuditId(int $auditId): void
    {
        $this->auditId = $auditId;
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
     * @return int|null
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    /**
     * @param int|null $ordre
     */
    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }

    /**
     * @return string|null
     */
    public function getHeureExecution(): ?string
    {
        return $this->heureExecution;
    }

    /**
     * @param string|null $heureExecution
     */
    public function setHeureExecution(?string $heureExecution): void
    {
        $this->heureExecution = $heureExecution;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut Doit être 'SUCCES' ou 'ECHEC'.
     */
    public function setStatut(string $statut): void
    {
        // On pourrait ajouter une validation ici si nécessaire
        $this->statut = $statut;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    // TODO: Ajouter les méthodes pour récupérer les objets Audit et Action associés si nécessaire
    // public function audit(): Audit { /* ... */ }
    // public function action(): Action { /* ... */ }
}
