<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Années Académiques</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter/Modifier Année Académique</h3>
        </div>
        <div class="section-content">
            <form id="anneeAcademiqueForm" class="ajax-form" data-target="self">
                <input type="hidden" name="id_hidden" id="id_hidden_annee_academique">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="annee_id_input" name="id" class="form-input" placeholder=" " required pattern="\d{4}-\d{4}">
                        <label for="annee_id_input" class="form-label">ID Année (e.g., 2023-2024)</label>
                    </div>
                    <div class="form-group">
                        <input type="date" id="annee_date_debut" name="date_debut" class="form-input" placeholder=" " required>
                        <label for="annee_date_debut" class="form-label">Date de Début</label>
                    </div>
                    <div class="form-group">
                        <input type="date" id="annee_date_fin" name="date_fin" class="form-input" placeholder=" " required>
                        <label for="annee_date_fin" class="form-label">Date de Fin</label>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
                    <button type="button" class="btn btn-secondary" id="btnCancelAnnee" style="margin-right: 10px; display: none;">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnValiderAnnee">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des Années Académiques</h3>
            <div class="header-actions">
                <input type="text" id="searchInputAnnee" class="search-input" placeholder="Rechercher par ID..." style="margin-right:10px;padding:8px;border:1px solid #ccc;">
                <button class="btn btn-primary" id="btnAddAnnee">➕ Ajouter une Année</button>
            </div>
        </div>

        <table class="table" id="anneeAcademiqueTable">
            <thead>
            <tr>
                <th>ID Année</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="anneeAcademiqueTableBody">
            <!-- Data rows will be loaded by JavaScript -->
            </tbody>
        </table>
    </div>
</main>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CrudManager !== 'undefined') {
            new CrudManager({
                entityName: "Année Académique",
                entitySlug: 'annee-academiques',
                formId: 'anneeAcademiqueForm',
                tableId: 'anneeAcademiqueTable',
                tableBodyId: 'anneeAcademiqueTableBody',
                addBtnId: 'btnAddAnnee',
                submitBtnId: 'btnValiderAnnee',
                cancelBtnId: 'btnCancelAnnee',
                searchInputId: 'searchInputAnnee',
                hiddenIdField: 'id_hidden_annee_academique',
                fields: [
                    {
                        formName: 'id', dbName: 'id', label: "ID Année", inTable: true, required: true,
                        validation: (value) => {
                            if (!/^\d{4}-\d{4}$/.test(value)) {
                                showPopup("L'ID de l'année académique doit être au format AAAA-AAAA (ex: 2023-2024).", "error");
                                return false;
                            }
                            const years = value.split('-');
                            if (parseInt(years[0]) + 1 !== parseInt(years[1])) {
                                 showPopup("L'année de fin doit être l'année de début + 1 (ex: 2023-2024).", "error");
                                return false;
                            }
                            return true;
                        }
                    },
                    { formName: 'date_debut', dbName: 'date_debut', label: "Date de Début", inTable: true, type: 'date', required: true },
                    {
                        formName: 'date_fin', dbName: 'date_fin', label: "Date de Fin", inTable: true, type: 'date', required: true,
                        validation: (value, data) => {
                            if (data.date_debut && value < data.date_debut) {
                                showPopup("La date de fin ne peut pas être antérieure à la date de début.", "error");
                                return false;
                            }
                            return true;
                        }
                    }
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
                     if (form.elements['id']) { // ID might be part of main form
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
