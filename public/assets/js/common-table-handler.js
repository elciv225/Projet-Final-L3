/**
 * Module de gestion des tableaux interactifs avec pagination, recherche et sélection.
 * Ce fichier centralise le code commun utilisé par plusieurs vues.
 */
(function() {
    /**
     * Gestionnaire de tableaux interactifs avec pagination, recherche et sélection.
     */
    const tableHandler = {
        /**
         * Initialise un tableau interactif.
         * @param {string} tableId - L'ID du tableau à initialiser
         * @param {Object} options - Options de configuration
         * @returns {Object} L'état du tableau
         */
        init: function (tableId, options = {}) {
            const table = document.getElementById(tableId);
            if (!table) return null;

            const state = {
                table,
                tableBody: table.querySelector('tbody'),
                allRows: Array.from(table.querySelector('tbody').querySelectorAll('tr')),
                searchInput: document.getElementById(`searchInput-${tableId}`),
                paginationContainer: document.getElementById(`pagination-${tableId}`),
                resultsInfoContainer: document.getElementById(`resultsInfo-${tableId}`),
                selectAllCheckbox: document.getElementById(`selectAll-${tableId}`),
                deleteForm: document.getElementById(`delete-form-${tableId}`),
                rowsPerPage: options.rowsPerPage || 9,
                currentPage: 1,
                entityName: options.entityName || 'élément',
                onEdit: options.onEdit || null,
            };

            if (!state.tableBody || state.allRows.length === 0 || !state.deleteForm) return null;

            const update = () => this.render(state);
            state.update = update;

            if (state.searchInput) state.searchInput.addEventListener('input', () => {
                state.currentPage = 1;
                update();
            });
            if (state.selectAllCheckbox) state.selectAllCheckbox.addEventListener('change', (e) => this.handleSelectAll(e, state));

            this.bindRowActions(state);
            update();
            
            return state;
        },

        render: function (state) {
            const searchTerm = state.searchInput ? state.searchInput.value.toLowerCase() : '';
            const filteredRows = state.allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));

            state.allRows.forEach(row => row.style.display = 'none');

            const totalPages = Math.ceil(filteredRows.length / state.rowsPerPage);
            state.currentPage = Math.min(state.currentPage, totalPages > 0 ? totalPages : 1);

            const start = (state.currentPage - 1) * state.rowsPerPage;
            const end = start + state.rowsPerPage;
            const visibleRows = filteredRows.slice(start, end);

            visibleRows.forEach(row => row.style.display = '');

            this.setupPagination(state, totalPages);
            this.updateResultsInfo(state, start, end, filteredRows.length);
            this.updateSelectAllCheckbox(state);
            this.updateDeleteForm(state);
        },

        setupPagination: function (state, totalPages) {
            if (!state.paginationContainer) return;
            state.paginationContainer.innerHTML = '';
            if (totalPages <= 1) return;

            const createButton = (text, page, isDisabled = false, isActive = false) => {
                const button = document.createElement('button');
                button.className = 'pagination-btn' + (isActive ? ' active' : '');
                button.innerHTML = text;
                button.disabled = isDisabled;
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    state.currentPage = page;
                    this.render(state);
                });
                return button;
            };

            state.paginationContainer.appendChild(createButton('‹', state.currentPage - 1, state.currentPage === 1));
            for (let i = 1; i <= totalPages; i++) {
                state.paginationContainer.appendChild(createButton(i, i, false, i === state.currentPage));
            }
            state.paginationContainer.appendChild(createButton('›', state.currentPage + 1, state.currentPage === totalPages));
        },

        updateResultsInfo: function (state, start, end, total) {
            if (!state.resultsInfoContainer) return;
            const startNum = total === 0 ? 0 : start + 1;
            const endNum = Math.min(end, total);
            state.resultsInfoContainer.textContent = `Affichage de ${startNum} à ${endNum} sur ${total} entrées`;
        },

        handleSelectAll: function(e, state) {
            const isChecked = e.target.checked;
            const filteredRows = state.allRows.filter(row => row.textContent.toLowerCase().includes(state.searchInput.value.toLowerCase()));
            filteredRows.forEach(row => {
                const checkbox = row.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = isChecked;
            });
            this.updateDeleteForm(state);
        },

        updateSelectAllCheckbox: function(state) {
            if (!state.selectAllCheckbox) return;
            const filteredRows = state.allRows.filter(row => row.textContent.toLowerCase().includes(state.searchInput.value.toLowerCase()));
            const checkboxes = filteredRows.map(row => row.querySelector('input[type="checkbox"]')).filter(Boolean);
            state.selectAllCheckbox.checked = checkboxes.length > 0 && checkboxes.every(cb => cb.checked);
        },

        updateDeleteForm: function(state) {
            const hiddenContainer = state.deleteForm.querySelector(`div[id^="hidden-inputs-for-delete-"]`);
            if (!hiddenContainer) return;

            const checkedBoxes = Array.from(state.tableBody.querySelectorAll('input[type="checkbox"]:checked'));
            const idsToDelete = checkedBoxes.map(cb => cb.value);

            hiddenContainer.innerHTML = '';
            idsToDelete.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                hiddenContainer.appendChild(input);
            });

            if (idsToDelete.length > 0) {
                state.deleteForm.setAttribute('data-warning', `Êtes-vous sûr de vouloir supprimer ${idsToDelete.length} ${state.entityName}(s) ?`);
            } else {
                state.deleteForm.removeAttribute('data-warning');
            }
        },

        bindRowActions: function(state) {
            state.tableBody.querySelectorAll('tr').forEach(row => {
                const deleteBtn = row.querySelector('.btn-delete-single');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const userId = row.dataset.userId;
                        const userName = row.querySelector('.user-data')?.textContent.trim() || `cet ${state.entityName}`;
                        const message = `Êtes-vous sûr de vouloir supprimer "${userName}" (${userId}) ?`;

                        state.deleteForm.setAttribute('data-warning', message);

                        const hiddenContainer = state.deleteForm.querySelector(`div[id^="hidden-inputs-for-delete-"]`);
                        hiddenContainer.innerHTML = '';
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = userId;
                        hiddenContainer.appendChild(input);

                        state.deleteForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    });
                }

                const editBtn = row.querySelector('.btn-edit');
                if (editBtn && state.onEdit) {
                    editBtn.addEventListener('click', () => state.onEdit(row));
                }

                const checkbox = row.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.addEventListener('change', () => {
                        this.updateSelectAllCheckbox(state);
                        this.updateDeleteForm(state);
                    });
                }
            });
        }
    };

    // Exporter le gestionnaire de tableaux
    window.tableHandler = tableHandler;
})();