<?php

namespace App\Models;

/**
 * Class AutorisationAction
 *
 * @package App\Models
 */
class AutorisationAction
{
    /**
     * @var string
     */
    protected string $table = 'autorisation_action';

    /**
     * Le groupe d'utilisateur concerné par l'autorisation.
     * @var GroupeUtilisateur
     */
    private GroupeUtilisateur $groupeUtilisateur;

    /**
     * Le traitement concerné par l'autorisation.
     * @var Traitement
     */
    private Traitement $traitement;

    /**
     * L'action spécifique autorisée pour le groupe sur le traitement.
     * @var Action
     */
    private Action $action;

    /**
     * @param GroupeUtilisateur $groupeUtilisateur
     * @param Traitement $traitement
     * @param Action $action
     */
    public function __construct(
        GroupeUtilisateur $groupeUtilisateur,
        Traitement $traitement,
        Action $action
    ) {
        $this->groupeUtilisateur = $groupeUtilisateur;
        $this->traitement = $traitement;
        $this->action = $action;
    }

    /**
     * @return GroupeUtilisateur
     */
    public function getGroupeUtilisateur(): GroupeUtilisateur
    {
        return $this->groupeUtilisateur;
    }

    /**
     * @param GroupeUtilisateur $groupeUtilisateur
     */
    public function setGroupeUtilisateur(GroupeUtilisateur $groupeUtilisateur): void
    {
        $this->groupeUtilisateur = $groupeUtilisateur;
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
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
