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
     * La catégorie utilisateur à laquelle ce type appartient.
     * @var CategorieUtilisateur
     */
    private CategorieUtilisateur $categorieUtilisateur;

    /**
     * @param string $id
     * @param string $libelle
     * @param CategorieUtilisateur $categorieUtilisateur
     */
    public function __construct(
        string $id,
        string $libelle,
        CategorieUtilisateur $categorieUtilisateur
    ) {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->categorieUtilisateur = $categorieUtilisateur;
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
     * @return CategorieUtilisateur
     */
    public function getCategorieUtilisateur(): CategorieUtilisateur
    {
        return $this->categorieUtilisateur;
    }

    /**
     * @param CategorieUtilisateur $categorieUtilisateur
     */
    public function setCategorieUtilisateur(CategorieUtilisateur $categorieUtilisateur): void
    {
        $this->categorieUtilisateur = $categorieUtilisateur;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
