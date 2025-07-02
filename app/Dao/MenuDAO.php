<?php

namespace App\Dao;

use App\Models\Menu;
use PDO;

class MenuDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'menu', Menu::class, 'id');
    }

    /**
     * Récupère tous les menus avec les détails de leur catégorie.
     * @return array
     */
    public function recupererTousAvecDetails(): array
    {
        $sql = "
            SELECT
                m.id,
                m.libelle,
                m.vue,
                m.categorie_menu_id,
                cm.libelle as categorie_menu_libelle
            FROM menu m
            LEFT JOIN categorie_menu cm ON m.categorie_menu_id = cm.id
            ORDER BY cm.libelle, m.libelle;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Récupère les menus autorisés pour un groupe d'utilisateurs spécifique.
     * @param string $groupeUtilisateurId
     * @return array
     */
    public function recupererMenusParGroupe(string $groupeUtilisateurId): array
    {
        $sql = "
            SELECT m.id, m.libelle, m.vue, m.categorie_menu_id, cm.libelle as categorie_menu_libelle,
                   gm.peut_ajouter, gm.peut_modifier, gm.peut_supprimer, gm.peut_imprimer
            FROM menu m
            JOIN groupe_menu gm ON m.id = gm.menu_id
            LEFT JOIN categorie_menu cm ON m.categorie_menu_id = cm.id
            WHERE gm.groupe_utilisateur_id = :groupe_id
            ORDER BY cm.libelle, m.libelle;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':groupe_id', $groupeUtilisateurId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les permissions pour un menu et un groupe donnés.
     * @param string $menuId
     * @param string $groupeUtilisateurId
     * @param array $permissions (ex: ['peut_ajouter' => true, 'peut_modifier' => false, ...])
     * @return bool
     */
    public function mettreAJourPermissions(string $menuId, string $groupeUtilisateurId, array $permissions): bool
    {
        // Vérifier si l'entrée existe déjà
        $sqlCheck = "SELECT COUNT(*) FROM groupe_menu WHERE menu_id = :menu_id AND groupe_utilisateur_id = :groupe_id";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([':menu_id' => $menuId, ':groupe_id' => $groupeUtilisateurId]);
        $exists = $stmtCheck->fetchColumn() > 0;

        if ($exists) {
            $sql = "
                UPDATE groupe_menu SET
                    peut_ajouter = :peut_ajouter,
                    peut_modifier = :peut_modifier,
                    peut_supprimer = :peut_supprimer,
                    peut_imprimer = :peut_imprimer
                WHERE menu_id = :menu_id AND groupe_utilisateur_id = :groupe_id;
            ";
        } else {
            $sql = "
                INSERT INTO groupe_menu (menu_id, groupe_utilisateur_id, peut_ajouter, peut_modifier, peut_supprimer, peut_imprimer)
                VALUES (:menu_id, :groupe_id, :peut_ajouter, :peut_modifier, :peut_supprimer, :peut_imprimer);
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':menu_id' => $menuId,
            ':groupe_id' => $groupeUtilisateurId,
            ':peut_ajouter' => (bool)($permissions['peut_ajouter'] ?? false),
            ':peut_modifier' => (bool)($permissions['peut_modifier'] ?? false),
            ':peut_supprimer' => (bool)($permissions['peut_supprimer'] ?? false),
            ':peut_imprimer' => (bool)($permissions['peut_imprimer'] ?? false),
        ]);
    }
}
