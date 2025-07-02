<?php

namespace App\Dao;

use App\Models\Ecue; // Assurez-vous que le modèle Ecue existe
use PDO;

class EcueDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'ecue', Ecue::class, 'id');
    }

    /**
     * Récupère tous les ECUEs avec le libellé de l'UE associée.
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "
            SELECT ecue.*, ue.libelle as ue_libelle
            FROM ecue
            LEFT JOIN ue ON ecue.ue_id = ue.id
            ORDER BY ecue.libelle;
        ";
        return $this->executerSelect($sql);
    }
}
