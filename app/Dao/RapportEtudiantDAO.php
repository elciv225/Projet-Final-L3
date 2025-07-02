<?php

namespace App\Dao;

use App\Models\RapportEtudiant;
use PDO;

class RapportEtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'rapport_etudiant', RapportEtudiant::class, 'id');
    }

    /**
     * Récupère les rapports d'un étudiant spécifique.
     * @param string $etudiantId
     * @return array
     */
    public function recupererParEtudiant(string $etudiantId): array
    {
        // Cette requête suppose qu'il y a une table de liaison ou une colonne etudiant_id dans la table rapport_etudiant
        // Adapter la requête à votre schéma exact.
        // Exemple si depot_rapport lie utilisateur (etudiant) et rapport_etudiant:
        $sql = "
            SELECT r.*
            FROM rapport_etudiant r
            JOIN depot_rapport dr ON r.id = dr.rapport_etudiant_id
            WHERE dr.utilisateur_id = :etudiant_id
            ORDER BY r.date_rapport DESC;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':etudiant_id' => $etudiantId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->nomClasseModele);
    }
}
