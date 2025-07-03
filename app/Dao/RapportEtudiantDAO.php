<?php

namespace App\Dao;

use PDO;
use App\Models\RapportEtudiant;

class RapportEtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'rapport_etudiant', RapportEtudiant::class);
    }

    /**
     * Récupère les rapports en attente de validation
     * @return array
     */
    public function getRapportsEnAttente(): array
    {
        $sql = "SELECT re.* FROM rapport_etudiant re
                JOIN depot_rapport dr ON re.id = dr.rapport_etudiant_id
                LEFT JOIN validation_rapport vr ON re.id = vr.rapport_etudiant_id
                WHERE vr.rapport_etudiant_id IS NULL";
        
        $rows = $this->executerSelect($sql);
        $rapports = [];
        
        foreach ($rows as $row) {
            $rapports[] = $this->hydrater($row);
        }
        
        return $rapports;
    }

    /**
     * Récupère les rapports validés mais pas encore approuvés
     * @return array
     */
    public function getRapportsValides(): array
    {
        $sql = "SELECT re.* FROM rapport_etudiant re
                JOIN validation_rapport vr ON re.id = vr.rapport_etudiant_id
                LEFT JOIN approbation_rapport ar ON re.id = ar.rapport_etudiant_id
                WHERE ar.rapport_etudiant_id IS NULL";
        
        $rows = $this->executerSelect($sql);
        $rapports = [];
        
        foreach ($rows as $row) {
            $rapports[] = $this->hydrater($row);
        }
        
        return $rapports;
    }

    /**
     * Récupère les rapports approuvés mais sans encadrants assignés
     * @return array
     */
    public function getRapportsApprouves(): array
    {
        $sql = "SELECT re.* FROM rapport_etudiant re
                JOIN approbation_rapport ar ON re.id = ar.rapport_etudiant_id
                LEFT JOIN affectation_encadrant ae ON re.id = ae.rapport_etudiant_id
                WHERE ae.rapport_etudiant_id IS NULL";
        
        $rows = $this->executerSelect($sql);
        $rapports = [];
        
        foreach ($rows as $row) {
            $rapports[] = $this->hydrater($row);
        }
        
        return $rapports;
    }

    /**
     * Récupère le rapport d'un étudiant spécifique
     * @param string $etudiantId
     * @return object|null
     */
    public function getRapportEtudiant(string $etudiantId): ?object
    {
        $sql = "SELECT re.* FROM rapport_etudiant re
                JOIN depot_rapport dr ON re.id = dr.rapport_etudiant_id
                WHERE dr.utilisateur_id = :etudiantId
                ORDER BY dr.date_depot DESC
                LIMIT 1";
        
        $rows = $this->executerSelect($sql, ['etudiantId' => $etudiantId]);
        
        if (empty($rows)) {
            return null;
        }
        
        return $this->hydrater($rows[0]);
    }

    /**
     * Récupère le statut complet d'un rapport
     * @param string $rapportId
     * @return array
     */
    public function getStatutRapport(string $rapportId): array
    {
        $sql = "SELECT 
                    CASE 
                        WHEN ae.rapport_etudiant_id IS NOT NULL THEN 'encadrants_assignes'
                        WHEN ar.rapport_etudiant_id IS NOT NULL THEN 'approuve'
                        WHEN vr.rapport_etudiant_id IS NOT NULL THEN 'valide'
                        WHEN dr.rapport_etudiant_id IS NOT NULL THEN 'depose'
                        ELSE 'inconnu'
                    END as statut,
                    dr.date_depot,
                    vr.date_validation,
                    ar.date_approbation,
                    ae.date_affectation
                FROM rapport_etudiant re
                LEFT JOIN depot_rapport dr ON re.id = dr.rapport_etudiant_id
                LEFT JOIN validation_rapport vr ON re.id = vr.rapport_etudiant_id
                LEFT JOIN approbation_rapport ar ON re.id = ar.rapport_etudiant_id
                LEFT JOIN affectation_encadrant ae ON re.id = ae.rapport_etudiant_id
                WHERE re.id = :rapportId";
        
        $rows = $this->executerSelect($sql, ['rapportId' => $rapportId]);
        
        if (empty($rows)) {
            return ['statut' => 'inconnu'];
        }
        
        return $rows[0];
    }
}