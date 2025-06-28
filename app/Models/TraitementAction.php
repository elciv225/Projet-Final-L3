<?php

namespace App\Models;

/**
 * Class TraitementAction
 *
 * Représente l'association entre un traitement et une action,
 * définissant l'ordre d'exécution des actions au sein d'un traitement.
 *
 * @package App\Models
 */
class TraitementAction
{
    /**
     * Nom de la table correspondante en base de données.
     * @var string
     */
    protected string $table = 'traitement_action';

    /**
     * L'ID du traitement (Clé étrangère, partie de la clé primaire composée).
     * @var string
     */
    private string $traitementId;

    /**
     * L'ID de l'action (Clé étrangère, partie de la clé primaire composée).
     * @var string
     */
    private string $actionId;

    /**
     * L'ordre d'exécution de l'action dans le traitement.
     * Peut être null si l'ordre n'est pas pertinent.
     * @var int|null
     */
    private ?int $ordre;

    /**
     * Constructeur de la classe TraitementAction.
     *
     * @param string $traitementId L'identifiant du traitement.
     * @param string $actionId L'identifiant de l'action.
     * @param int|null $ordre L'ordre d'exécution de l'action (optionnel).
     */
    public function __construct(string $traitementId, string $actionId, ?int $ordre)
    {
        $this->traitementId = $traitementId;
        $this->actionId = $actionId;
        $this->ordre = $ordre;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
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

    // TODO: Ajouter les méthodes pour récupérer les objets Traitement et Action associés si nécessaire
    // public function traitement(): Traitement { /* ... */ }
    // public function action(): Action { /* ... */ }
}
