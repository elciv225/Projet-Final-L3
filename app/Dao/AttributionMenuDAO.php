<?php

namespace App\Dao;

use PDO;
use System\Database\Database;

class AttributionMenuDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les menus avec les traitements et actions qui leur sont associés.
     * @return array
     */
    public function recupererMenusAvecActions(): array
    {
        $sql = "
            SELECT 
                m.id as menu_id,
                m.libelle as menu_libelle,
                t.id as traitement_id,
                t.libelle as traitement_libelle,
                a.id as action_id,
                a.libelle as action_libelle
            FROM menu m
            JOIN menu_traitement mt ON m.id = mt.menu_id
            JOIN traitement t ON mt.traitement_id = t.id
            JOIN traitement_action ta ON t.id = ta.traitement_id
            JOIN action a ON ta.action_id = a.id
            ORDER BY m.libelle, t.libelle, a.libelle;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Récupère les permissions (actions autorisées) pour un groupe d'utilisateurs spécifique.
     * @param string $groupeId
     * @return array Un tableau des actions autorisées.
     */
    public function recupererPermissionsParGroupe(string $groupeId): array
    {
        $sql = "SELECT traitement_id, action_id FROM autorisation_action WHERE groupe_utilisateur_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$groupeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les menus auxquels un groupe d'utilisateurs a accès.
     * @param string $groupeId
     * @return array Un tableau des menus accessibles.
     */
    public function recupererMenusParGroupe(string $groupeId): array
    {
        $sql = "
            SELECT DISTINCT
                cm.id as categorie_id,
                cm.libelle as categorie_libelle,
                m.id as menu_id,
                m.libelle as menu_libelle,
                m.vue as menu_vue
            FROM autorisation_action aa
            JOIN traitement_action ta ON aa.traitement_id = ta.traitement_id AND aa.action_id = ta.action_id
            JOIN menu_traitement mt ON ta.traitement_id = mt.traitement_id
            JOIN menu m ON mt.menu_id = m.id
            JOIN categorie_menu cm ON m.categorie_menu_id = cm.id
            WHERE aa.groupe_utilisateur_id = ?
            ORDER BY cm.libelle, m.libelle
        ";

        return $this->executerSelect($sql, [$groupeId]);
    }

    /**
     * Récupère tous les menus disponibles dans la base de données.
     * @return array Un tableau de tous les menus.
     */
    public function recupererTousLesMenus(): array
    {
        $sql = "
            SELECT DISTINCT
                cm.id as categorie_id,
                cm.libelle as categorie_libelle,
                m.id as menu_id,
                m.libelle as menu_libelle,
                m.vue as menu_vue
            FROM menu m
            JOIN categorie_menu cm ON m.categorie_menu_id = cm.id
            ORDER BY cm.libelle, m.libelle
        ";

        return $this->executerSelect($sql);
    }

    /**
     * Met à jour les permissions pour un groupe d'utilisateurs donné.
     * @param string $groupeId
     * @param array $permissions Un tableau associatif des permissions.
     * @return bool
     */
    public function mettreAJourPermissions(string $groupeId, array $permissions): bool
    {
        $this->pdo->beginTransaction();
        try {
            // 1. Supprimer toutes les anciennes permissions pour ce groupe
            $stmtDelete = $this->pdo->prepare("DELETE FROM autorisation_action WHERE groupe_utilisateur_id = ?");
            $stmtDelete->execute([$groupeId]);

            // 2. Insérer les nouvelles permissions
            $stmtInsert = $this->pdo->prepare(
                "INSERT INTO autorisation_action (groupe_utilisateur_id, traitement_id, action_id) VALUES (?, ?, ?)"
            );

            foreach ($permissions as $traitementId => $actions) {
                foreach ($actions as $actionId => $isAllowed) {
                    if ($isAllowed) {
                        $stmtInsert->execute([$groupeId, $traitementId, $actionId]);
                    }
                }
            }

            $this->pdo->commit();
            return true;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
