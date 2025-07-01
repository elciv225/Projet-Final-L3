<main class="main-content">

    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des menus</h1>
        </div>
    </div>

    <div class="container">
        <!-- Section unique pour la configuration des permissions -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">Configuration des Permissions de Groupe</h3>
            </div>
            <div class="section-content">
                <!-- Sous-section pour la sélection du groupe -->
                <div class="form-group-inline" style="margin-bottom: 32px;">
                    <label for="userGroupSelect">Groupe utilisateur:</label>
                    <div class="select-wrapper">
                        <select id="userGroupSelect" name="id_groupe_utilisateur" class="custom-select form-input">
                            <option value="">Sélectionner un groupe</option>
                            <?php if (isset($groupes) && !empty($groupes)): ?>
                                <?php foreach ($groupes as $groupe): ?>
                                    <option value="<?= htmlspecialchars($groupe->getId()) ?>">
                                        <?= htmlspecialchars($groupe->getLibelle()) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Sous-section pour le tableau des permissions -->
                <div class="permissions-table-container">
                    <div class="total-select-header">
                        <span>Tout Sélectionner</span>
                        <input type="checkbox" id="masterPermissionCheckbox" class="checkbox">
                    </div>
                    <form id="permissionsForm" action="/attribution-menu/sauvegarder" method="POST" class="ajax-form" data-target="permissions-table-container"> <!-- TODO: Define actual save route -->
                        <input type="hidden" name="id_groupe_utilisateur_form" id="id_groupe_utilisateur_form_hidden">
                        <table class="permissions-table">
                            <thead>
                            <tr>
                                <th>Traitement</th>
                                <?php
                                // Assuming specific actions are standard and known, e.g., CRUD.
                                // Or, use the $actions variable passed from controller if it's a simple list of action objects/arrays.
                                // For this example, let's use a predefined list that matches common expectations.
                                $standardActions = [
                                    'ACT_CREER_USER' => 'Créer', // Assuming these IDs match what's in $actions or $traitementActions
                                    'ACT_MODIF_USER' => 'Modifier',
                                    'ACT_SUPPR_USER' => 'Supprimer',
                                    'ACT_CONSULT_HISTO' => 'Consulter', // Example
                                    // Add other relevant general actions that might appear as columns
                                ];
                                foreach ($standardActions as $actionId => $actionLabel): ?>
                                    <th><?= htmlspecialchars($actionLabel) ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody id="permissionsTableBody">
                                <?php if (isset($traitements) && !empty($traitements) && isset($traitementActions)): ?>
                                    <?php foreach ($traitements as $traitement): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($traitement->getLibelle()) ?></td>
                                            <?php foreach ($standardActions as $actionId => $actionLabel): ?>
                                                <td>
                                                    <?php
                                                    $isAssociated = isset($traitementActions[$traitement->getId()]) && in_array($actionId, $traitementActions[$traitement->getId()]);
                                                    $isChecked = false; // Will be set by JS based on selected group and $attributions
                                                    if ($isAssociated): ?>
                                                        <div class="checkbox-container">
                                                            <input type="checkbox"
                                                                   class="checkbox permission-checkbox"
                                                                   name="permissions[<?= htmlspecialchars($traitement->getId()) ?>][<?= htmlspecialchars($actionId) ?>]"
                                                                   value="1"
                                                                   data-treatment-id="<?= htmlspecialchars($traitement->getId()) ?>"
                                                                   data-action-id="<?= htmlspecialchars($actionId) ?>"
                                                                <?= $isChecked ? 'checked' : '' ?>>
                                                        </div>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?= count($standardActions) + 1 ?>" style="text-align: center; color: var(--text-disabled); padding: 40px;">
                                            Veuillez sélectionner un groupe pour configurer les permissions ou aucun traitement n'est défini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-buttons">
        <button class="btn btn-primary" id="validateButton" type="submit" form="permissionsForm">Valider les Modifications</button>
    </div>
</main>

