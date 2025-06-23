<?php

namespace App\Models;

/**
 * Class PersonnelAdministratif
 *
 * @package App\Models
 */
class PersonnelAdministratif
{
    /**
     * @var string
     */
    protected string $table = 'personnel_administratif';

    /**
     * @var string L'ID du personnel administratif (FK vers utilisateur.id).
     */
    private string $utilisateurId;

    /**
     * @param string $utilisateurId
     */
    public function __construct(string $utilisateurId)
    {
        $this->utilisateurId = $utilisateurId;
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
