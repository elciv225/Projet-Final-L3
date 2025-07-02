class DataTableManager {
    constructor(tableId, options = {}) {
        this.table = document.getElementById(tableId);
        if (!this.table) {
            console.error(`DataTableManager: Table with ID '${tableId}' not found.`);
            this.isValid = false;
            return;
        }
        this.isValid = true;

        this.options = Object.assign({
            rowsPerPage: 10,
            searchInputId: `searchInput-${tableId}`,
            paginationContainerId: `pagination-${tableId}`,
            resultsInfoContainerId: `resultsInfo-${tableId}`,
            selectAllCheckboxId: `selectAll-${tableId}`,
            deleteFormId: `delete-form-${tableId}`,
            hiddenInputsContainerId: `hidden-inputs-for-delete-${tableId}`,
            rowCheckboxClass: 'select-item-checkbox',
            editButtonClass: 'btn-edit-row',
            deleteButtonClass: 'btn-delete-row',
            noResultsMessage: 'Aucun élément trouvé.',
            onEditRow: function(rowElement, event) { console.warn('DataTableManager: onEditRow callback not implemented for row:', rowElement, event); },
            getSearchableText: function(tr) { return tr.textContent.toLowerCase(); }, // Fonction par défaut pour le texte de recherche
            getRowId: function(tr) { // Fonction pour obtenir l'ID unique d'une ligne
                const checkbox = tr.querySelector(`.${this.rowCheckboxClass}`);
                return checkbox ? checkbox.value : tr.dataset.id;
            }
        }, options);

        // Assurer que this.options.getRowId a accès au contexte de this.options
        this.options.getRowId = this.options.getRowId.bind(this.options);


        this.tbody = this.table.querySelector('tbody');
        this.searchInput = document.getElementById(this.options.searchInputId);
        this.paginationContainer = document.getElementById(this.options.paginationContainerId);
        this.resultsInfoContainer = document.getElementById(this.options.resultsInfoContainerId);
        this.selectAllCheckbox = document.getElementById(this.options.selectAllCheckboxId);
        this.deleteForm = document.getElementById(this.options.deleteFormId);

        if (this.deleteForm) {
            this.hiddenInputsContainer = this.deleteForm.querySelector(`#${this.options.hiddenInputsContainerId}`);
            if (!this.hiddenInputsContainer && this.options.selectAllCheckboxId) {
                 // Créer le conteneur s'il n'existe pas et qu'on a une gestion de sélection multiple
                this.hiddenInputsContainer = document.createElement('div');
                this.hiddenInputsContainer.id = this.options.hiddenInputsContainerId;
                this.deleteForm.appendChild(this.hiddenInputsContainer);
            }
        } else if (this.options.selectAllCheckboxId) {
            console.warn(`DataTableManager: deleteFormId '${this.options.deleteFormId}' not found, but selectAllCheckbox is present. Group delete might not work.`);
        }


        this.allRowsData = [];
        this.currentPage = 1;

        if (!this.tbody) {
            console.error(`DataTableManager: tbody not found in table '${tableId}'.`);
            this.isValid = false;
            return;
        }

        this._init();
    }

    _init() {
        if (!this.isValid) return;
        this.refreshRowData();
        this._bindGlobalListeners();
        this.renderTable();
    }

    refreshRowData() {
        if (!this.isValid || !this.tbody) return;
        this.allRowsData = Array.from(this.tbody.querySelectorAll('tr')).map(tr => ({
            element: tr, // Conserver l'élément original pour le clonage
            searchableText: this.options.getSearchableText(tr),
            id: this.options.getRowId(tr) // Stocker l'ID pour la gestion des sélections
        }));
    }

    updateTableContent(newTbodyHtmlOrRowsArray) {
        if (!this.isValid || !this.tbody) return;

        if (typeof newTbodyHtmlOrRowsArray === 'string') {
            this.tbody.innerHTML = newTbodyHtmlOrRowsArray;
        } else if (Array.isArray(newTbodyHtmlOrRowsArray)) {
            this.tbody.innerHTML = ''; // Vider
            newTbodyHtmlOrRowsArray.forEach(rowElement => this.tbody.appendChild(rowElement));
        }

        this.refreshRowData();
        this.currentPage = 1;
        this.renderTable();
    }

    _bindGlobalListeners() {
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => {
                this.currentPage = 1;
                this.renderTable();
            });
        }
        if (this.selectAllCheckbox) {
            this.selectAllCheckbox.addEventListener('change', (e) => this._handleSelectAll(e));
        }
    }

    _bindRowListeners(rowElement) { // Attacher à une ligne spécifique
        const checkbox = rowElement.querySelector(`.${this.options.rowCheckboxClass}`);
        if (checkbox) {
            checkbox.addEventListener('change', () => {
                this._updateSelectAllCheckboxState();
                if (this.deleteForm) this._updateDeleteFormState();
            });
        }

        const editBtn = rowElement.querySelector(`.${this.options.editButtonClass}`);
        if (editBtn) {
            editBtn.addEventListener('click', (e) => this.options.onEditRow(rowElement, e));
        }

        const deleteBtn = rowElement.querySelector(`.${this.options.deleteButtonClass}`);
        if (deleteBtn) {
            deleteBtn.addEventListener('click', (e) => this._handleDeleteSingle(rowElement, e));
        }
    }

    _getFilteredRows() {
        const searchTerm = this.searchInput ? this.searchInput.value.toLowerCase() : '';
        if (!searchTerm) return this.allRowsData;
        return this.allRowsData.filter(item => item.searchableText.includes(searchTerm));
    }

    renderTable() {
        if (!this.isValid || !this.tbody) return;
        const filteredData = this._getFilteredRows();
        const totalRows = filteredData.length;
        const totalPages = Math.ceil(totalRows / this.options.rowsPerPage);
        this.currentPage = Math.max(1, Math.min(this.currentPage, totalPages > 0 ? totalPages : 1));

        const start = (this.currentPage - 1) * this.options.rowsPerPage;
        const end = start + this.options.rowsPerPage;
        const paginatedData = filteredData.slice(start, end);

        this.tbody.innerHTML = ''; // Vider

        if (paginatedData.length > 0) {
            paginatedData.forEach(item => {
                const clonedRow = item.element.cloneNode(true); // Cloner pour éviter les problèmes de référence
                this._bindRowListeners(clonedRow); // Attacher les listeners à la ligne clonée
                this.tbody.appendChild(clonedRow);
            });
        } else {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = this.table.querySelector('thead tr')?.children.length || 1;
            td.textContent = this.options.noResultsMessage;
            td.style.textAlign = 'center';
            td.style.padding = '20px';
            tr.appendChild(td);
            this.tbody.appendChild(tr);
        }

        if (this.paginationContainer) this._renderPagination(totalPages);
        if (this.resultsInfoContainer) this._renderResultsInfo(start, end, totalRows);
        if (this.selectAllCheckbox) this._updateSelectAllCheckboxState();
        if (this.deleteForm) this._updateDeleteFormState();
    }

    _renderPagination(totalPages) {
        this.paginationContainer.innerHTML = '';
        if (totalPages <= 1) return;

        const createButton = (text, page, isDisabled = false, isActive = false) => {
            const button = document.createElement('button');
            button.className = 'pagination-btn' + (isActive ? ' active' : '');
            button.innerHTML = text;
            button.disabled = isDisabled;
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.currentPage = page;
                this.renderTable();
            });
            return button;
        };

        this.paginationContainer.appendChild(createButton('‹', this.currentPage - 1, this.currentPage === 1));
        for (let i = 1; i <= totalPages; i++) {
            this.paginationContainer.appendChild(createButton(i, i, false, i === this.currentPage));
        }
        this.paginationContainer.appendChild(createButton('›', this.currentPage + 1, this.currentPage === totalPages));
    }

    _renderResultsInfo(start, end, total) {
        this.resultsInfoContainer.textContent = `Affichage de ${total === 0 ? 0 : start + 1} à ${Math.min(end, total)} sur ${total} entrées`;
    }

    _handleSelectAll(e) {
        const isChecked = e.target.checked;
        // Mettre à jour les checkboxes des lignes actuellement visibles dans le DOM du tbody
        this.tbody.querySelectorAll(`.${this.options.rowCheckboxClass}`).forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        // Mettre aussi à jour l'état de sélection dans `allRowsData` pour la persistance entre paginations/filtrages
        // C'est plus complexe si on veut que "Tout sélectionner" affecte toutes les lignes filtrées, pas seulement visibles.
        // Pour l'instant, on affecte seulement les visibles, ce qui est un comportement courant.
        if (this.deleteForm) this._updateDeleteFormState();
    }

    _updateSelectAllCheckboxState() {
        if(!this.selectAllCheckbox) return;
        const visibleCheckboxes = Array.from(this.tbody.querySelectorAll(`.${this.options.rowCheckboxClass}`));
        if (visibleCheckboxes.length === 0) {
            this.selectAllCheckbox.checked = false;
            this.selectAllCheckbox.indeterminate = false;
            return;
        }
        const checkedVisible = visibleCheckboxes.filter(cb => cb.checked).length;
        this.selectAllCheckbox.checked = checkedVisible === visibleCheckboxes.length;
        this.selectAllCheckbox.indeterminate = checkedVisible > 0 && checkedVisible < visibleCheckboxes.length;
    }

    _updateDeleteFormState() {
        if (!this.deleteForm || !this.hiddenInputsContainer) return;
        this.hiddenInputsContainer.innerHTML = '';

        // On prend toutes les lignes de `allRowsData` dont la checkbox correspondante est cochée.
        // Cela nécessite que les checkboxes dans `allRowsData.element` reflètent l'état actuel.
        // Ou, plus simplement, on se base sur les checkboxes actuellement dans le DOM du tbody.
        const checkedItemsInDOM = Array.from(this.tbody.querySelectorAll(`.${this.options.rowCheckboxClass}:checked`));

        checkedItemsInDOM.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]'; // Nom standard pour les IDs multiples
            input.value = cb.value; // S'assurer que la checkbox a une 'value' pertinente (l'ID de l'item)
            this.hiddenInputsContainer.appendChild(input);
        });

        const deleteButton = this.deleteForm.querySelector('button[type="submit"]');
        if (deleteButton) deleteButton.disabled = checkedItemsInDOM.length === 0;

        if (this.deleteForm.dataset.warningDefault === undefined) {
            this.deleteForm.dataset.warningDefault = this.deleteForm.dataset.warning || "";
        }
        if (checkedItemsInDOM.length > 0) {
            this.deleteForm.dataset.warning = `Êtes-vous sûr de vouloir supprimer ${checkedItemsInDOM.length} élément(s) ?`;
        } else {
            this.deleteForm.dataset.warning = this.deleteForm.dataset.warningDefault;
        }
    }

    _handleDeleteSingle(rowElement, event) {
        if (!this.deleteForm) {
            console.warn('DataTableManager: Delete form not found for single delete.');
            return;
        }
        const idToDelete = this.options.getRowId(rowElement);
        const rowDescription = rowElement.cells[1]?.textContent || rowElement.cells[0]?.textContent || "cet élément";

        if (confirm(`Êtes-vous sûr de vouloir supprimer "${rowDescription}" ?`)) {
            this.hiddenInputsContainer.innerHTML = ''; // Vider
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = idToDelete;
            this.hiddenInputsContainer.appendChild(input);

            // Important: ajax.js s'attend à ce que le formulaire ait l'attribut data-warning pour la confirmation
            this.deleteForm.setAttribute('data-warning', `Êtes-vous sûr de vouloir supprimer "${rowDescription}" ?`);

            this.deleteForm.dispatchEvent(new Event('submit', {bubbles: true, cancelable: true}));
        }
    }
}
