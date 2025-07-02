(function () {
    /**
     * Gère les interactions spécifiques au formulaire étudiant.
     */
    const formHandler = {
        init: function () {
            const form = document.querySelector('form[action="/traitement-etudiant"]');
            if (!form) return;

            const cancelBtn = form.querySelector('#btn-cancel-edit');
            const editOnlyFieldsContainer = document.getElementById('edit-only-fields');

            if (cancelBtn) {
                cancelBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    form.reset();
                    form.querySelector('#id-etudiant-form').value = '';
                    form.querySelector('#form-operation').value = 'inscrire';
                    form.querySelector('#form-title').textContent = 'Inscrire un nouvel étudiant';
                    form.querySelector('#btn-submit-form').textContent = 'Inscrire';
                    cancelBtn.style.display = 'none';

                    if (editOnlyFieldsContainer) {
                        editOnlyFieldsContainer.style.display = 'none';
                    }
                });
            }
        },

        populateForEdit: function(row) {
            const form = document.querySelector('form[action="/traitement-etudiant"]');
            if (!form || !row) return;

            const id = row.querySelector('.id-etudiant')?.textContent.trim();
            const carte = row.querySelector('.numero-carte')?.textContent.trim();
            const nom = row.querySelector('.nom-prenoms')?.dataset.nom || '';
            const prenoms = row.querySelector('.nom-prenoms')?.dataset.prenoms || '';
            const naissance = row.querySelector('.nom-prenoms')?.dataset.dateNaissance || '';
            const email = row.querySelector('.email-etudiant')?.textContent.trim();
            const niveauId = row.querySelector('.niveau-etude')?.dataset.niveauId;
            const anneeId = row.querySelector('.annee-academique')?.dataset.anneeId;
            const montant = row.querySelector('.montant-inscription')?.textContent.trim().replace(/[^0-9]/g, '');

            form.querySelector('#nom-etudiant').value = nom;
            form.querySelector('#prenom-etudiant').value = prenoms;
            form.querySelector('#email-etudiant').value = email;
            form.querySelector('#date-naissance').value = naissance;
            form.querySelector('#id-niveau-etude').value = niveauId;
            form.querySelector('#id-annee-academique').value = anneeId;
            form.querySelector('#montant-inscription').value = montant;

            form.querySelector('#id-etudiant-form').value = id;
            form.querySelector('#form-operation').value = 'modifier';

            const editOnlyFieldsContainer = document.getElementById('edit-only-fields');
            if (editOnlyFieldsContainer) {
                document.getElementById('id-etudiant-display').value = id;
                document.getElementById('numero-carte-display').value = carte;
                editOnlyFieldsContainer.style.display = 'grid';
            }

            form.querySelector('#form-title').textContent = 'Modifier les informations de l’étudiant';
            form.querySelector('#btn-submit-form').textContent = 'Modifier';
            form.querySelector('#btn-cancel-edit').style.display = 'inline-block';

            window.scrollTo({ top: 0, behavior: 'smooth' });
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

            // Note: Le clic sur le bouton de suppression est maintenant géré par le handler de submit de ajax.js
            // Il n'est plus nécessaire de lier un événement de clic ici.

            this.bindRowActions(tableState);
            updateTable();
        },

        getFilteredRows: function(state) {
            const searchTerm = state.searchInput ? state.searchInput.value.toLowerCase() : '';
            if (!searchTerm) {
                return state.allRows;
            }
            return state.allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));
        },

        update: function (state) {
            const filteredRows = this.getFilteredRows(state);

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
            this.updateDeleteForm(state); // Assurer la cohérence à chaque mise à jour
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
            const filteredRows = this.getFilteredRows(state);

            filteredRows.forEach(row => {
                const checkbox = row.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = isChecked;
            });
            this.updateDeleteForm(state); // Mettre à jour le formulaire
        },

        updateSelectAllCheckbox: function(state) {
            if (!state.selectAllCheckbox) return;
            const filteredRows = this.getFilteredRows(state);
            const checkboxes = filteredRows.map(row => row.querySelector('input[type="checkbox"]')).filter(Boolean);
            state.selectAllCheckbox.checked = checkboxes.length > 0 && checkboxes.every(cb => cb.checked);
        },

        /**
         * **CORRECTION**: Met à jour les inputs cachés ET l'attribut data-warning en temps réel.
         */
        updateDeleteForm: function(state) {
            const hiddenInputsContainer = state.deleteForm.querySelector('#hidden-inputs-for-delete-etudiantTable');
            if (!hiddenInputsContainer) return;

            const checkedBoxes = Array.from(state.tableBody.querySelectorAll('input[type="checkbox"]:checked'));
            const idsToDelete = checkedBoxes.map(cb => cb.value);

            hiddenInputsContainer.innerHTML = ''; // Vide les anciens inputs

            idsToDelete.forEach(id => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ids[]';
                hiddenInput.value = id;
                hiddenInputsContainer.appendChild(hiddenInput);
            });

            // Mettre à jour le message de confirmation
            if (idsToDelete.length > 0) {
                const message = `Êtes-vous sûr de vouloir supprimer ${idsToDelete.length} étudiant(s) ?`;
                state.deleteForm.setAttribute('data-warning', message);
            } else {
                // Retirer l'attribut si rien n'est sélectionné pour éviter une fausse confirmation
                state.deleteForm.removeAttribute('data-warning');
            }
        },

        bindRowActions: function(state) {
            state.tableBody.querySelectorAll('tr').forEach(row => {
                const deleteBtn = row.querySelector('.btn-delete-single');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const userId = row.querySelector('.id-etudiant')?.textContent.trim();
                        const userName = row.querySelector('.nom-prenoms')?.textContent.trim() || 'cet étudiant';
                        const message = `Êtes-vous sûr de vouloir supprimer l'étudiant "${userName}" (${userId}) ?`;

                        // Préparer le formulaire pour la soumission
                        state.deleteForm.setAttribute('data-warning', message);

                        const hiddenInputsContainer = state.deleteForm.querySelector('#hidden-inputs-for-delete-etudiantTable');
                        hiddenInputsContainer.innerHTML = '';
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'ids[]';
                        hiddenInput.value = userId;
                        hiddenInputsContainer.appendChild(hiddenInput);

                        // Lancer la soumission, qui sera interceptée par ajax.js
                        state.deleteForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    });
                }

                const editBtn = row.querySelector('.btn-edit');
                if (editBtn) {
                    editBtn.addEventListener('click', () => {
                        formHandler.populateForEdit(row);
                    });
                }

                const checkbox = row.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.addEventListener('change', () => {
                        this.updateSelectAllCheckbox(state);
                        this.updateDeleteForm(state); // Mettre à jour le formulaire
                    });
                }
            });
        }
    };

    /**
     * Initialise tous les modules de la page.
     */
    function initializeEtudiantModule() {
        formHandler.init();
        tableHandler.init('etudiantTable');
    }

    document.addEventListener('DOMContentLoaded', initializeEtudiantModule);
    window.ajaxRebinders = window.ajaxRebinders || [];
    window.ajaxRebinders.push(initializeEtudiantModule);

})();
