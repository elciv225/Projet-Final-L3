/**
 * gestion.js
 * Generic script for managing CRUD operations in "gestion" views.
 * Relies on ajax.js for AJAX calls, popups, and loaders.
 */

class CrudManager {
    constructor(options) {
        this.options = {
            entityName: 'Élément', // Generic name, e.g., 'Étudiant', 'Enseignant'
            entitySlug: '', // e.g., 'etudiants', 'enseignants' for API endpoints
            formId: 'gestionForm',
            tableId: 'gestionTable',
            tableBodyId: null, // Optional, defaults to tableId + ' tbody'
            modalFormId: null, // If using a modal for add/edit

            addBtnId: 'btnAdd',
            submitBtnId: 'btnValider',
            searchInpId: 'searchInput',
            deleteSelectedBtnId: 'btnSupprimerSelection',

            exportPdfBtnId: 'btnExportPDF',
            exportExcelBtnId: 'btnExportExcel',
            printBtnId: 'btnPrint',

            // API Endpoints - to be configured per module
            listUrl: null,
            createUrl: null,
            updateUrl: null, // Should include a placeholder like :id
            deleteUrl: null, // Should include a placeholder like :id

            // Field definitions: [{ formName: 'nom_input', dbName: 'nom', tableRender: (val) => val, validation: (val) => true }, ...]
            fields: [],

            // Custom function to render a table row from a data item
            renderTableRow: (item) => {
                let cells = '';
                this.options.fields.forEach(field => {
                    if (field.inTable !== false) { // Default to true if not specified
                        const value = item[field.dbName] || '';
                        cells += `<td>${field.tableRender ? field.tableRender(value, item) : value}</td>`;
                    }
                });
                return `
                    <td><input type="checkbox" class="checkbox item-checkbox" data-id="${item.id || ''}"></td>
                    ${cells}
                    <td>
                        <div class="table-actions">
                            <button class="action-btn edit-btn" data-id="${item.id || ''}">✏️</button>
                            <button class="action-btn delete-btn" data-id="${item.id || ''}">🗑️</button>
                        </div>
                    </td>
                `;
            },
            // Custom function to populate form for editing
            populateForm: (item, form) => {
                this.options.fields.forEach(field => {
                    if (form.elements[field.formName]) {
                        form.elements[field.formName].value = item[field.dbName] || '';
                    }
                });
                if (form.elements['id'] && item.id) { // Common hidden ID field or main identifier
                    form.elements['id'].value = item.id;
                }
            },
            // Custom function to get form data as an object
            getFormData: (form) => {
                const formData = new FormData(form);
                const data = {};
                this.options.fields.forEach(field => {
                    if (form.elements[field.formName]) {
                        data[field.dbName] = formData.get(field.formName);
                    }
                });
                // Include ID if present (especially for updates)
                if (formData.get('id')) {
                    data.id = formData.get('id');
                }
                return data;
            },
            // Basic client-side validation (can be expanded)
            validateForm: (data) => {
                for (const field of this.options.fields) {
                    if (field.required && !data[field.dbName]) {
                        showPopup(`Le champ "${field.label || field.dbName}" est requis.`, 'error');
                        return false;
                    }
                    if (field.validation && !field.validation(data[field.dbName], data)) {
                        // Validation function should show its own specific popup
                        return false;
                    }
                }
                return true;
            },
            ...options // Override defaults with provided options
        };

        this.form = document.getElementById(this.options.formId);
        this.table = document.getElementById(this.options.tableId);
        this.tableBody = this.options.tableBodyId ? document.getElementById(this.options.tableBodyId) : (this.table ? this.table.querySelector('tbody') : null);

        this.submitBtn = this.form ? this.form.querySelector('button[type="submit"], #' + this.options.submitBtnId) : document.getElementById(this.options.submitBtnId);
        this.addBtn = document.getElementById(this.options.addBtnId); // Might be the same as submitBtn or a separate one for modals
        this.searchInput = document.getElementById(this.options.searchInpId);
        this.deleteSelectedBtn = document.getElementById(this.options.deleteSelectedBtnId);
        this.exportPdfBtn = document.getElementById(this.options.exportPdfBtnId);
        this.exportExcelBtn = document.getElementById(this.options.exportExcelBtnId);
        this.printBtn = document.getElementById(this.options.printBtnId);

        this.data = []; // To store fetched data for client-side filtering/sorting if needed
        this.editingItemId = null; // ID of the item being edited

        if (!this.form && !this.table) {
            console.warn(`Gestion.js: Formulaire (${this.options.formId}) ou Table (${this.options.tableId}) non trouvé pour ${this.options.entityName}.`);
            return;
        }

        this.initEventListeners();
        if (this.options.listUrl) {
            // this.fetchData(); // Initial data load - to be implemented
        } else if (this.tableBody) {
            // If no listUrl, assume table is pre-populated or managed by other means initially
            // Still bind actions to existing rows if any
            this.bindTableActionsToExistingRows();
        }
    }

