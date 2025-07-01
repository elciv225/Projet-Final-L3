<?php

namespace App\Dao;

use PDO;
use App\Models\Utilisateur;

class UtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'utilisateur', Utilisateur::class, 'id');
    }

    /**
     * Récupère tous les utilisateurs avec les libellés de leur type et groupe.
     * @return array
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "
            SELECT u.id, u.nom, u.prenoms, u.email, tu.libelle as type_user, gu.libelle as groupe
            FROM utilisateur u
            LEFT JOIN type_utilisateur tu ON u.type_utilisateur_id = tu.id
            LEFT JOIN groupe_utilisateur gu ON u.groupe_utilisateur_id = gu.id
            ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Supprime un utilisateur uniquement s'il n'est pas référencé dans les tables enfants.
     * @param string $id L'ID de l'utilisateur à supprimer.
     * @return bool True si la suppression a réussi (1 ligne affectée), false sinon.
     */
    public function supprimer(string $id): bool
    {
        $sql = "
            DELETE FROM utilisateur 
            WHERE id = :id AND id NOT IN (
                SELECT utilisateur_id FROM etudiant WHERE utilisateur_id = :id
                UNION ALL
                SELECT utilisateur_id FROM enseignant WHERE utilisateur_id = :id
                UNION ALL
                SELECT utilisateur_id FROM personnel_administratif WHERE utilisateur_id = :id
            )
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}