<?php

namespace App\Dao;

use PDO;
use App\Models\Evaluation;

class EvaluationDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'evaluation', Evaluation::class, 'enseignant_id');
    }

    /**
     * Récupère toutes les évaluations avec les informations détaillées
     * @return array
     */
    public function recupererToutesAvecDetails(): array
    {
        $sql = "SELECT 
                    e.enseignant_id,
                    CONCAT(ens.nom, ' ', ens.prenoms) AS enseignant_nom,
                    e.etudiant_id,
                    CONCAT(etu.nom, ' ', etu.prenoms) AS etudiant_nom,
                    e.ecue_id,
                    ec.libelle AS ecue_libelle,
                    e.date_evaluation,
                    e.note
                FROM evaluation e
                JOIN utilisateur ens ON e.enseignant_id = ens.id
                JOIN utilisateur etu ON e.etudiant_id = etu.id
                JOIN ecue ec ON e.ecue_id = ec.id
                ORDER BY e.date_evaluation DESC";
        
        return $this->executerSelect($sql);
    }

    /**
     * Récupère tous les enseignants disponibles
     * @return array
     */
    public function recupererEnseignants(): array
    {
        $sql = "SELECT u.id, CONCAT(u.nom, ' ', u.prenoms) AS nom_complet
                FROM utilisateur u
                JOIN enseignant ens ON u.id = ens.utilisateur_id
                ORDER BY u.nom, u.prenoms";
        
        return $this->executerSelect($sql);
    }

    /**
     * Récupère tous les étudiants disponibles
     * @return array
     */
    public function recupererEtudiants(): array
    {
        $sql = "SELECT u.id, CONCAT(u.nom, ' ', u.prenoms) AS nom_complet, e.numero_carte
                FROM utilisateur u
                JOIN etudiant e ON u.id = e.utilisateur_id
                ORDER BY u.nom, u.prenoms";
        
        return $this->executerSelect($sql);
    }

    /**
     * Récupère tous les ECUEs disponibles
     * @return array
     */
    public function recupererEcues(): array
    {
        $sql = "SELECT e.id, e.libelle, e.credit, u.libelle AS ue_libelle
                FROM ecue e
                JOIN ue u ON e.ue_id = u.id
                ORDER BY u.libelle, e.libelle";
        
        return $this->executerSelect($sql);
    }

    /**
     * Ajoute une nouvelle évaluation
     * @param array $data Les données de l'évaluation
     * @return bool
     */
    public function ajouterEvaluation(array $data): bool
    {
        $sql = "INSERT INTO evaluation (enseignant_id, etudiant_id, ecue_id, date_evaluation, note)
                VALUES (:enseignant_id, :etudiant_id, :ecue_id, :date_evaluation, :note)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}