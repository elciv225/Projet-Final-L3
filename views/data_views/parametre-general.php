<?php
// Variables available:
// $parametre_label (e.g., "Niveau Etude")
// $parametre_type (e.g., "niveau_etude") - This is the actual key for the parameter
// $liste_donnees (array of objects for the current parameter type)
// $categories_menu (optional, if $parametre_type is 'menu')

$item_id_val = ''; // For pre-filling in edit mode - to be implemented fully
$libelle_val = '';
$vue_val = ''; // For menu
$categorie_menu_id_val = ''; // For menu

// TODO: Add logic here if an item is being edited to pre-fill $item_id_val, $libelle_val, etc.
// For example, if $this->request->getQueryParam('id_item_a_modifier') is set.
// $itemToEdit = $daoAppropriate->recupererParId($this->request->getQueryParam('id_item_a_modifier'));
// if($itemToEdit) { $libelle_val = $itemToEdit->getLibelle(); ... }
?>

<form class="form-section ajax-form" method="post" action="/parametres-generaux/executer-action" data-target="#parametre-<?= htmlspecialchars($parametre_type ?? 'unk') ?>-table-container">
    <input type="hidden" name="operation" value="ajouter"> <!-- Value changes to 'modifier' with JS if editing -->
    <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type ?? '') ?>">
    <input type="hidden" name="id_item" value="<?= $item_id_val ?>"> <!-- For modification -->

    <div class="section-header">
        <h3 class="section-title">Ajouter/Modifier <?= htmlspecialchars($parametre_label ?? "Paramètre") ?></h3>
    </div>
    <div class="section-content">
        <div class="form-grid">
            <div class="form-group">
                <input type="text" id="param_libelle" name="libelle" class="form-input" placeholder=" " value="<?= $libelle_val ?>" required>
                <label for="param_libelle" class="form-label">Libellé <?= htmlspecialchars($parametre_label ?? '') ?></label>
            </div>

            <?php if (isset($parametre_type) && $parametre_type === 'menu'): ?>
                <div class="form-group">
                    <input type="text" id="param_vue" name="vue" class="form-input" placeholder=" " value="<?= $vue_val ?>" required>
                    <label for="param_vue" class="form-label">Chemin Vue</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="param_categorie_menu_id" name="categorie_menu_id" required>
                        <option value="">Sélectionnez Catégorie Menu</option>
                        <?php if (isset($categories_menu) && !empty($categories_menu)): ?>
                            <?php foreach ($categories_menu as $catMenu): ?>
                                <option value="<?= htmlspecialchars($catMenu->getId()) ?>" <?= ($categorie_menu_id_val == $catMenu->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($catMenu->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="param_categorie_menu_id">Catégorie de Menu</label>
                </div>
            <?php endif; ?>

            <!-- Add other specific fields for other parametre_types if necessary -->

        </div>
    </div>
    <div class="section-bottom">
        <h3 class="section-title">Action</h3>
        <div style="display: flex">
            <button class="btn btn-primary" type="submit" id="param_submit_button">Ajouter <?= htmlspecialchars($parametre_label ?? '') ?></button>
        </div>
    </div>
</form>

<div class="table-container" id="parametre-<?= htmlspecialchars($parametre_type ?? 'unk') ?>-table-container">
    <div class="table-header">
        <h3 class="table-title">Liste des <?= htmlspecialchars($parametre_label ?? "Paramètres") ?></h3>
        <div class="header-actions">
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="paramSearchInput" class="search-input" placeholder="Rechercher...">
            </div>
            <!-- Action buttons for table items (edit, delete) would typically be per row -->
        </div>
    </div>

    <div class="table-scroll-wrapper scroll-custom">
        <table class="table">
            <thead>
            <tr>
                <th><input type="checkbox" class="checkbox master-checkbox-param"></th>
                <th>ID</th>
                <th>Libellé</th>
                <?php if (isset($parametre_type) && $parametre_type === 'menu'): ?>
                    <th>Vue</th>
                    <th>Catégorie Menu ID</th>
                <?php endif; ?>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($liste_donnees) && !empty($liste_donnees)): ?>
                <?php foreach ($liste_donnees as $item): ?>
                    <tr>
                        <td><input type="checkbox" class="checkbox item-checkbox-param" value="<?= htmlspecialchars($item->getId()) ?>"></td>
                        <td><?= htmlspecialchars($item->getId()) ?></td>
                        <td><?= htmlspecialchars(method_exists($item, 'getLibelle') ? $item->getLibelle() : (property_exists($item, 'libelle') ? $item->libelle : 'N/A')) ?></td>

                        <?php if (isset($parametre_type) && $parametre_type === 'menu'): ?>
                            <td><?= htmlspecialchars(method_exists($item, 'getVue') ? $item->getVue() : 'N/A') ?></td>
                            <td><?= htmlspecialchars(method_exists($item, 'getCategorieMenuId') ? $item->getCategorieMenuId() : 'N/A') ?></td>
                        <?php endif; ?>

                        <td>
                            <button class="btn btn-action btn-edit-param" data-id="<?= htmlspecialchars($item->getId()) ?>">✏️</button>
                            <button class="btn btn-action btn-delete-param" data-id="<?= htmlspecialchars($item->getId()) ?>">🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= (isset($parametre_type) && $parametre_type === 'menu') ? '5' : '4' ?>" class="text-center">Aucun enregistrement trouvé.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <div class="results-info">
            Affichage de <?= count($liste_donnees ?? []) ?> entrée(s).
        </div>
        <!-- Pagination (if needed) would go here -->
    </div>
</div>

<script>
// Basic JS for edit/delete button interaction (example)
document.querySelectorAll('.btn-edit-param').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.dataset.id;
        // TODO: Populate form with item data for editing
        // Example: document.getElementById('param_libelle').value = ... (fetch item data via AJAX or use data attributes)
        document.querySelector('input[name="operation"]').value = 'modifier';
        document.querySelector('input[name="id_item"]').value = itemId;
        document.getElementById('param_submit_button').textContent = 'Modifier <?= htmlspecialchars($parametre_label ?? '') ?>';
        // Scroll to form or highlight it
    });
});

document.querySelectorAll('.btn-delete-param').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.dataset.id;
        if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            // TODO: Submit deletion form via AJAX
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '/parametres-generaux/executer-action'; // Same action URL
            form.classList.add('ajax-form');
            form.dataset.target = '#parametre-<?= htmlspecialchars($parametre_type ?? 'unk') ?>-table-container';

            const operationInput = document.createElement('input');
            operationInput.type = 'hidden';
            operationInput.name = 'operation';
            operationInput.value = 'supprimer';
            form.appendChild(operationInput);

            const typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'parametre_type';
            typeInput.value = '<?= htmlspecialchars($parametre_type ?? '') ?>';
            form.appendChild(typeInput);

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id'; // Controller expects 'id' for suppression
            idInput.value = itemId;
            form.appendChild(idInput);

            document.body.appendChild(form);
            form.submit(); // This will be an AJAX submit due to class 'ajax-form' if global AJAX handling is set up
        }
    });
});
</script>