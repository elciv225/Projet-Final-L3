<?php

namespace App\Dao;

use App\Models\Ue; // Assurez-vous que le modèle Ue existe
use PDO;

class UeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'ue', Ue::class, 'id');
    }

    /**
     * Récupère toutes les UE avec le libellé du niveau d'étude associé.
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "
            SELECT ue.*, ne.libelle as niveau_etude_libelle
            FROM ue
            LEFT JOIN niveau_etude ne ON ue.niveau_etude_id = ne.id
            ORDER BY ue.libelle;
        ";
        return $this->executerSelect($sql);
    }

    public function compterTous(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM " . $this->nomTable);
        return (int) $stmt->fetchColumn();
    }
}
