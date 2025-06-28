<?php

namespace App\Models;

/**
 * Class Etudiant
 * Représente un étudiant, qui est un type d'utilisateur.
 * @package App\Models
 */
class Etudiant
{
    /**
     * @var string
     */
    protected string $table = 'etudiant'; // Référence la table de jointure/spécialisation

    /**
     * L'objet Utilisateur correspondant à cet étudiant.
     * @var Utilisateur
     */
    private Utilisateur $utilisateur;

    /**
     * @param Utilisateur $utilisateur L'objet Utilisateur de base.
     */
    public function __construct(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
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
     * Raccourci pour obtenir l'ID de l'utilisateur.
     * @return string
     */
    public function getUtilisateurId(): string
    {
        return $this->utilisateur->getId();
    }

    /**
     * Méthode pour accéder facilement aux propriétés de l'utilisateur sous-jacent.
     * Exemple: $etudiant->getNom() au lieu de $etudiant->getUtilisateur()->getNom()
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->utilisateur, $name)) {
            return call_user_func_array([$this->utilisateur, $name], $arguments);
        }
        trigger_error("Call to undefined method " . __CLASS__ . "::$name()", E_USER_ERROR);
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
