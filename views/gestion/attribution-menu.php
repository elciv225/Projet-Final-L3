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
                        <select id="userGroupSelect" class="custom-select">
                            <option value="">Sélectionner un groupe</option>
                            <option value="admin">Administrateur</option>
                            <option value="editor">Éditeur</option>
                            <option value="viewer">Lecteur</option>
                            <option value="guest">Invité</option>
                        </select>
                    </div>
                </div>

                <!-- Sous-section pour le tableau des permissions -->
                <div class="permissions-table-container">
                    <div class="total-select-header">
                        <span>Tout Sélectionner</span>
                        <input type="checkbox" id="masterPermissionCheckbox" class="checkbox">
                    </div>
                    <table class="permissions-table">
                        <thead>
                        <tr>
                            <th>Traitement</th>
                            <th>Ajouter</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                            <th>Imprimer</th>
                        </tr>
                        </thead>
                        <tbody id="permissionsTableBody">
                        <!-- Le contenu sera vide initialement -->
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-disabled); padding: 40px;">
                                Veuillez sélectionner un groupe pour voir les permissions.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-buttons">
        <button class="btn btn-primary" id="validateButton">Valider</button>
    </div>
</main>


<script>
    // Le code JavaScript reste inchangé
    document.addEventListener('DOMContentLoaded', function () {
        const userGroupSelect = document.getElementById('userGroupSelect');
        const masterPermissionCheckbox = document.getElementById('masterPermissionCheckbox');
        const permissionsTableBody = document.getElementById('permissionsTableBody');
        const validateButton = document.getElementById('validateButton');

        const treatments = [
            {id: 'trait1', name: 'Traitement 1', permissions: {add: false, mod: true, del: true, imp: true}},
            {id: 'trait2', name: 'Traitement 2', permissions: {add: true, mod: false, del: true, imp: true}},
            {id: 'trait3', name: 'Traitement 3', permissions: {add: false, mod: false, del: true, imp: true}},
            {id: 'trait4', name: 'Traitement 4', permissions: {add: false, mod: false, del: false, imp: true}},
        ];

        function renderPermissionsTable() {
            permissionsTableBody.innerHTML = '';
            treatments.forEach(treatment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                        <td>${treatment.name}</td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="add" ${treatment.permissions.add ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="mod" ${treatment.permissions.mod ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="del" ${treatment.permissions.del ? 'checked' : ''}></div></td>
                        <td><div class="checkbox-container"><input type="checkbox" class="checkbox permission-checkbox" data-treatment-id="${treatment.id}" data-permission-type="imp" ${treatment.permissions.imp ? 'checked' : ''}></div></td>
                    `;
                permissionsTableBody.appendChild(row);
            });
            updateMasterPermissionCheckboxState();
            addPermissionCheckboxListeners();
        }

        function addPermissionCheckboxListeners() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.removeEventListener('change', updateMasterPermissionCheckboxState);
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
                const treatmentId = checkbox.dataset.treatmentId;
                const permissionType = checkbox.dataset.permissionType;
                const treatment = treatments.find(t => t.id === treatmentId);
                if (treatment) {
                    treatment.permissions[permissionType] = isChecked;
                }
            });
        });

        validateButton.addEventListener('click', function () {
            const selectedGroup = userGroupSelect.value;
            if (!selectedGroup) {
                alert("Veuillez sélectionner un groupe utilisateur.");
                return;
            }
            const currentPermissions = {};
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                const treatmentId = checkbox.dataset.treatmentId;
                const permissionType = checkbox.dataset.permissionType;
                if (!currentPermissions[treatmentId]) {
                    currentPermissions[treatmentId] = {};
                }
                currentPermissions[treatmentId][permissionType] = checkbox.checked;
            });
            console.log('Groupe sélectionné:', selectedGroup);
            console.log('Permissions actuelles:', currentPermissions);
            alert("Permissions mises à jour pour le groupe: " + selectedGroup + "\n(Vérifiez la console pour les détails)");
        });

        renderPermissionsTable();
    });
</script>