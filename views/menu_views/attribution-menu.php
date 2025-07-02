<?php
// Données passées par le contrôleur :
// $groupesUtilisateur (array d'objets GroupeUtilisateur)
// $menusParCategorie (array associatif de menus groupés par catégorie)
?>
<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Attribution des Menus aux Groupes</h1>
            <p>Définissez ici les menus accessibles pour chaque groupe d'utilisateurs et leurs permissions spécifiques.</p>
        </div>
    </div>

    <div class="container">
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">Configuration des Permissions de Groupe</h3>
            </div>
            <div class="section-content">
                <div class="form-group-inline" style="margin-bottom: 32px;">
                    <label for="userGroupSelect" class="form-label" style="margin-right: 10px;">Groupe utilisateur :</label>
                    <div class="select-wrapper">
                        <select id="userGroupSelect" class="form-input custom-select">
                            <option value="">Sélectionner un groupe...</option>
                            <?php if (isset($groupesUtilisateur)): ?>
                                <?php foreach ($groupesUtilisateur as $groupe): ?>
                                    <option value="<?= htmlspecialchars($groupe->getId()) ?>">
                                        <?= htmlspecialchars($groupe->getLibelle()) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="permissions-table-container">
                    <div class="total-select-header">
                        <span>Tout Sélectionner/Désélectionner</span>
                        <input type="checkbox" id="masterPermissionCheckbox" class="checkbox">
                    </div>
                    <table class="permissions-table">
                        <thead>
                            <tr>
                                <th>Menu / Catégorie</th>
                                <th>Peut Ajouter</th>
                                <th>Peut Modifier</th>
                                <th>Peut Supprimer</th>
                                <th>Peut Imprimer</th>
                            </tr>
                        </thead>
                        <tbody id="permissionsTableBody">
                            <!-- Message initial -->
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-disabled); padding: 40px;">
                                    Veuillez sélectionner un groupe pour charger et configurer les permissions des menus.
                                </td>
                            </tr>
                             <?php /* Boucle PHP pour l'affichage initial si nécessaire, ou laisser JS peupler
                            if (isset($menusParCategorie) && !empty($menusParCategorie)):
                                foreach ($menusParCategorie as $categorieId => $categorieData): ?>
                                    <tr class="category-row">
                                        <td colspan="5"><strong><?= htmlspecialchars($categorieData['libelle']) ?></strong></td>
                                    </tr>
                                    <?php foreach ($categorieData['menus'] as $menu):
                                        $menuId = htmlspecialchars($menu['id']);
                                        $permissions = $menu['permissions'] ?? ['peut_ajouter' => false, 'peut_modifier' => false, 'peut_supprimer' => false, 'peut_imprimer' => false];
                                    ?>
                                    <tr data-menu-id="<?= $menuId ?>">
                                        <td><?= htmlspecialchars($menu['libelle']) ?></td>
                                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_ajouter" <?= $permissions['peut_ajouter'] ? 'checked' : '' ?>></div></td>
                                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_modifier" <?= $permissions['peut_modifier'] ? 'checked' : '' ?>></div></td>
                                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_supprimer" <?= $permissions['peut_supprimer'] ? 'checked' : '' ?>></div></td>
                                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-permission-type="peut_imprimer" <?= $permissions['peut_imprimer'] ? 'checked' : '' ?>></div></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endforeach;
                            else: ?>
                                <tr><td colspan="5" style="text-align: center;">Aucun menu disponible.</td></tr>
                            <?php endif; */?>
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="section-bottom" style="margin-top: 20px;">
                <h3 class="section-title">Action</h3>
                <button class="btn btn-primary" id="savePermissionsButton">Enregistrer les Permissions</button>
            </div>
        </div>
    </div>
</main>

<script src="/assets/js/attribution-menu.js" defer></script>