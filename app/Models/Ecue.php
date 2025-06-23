<?php

namespace App\Models;

/**
 * Class Ecue
 *
 * @package App\Models
 */
class Ecue
{
    /**
     * @var string
     */
    protected string $table = 'ecue';

    /**
     * @var string L'ID de l'ECUE.
     */
    private string $id;

    /**
     * @var string Le libellé de l'ECUE.
     */
    private string $libelle;

    /**
     * @var int|null Le nombre de crédits pour l'ECUE.
     */
    private ?int $credit; // DDL spécifie TINYINT UNSIGNED

    /**
     * @param string $id
     * @param string $libelle
     * @param int|null $credit
     */
    public function __construct(string $id, string $libelle, ?int $credit)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->credit = $credit;
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
     * @return int|null
     */
    public function getCredit(): ?int
    {
        return $this->credit;
    }

    /**
     * @param int|null $credit
     */
    public function setCredit(?int $credit): void
    {
        $this->credit = $credit;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
