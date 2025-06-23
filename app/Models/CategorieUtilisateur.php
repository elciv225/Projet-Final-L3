<?php

namespace App\Models;

/**
 * Class CategorieUtilisateur
 *
 * @package App\Models
 */
class CategorieUtilisateur
{
    /**
     * @var string
     */
    protected string $table = 'categorie_utilisateur';

    /**
     * @var string L'ID de la catégorie utilisateur.
     */
    private string $id;

    /**
     * @var string Le libellé de la catégorie utilisateur.
     */
    private string $libelle;

    /**
     * @param string $id
     * @param string $libelle
     */
    public function __construct(string $id, string $libelle)
    {
        $this->id = $id;
        $this->libelle = $libelle;
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
    public function getTable(): string
    {
        return $this->table;
    }
}
