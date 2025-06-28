<?php

namespace App\Models;

/**
 * Class Ue
 *
 * @package App\Models
 */
class Ue
{
    /**
     * @var string
     */
    protected string $table = 'ue';

    /**
     * @var string L'ID de l'UE.
     */
    private string $id;

    /**
     * @var string Le libellé de l'UE.
     */
    private string $libelle;

    /**
     * @var int|null Le nombre de crédits pour l'UE.
     */
    private ?int $credit; // DDL SQL spécifie SMALLINT, le modèle PHP avait TINYINT UNSIGNED

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
