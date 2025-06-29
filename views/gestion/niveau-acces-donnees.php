<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Niveaux d'Accès aux Données</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter/Modifier Niveau d'Accès</h3>
        </div>
        <div class="section-content">
            <form id="niveauAccesForm" class="ajax-form" data-target="self">
                <input type="hidden" name="id_hidden" id="id_hidden_niveau_acces">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="niveau_acces_id_input" name="id" class="form-input" placeholder=" " required>
                        <label for="niveau_acces_id_input" class="form-label">ID Niveau Accès (e.g., ACCES_DEPT_INFO)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="niveau_acces_libelle" name="libelle" class="form-input" placeholder=" " required>
                        <label for="niveau_acces_libelle" class="form-label">Libellé (e.g., Accès Département Informatique)</label>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
                    <button type="button" class="btn btn-secondary" id="btnCancelNiveauAcces" style="margin-right: 10px; display: none;">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnValiderNiveauAcces">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des Niveaux d'Accès</h3>
            <div class="header-actions">
                <input type="text" id="searchInputNiveauAcces" class="search-input" placeholder="Rechercher..." style="margin-right:10px;padding:8px;border:1px solid #ccc;">
                <button class="btn btn-primary" id="btnAddNiveauAcces">➕ Ajouter un Niveau d'Accès</button>
            </div>
        </div>

        <table class="table" id="niveauAccesTable">
            <thead>
            <tr>
                <th>ID Niveau Accès</th>
                <th>Libellé</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="niveauAccesTableBody">
            <!-- Data rows will be loaded by JavaScript -->
            </tbody>
        </table>
    </div>
</main>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CrudManager !== 'undefined') {
            new CrudManager({
                entityName: "Niveau d'Accès Données",
                entitySlug: 'niveau-acces-donnees',
                formId: 'niveauAccesForm',
                tableId: 'niveauAccesTable',
                tableBodyId: 'niveauAccesTableBody',
                addBtnId: 'btnAddNiveauAcces',
                submitBtnId: 'btnValiderNiveauAcces',
                cancelBtnId: 'btnCancelNiveauAcces',
                searchInputId: 'searchInputNiveauAcces',
                hiddenIdField: 'id_hidden_niveau_acces',
                fields: [
                    { formName: 'id', dbName: 'id', label: "ID Niveau Accès", inTable: true, required: true },
                    { formName: 'libelle', dbName: 'libelle', label: "Libellé", inTable: true, required: true }
                ],
                renderTableRow: function(item) {
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
            });
        } else {
            console.error('CrudManager is not defined. Make sure gestion.js is loaded before this script.');
        }
    });
</script>
