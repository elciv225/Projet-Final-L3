<?php

namespace App\Dao;

use PDO;

class ConfirmationRapportDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère les rapports avec pagination et filtrage
     * @param int $page Numéro de la page (commence à 1)
     * @param int $limit Nombre d'éléments par page
     * @param array $filtres Critères de filtrage (statut, etudiant_id, date_debut, date_fin)
     * @return array
     */
    public function getRapportsWithPagination(int $page = 1, int $limit = 10, array $filtres = []): array
    {
        // Calculer l'offset pour la pagination
        $offset = ($page - 1) * $limit;
        
        // Construire la requête de base
        $sql = "SELECT 
                    re.id as rapport_id,
                    re.titre,
                    u.id as etudiant_id,
                    CONCAT(u.nom, ' ', u.prenoms) as etudiant_nom,
                    dr.date_depot,
                    CASE 
                        WHEN ae.rapport_etudiant_id IS NOT NULL THEN 'encadrants_assignes'
                        WHEN ar.rapport_etudiant_id IS NOT NULL THEN 'approuve'
                        WHEN vr.rapport_etudiant_id IS NOT NULL THEN 'valide'
                        WHEN dr.rapport_etudiant_id IS NOT NULL THEN 'depose'
                        ELSE 'inconnu'
                    END as statut
                FROM rapport_etudiant re
                JOIN depot_rapport dr ON re.id = dr.rapport_etudiant_id
                JOIN utilisateur u ON dr.utilisateur_id = u.id
                LEFT JOIN validation_rapport vr ON re.id = vr.rapport_etudiant_id
                LEFT JOIN approbation_rapport ar ON re.id = ar.rapport_etudiant_id
                LEFT JOIN affectation_encadrant ae ON re.id = ae.rapport_etudiant_id
                WHERE 1=1";
        
        $params = [];
        
        // Ajouter les filtres si présents
        if (!empty($filtres['statut'])) {
            switch ($filtres['statut']) {
                case 'depose':
                    $sql .= " AND vr.rapport_etudiant_id IS NULL";
                    break;
                case 'valide':
                    $sql .= " AND vr.rapport_etudiant_id IS NOT NULL AND ar.rapport_etudiant_id IS NULL";
                    break;
                case 'approuve':
                    $sql .= " AND ar.rapport_etudiant_id IS NOT NULL AND ae.rapport_etudiant_id IS NULL";
                    break;
                case 'encadrants_assignes':
                    $sql .= " AND ae.rapport_etudiant_id IS NOT NULL";
                    break;
            }
        }
        
        if (!empty($filtres['etudiant_id'])) {
            $sql .= " AND u.id = :etudiant_id";
            $params['etudiant_id'] = $filtres['etudiant_id'];
        }
        
        if (!empty($filtres['date_debut'])) {
            $sql .= " AND dr.date_depot >= :date_debut";
            $params['date_debut'] = $filtres['date_debut'];
        }
        
        if (!empty($filtres['date_fin'])) {
            $sql .= " AND dr.date_depot <= :date_fin";
            $params['date_fin'] = $filtres['date_fin'];
        }
        
        // Ajouter l'ordre et la pagination
        $sql .= " ORDER BY dr.date_depot DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        // Exécuter la requête
        $stmt = $this->pdo->prepare($sql);
        
        // Binder les paramètres
        foreach ($params as $key => $value) {
            if ($key == 'limit' || $key == 'offset') {
                $stmt->bindValue(':' . $key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':' . $key, $value);
            }
        }
        
        $stmt->execute();
        $rapports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer le nombre total de rapports pour la pagination
        $countSql = "SELECT COUNT(*) as total FROM rapport_etudiant re
                    JOIN depot_rapport dr ON re.id = dr.rapport_etudiant_id
                    JOIN utilisateur u ON dr.utilisateur_id = u.id
                    LEFT JOIN validation_rapport vr ON re.id = vr.rapport_etudiant_id
                    LEFT JOIN approbation_rapport ar ON re.id = ar.rapport_etudiant_id
                    LEFT JOIN affectation_encadrant ae ON re.id = ae.rapport_etudiant_id
                    WHERE 1=1";
        
        // Ajouter les mêmes filtres à la requête de comptage
        if (!empty($filtres['statut'])) {
            switch ($filtres['statut']) {
                case 'depose':
                    $countSql .= " AND vr.rapport_etudiant_id IS NULL";
                    break;
                case 'valide':
                    $countSql .= " AND vr.rapport_etudiant_id IS NOT NULL AND ar.rapport_etudiant_id IS NULL";
                    break;
                case 'approuve':
                    $countSql .= " AND ar.rapport_etudiant_id IS NOT NULL AND ae.rapport_etudiant_id IS NULL";
                    break;
                case 'encadrants_assignes':
                    $countSql .= " AND ae.rapport_etudiant_id IS NOT NULL";
                    break;
            }
        }
        
        if (!empty($filtres['etudiant_id'])) {
            $countSql .= " AND u.id = :etudiant_id";
        }
        
        if (!empty($filtres['date_debut'])) {
            $countSql .= " AND dr.date_depot >= :date_debut";
        }
        
        if (!empty($filtres['date_fin'])) {
            $countSql .= " AND dr.date_depot <= :date_fin";
        }
        
        $countStmt = $this->pdo->prepare($countSql);
        
        // Binder les paramètres pour la requête de comptage
        foreach ($params as $key => $value) {
            if ($key != 'limit' && $key != 'offset') {
                $countStmt->bindValue(':' . $key, $value);
            }
        }
        
        $countStmt->execute();
        $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return [
            'rapports' => $rapports,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($totalCount / $limit)
        ];
    }

    /**
     * Récupère la liste des étudiants ayant déposé un rapport
     * @return array
     */
    public function getEtudiantsAvecRapport(): array
    {
        $sql = "SELECT DISTINCT u.id, CONCAT(u.nom, ' ', u.prenoms) as nom_complet
                FROM utilisateur u
                JOIN depot_rapport dr ON u.id = dr.utilisateur_id
                ORDER BY u.nom, u.prenoms";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}