<?php

namespace App\Models;

/**
 * Class Grade
 *
 * @package App\Models
 */
class Grade
{
    /**
     * @var string
     */
    protected string $table = 'grade';

    /**
     * @var string L'ID du grade.
     */
    private string $id;

    /**
     * @var string Le libellé du grade.
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
