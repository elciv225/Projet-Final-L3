<?php

namespace App\Models;

/**
 * Class Etudiant
 *
 * @package App\Models
 */
class Etudiant
{
    /**
     * @var string
     */
    protected string $table = 'etudiant';

    /**
     * @var string L'ID de l'étudiant (FK vers utilisateur.id).
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
