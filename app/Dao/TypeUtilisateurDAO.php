<?php

namespace App\Dao;

use App\Models\TypeUtilisateur;
use PDO;

class TypeUtilisateurDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'type_utilisateur', TypeUtilisateur::class);
    }

    /**
     * Récupère tous les types d'utilisateurs appartenant à une catégorie spécifique.
     *
     * @param string $categorieId L'ID de la catégorie (ex: 'CAT_ADMIN').
     * @return array La liste des objets TypeUtilisateur.
     */
    public function recupererParCategorie(string $categorieId): array
    {
        $sql = "SELECT * FROM type_utilisateur WHERE categorie_utilisateur_id = :categorieId ORDER BY libelle";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':categorieId', $categorieId, PDO::PARAM_STR);
        $stmt->execute();

        // CORRECTION: Utilisation directe de la classe au lieu d'une propriété inexistante.
        return $stmt->fetchAll(PDO::FETCH_CLASS, TypeUtilisateur::class);
    }
}
