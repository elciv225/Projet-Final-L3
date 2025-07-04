<?php

namespace App\Dao;

use PDO;

class HistoriquePersonnelDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère la liste des enseignants
     * @return array
     */
    public function recupererEnseignants(): array
    {
        $sql = "SELECT u.id as utilisateur_id, CONCAT(u.nom, ' ', u.prenoms) as 'nom-prenom' 
                FROM utilisateur u 
                JOIN enseignant e ON u.id = e.utilisateur_id 
                ORDER BY u.nom, u.prenoms";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère la liste du personnel administratif
     * @return array
     */
    public function recupererPersonnelAdministratif(): array
    {
        $sql = "SELECT u.id as utilisateur_id, CONCAT(u.nom, ' ', u.prenoms) as 'nom-prenom' 
                FROM utilisateur u 
                JOIN personnel_administratif pa ON u.id = pa.utilisateur_id 
                ORDER BY u.nom, u.prenoms";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère l'historique des fonctions d'un utilisateur avec pagination
     * @param string $utilisateurId
     * @param int $page Numéro de page (commence à 1)
     * @param int $itemsPerPage Nombre d'éléments par page
     * @return array
     */
    public function recupererHistoriqueFonctions(string $utilisateurId, int $page = 1, int $itemsPerPage = 10): array
    {
        // Calculer l'offset pour la pagination
        $offset = ($page - 1) * $itemsPerPage;

        $sql = "SELECT f.libelle as fonction, hf.date_occupation as date_debut, 
                LEAD(hf.date_occupation) OVER (PARTITION BY hf.utilisateur_id ORDER BY hf.date_occupation) as date_fin
                FROM historique_fonction hf
                JOIN fonction f ON hf.fonction_id = f.id
                WHERE hf.utilisateur_id = :utilisateur_id
                ORDER BY hf.date_occupation DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formater les dates et gérer la dernière fonction (actuelle)
        foreach ($result as &$row) {
            $row['date_debut'] = date('d/m/Y', strtotime($row['date_debut']));
            $row['date_fin'] = $row['date_fin'] ? date('d/m/Y', strtotime($row['date_fin'])) : 'Actuel';
        }

        return $result;
    }

    /**
     * Compte le nombre total d'entrées dans l'historique des fonctions d'un utilisateur
     * @param string $utilisateurId
     * @return int
     */
    public function compterHistoriqueFonctions(string $utilisateurId): int
    {
        $sql = "SELECT COUNT(*) FROM historique_fonction WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Récupère l'historique des grades d'un utilisateur avec pagination
     * @param string $utilisateurId
     * @param int $page Numéro de page (commence à 1)
     * @param int $itemsPerPage Nombre d'éléments par page
     * @return array
     */
    public function recupererHistoriqueGrades(string $utilisateurId, int $page = 1, int $itemsPerPage = 10): array
    {
        // Calculer l'offset pour la pagination
        $offset = ($page - 1) * $itemsPerPage;

        $sql = "SELECT g.libelle as grade, hg.date_grade as date_debut, 
                CONCAT('diplome_', LOWER(REPLACE(g.libelle, ' ', '_')), '.pdf') as document
                FROM historique_grade hg
                JOIN grade g ON hg.grade_id = g.id
                WHERE hg.utilisateur_id = :utilisateur_id
                ORDER BY hg.date_grade DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formater les dates
        foreach ($result as &$row) {
            $row['date_debut'] = date('d/m/Y', strtotime($row['date_debut']));
        }

        return $result;
    }

    /**
     * Compte le nombre total d'entrées dans l'historique des grades d'un utilisateur
     * @param string $utilisateurId
     * @return int
     */
    public function compterHistoriqueGrades(string $utilisateurId): int
    {
        $sql = "SELECT COUNT(*) FROM historique_grade WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
