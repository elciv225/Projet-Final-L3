(function () {
    const eventHandlers = {
        handleSearchInput: function (tableState) {
            tableState.currentPage = 1;
            tableState.updateTable();
        },
        handleSelectAllChange: function (e, tableState) {
            const isChecked = e.target.checked;
            const { allRows, currentPage, rowsPerPage } = tableState;
            const filteredRows = allRows.filter(row => row.style.display !== 'none');
            const startRow = (currentPage - 1) * rowsPerPage;
            const endRow = startRow + rowsPerPage;

            filteredRows.slice(startRow, endRow).forEach(row => {
                const checkbox = row.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = isChecked;
            });
        },
        handleDeleteFormSubmit: function (e, tableState) {
            const { tableBody, deleteForm, hiddenInputsContainer } = tableState;
            const checkedBoxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]:checked'));
            const idsToDelete = checkedBoxes.map(cb => cb.value);

            if (idsToDelete.length === 0) {
                e.preventDefault();
                if (typeof window.showPopup === 'function') {
                    window.showPopup("Veuillez sélectionner au moins un utilisateur à supprimer.", "error");
                } else {
                    alert("Veuillez sélectionner au moins un utilisateur à supprimer.");
                }
                return;
            }

            const warningMessage = `Êtes-vous sûr de vouloir supprimer ${idsToDelete.length} utilisateur(s) ?`;
            deleteForm.setAttribute('data-warning', warningMessage);

            hiddenInputsContainer.innerHTML = '';
            idsToDelete.forEach(id => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ids[]';
                hiddenInput.value = id;
                hiddenInputsContainer.appendChild(hiddenInput);
            });
        },
        generateLoginPreview: function (formState) {
            const { nomInput, dateInput, loginInput } = formState;
            const nom = nomInput.value.replace(/[^a-zA-Z]/g, '').toUpperCase().substring(0, 4);
            const dateValue = dateInput.value;

            if (nom.length > 0 && dateValue) {
                const dateParts = dateValue.split('-');
                if (dateParts.length === 3) {
                    const datePart = dateParts[2] + dateParts[1] + dateParts[0].substring(2);
                    loginInput.value = nom + datePart + '...';
                }
            } else {
                loginInput.value = 'login généré';
            }
        },
        filterUserGroups: function (formState) {
            const { typeSelect, groupSelect } = formState;
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            if (!selectedOption) return;

            const categoryId = selectedOption.dataset.categoryId;
            let firstVisibleOptionValue = null;

            Array.from(groupSelect.options).forEach(option => {
                option.style.display = 'none';
                if (option.value === "" || option.dataset.categoryMap === categoryId) {
                    option.style.display = 'block';
                    if (option.value !== "" && firstVisibleOptionValue === null) {
                        firstVisibleOptionValue = option.value;
                    }
                }
            });
            groupSelect.value = firstVisibleOptionValue || "";
        }
    };

    function initializeInteractiveTable(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const tableState = {
            table: table,
            tableBody: table.querySelector('tbody'),
            allRows: Array.from(table.querySelector('tbody').querySelectorAll('tr')),
            searchInput: document.getElementById(`searchInput-${tableId}`),
            paginationContainer: document.getElementById(`pagination-${tableId}`),
            resultsInfoContainer: document.getElementById(`resultsInfo-${tableId}`),
            selectAllCheckbox: document.getElementById(`selectAll-${tableId}`),
            deleteForm: document.getElementById(`delete-form-${tableId}`),
            hiddenInputsContainer: document.getElementById(`hidden-inputs-for-delete-${tableId}`),
            rowsPerPage: 9,
            currentPage: 1,
            updateTable: null
        };

        if (!tableState.tableBody) return;

        function updateTable() {
            const searchTerm = tableState.searchInput ? tableState.searchInput.value.toLowerCase() : '';
            const filteredRows = tableState.allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));

            tableState.allRows.forEach(row => row.style.display = 'none');

            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / tableState.rowsPerPage);
            if (tableState.currentPage > totalPages) tableState.currentPage = totalPages > 0 ? totalPages : 1;

            const startRow = (tableState.currentPage - 1) * tableState.rowsPerPage;
            const endRow = startRow + tableState.rowsPerPage;
            const visibleRows = filteredRows.slice(startRow, endRow);

            visibleRows.forEach(row => row.style.display = '');

            setupPagination(totalPages);
            updateResultsInfo(startRow, endRow, totalRows);
            updateSelectAllCheckbox(visibleRows);
        }

        tableState.updateTable = updateTable;

        function setupPagination(totalPages) {
            if (!tableState.paginationContainer) return;
            tableState.paginationContainer.innerHTML = '';
            if (totalPages <= 1) return;

            const createButton = (text, page, isDisabled = false) => {
                const button = document.createElement('button');
                button.className = 'pagination-btn';
                button.innerHTML = text;
                button.disabled = isDisabled;
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    tableState.currentPage = page;
                    updateTable();
                });
                return button;
            };

            tableState.paginationContainer.appendChild(createButton('‹', tableState.currentPage - 1, tableState.currentPage === 1));
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = createButton(i, i);
                if (i === tableState.currentPage) pageButton.classList.add('active');
                tableState.paginationContainer.appendChild(pageButton);
            }
            tableState.paginationContainer.appendChild(createButton('›', tableState.currentPage + 1, tableState.currentPage === totalPages));
        }

        function updateResultsInfo(start, end, total) {
            if (!tableState.resultsInfoContainer) return;
            const startNum = total === 0 ? 0 : start + 1;
            const endNum = Math.min(end, total);
            tableState.resultsInfoContainer.textContent = `Affichage de ${startNum} à ${endNum} sur ${total} entrées`;
        }

        function updateSelectAllCheckbox(visibleRows) {
            if (!tableState.selectAllCheckbox) return;
            const checkboxes = visibleRows.map(row => row.querySelector('input[type="checkbox"]')).filter(Boolean);
            tableState.selectAllCheckbox.checked = checkboxes.length > 0 && checkboxes.every(cb => cb.checked);
        }

        if (tableState.searchInput) tableState.searchInput.addEventListener('input', () => eventHandlers.handleSearchInput(tableState));
        if (tableState.selectAllCheckbox) tableState.selectAllCheckbox.addEventListener('change', (e) => eventHandlers.handleSelectAllChange(e, tableState));
        if (tableState.deleteForm) tableState.deleteForm.addEventListener('submit', (e) => eventHandlers.handleDeleteFormSubmit(e, tableState));

        updateTable();
    }

    function initializeUserFormInteractions() {
        const formState = {
            nomInput: document.getElementById('nom-utilisateur'),
            dateInput: document.getElementById('date-naissance'),
            loginInput: document.getElementById('login'),
            typeSelect: document.getElementById('id-type-utilisateur'),
            groupSelect: document.getElementById('id-groupe-utilisateur')
        };

        if (!formState.nomInput || !formState.dateInput || !formState.loginInput || !formState.typeSelect || !formState.groupSelect) return;

        formState.nomInput.addEventListener('input', () => eventHandlers.generateLoginPreview(formState));
        formState.dateInput.addEventListener('change', () => eventHandlers.generateLoginPreview(formState));
        formState.typeSelect.addEventListener('change', () => eventHandlers.filterUserGroups(formState));

        eventHandlers.filterUserGroups(formState);
    }

    function initializeUserModule() {
        initializeInteractiveTable('userTable');
        initializeUserFormInteractions();
    }

    window.ajaxRebinders = window.ajaxRebinders || [];
    window.ajaxRebinders.push(initializeUserModule);
})();
