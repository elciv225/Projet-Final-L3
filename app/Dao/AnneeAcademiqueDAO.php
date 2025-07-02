<?php

namespace App\Dao;

use App\Models\AnneeAcademique;
use PDO;
class AnneeAcademiqueDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'annee_academique', AnneeAcademique::class, 'id');
    }

    /**
     * Récupère toutes les années académiques triées par ID décroissant (les plus récentes en premier).
     * @return array
     */
    public function recupererTousTriesParIdDesc(): array
    {
        $sql = "SELECT * FROM {$this->nomTable} ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->nomClasseModele);
    }

    public function compterTous(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM " . $this->nomTable);
        return (int) $stmt->fetchColumn();
    }
}