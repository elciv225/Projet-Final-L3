<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Niveaux d'Étude</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter/Modifier Niveau d'Étude</h3>
        </div>
        <div class="section-content">
            <!-- Ensure form ID and element IDs/names are consistent for CrudManager -->
            <form id="niveauEtudeForm" class="ajax-form" data-target="self">
                <input type="hidden" name="id_hidden" id="id_hidden_niveau_etude"> <!-- For storing ID during edit -->
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="niveau_etude_id_input" name="id" class="form-input" placeholder=" " required>
                        <label for="niveau_etude_id_input" class="form-label">ID Niveau (e.g., NIVEAU_LICENCE1)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="niveau_etude_libelle" name="libelle" class="form-input" placeholder=" " required>
                        <label for="niveau_etude_libelle" class="form-label">Libellé (e.g., Licence 1)</label>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
                    <button type="button" class="btn btn-secondary" id="btnCancelNiveauEtude" style="margin-right: 10px; display: none;">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnValiderNiveauEtude">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des Niveaux d'Étude</h3>
            <div class="header-actions">
                <input type="text" id="searchInputNiveauEtude" class="search-input" placeholder="Rechercher..." style="margin-right:10px;padding:8px;border:1px solid #ccc;">
                <button class="btn btn-primary" id="btnAddNiveauEtude">➕ Ajouter un Niveau</button>
            </div>
        </div>

        <table class="table" id="niveauEtudeTable">
            <thead>
            <tr>
                <!-- <th><input type="checkbox" class="checkbox" id="selectAllNiveauEtudeCheckbox"></th> -->
                <th>ID Niveau</th>
                <th>Libellé</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="niveauEtudeTableBody">
            <!-- Les lignes de données seront chargées ici par JavaScript -->
            </tbody>
        </table>
        <!-- Optional: Pagination and results info if many entries -->
    </div>
</main>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CrudManager !== 'undefined') {
            new CrudManager({
                entityName: "Niveau d'Étude",
                entitySlug: 'niveau-etudes', // Used for constructing API endpoints if not fully specified
                formId: 'niveauEtudeForm',
                tableId: 'niveauEtudeTable',
                tableBodyId: 'niveauEtudeTableBody',
                addBtnId: 'btnAddNiveauEtude', // Button to clear form for adding
                submitBtnId: 'btnValiderNiveauEtude', // Submit button of the form
                cancelBtnId: 'btnCancelNiveauEtude', // Optional: Cancel button for edit mode
                searchInputId: 'searchInputNiveauEtude',

                // Assuming your API uses these patterns. Adjust as needed.
                // listUrl: '/api/gestion/niveau-etudes', // Example
                // createUrl: '/api/gestion/niveau-etudes/ajouter', // Example
                // updateUrl: '/api/gestion/niveau-etudes/modifier/:id', // Example
                // deleteUrl: '/api/gestion/niveau-etudes/supprimer/:id', // Example

                hiddenIdField: 'id_hidden_niveau_etude', // Name of the hidden input for ID in edit mode

                fields: [
                    { formName: 'id', dbName: 'id', label: "ID Niveau", inTable: true, required: true },
                    { formName: 'libelle', dbName: 'libelle', label: "Libellé", inTable: true, required: true }
                ],

                renderTableRow: function(item) { // Override to simplify, no select all for these simple tables
                    let cells = '';
                    this.options.fields.forEach(field => {
                        if (field.inTable !== false) {
                            const value = item[field.dbName] || '';
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
                    // For simple forms like this, ID might be part of the main form inputs
                    // If using a hidden field for ID on edit:
                    const hiddenIdElem = document.getElementById(this.options.hiddenIdField);
                    if (hiddenIdElem) hiddenIdElem.value = item.id;

                    // Disable ID field when editing if it's the PK and shouldn't be changed
                    if (form.elements['id']) {
                        form.elements['id'].readOnly = true;
                    }
                },
                clearFormCustom: function(form) { // Custom clear if needed
                    form.reset();
                    if (form.elements['id']) {
                        form.elements['id'].readOnly = false;
                    }
                    const hiddenIdElem = document.getElementById(this.options.hiddenIdField);
                    if (hiddenIdElem) hiddenIdElem.value = '';
                }
            });
        } else {
            console.error('CrudManager is not defined. Make sure gestion.js is loaded before this script.');
        }
    });
</script>
