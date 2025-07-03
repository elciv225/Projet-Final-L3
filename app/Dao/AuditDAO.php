<?php

namespace App\Dao;

use PDO;
use App\Models\Audit;

class AuditDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'audit', Audit::class);
    }

    /**
     * Récupère tous les audits avec les informations détaillées
     * @param array $filtres Filtres optionnels (type, utilisateur_id, date_debut, date_fin)
     * @return array
     */
    public function recupererTousAvecDetails(array $filtres = []): array
    {
        $sql = "SELECT 
                    a.id, 
                    a.description,
                    a.date_traitement,
                    a.traitement_id,
                    t.libelle AS traitement_libelle,
                    a.utilisateur_id,
                    CONCAT(u.nom, ' ', u.prenoms) AS utilisateur_nom,
                    MAX(aa.heure_execution) AS derniere_action,
                    SUM(CASE WHEN aa.statut = 'SUCCES' THEN 1 ELSE 0 END) AS actions_succes,
                    SUM(CASE WHEN aa.statut = 'ECHEC' THEN 1 ELSE 0 END) AS actions_echec
                FROM audit a
                JOIN traitement t ON a.traitement_id = t.id
                JOIN utilisateur u ON a.utilisateur_id = u.id
                LEFT JOIN audit_action aa ON aa.audit_id = a.id
                WHERE 1=1";

        $params = [];

        // Appliquer les filtres
        if (!empty($filtres['type'])) {
            $sql .= " AND a.traitement_id = :type";
            $params['type'] = $filtres['type'];
        }

        if (!empty($filtres['utilisateur_id'])) {
            $sql .= " AND a.utilisateur_id = :utilisateur_id";
            $params['utilisateur_id'] = $filtres['utilisateur_id'];
        }

        if (!empty($filtres['date_debut'])) {
            $sql .= " AND a.date_traitement >= :date_debut";
            $params['date_debut'] = $filtres['date_debut'] . ' 00:00:00';
        }

        if (!empty($filtres['date_fin'])) {
            $sql .= " AND a.date_traitement <= :date_fin";
            $params['date_fin'] = $filtres['date_fin'] . ' 23:59:59';
        }

        $sql .= " GROUP BY a.id, a.description, a.date_traitement, a.traitement_id, t.libelle, a.utilisateur_id, CONCAT(u.nom, ' ', u.prenoms)";
        $sql .= " ORDER BY a.date_traitement DESC";

        return $this->executerSelect($sql, $params);
    }

    /**
     * Récupère tous les utilisateurs qui ont des audits
     * @return array
     */
    public function recupererUtilisateursAudit(): array
    {
        $sql = "SELECT DISTINCT u.id, CONCAT(u.nom, ' ', u.prenoms) AS nom_complet
                FROM audit a
                JOIN utilisateur u ON a.utilisateur_id = u.id
                ORDER BY u.nom, u.prenoms";

        return $this->executerSelect($sql);
    }

    /**
     * Récupère tous les types de traitement qui ont des audits
     * @return array
     */
    public function recupererTypesTraitement(): array
    {
        $sql = "SELECT DISTINCT t.id, t.libelle
                FROM audit a
                JOIN traitement t ON a.traitement_id = t.id
                ORDER BY t.libelle";

        return $this->executerSelect($sql);
    }
}