<script>
    // Pass PHP data to JavaScript
    const phpTraitements = <?= isset($traitements) ? json_encode(array_map(function($t){ return ['id' => $t->getId(), 'libelle' => $t->getLibelle()]; }, $traitements)) : '[]' ?>;
    const phpActions = <?= isset($actions) ? json_encode(array_map(function($a){ return ['id' => $a->getId(), 'libelle' => $a->getLibelle()]; }, $actions)) : '[]' ?>;
    const phpTraitementActions = <?= isset($traitementActions) ? json_encode($traitementActions) : '{}' ?>; // [traitement_id => [action_id1, ...]]
    const phpAttributions = <?= isset($attributions) ? json_encode($attributions) : '{}' ?>; // [groupe_id => [traitement_id => [action_id => true]]]
    const standardActionIds = <?= json_encode(array_keys($standardActions ?? [])) ?>; // For JS to know which action columns to expect

    document.addEventListener('DOMContentLoaded', function () {
        const userGroupSelect = document.getElementById('userGroupSelect');
        const masterPermissionCheckbox = document.getElementById('masterPermissionCheckbox');
        const permissionsTableBody = document.getElementById('permissionsTableBody');
        const validateButton = document.getElementById('validateButton'); // Is now type="submit" for the form
        const permissionsForm = document.getElementById('permissionsForm');
        const hiddenGroupIdFormInput = document.getElementById('id_groupe_utilisateur_form_hidden');

        function renderPermissionsForGroup(selectedGroupId) {
            if (!selectedGroupId) {
                permissionsTableBody.innerHTML = `<tr><td colspan="${standardActionIds.length + 1}" style="text-align: center; color: var(--text-disabled); padding: 40px;">Veuillez sélectionner un groupe pour voir les permissions.</td></tr>`;
                return;
            }
            hiddenGroupIdFormInput.value = selectedGroupId; // Set group ID for form submission

            permissionsTableBody.innerHTML = ''; // Clear existing rows
            phpTraitements.forEach(traitement => {
                const row = document.createElement('tr');
                let cells = `<td>${traitement.libelle}</td>`;
                const currentTraitementActions = phpTraitementActions[traitement.id] || [];
                const groupPermissionsForTraitement = (phpAttributions[selectedGroupId] && phpAttributions[selectedGroupId][traitement.id]) || {};

                standardActionIds.forEach(actionId => {
                    cells += '<td>';
                    if (currentTraitementActions.includes(actionId)) {
                        const isChecked = groupPermissionsForTraitement[actionId] === true;
                        cells += `<div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" name="permissions[${traitement.id}][${actionId}]" value="1" data-treatment-id="${traitement.id}" data-action-id="${actionId}" ${isChecked ? 'checked' : ''}></div>`;
                    } else {
                        cells += 'N/A';
                    }
                    cells += '</td>';
                });
                row.innerHTML = cells;
                permissionsTableBody.appendChild(row);
            });
            updateMasterPermissionCheckboxState();
            addPermissionCheckboxListeners();
        }

        userGroupSelect.addEventListener('change', function() {
            renderPermissionsForGroup(this.value);
        });

        function addPermissionCheckboxListeners() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.removeEventListener('change', updateMasterPermissionCheckboxState); // Prevent multiple listeners
                checkbox.addEventListener('change', updateMasterPermissionCheckboxState);
            });
        }

        function updateMasterPermissionCheckboxState() {
            const allCheckboxes = document.querySelectorAll('.permission-checkbox');
            if (allCheckboxes.length === 0) {
                masterPermissionCheckbox.checked = false;
                masterPermissionCheckbox.indeterminate = false;
                return;
            }
            const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
            masterPermissionCheckbox.checked = checkedCheckboxes.length === allCheckboxes.length;
            masterPermissionCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
        }

        masterPermissionCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        // Initial render if a group is pre-selected (e.g. on error reload)
        if (userGroupSelect.value) {
            renderPermissionsForGroup(userGroupSelect.value);
        } else {
            renderPermissionsForGroup(null); // Show placeholder
        }
    });
</script>