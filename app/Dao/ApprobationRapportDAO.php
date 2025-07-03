<?php

namespace App\Dao;

use PDO;
use App\Models\ApprobationRapport;

class ApprobationRapportDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'approbation_rapport', ApprobationRapport::class, 'utilisateur_id');
    }

    /**
     * Approuve un rapport par un membre du personnel administratif
     * @param string $utilisateurId
     * @param string $rapportId
     * @param string $dateApprobation
     * @return bool
     */
    public function approuverRapport(string $utilisateurId, string $rapportId, string $dateApprobation): bool
    {
        $sql = "INSERT INTO approbation_rapport (utilisateur_id, rapport_etudiant_id, date_approbation) 
                VALUES (:utilisateur_id, :rapport_etudiant_id, :date_approbation)";
        
        return $this->executerRequeteAction($sql, [
            'utilisateur_id' => $utilisateurId,
            'rapport_etudiant_id' => $rapportId,
            'date_approbation' => $dateApprobation
        ]) > 0;
    }

    /**
     * Vérifie si un rapport a déjà été approuvé
     * @param string $rapportId
     * @return bool
     */
    public function estDejaApprouve(string $rapportId): bool
    {
        $sql = "SELECT COUNT(*) as nb FROM approbation_rapport WHERE rapport_etudiant_id = :rapport_id";
        $result = $this->executerSelect($sql, ['rapport_id' => $rapportId]);
        
        return isset($result[0]['nb']) && $result[0]['nb'] > 0;
    }

    /**
     * Récupère les informations d'approbation pour un rapport
     * @param string $rapportId
     * @return array|null
     */
    public function getInfosApprobation(string $rapportId): ?array
    {
        $sql = "SELECT ar.*, u.nom, u.prenoms, u.email 
                FROM approbation_rapport ar
                JOIN utilisateur u ON ar.utilisateur_id = u.id
                WHERE ar.rapport_etudiant_id = :rapport_id";
        
        $result = $this->executerSelect($sql, ['rapport_id' => $rapportId]);
        
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Récupère tous les rapports approuvés avec les informations des administrateurs et des rapports
     * @return array
     */
    public function getAllApprobationsWithInfo(): array
    {
        $sql = "SELECT ar.*, u.nom, u.prenoms, u.email, re.titre, re.date_rapport, re.lien_rapport
                FROM approbation_rapport ar
                JOIN utilisateur u ON ar.utilisateur_id = u.id
                JOIN rapport_etudiant re ON ar.rapport_etudiant_id = re.id
                ORDER BY ar.date_approbation DESC";
        
        return $this->executerSelect($sql);
    }
}