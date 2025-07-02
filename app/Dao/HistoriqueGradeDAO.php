<?php

namespace App\Dao;

use PDO;
use App\Models\HistoriqueGrade;

class HistoriqueGradeDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // Clé primaire composite (utilisateur_id, grade_id, date_grade)
        parent::__construct($pdo, 'historique_grade', HistoriqueGrade::class, 'utilisateur_id');
    }

    /**
     * Récupère l'historique des grades pour un utilisateur donné, avec le libellé du grade.
     * @param string $utilisateurId
     * @return array
     */
    public function recupererAvecDetailsPourUtilisateur(string $utilisateurId): array
    {
        $sql = "
            SELECT hg.grade_id, g.libelle as grade_libelle, hg.date_grade
            FROM historique_grade hg
            JOIN grade g ON hg.grade_id = g.id
            WHERE hg.utilisateur_id = :utilisateur_id
            ORDER BY hg.date_grade DESC;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une nouvelle entrée dans l'historique des grades.
     * @param string $utilisateurId
     * @param string $gradeId
     * @param string $dateGrade
     * @return bool
     */
    public function ajouterHistorique(string $utilisateurId, string $gradeId, string $dateGrade): bool
    {
        $sql = "INSERT INTO historique_grade (utilisateur_id, grade_id, date_grade)
                VALUES (:utilisateur_id, :grade_id, :date_grade)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':grade_id' => $gradeId,
            ':date_grade' => $dateGrade
        ]);
    }

    /**
     * Supprime une entrée spécifique de l'historique des grades.
     * @param string $utilisateurId
     * @param string $gradeId
     * @param string $dateGrade
     * @return bool
     */
    public function supprimerHistorique(string $utilisateurId, string $gradeId, string $dateGrade): bool
    {
        $sql = "DELETE FROM historique_grade
                WHERE utilisateur_id = :utilisateur_id
                  AND grade_id = :grade_id
                  AND date_grade = :date_grade";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':grade_id' => $gradeId,
            ':date_grade' => $dateGrade
        ]);
    }
}