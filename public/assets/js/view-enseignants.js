(function () {
    /**
     * Gère les interactions spécifiques au formulaire enseignant.
     */
    const formHandler = {
        form: document.querySelector('form[action="/traitement-enseignant"]'),

        init: function() {
            if (!this.form) return;

            this.nomInput = document.getElementById('nom-enseignant');
            this.prenomInput = document.getElementById('prenom-enseignant');
            this.loginInput = document.getElementById('login');
            this.cancelBtn = document.getElementById('btn-cancel-edit');

            this.bindEvents();
        },

        bindEvents: function() {
            if (this.nomInput && this.prenomInput) {
                this.nomInput.addEventListener('input', () => this.updateLogin());
                this.prenomInput.addEventListener('input', () => this.updateLogin());
            }
            if (this.cancelBtn) {
                this.cancelBtn.addEventListener('click', () => this.reset());
            }
        },

        updateLogin: function() {
            if (!this.loginInput || !this.nomInput || !this.prenomInput) return;
            const nom = this.nomInput.value.toLowerCase().replace(/[^a-z]/g, '');
            const prenom = this.prenomInput.value.toLowerCase().charAt(0);
            this.loginInput.value = (nom && prenom) ? prenom + nom : '';
        },

        populateForEdit: function(row) {
            this.reset();

            const userData = row.querySelector('.user-data');

            this.form.querySelector('#id-utilisateur-form').value = row.dataset.userId;
            this.form.querySelector('#nom-enseignant').value = userData.dataset.nom;
            this.form.querySelector('#prenom-enseignant').value = userData.dataset.prenoms;
            this.form.querySelector('#email-enseignant').value = userData.dataset.email;
            this.form.querySelector('#date-naissance').value = userData.dataset.naissance;

            this.form.querySelector('#id-grade').value = row.querySelector('.grade-data')?.dataset.gradeId || '';
            this.form.querySelector('#id-specialite').value = row.querySelector('.specialite-data')?.dataset.specialiteId || '';
            this.form.querySelector('#id-fonction').value = row.querySelector('.fonction-data')?.dataset.fonctionId || '';

            this.updateLogin();
            this.form.querySelector('#form-operation').value = 'modifier';
            this.form.querySelector('#form-title').textContent = 'Modifier les informations de l\'enseignant';
            this.form.querySelector('#btn-submit-form').textContent = 'Modifier';
            this.cancelBtn.style.display = 'inline-block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        reset: function() {
            this.form.reset();
            this.form.querySelector('#id-utilisateur-form').value = '';
            this.form.querySelector('#form-operation').value = 'ajouter';
            this.form.querySelector('#form-title').textContent = 'Ajouter un nouvel enseignant';
            this.form.querySelector('#btn-submit-form').textContent = 'Ajouter';
            if (this.cancelBtn) this.cancelBtn.style.display = 'none';
            this.updateLogin();
        }
    };

    /**
     * Gère la logique du tableau interactif.
     */
    const tableHandler = {
        init: function (tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const tableState = {
                table,
                tableBody: table.querySelector('tbody'),
                allRows: Array.from(table.querySelector('tbody').querySelectorAll('tr')),
                searchInput: document.getElementById(`searchInput-${tableId}`),
                paginationContainer: document.getElementById(`pagination-${tableId}`),
                resultsInfoContainer: document.getElementById(`resultsInfo-${tableId}`),
                selectAllCheckbox: document.getElementById(`selectAll-${tableId}`),
                deleteForm: document.getElementById(`delete-form-${tableId}`),
                rowsPerPage: 9,
                currentPage: 1,
            };

            if (!tableState.tableBody || tableState.allRows.length === 0 || !tableState.deleteForm) return;

            const updateTable = () => this.update(tableState);
            tableState.updateTable = updateTable;

            if (tableState.searchInput) tableState.searchInput.addEventListener('input', () => {
                tableState.currentPage = 1;
                updateTable();
            });
            if (tableState.selectAllCheckbox) tableState.selectAllCheckbox.addEventListener('change', (e) => this.handleSelectAll(e, tableState));

            this.bindRowActions(tableState);
            updateTable();
        },

        update: function (state) {
            const searchTerm = state.searchInput ? state.searchInput.value.toLowerCase() : '';
            const filteredRows = state.allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));

            state.allRows.forEach(row => row.style.display = 'none');

            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / state.rowsPerPage);
            if (state.currentPage > totalPages) state.currentPage = totalPages > 0 ? totalPages : 1;

            const startRow = (state.currentPage - 1) * state.rowsPerPage;
            const endRow = startRow + state.rowsPerPage;
            const visibleRows = filteredRows.slice(startRow, endRow);

            visibleRows.forEach(row => row.style.display = '');

            this.setupPagination(state, totalPages);
            this.updateResultsInfo(state, startRow, endRow, totalRows);
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
                    this.update(state);
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
            const filteredRows = state.allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));
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
            const hiddenInputsContainer = state.deleteForm.querySelector(`div[id^="hidden-inputs-for-delete-"]`);
            if (!hiddenInputsContainer) return;

            const checkedBoxes = Array.from(state.tableBody.querySelectorAll('input[type="checkbox"]:checked'));
            const idsToDelete = checkedBoxes.map(cb => cb.value);

            hiddenInputsContainer.innerHTML = '';
            idsToDelete.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                hiddenInputsContainer.appendChild(input);
            });

            if (idsToDelete.length > 0) {
                state.deleteForm.setAttribute('data-warning', `Êtes-vous sûr de vouloir supprimer ${idsToDelete.length} enseignant(s) ?`);
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
                        const userName = row.querySelector('.user-data')?.textContent.trim() || 'cet enseignant';
                        const message = `Êtes-vous sûr de vouloir supprimer "${userName}" (${userId}) ?`;

                        state.deleteForm.setAttribute('data-warning', message);

                        const hiddenInputsContainer = state.deleteForm.querySelector(`div[id^="hidden-inputs-for-delete-"]`);
                        hiddenInputsContainer.innerHTML = '';
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = userId;
                        hiddenInputsContainer.appendChild(input);

                        state.deleteForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    });
                }

                const editBtn = row.querySelector('.btn-edit');
                if (editBtn) {
                    editBtn.addEventListener('click', () => formHandler.populateForEdit(row));
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

    /**
     * Initialise tous les modules de la page.
     */
    function initializeEnseignantModule() {
        formHandler.init();
        tableHandler.init('enseignantTable');
    }

    document.addEventListener('DOMContentLoaded', initializeEnseignantModule);
    window.ajaxRebinders = window.ajaxRebinders || [];
    window.ajaxRebinders.push(initializeEnseignantModule);

})();
