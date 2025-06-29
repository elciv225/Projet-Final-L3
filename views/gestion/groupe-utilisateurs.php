<main class="main-content">
    <header class="page-header">
        <div class="header-left">
            <h1>Gestion des Groupes d'Utilisateurs</h1>
        </div>
    </header>

    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Ajouter/Modifier Groupe d'Utilisateur</h3>
        </div>
        <div class="section-content">
            <form id="groupeUtilisateurForm" class="ajax-form" data-target="self">
                <input type="hidden" name="id_hidden" id="id_hidden_groupe_utilisateur">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="groupe_id_input" name="id" class="form-input" placeholder=" " required>
                        <label for="groupe_id_input" class="form-label">ID Groupe (e.g., GRP_ADMINS)</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="groupe_libelle" name="libelle" class="form-input" placeholder=" " required>
                        <label for="groupe_libelle" class="form-label">Libellé (e.g., Administrateurs)</label>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; padding: 20px 0;">
                    <button type="button" class="btn btn-secondary" id="btnCancelGroupe" style="margin-right: 10px; display: none;">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnValiderGroupe">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Liste des Groupes d'Utilisateurs</h3>
            <div class="header-actions">
                <input type="text" id="searchInputGroupe" class="search-input" placeholder="Rechercher..." style="margin-right:10px;padding:8px;border:1px solid #ccc;">
                <button class="btn btn-primary" id="btnAddGroupe">➕ Ajouter un Groupe</button>
            </div>
        </div>

        <table class="table" id="groupeUtilisateurTable">
            <thead>
            <tr>
                <th>ID Groupe</th>
                <th>Libellé</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="groupeUtilisateurTableBody">
            <!-- Data rows will be loaded by JavaScript -->
            </tbody>
        </table>
    </div>
</main>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CrudManager !== 'undefined') {
            new CrudManager({
                entityName: "Groupe d'Utilisateur",
                entitySlug: 'groupe-utilisateurs',
                formId: 'groupeUtilisateurForm',
                tableId: 'groupeUtilisateurTable',
                tableBodyId: 'groupeUtilisateurTableBody',
                addBtnId: 'btnAddGroupe',
                submitBtnId: 'btnValiderGroupe',
                cancelBtnId: 'btnCancelGroupe',
                searchInputId: 'searchInputGroupe',
                hiddenIdField: 'id_hidden_groupe_utilisateur',
                fields: [
                    { formName: 'id', dbName: 'id', label: "ID Groupe", inTable: true, required: true },
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
