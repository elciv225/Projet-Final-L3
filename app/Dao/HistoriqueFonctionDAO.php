<?php

namespace App\Dao;

use PDO;
use App\Models\HistoriqueFonction;

class HistoriqueFonctionDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // Note: La table historique_fonction a une clé primaire composite (utilisateur_id, fonction_id, date_occupation)
        // DAO.php est conçu pour une clé primaire simple 'id'. Il faudra adapter ou ne pas utiliser les méthodes
        // generiques comme supprimer() ou recupererParId() si elles se basent sur 'id'.
        // Pour l'instant, on met 'utilisateur_id' comme placeholder, mais ce n'est pas correct pour une clé primaire.
        parent::__construct($pdo, 'historique_fonction', HistoriqueFonction::class, 'utilisateur_id');
    }

    /**
     * Récupère l'historique des fonctions pour un utilisateur donné, avec le libellé de la fonction.
     * @param string $utilisateurId
     * @return array
     */
    public function recupererAvecDetailsPourUtilisateur(string $utilisateurId): array
    {
        $sql = "
            SELECT hf.fonction_id, f.libelle as fonction_libelle, hf.date_occupation
            FROM historique_fonction hf
            JOIN fonction f ON hf.fonction_id = f.id
            WHERE hf.utilisateur_id = :utilisateur_id
            ORDER BY hf.date_occupation DESC;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une nouvelle entrée dans l'historique des fonctions.
     * @param string $utilisateurId
     * @param string $fonctionId
     * @param string $dateOccupation
     * @return bool
     */
    public function ajouterHistorique(string $utilisateurId, string $fonctionId, string $dateOccupation): bool
    {
        $sql = "INSERT INTO historique_fonction (utilisateur_id, fonction_id, date_occupation)
                VALUES (:utilisateur_id, :fonction_id, :date_occupation)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':fonction_id' => $fonctionId,
            ':date_occupation' => $dateOccupation
        ]);
    }

    /**
     * Supprime une entrée spécifique de l'historique des fonctions.
     * @param string $utilisateurId
     * @param string $fonctionId
     * @param string $dateOccupation
     * @return bool
     */
    public function supprimerHistorique(string $utilisateurId, string $fonctionId, string $dateOccupation): bool
    {
        $sql = "DELETE FROM historique_fonction
                WHERE utilisateur_id = :utilisateur_id
                  AND fonction_id = :fonction_id
                  AND date_occupation = :date_occupation";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateurId,
            ':fonction_id' => $fonctionId,
            ':date_occupation' => $dateOccupation
        ]);
    }
}