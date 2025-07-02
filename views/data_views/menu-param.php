<?php
// Données passées :
// $parametre_libelle ("Menus")
// $parametre_type ("menu")
// $elements (array des menus existants, avec détails de catégorie)
// $categoriesMenu (array des catégories de menu pour le select)
// $message_succes (optionnel)

// Récupérer les détails pour chaque menu (y compris le libellé de la catégorie)
// Normalement, $elements contient déjà ces détails si recupererTousAvecDetails() a été appelé.
?>

<div class="form-section table-container parametre-general-section" id="container-<?= htmlspecialchars($parametre_type) ?>">
    <?php if (isset($message_succes)): ?>
        <div class="alert alert-succes" role="alert">
            <?= htmlspecialchars($message_succes) ?>
        </div>
    <?php endif; ?>

    <div class="section-header">
        <h3 class="section-title">Gestion des <?= htmlspecialchars($parametre_libelle) ?></h3>
    </div>

    <!-- Formulaire d'ajout/modification pour les Menus -->
    <form action="/traitement-parametre-general" method="post" class="ajax-form form-parametre-general" data-target="#container-<?= htmlspecialchars($parametre_type) ?>">
        <input type="hidden" name="operation" value="ajouter">
        <input type="hidden" name="id" value=""> <!-- Pour la modification -->
        <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">

        <div class="form-grid"> <!-- Utiliser form-grid pour plus de champs -->
            <div class="form-group">
                <input type="text" name="libelle" id="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required>
                <label for="libelle-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Libellé du Menu</label>
            </div>
            <div class="form-group">
                <input type="text" name="vue" id="vue-<?= htmlspecialchars($parametre_type) ?>" class="form-input" placeholder=" " required>
                <label for="vue-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Nom de la Vue (ex: etudiants.php)</label>
            </div>
            <div class="form-group">
                <select name="categorie_menu_id" id="categorie-<?= htmlspecialchars($parametre_type) ?>" class="form-input" required>
                    <option value="" disabled selected>Choisir une catégorie...</option>
                    <?php if (isset($categoriesMenu)): foreach ($categoriesMenu as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie->getId()) ?>"><?= htmlspecialchars($categorie->getLibelle()) ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <label for="categorie-<?= htmlspecialchars($parametre_type) ?>" class="form-label">Catégorie du Menu</label>
            </div>
        </div>
        <div class="form-actions" style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary btn-cancel-param" style="display: none;">Annuler</button>
        </div>
    </form>

    <!-- Tableau des Menus existants -->
    <?php $tableId = 'table-param-' . htmlspecialchars($parametre_type); ?>
    <div class="table-header" style="margin-top: 20px;">
        <h4 class="table-title">Liste des <?= htmlspecialchars($parametre_libelle) ?> existants</h4>
         <div class="header-actions"> <!-- Ajout du conteneur header-actions -->
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput-<?= $tableId ?>" class="search-input search-input-param" placeholder="Rechercher...">
            </div>
            <form id="delete-form-<?= $tableId ?>" class="form-delete-selected-param ajax-form" method="post" action="/traitement-parametre-general" data-target="#container-<?= htmlspecialchars($parametre_type) ?>">
                <input type="hidden" name="operation" value="supprimer">
                <input type="hidden" name="parametre_type" value="<?= htmlspecialchars($parametre_type) ?>">
                <div id="hidden-inputs-for-delete-<?= $tableId ?>"></div>
                <button type="submit" class="btn btn-danger btn-delete-selected-param" disabled>Supprimer la sélection</button>
            </form>
        </div>
    </div>
    <div class="table-scroll-wrapper scroll-custom">
        <table class="table table-parametre-general" id="<?= $tableId ?>" data-parametre-type="<?= htmlspecialchars($parametre_type) ?>">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll-<?= $tableId ?>" class="checkbox select-all-param"></th>
                    <th>ID Menu</th>
                    <th>Libellé</th>
                    <th>Vue</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($elements)): ?>
                    <?php foreach ($elements as $menu): // $elements contient les menus avec détails de catégorie ?>
                        <tr data-id="<?= htmlspecialchars($menu['id']) ?>"
                            data-libelle="<?= htmlspecialchars($menu['libelle']) ?>"
                            data-vue="<?= htmlspecialchars($menu['vue']) ?>"
                            data-categorie_menu_id="<?= htmlspecialchars($menu['categorie_menu_id']) ?>">
                            <td><input type="checkbox" class="checkbox select-param-item" name="ids[]" value="<?= htmlspecialchars($menu['id']) ?>"></td>
                            <td><?= htmlspecialchars($menu['id']) ?></td>
                            <td><?= htmlspecialchars($menu['libelle']) ?></td>
                            <td><?= htmlspecialchars($menu['vue']) ?></td>
                            <td><?= htmlspecialchars($menu['categorie_menu_libelle'] ?? 'N/A') ?></td>
                            <td>
                                <button class="btn-action btn-edit-param" title="Modifier">✏️</button>
                                <button class="btn-action btn-delete-param" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Aucun menu trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Ré-attacher les événements pour les nouveaux éléments chargés par AJAX
    // Cette partie est généralement gérée par la logique existante dans parametres-generaux.js
    // qui est appelée via window.ajaxRebinders.push(initializeFormParametre);
    // Assurez-vous que initializeFormParametre dans parametres-generaux.js
    // gère correctement le remplissage des champs spécifiques à ce formulaire (vue, categorie_menu_id).

    // Exemple d'adaptation pour le btn-edit-param spécifique aux menus :
    document.querySelectorAll('#container-<?= htmlspecialchars($parametre_type) ?> .btn-edit-param').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const form = document.querySelector('#container-<?= htmlspecialchars($parametre_type) ?> .form-parametre-general');

            form.querySelector('input[name="id"]').value = row.dataset.id;
            form.querySelector('input[name="libelle"]').value = row.dataset.libelle;
            form.querySelector('input[name="vue"]').value = row.dataset.vue;
            form.querySelector('select[name="categorie_menu_id"]').value = row.dataset.categorie_menu_id;

            form.querySelector('input[name="operation"]').value = 'modifier';
            form.querySelector('button[type="submit"]').textContent = 'Modifier';
            const btnCancel = form.querySelector('.btn-cancel-param');
            if(btnCancel) btnCancel.style.display = 'inline-block';

            form.querySelector('input[name="libelle"]').focus();
            window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });
        });
    });
</script>
