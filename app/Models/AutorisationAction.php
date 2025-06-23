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
     * @var string L'ID du groupe utilisateur (FK, partie de la CPK).
     */
    private string $groupeUtilisateurId;

    /**
     * @var string L'ID du traitement (FK, partie de la CPK).
     */
    private string $traitementId;

    /**
     * @var string L'ID de l'action (FK, partie de la CPK).
     */
    private string $actionId;

    /**
     * @param string $groupeUtilisateurId
     * @param string $traitementId
     * @param string $actionId
     */
    public function __construct(string $groupeUtilisateurId, string $traitementId, string $actionId)
    {
        $this->groupeUtilisateurId = $groupeUtilisateurId;
        $this->traitementId = $traitementId;
        $this->actionId = $actionId;
    }

    /**
     * @return string
     */
    public function getGroupeUtilisateurId(): string
    {
        return $this->groupeUtilisateurId;
    }

    /**
     * @param string $groupeUtilisateurId
     */
    public function setGroupeUtilisateurId(string $groupeUtilisateurId): void
    {
        $this->groupeUtilisateurId = $groupeUtilisateurId;
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
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
