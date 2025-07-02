<?php

namespace App\Dao;

use App\Models\HistoriqueSpecialite;
use PDO;

class HistoriqueSpecialiteDAO extends DAO
{

    public function __construct(PDO $pdo)
    {
        // Clé primaire composite (utilisateur_id, specialite_id, date_occupation)
        parent::__construct($pdo, 'historique_specialite', HistoriqueSpecialite::class, 'utilisateur_id');
    }

    /**
     * Récupère l'historique des spécialités pour un utilisateur donné, avec le libellé de la spécialité.
     * @param string $utilisateurId
     * @return array
     */
    public function recupererAvecDetailsPourUtilisateur(string $utilisateurId): array
    {
        $sql = "
            SELECT hs.specialite_id, s.libelle as specialite_libelle, hs.date_specialite as date_occupation -- Correction du nom de colonne date_specialite
            FROM historique_specialite hs
            JOIN specialite s ON hs.specialite_id = s.id
            WHERE hs.utilisateur_id = :utilisateur_id
            ORDER BY hs.date_specialite DESC; -- Correction du nom de colonne date_specialite
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une nouvelle entrée dans l'historique des spécialités.
     * @param string $utilisateurId
     * @param string $specialiteId
     * @param string $dateOccupation -- Nom de colonne corrigé en date_specialite
     * @return bool
     */
    public function ajouterHistorique(string $utilisateurId, string $specialiteId, string $dateSpecialite): bool
    {
        $sql = "INSERT INTO historique_specialite (utilisateur_id, specialite_id, date_specialite)
                VALUES (:utilisateur_id, :specialite_id, :date_specialite)"; // Correction du nom de colonne
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':specialite_id' => $specialiteId,
            ':date_specialite' => $dateSpecialite // Correction du nom de colonne
        ]);
    }

    /**
     * Supprime une entrée spécifique de l'historique des spécialités.
     * @param string $utilisateurId
     * @param string $specialiteId
     * @param string $dateOccupation -- Nom de colonne corrigé en date_specialite
     * @return bool
     */
    public function supprimerHistorique(string $utilisateurId, string $specialiteId, string $dateSpecialite): bool
    {
        $sql = "DELETE FROM historique_specialite
                WHERE utilisateur_id = :utilisateur_id
                  AND specialite_id = :specialite_id
                  AND date_specialite = :date_specialite"; // Correction du nom de colonne
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':specialite_id' => $specialiteId,
            ':date_specialite' => $dateSpecialite // Correction du nom de colonne
        ]);
    }
}