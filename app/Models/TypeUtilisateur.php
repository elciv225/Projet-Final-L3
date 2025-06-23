<?php

namespace App\Models;

/**
 * Class TypeUtilisateur
 *
 * @package App\Models
 */
class TypeUtilisateur
{
    /**
     * @var string
     */
    protected string $table = 'type_utilisateur';

    /**
     * @var string L'ID du type d'utilisateur.
     */
    private string $id;

    /**
     * @var string Le libellé du type d'utilisateur.
     */
    private string $libelle;

    /**
     * @var string L'ID de la catégorie utilisateur à laquelle ce type appartient (FK).
     */
    private string $categorieUtilisateurId; // DDL spécifie NOT NULL

    /**
     * @param string $id
     * @param string $libelle
     * @param string $categorieUtilisateurId
     */
    public function __construct(string $id, string $libelle, string $categorieUtilisateurId)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->categorieUtilisateurId = $categorieUtilisateurId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getCategorieUtilisateurId(): string
    {
        return $this->categorieUtilisateurId;
    }

    /**
     * @param string $categorieUtilisateurId
     */
    public function setCategorieUtilisateurId(string $categorieUtilisateurId): void
    {
        $this->categorieUtilisateurId = $categorieUtilisateurId;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
