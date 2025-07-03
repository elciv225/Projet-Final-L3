<?php

namespace App\Dao;

use PDO;
use App\Models\HistoriqueApprobation;

class HistoriqueApprobationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'historique_approbation', HistoriqueApprobation::class, 'niveau_approbation_id');
    }

    /**
     * Récupère tous les historiques d'approbation avec les informations détaillées
     * @return array
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "SELECT 
                    ha.niveau_approbation_id, 
                    na.libelle AS niveau_approbation_libelle,
                    ha.compte_rendu_id,
                    cr.titre AS compte_rendu_titre,
                    ha.date_approbation
                FROM historique_approbation ha
                JOIN niveau_approbation na ON ha.niveau_approbation_id = na.id
                JOIN compte_rendu cr ON ha.compte_rendu_id = cr.id
                ORDER BY ha.date_approbation DESC";
        
        return $this->executerSelect($sql);
    }

    /**
     * Récupère tous les niveaux d'approbation disponibles
     * @return array
     */
    public function recupererNiveauxApprobation(): array
    {
        $sql = "SELECT id, libelle FROM niveau_approbation ORDER BY libelle";
        return $this->executerSelect($sql);
    }

    /**
     * Récupère tous les comptes rendus disponibles
     * @return array
     */
    public function recupererComptesRendus(): array
    {
        $sql = "SELECT id, titre, date_rapport FROM compte_rendu ORDER BY date_rapport DESC";
        return $this->executerSelect($sql);
    }
}