<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Types d'Utilisateurs</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter/Modifier Type d'Utilisateur</h3>
        </div>
        <div class="section-content">
            <form id="typeUtilisateurForm" class="ajax-form" data-target="self">
                <input type="hidden" name="id_hidden" id="id_hidden_type_utilisateur">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="type_utilisateur_id_input" name="id" class="form-input" placeholder=" " required>
                        <label for="type_utilisateur_id_input" class="form-label">ID Type (e.g., TYPE_ETUDIANT_L1)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="type_utilisateur_libelle" name="libelle" class="form-input" placeholder=" " required>
                        <label for="type_utilisateur_libelle" class="form-label">Libellé (e.g., Étudiant Licence 1)</label>
                    </div>
                    <div class="form-group">
                        <select id="type_utilisateur_categorie_id" name="categorie_utilisateur_id" class="form-input" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <!-- Options à peupler par PHP/JS depuis la table categorie_utilisateur -->
                        </select>
                        <label for="type_utilisateur_categorie_id" class="form-label">Catégorie Utilisateur</label>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
                    <button type="button" class="btn btn-secondary" id="btnCancelTypeUtilisateur" style="margin-right: 10px; display: none;">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnValiderTypeUtilisateur">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des Types d'Utilisateurs</h3>
            <div class="header-actions">
                <input type="text" id="searchInputTypeUtilisateur" class="search-input" placeholder="Rechercher..." style="margin-right:10px;padding:8px;border:1px solid #ccc;">
                <button class="btn btn-primary" id="btnAddTypeUtilisateur">➕ Ajouter un Type</button>
            </div>
        </div>

        <table class="table" id="typeUtilisateurTable">
            <thead>
            <tr>
                <th>ID Type</th>
                <th>Libellé</th>
                <th>Catégorie Utilisateur ID</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="typeUtilisateurTableBody">
            <!-- Data rows will be loaded by JavaScript -->
            </tbody>
        </table>
    </div>
</main>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CrudManager !== 'undefined') {
            new CrudManager({
                entityName: "Type d'Utilisateur",
                entitySlug: 'type-utilisateurs',
                formId: 'typeUtilisateurForm',
                tableId: 'typeUtilisateurTable',
                tableBodyId: 'typeUtilisateurTableBody',
                addBtnId: 'btnAddTypeUtilisateur',
                submitBtnId: 'btnValiderTypeUtilisateur',
                cancelBtnId: 'btnCancelTypeUtilisateur',
                searchInputId: 'searchInputTypeUtilisateur',
                hiddenIdField: 'id_hidden_type_utilisateur',
                fields: [
                    { formName: 'id', dbName: 'id', label: "ID Type", inTable: true, required: true },
                    { formName: 'libelle', dbName: 'libelle', label: "Libellé", inTable: true, required: true },
                    {
                        formName: 'categorie_utilisateur_id',
                        dbName: 'categorie_utilisateur_id',
                        label: "Catégorie Utilisateur ID",
                        inTable: true,
                        required: true,
                        // Assuming you'll have a way to display categorie_utilisateur.libelle in table
                        // tableRender: (value, item) => item.categorie_utilisateur_libelle || value
                    }
                ],
                renderTableRow: function(item) {
                    let cells = '';
                    this.options.fields.forEach(field => {
                        if (field.inTable !== false) {
                            let value = item[field.dbName] || '';
                            // Potentially fetch related libelle for categorie_utilisateur_id for display
                            // For now, just displaying the ID.
                            cells += `<td>${field.tableRender ? field.tableRender(value, item) : value}</td>`;
                        }
                    });
                    return `
                        ${cells}
                        <td>
                            <div class="table-actions">
                                <button class="action-btn edit-btn" data-id="${item.id || ''}">✏️</button>
                                <button class="action-btn delete-btn" data-id="${item.id || ''}">🗑️</button>
                            </div>
                        </td>
                    `;
                },
                populateForm: function(item, form) {
                    this.options.fields.forEach(field => {
                        if (form.elements[field.formName]) {
                            form.elements[field.formName].value = item[field.dbName] || '';
                        }
                    });
                    const hiddenIdElem = document.getElementById(this.options.hiddenIdField);
                    if (hiddenIdElem) hiddenIdElem.value = item.id;
                    if (form.elements['id']) {
                        form.elements['id'].readOnly = true;
                    }
                },
                clearFormCustom: function(form) {
                    form.reset();
                    if (form.elements['id']) {
                        form.elements['id'].readOnly = false;
                    }
                    const hiddenIdElem = document.getElementById(this.options.hiddenIdField);
                    if (hiddenIdElem) hiddenIdElem.value = '';
                }
                // Note: Populate logic for 'categorie_utilisateur_id' select needs to be handled
                // (e.g. an async call in CrudManager or options to pre-fill select boxes)
            });
        } else {
            console.error('CrudManager is not defined. Make sure gestion.js is loaded before this script.');
        }
    });
</script>
