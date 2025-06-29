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
     * L'enregistrement d'audit parent auquel cette action est liée.
     * @var Audit
     */
    private Audit $audit;

    /**
     * L'action qui a été exécutée.
     * @var Action
     */
    private Action $action;

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
     * @param Audit $audit L'audit parent.
     * @param Action $action L'action exécutée.
     * @param string $statut Le statut de l'exécution ('SUCCES', 'ECHEC').
     * @param int|null $ordre L'ordre d'exécution (optionnel).
     * @param string|null $heureExecution L'heure d'exécution (optionnel, défaut à maintenant si non fourni par la DB).
     * @param string|null $message Message additionnel (optionnel).
     * @param int|null $id L'ID de l'enregistrement (optionnel, pour instanciation depuis la DB).
     */
    public function __construct(
        Audit $audit,
        Action $action,
        string $statut,
        ?int $ordre = null,
        ?string $heureExecution = null,
        ?string $message = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->audit = $audit;
        $this->action = $action;
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
     * @return Audit
     */
    public function getAudit(): Audit
    {
        return $this->audit;
    }

    /**
     * @param Audit $audit
     */
    public function setAudit(Audit $audit): void
    {
        $this->audit = $audit;
    }

    /**
     * @return Action
     */
    public function getAction(): Action
    {
        return $this->action;
    }

    /**
     * @param Action $action
     */
    public function setAction(Action $action): void
    {
        $this->action = $action;
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
}
