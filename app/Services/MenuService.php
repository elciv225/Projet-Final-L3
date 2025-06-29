<?php

namespace App\Services;

use App\Dao\MenuDAO;
use App\Dao\AutorisationActionDAO; // Assuming this DAO exists or will be created
use System\Database\Database;      // To get PDO instance for DAOs

class MenuService
{
    private MenuDAO $menuDAO;
    private AutorisationActionDAO $autorisationActionDAO;

    public function __construct()
    {
        // Consider Dependency Injection for DAOs
        $pdo = Database::getInstance();
        $this->menuDAO = new MenuDAO($pdo);
        $this->autorisationActionDAO = new AutorisationActionDAO($pdo); // Assuming AutorisationActionDAO exists
    }

    /**
     * Fetches and filters menu items for a given groupe_utilisateur_id.
     *
     * @param string $groupeUtilisateurId The ID of the user's group.
     * @return array A structured array of accessible menu items.
     */
    public function getMenuItemsForGroup(string $groupeUtilisateurId): array
    {
        $allMenusWithTraitement = $this->menuDAO->recupererTousLesMenusAvecTraitement();
        $accessibleMenus = [];

        // Step 1: Filter menus based on permissions for their associated traitement
        foreach ($allMenusWithTraitement as $menuItem) {
            if (empty($menuItem['traitement_id'])) {
                // Menu item has no specific traitement associated, consider it accessible by default
                // Or, apply a different rule (e.g., only for certain superadmin groups)
                // For now, let's assume such menu items are generally accessible if they have a URL
                if (!empty($menuItem['url'])) {
                    $accessibleMenus[$menuItem['menu_id']] = $menuItem;
                }
                continue;
            }

            // Check if the group has ANY permission for this traitement_id
            // The AutorisationActionDAO's 'rechercher' method can be used.
            // We just need to know if there's at least one entry.
            $permissions = $this->autorisationActionDAO->rechercher([
                'groupe_utilisateur_id' => $groupeUtilisateurId,
                'traitement_id' => $menuItem['traitement_id']
            ]);

            if (!empty($permissions)) {
                $accessibleMenus[$menuItem['menu_id']] = $menuItem;
            }
        }

        // Step 2: Build hierarchical structure if parent_id is used
        // And ensure that if a parent is not accessible, its children are also not shown,
        // or re-parent them if necessary (simpler to just hide).
        $structuredMenu = [];
        foreach ($accessibleMenus as $menuId => $menuItem) {
            // Ensure parent is also accessible if it's a child menu
            if ($menuItem['parent_id'] !== null && !isset($accessibleMenus[$menuItem['parent_id']])) {
                continue; // Skip this child if its parent is not accessible
            }

            if ($menuItem['parent_id'] === null) { // Root item
                $structuredMenu[$menuId] = $menuItem;
                $structuredMenu[$menuId]['children'] = [];
            } elseif (isset($structuredMenu[$menuItem['parent_id']])) { // Child of an already added root/parent
                 $structuredMenu[$menuItem['parent_id']]['children'][$menuId] = $menuItem;
                 $structuredMenu[$menuItem['parent_id']]['children'][$menuId]['children'] = []; // Prepare for grandchildren
            } else {
                // This handles multi-level children where parent might not be root
                // This part needs a more robust tree-building algorithm if deep nesting is common
                // For simplicity, assuming 1-2 levels for now or that parents are processed first due to ORDER BY
                // A better way is to build a flat list of accessible items, then treeify.
                // Let's refine the tree building:
            }
        }

        // Refined tree building:
        $finalMenu = [];
        $childrenMap = [];

        // First, map all children to their parents
        foreach ($accessibleMenus as $menuId => $menuItem) {
            // Skip if parent is defined but not accessible (already handled conceptually above, but good to be explicit)
            if ($menuItem['parent_id'] !== null && !isset($accessibleMenus[$menuItem['parent_id']])) {
                continue;
            }
            $menuItem['children'] = []; // Initialize children array
            if ($menuItem['parent_id'] !== null) {
                if (!isset($childrenMap[$menuItem['parent_id']])) {
                    $childrenMap[$menuItem['parent_id']] = [];
                }
                $childrenMap[$menuItem['parent_id']][] = $menuItem;
            } else {
                $finalMenu[$menuItem['menu_id']] = $menuItem;
            }
        }

        // Assign children to their parents
        foreach ($finalMenu as $menuId => &$menuItem) { // Pass $menuItem by reference
            if (isset($childrenMap[$menuId])) {
                $menuItem['children'] = $this->assignChildrenRecursive($childrenMap[$menuId], $childrenMap, $accessibleMenus);
            }
        }
        unset($menuItem); // Unset reference

        // Sort root menu items by 'ordre'
        uasort($finalMenu, function ($a, $b) {
            return ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0);
        });

        return $finalMenu;
    }

    /**
     * Helper function to recursively assign children to menu items.
     */
    private function assignChildrenRecursive(array &$parentChildren, array &$allChildrenMap, array &$accessibleMenus): array
    {
        $sortedChildren = [];
        foreach ($parentChildren as $child) {
            // Ensure child itself is in the accessible list (should be, but good check)
            if (!isset($accessibleMenus[$child['menu_id']])) continue;

            if (isset($allChildrenMap[$child['menu_id']])) {
                $child['children'] = $this->assignChildrenRecursive($allChildrenMap[$child['menu_id']], $allChildrenMap, $accessibleMenus);
            }
            $sortedChildren[$child['menu_id']] = $child;
        }

        uasort($sortedChildren, function ($a, $b) {
            return ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0);
        });
        return $sortedChildren;
    }
}