    clearForm() {
        if (this.form) {
            this.form.reset();
            // Clear any hidden ID field if it's part of the main form
            if (this.form.elements['id']) {
                this.form.elements['id'].value = '';
            }
        }
        this.editingItemId = null;
        if (this.submitBtn) {
            this.submitBtn.textContent = this.options.addBtnId === this.options.submitBtnId ? 'Ajouter' : 'Valider';
        }
    }

    displayDataInTable() {
        if (!this.tableBody) return;
        this.tableBody.innerHTML = ''; // Clear existing rows
        this.data.forEach(item => {
            const row = this.tableBody.insertRow();
            row.innerHTML = this.options.renderTableRow(item);
        });
        // Re-bind actions if not using event delegation on tableBody for everything
    }

    bindTableActionsToExistingRows() {
        if (!this.tableBody) return;
        this.tableBody.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleEditClick(e));
        });
        this.tableBody.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleDeleteClick(e));
        });
    }

    initEventListeners() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        if (this.tableBody) { // Event delegation for table actions
            this.tableBody.addEventListener('click', (e) => {
                const target = e.target;
                if (target.closest('.edit-btn')) {
                    this.handleEditClick(e);
                } else if (target.closest('.delete-btn')) {
                    this.handleDeleteClick(e);
                }
            });
        }

        if (this.addBtn && this.addBtn !== this.submitBtn) { // If there's a distinct "Add" button (e.g., for modals or clearing form)
            this.addBtn.addEventListener('click', () => {
                this.editingItemId = null;
                this.clearForm();
                if (this.options.modalFormId) {
                    // Show modal
                } else {
                    this.form.elements[this.options.fields[0].formName].focus();
                }
            });
        }

        if (this.deleteSelectedBtn) {
            this.deleteSelectedBtn.addEventListener('click', () => this.handleDeleteSelected());
        }
        if (this.searchInput) {
            this.searchInput.addEventListener('keyup', () => this.searchTable());
        }
        if (this.exportPdfBtn) {
            this.exportPdfBtn.addEventListener('click', () => this.exportToPDF());
        }
        if (this.exportExcelBtn) {
            this.exportExcelBtn.addEventListener('click', () => this.exportToExcel());
        }
        if (this.printBtn) {
            this.printBtn.addEventListener('click', () => this.printTable());
        }
    }

    // --- Placeholder methods for actual CRUD and table features ---
    // These will be fleshed out to use ajaxRequest and DOM manipulation

    async fetchData() {
        if (!this.options.listUrl) return;
        showLoader('global'); // Or a specific target
        try {
            // const data = await ajaxRequest(this.options.listUrl, { method: 'GET' });
            // this.data = data.items || data; // Assuming server returns { items: [...] } or just [...]
            // this.displayDataInTable();
            console.log("fetchData to be implemented. Would fetch from:", this.options.listUrl);
            // TEMP: Use existing rows if any for now for testing other logic
            if (this.tableBody && this.tableBody.rows.length > 0 && this.data.length === 0) {
                // This part is tricky without knowing the exact table structure vs. field definitions
                // For now, this method will primarily rely on server-fetched data.
            }

        } catch (error) {
            showPopup(`Erreur de chargement des ${this.options.entityName}s.`, 'error');
        } finally {
            hideLoader('global');
        }
    }

    async handleFormSubmit(event) {
        event.preventDefault();
        if (!this.form) return;

        const itemData = this.options.getFormData(this.form);
        if (!this.options.validateForm(itemData)) {
            return;
        }

        const url = this.editingItemId
            ? this.options.updateUrl.replace(':id', this.editingItemId)
            : this.options.createUrl;
        const method = this.editingItemId ? 'PUT' : 'POST'; // Often POST with a _method field for PUT

        if (!url) {
            showPopup("URL d'action non configurée.", "error");
            return;
        }

        showLoader('global');
        try {
            // const response = await ajaxRequest(url, { method, data: itemData });
            // For now, simulate success and update DOM if client-side only for testing structure
            console.log("Simulating AJAX Submit:", method, url, itemData);
            if (this.editingItemId) {
                 // Find item in this.data and update, then re-render table
                const index = this.data.findIndex(d => d.id == this.editingItemId); // Ensure ID types match
                if (index > -1) this.data[index] = {...this.data[index], ...itemData};
                 showPopup(`${this.options.entityName} modifié(e) (simulation).`, 'success');
            } else {
                itemData.id = Date.now(); // Simulate new ID
                this.data.push(itemData);
                showPopup(`${this.options.entityName} ajouté(e) (simulation).`, 'success');
            }
            this.displayDataInTable(); // Re-render
            this.clearForm();

        } catch (error) {
            showPopup(`Erreur lors de la sauvegarde de ${this.options.entityName}.`, 'error');
        } finally {
            hideLoader('global');
        }
    }

    handleEditClick(event) {
        const button = event.target.closest('.edit-btn');
        if (!button || !this.form) return;

        this.editingItemId = button.dataset.id;
        // In a real scenario, you might fetch the full item data via AJAX if table rows are summaries
        // const item = this.data.find(d => d.id == this.editingItemId);
        // For now, try to get data from table row (less robust)
        const row = button.closest('tr');
        if (!row) return;

        // Crude way to get data from table cells for now, assuming order matches fields for form population
        // This should ideally use this.data or fetch fresh data
        const cells = Array.from(row.cells).slice(1, -1); // Skip checkbox and actions
        const itemForForm = {};
        this.options.fields.forEach((field, index) => {
            if (field.inTable !== false && cells[index]) {
                itemForForm[field.dbName] = cells[index].textContent;
            }
        });
        itemForForm.id = this.editingItemId; // Ensure ID is set

        if (itemForForm) {
            this.options.populateForm(itemForForm, this.form);
            if (this.submitBtn) this.submitBtn.textContent = 'Mettre à jour';
            window.scrollTo({ top: this.form.offsetTop - 20, behavior: 'smooth' });
        } else {
            showPopup("Données de l'élément non trouvées pour modification.", "error");
            this.editingItemId = null;
        }
    }

    async handleDeleteClick(event) {
        const button = event.target.closest('.delete-btn');
        if (!button) return;
        const itemId = button.dataset.id;

        showWarningCard(`Voulez-vous vraiment supprimer cet ${this.options.entityName.toLowerCase()} (ID: ${itemId})?`, async () => {
            if (!this.options.deleteUrl) {
                console.log("Simulating Client-Side Delete for ID:", itemId);
                this.data = this.data.filter(d => d.id != itemId);
                this.displayDataInTable();
                showPopup(`${this.options.entityName} supprimé(e) (simulation).`, 'info');
                return;
            }
            // Actual AJAX delete
            // showLoader('global');
            // try {
            //     const url = this.options.deleteUrl.replace(':id', itemId);
            //     await ajaxRequest(url, { method: 'DELETE' });
            //     showPopup(`${this.options.entityName} supprimé(e).`, 'success');
            //     this.fetchData(); // Refresh
            // } catch (error) {
            //     showPopup(`Erreur suppression ${this.options.entityName}.`, 'error');
            // } finally {
            //     hideLoader('global');
            // }
        });
    }

    handleDeleteSelected() {
        if (!this.tableBody) return;
        const selectedCheckboxes = this.tableBody.querySelectorAll('.item-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            showPopup('Veuillez sélectionner au moins un élément à supprimer.', 'warning');
            return;
        }
        const itemIdsToDelete = Array.from(selectedCheckboxes).map(cb => cb.dataset.id);

        showWarningCard(`Voulez-vous vraiment supprimer les ${selectedCheckboxes.length} ${this.options.entityName.toLowerCase()}s sélectionnés ?`, async () => {
            console.log("Simulating Client-Side Batch Delete for IDs:", itemIdsToDelete);
            itemIdsToDelete.forEach(id => {
                this.data = this.data.filter(d => d.id != id);
            });
            this.displayDataInTable();
            showPopup(`${selectedCheckboxes.length} ${this.options.entityName}(s) supprimé(s) (simulation).`, 'info');

            // Actual AJAX batch delete (backend needs to support this)
            // showLoader('global');
            // try {
            //     await ajaxRequest(this.options.deleteBatchUrl || this.options.deleteUrl, { method: 'POST', data: { ids: itemIdsToDelete } }); // Or DELETE with body
            //     showPopup(`${itemIdsToDelete.length} ${this.options.entityName}(s) supprimé(s).`, 'success');
            //     this.fetchData(); // Refresh
            // } catch (error) {
            //     showPopup(`Erreur suppression ${this.options.entityName}.`, 'error');
            // } finally {
            //     hideLoader('global');
            // }
        });
    }

    searchTable() {
        if (!this.searchInput || !this.tableBody) return;
        const searchTerm = this.searchInput.value.toLowerCase();
        this.tableBody.querySelectorAll('tr').forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    }

    exportToPDF() {
        if (!window.jspdf || !this.table) {
            showPopup("La librairie jsPDF n'est pas chargée ou table non trouvée.", "error");
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text(`Liste des ${this.options.entityName}s`, 10, 10);
        doc.autoTable({ html: `#${this.options.tableId}`, startY: 20 });
        doc.save(`${this.options.entitySlug || 'export'}.pdf`);
    }

    exportToExcel() {
        if (!window.XLSX || !this.table) {
            showPopup("La librairie XLSX n'est pas chargée ou table non trouvée.", "error");
            return;
        }
        const wb = XLSX.utils.table_to_book(this.table, { sheet: this.options.entityName });
        XLSX.writeFile(wb, `${this.options.entitySlug || 'export'}.xlsx`);
    }

    printTable() {
        if (!this.table) {
             showPopup("Table non trouvée pour l'impression.", "error");
            return;
        }
        const tableHTML = this.table.outerHTML;
        const newWindow = window.open("", "", "width=900,height=700");
        newWindow.document.write(`
            <html><head><title>Impression - Liste des ${this.options.entityName}s</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .table-actions { display: none; } /* Hide actions for print */
            </style>
            </head><body>
                <h2>Liste des ${this.options.entityName}s</h2>
                ${tableHTML}
            </body></html>
        `);
        newWindow.document.close();
        // Wait for content to load before printing
        setTimeout(() => {
            newWindow.print();
            newWindow.close();
        }, 250);
    }
}

// Helper to initialize CrudManager for different pages
// This will be called in specific page scripts or after AJAX page loads
// window.initializeGestionPage = (pageType, options) => {
//     const commonOptions = { /* common defaults for all gestion pages */ };
//     let specificOptions = {};
//
//     switch(pageType) {
//         case 'etudiants':
//             specificOptions = {
//                 entityName: 'Étudiant',
//                 entitySlug: 'etudiants',
//                 formId: 'etudiantForm', // Make sure form IDs are unique if multiple on one page
//                 tableId: 'etudiantsTable',
//                 fields: [ /* define fields for etudiant */ ],
//                 // ... other specific overrides
//             };
//             break;
//         // ... cases for enseignants, personnel, utilisateurs, ue, ecue
//     }
//     new CrudManager({...commonOptions, ...specificOptions, ...options});
// };

// Note: For pages like evaluation-etudiant (dynamic rows) or attribution-menu (permissions matrix),
// a more specialized class or standalone script might still be needed, or CrudManager
// would need significant extension to handle those complex UI patterns.
// This initial CrudManager targets the simpler list-form-table structures.

[end of public/assets/js/gestion.js]
