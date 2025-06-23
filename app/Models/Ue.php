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
    private ?int $credit; // DDL spécifie TINYINT UNSIGNED

    /**
     * @var string L'ID de l'ECUE à laquelle cette UE appartient (FK).
     */
    private string $ecueId; // DDL spécifie NOT NULL

    /**
     * @param string $id
     * @param string $libelle
     * @param int|null $credit
     * @param string $ecueId
     */
    public function __construct(string $id, string $libelle, ?int $credit, string $ecueId)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->credit = $credit;
        $this->ecueId = $ecueId;
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
    public function getEcueId(): string
    {
        return $this->ecueId;
    }

    /**
     * @param string $ecueId
     */
    public function setEcueId(string $ecueId): void
    {
        $this->ecueId = $ecueId;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
