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
     * L'objet Traitement associé.
     * @var Traitement
     */
    private Traitement $traitement;

    /**
     * L'objet Action associé.
     * @var Action
     */
    private Action $action;

    /**
     * L'ordre d'exécution de l'action dans le traitement.
     * Peut être null si l'ordre n'est pas pertinent.
     * @var int|null
     */
    private ?int $ordre;

    /**
     * Constructeur de la classe TraitementAction.
     *
     * @param Traitement $traitement L'objet Traitement.
     * @param Action $action L'objet Action.
     * @param int|null $ordre L'ordre d'exécution de l'action (optionnel).
     */
    public function __construct(Traitement $traitement, Action $action, ?int $ordre)
    {
        $this->traitement = $traitement;
        $this->action = $action;
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
}
