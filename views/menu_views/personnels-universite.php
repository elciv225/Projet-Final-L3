<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion du Personnels de l'Université</h1>
        </div>
    </div>
    <form class="form-section  ajax-form" method="post" action="/traitement-utilisateur"
          data-target=".table-scroll-wrapper">
        <input name="operation" value="ajouter" type="hidden">
        <div class="section-header">
            <h3 class="section-title">Informations Générales de l'utilisateur</h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="nom-utilisateur" id="nom-utilisateur" class="form-input" placeholder=" ">
                    <label class="form-label" for="nom-utilisateur">Nom</label>
                </div>
                <div class="form-group">
                    <input type="text" name="prenom-utilisateur" id="prenom-utilisateur" class="form-input"
                           placeholder=" ">
                    <label class="form-label" for="prenom-utilisateur">Prénom</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email-utilisateur" id="email-utilisateur" class="form-input"
                           placeholder=" ">
                    <label class="form-label" for="email-utilisateur">Email</label>
                </div>
                <div class="form-group">
                    <input type="date" name="date-naissance" id="date-naissance" class="form-input" placeholder=" ">
                    <label class="form-label" for="date-naissance">Date de naissance</label>
                </div>
                <div class="form-group">
                    <select class="form-input" id="id-type-utilisateur" name="id-type-utilisateur">
                        <option value="">Type Utilisateur</option>
                        <?php if (isset($typesUtilisateur)): ?>
                            <?php foreach ($typesUtilisateur as $typeUtilisateur): ?>
                                <option value="<?= $typeUtilisateur->getId() ?>"
                                        data-category-id="<?= $typeUtilisateur->getCategorieUtilisateurId() ?>">
                                    <?= htmlspecialchars($typeUtilisateur->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-type-utilisateur">Type d'utilisateur</label>
                </div>
                <div class="form-group">
                    <?php
                    $groupCategoryMap = [
                        'GRP_ETUDIANTS' => 'CAT_ETUDIANT',
                        'GRP_ETUDIANT_STD' => 'CAT_ETUDIANT',
                        'GRP_VALID_RAPPORT' => 'CAT_ENSEIGNANT',
                        'GRP_ADMIN_PEDAGO' => 'CAT_ADMIN'
                    ];
                    ?>
                    <select class="form-input" id="id-groupe-utilisateur" name="id-groupe-utilisateur">
                        <option value="">Groupe Utilisateur</option>
                        <?php if (isset($groupesUtilisateur)): ?>
                            <?php foreach ($groupesUtilisateur as $groupeUtilisateur): ?>
                                <option value="<?= $groupeUtilisateur->getId() ?>"
                                        data-category-map="<?= $groupCategoryMap[$groupeUtilisateur->getId()] ?? '' ?>">
                                    <?= htmlspecialchars($groupeUtilisateur->getLibelle()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="form-label" for="id-groupe-utilisateur">Groupe utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="text" name="login" id="login" class="form-input" placeholder="" value="login généré"
                           readonly>
                    <label class="form-label" for="login">Login</label>
                </div>
            </div>
        </div>
        <div class="section-bottom">
            <h3 class="section-title">Action</h3>
            <div style="display: flex">
                <button class="btn btn-primary" type="submit">Créer</button>
            </div>
        </div>
    </form>
    <div class="form-section">
        <div class="section-header">
            <h3 class="section-title">Informations liées aux <?= $categorieUtilisateur ?? "Personnels de l'UFHB" ?></h3>
        </div>
        <div class="section-content">
            <div class="form-grid">
                <div class="form-group">
                    <select name="id-grade" class="form-input" id="id-grade">
                        <option value="">Choisir</option>
                    </select>
                    <label class="form-label" for="id-grade">Grade</label>
                </div>
                <div class="form-group">
                    <select name="id-fonction" class="form-input" id="id-fonction">
                        <option value="">Choisir</option>
                    </select>
                    <label class="form-label" for="id-fonction">Fonction</label>
                </div>
                <div class="form-group">
                    <input type="date" name="dategrade" class="form-input" placeholder=" " id="dategrade">
                    <label class="form-label" for="dategrade">Date du Grade</label>
                </div>
                <div class="form-group">
                    <input type="date" name="datefonction" class="form-input" placeholder=" " id="datefonction">
                    <label class="form-label" for="datefonction">Date de la Fonction</label>
                </div>
                <?php if ($categorieUtilisateur === "Enseignant") : ?>
                    <div class="form-group">
                        <select name="specialite" class="form-input" id="specialite">
                            <option value="">Choisir</option>
                        </select>
                        <label class="form-label" for="specialite">Spécialités</label>
                    </div>
                <?php endif ?>

            </div>
        </div>
    </div>

    <div id="zone-dynamique">
        <div class="table-container" id="container-userTable">
            <div class="table-header">
                <h3 class="table-title">Liste des Utilisateurs</h3>
                <div class="header-actions">
                    <div class="search-container">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput-userTable" class="search-input"
                               placeholder="Rechercher par ...">
                    </div>
                </div>
                <div class="header-actions">
                    <button id="btnExportPDF-userTable" class="btn btn-secondary">🕐 Exporter en PDF</button>
                    <button id="btnExportExcel-userTable" class="btn btn-secondary">📤 Exporter sur Excel</button>
                    <button id="btnPrint-userTable" class="btn btn-secondary">📊 Imprimer</button>

                    <form id="delete-form-userTable" class="ajax-form" method="post" action="/traitement-utilisateur"
                          data-warning="" data-target=".table-scroll-wrapper">
                        <input name="operation" value="supprimer" type="hidden">
                        <div id="hidden-inputs-for-delete-userTable"></div>
                        <button type="submit" class="btn btn-primary" id="btnSupprimerSelection-userTable">Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div style="padding: 0 24px; border-bottom: 1px solid #E5E7EB;">
                <div class="table-tabs">
                    <div class="tab active">Tout sélectionner</div>
                </div>
            </div>
            <div class="table-scroll-wrapper scroll-custom">
                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" class="checkbox"></th>
                        <th>Numero Matricule</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Date de naissance</th>
                        <th>Email</th>
                        <th>Grade</th>
                        <th>Fonction</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <div class="results-info" id="resultsInfo-userTable"></div>
                <div class="pagination" id="pagination-userTable"></div>
            </div>
        </div>
    </div>
</main>
<script>
    // =========================================================================
    //  SCRIPT REFACTORISÉ POUR LA GESTION DES VUES
    // =========================================================================
    (function () {
        // --- Définition des gestionnaires d'événements dans un scope accessible ---
        const eventHandlers = {
            handleSearchInput: function (tableState) {
                tableState.currentPage = 1;
                tableState.updateTable();
            },
            handleSelectAllChange: function (e, tableState) {
                const isChecked = e.target.checked;
                const {allRows, currentPage, rowsPerPage} = tableState;
                const filteredRows = allRows.filter(row => row.style.display !== 'none');
                const startRow = (currentPage - 1) * rowsPerPage;
                const endRow = startRow + rowsPerPage;

                filteredRows.slice(startRow, endRow).forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (checkbox) checkbox.checked = isChecked;
                });
            },
            handleDeleteFormSubmit: function (e, tableState) {
                const {tableBody, deleteForm, hiddenInputsContainer} = tableState;
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
                const {nomInput, dateInput, loginInput} = formState;
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
                const {typeSelect, groupSelect} = formState;
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
                updateTable: null // Sera défini ci-dessous
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

            eventHandlers.filterUserGroups(formState); // Initial call
        }

        // --- Initialisation et Rebinding ---
        function initializeAll() {
            initializeInteractiveTable('userTable');
            initializeUserFormInteractions();
        }

        // Exposer la fonction d'initialisation pour que ajax.js puisse l'appeler
        if (typeof window.ajaxRebinders !== 'undefined') {
            // S'assurer de ne pas l'ajouter plusieurs fois
            if (!window.ajaxRebinders.includes(initializeAll)) {
                window.ajaxRebinders.push(initializeAll);
            }
        }

        // Exécution initiale
        document.addEventListener('DOMContentLoaded', initializeAll);
    })();
</script>






