<?php

namespace App\Dao;

use PDO;
use App\Models\Menu; // Assuming Menu model exists

class MenuDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // Ensure 'menu' is the correct table name and 'id' its primary key.
        // The Menu::class should map to your App\Models\Menu class.
        parent::__construct($pdo, 'menu', Menu::class, 'id');
    }

    /**
     * Récupère tous les éléments de menu avec leur traitement_id associé.
     * Les menus sont ordonnés par parent_id puis par un champ 'ordre'.
     * S'il n'y a pas de champ parent_id ou ordre, ces clauses ORDER BY peuvent être ajustées/supprimées.
     *
     * @return array Un tableau associatif des menus, chaque menu incluant:
     *               menu_id, libelle, url, icon, parent_id, ordre, traitement_id (peut être null).
     */
    public function recupererTousLesMenusAvecTraitement(): array
    {
        // Assumed columns in 'menu' table: id, libelle, url, icon, parent_id, ordre
        // Assumed columns in 'menu_traitement' table: menu_id, traitement_id
        // If your column names are different, please adjust the query.
        $sql = "SELECT m.id AS menu_id, m.libelle, m.url, m.icon, m.parent_id, m.ordre, mt.traitement_id
                FROM {$this->table} m
                LEFT JOIN menu_traitement mt ON m.id = mt.menu_id
                ORDER BY m.parent_id ASC, m.ordre ASC"; // Adjust 'ordre' if your column is named differently or doesn't exist

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Returns an array of associative arrays. The MenuService will handle object mapping if needed.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * You can add other specific menu fetching methods here if needed, e.g.:
     *  - getMenuByIdWithTraitement(int $menuId)
     *  - getMenusByParentIdWithTraitement(int $parentId)
     *
     * For now, recupererTousLesMenusAvecTraitement() provides all necessary raw data
     * for the MenuService to build the hierarchical and permission-filtered menu.
     */
}