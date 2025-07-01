<main class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Gestion des Utilisateurs</h1>
        </div>
        <div class="form-group small-width right-align  mb-20">
            <form action="/charger-formulaire-categorie" method="post" class="ajax-form" data-target="#zone-dynamique">
                <select class="form-input select-submit" id="categorie-utilisateur" name="categorie-utilisateur">
                    <option value="">Catégorie Utilisateur</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="administratif">Personnel Administratif</option>
                    <option value="etudiant">Etudiant</option>
                </select>
                <label class="form-label" for="categorie-utilisateur">Personnel Administratif</label>
            </form>
        </div>
    </div>

    <form class="form-section  ajax-form" method="post" action="/traitement-utilisateur"
          data-target=".table-scroll-wrapper" data-warning="AAA">
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
                                <option value="<?= $typeUtilisateur->getId() ?>" data-category-id="<?= $typeUtilisateur->getCategorieUtilisateurId() ?>">
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
                                <option value="<?= $groupeUtilisateur->getId() ?>" data-category-map="<?= $groupCategoryMap[$groupeUtilisateur->getId()] ?? '' ?>">
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

    <div id="zone-dynamique">
        <div class="table-container" id="container-userTable">
            <div class="table-header">
                <h3 class="table-title">Liste des Utilisateurs</h3>
                <div class="header-actions">
                    <div class="search-container">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput-userTable" class="search-input" placeholder="Rechercher par ...">
                    </div>
                </div>
                <div class="header-actions">
                    <button id="btnExportPDF-userTable" class="btn btn-secondary">🕐 Exporter en PDF</button>
                    <button id="btnExportExcel-userTable" class="btn btn-secondary">📤 Exporter sur Excel</button>
                    <button id="btnPrint-userTable" class="btn btn-secondary">📊 Imprimer</button>

                    <form id="delete-form-userTable" class="ajax-form" method="post" action="/traitement-utilisateur" data-warning="">
                        <input name="operation" value="supprimer" type="hidden">
                        <div id="hidden-inputs-for-delete-userTable"></div>
                        <button type="submit" class="btn btn-primary" id="btnSupprimerSelection-userTable">Supprimer</button>
                    </form>
                </div>
            </div>

            <div style="padding: 0 24px; border-bottom: 1px solid #E5E7EB;">
                <div class="table-tabs">
                    <div class="tab active">Tout sélectionner</div>
                </div>
            </div>
            <div class="table-scroll-wrapper scroll-custom">
                <table class="table" id="userTable">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll-userTable" class="checkbox"></th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Type d'utilisateur</th>
                        <th>Groupe utilisateur</th>
                        <th>Login</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($utilisateurs) && !empty($utilisateurs)): ?>
                        <?php foreach ($utilisateurs as $utilisateur): ?>
                            <tr>
                                <td><input type="checkbox" class="checkbox" value="<?= $utilisateur['id'] ?>"></td>
                                <td><?= htmlspecialchars($utilisateur['nom'] ?? '') ?></td>
                                <td><?= htmlspecialchars($utilisateur['prenoms'] ?? '') ?></td>
                                <td><?= htmlspecialchars($utilisateur['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($utilisateur['type_user'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($utilisateur['groupe'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($utilisateur['id'] ?? '') ?></td>
                                <td>
                                    <button class="btn-action">✏️</button>
                                    <button class="btn-action">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function () {

        function initializeInteractiveTable(tableId) {
            const container = document.getElementById(`container-${tableId}`);
            if (!container) return;

            const table = document.getElementById(tableId);
            const tableBody = table.querySelector('tbody');
            let allRows = Array.from(tableBody.querySelectorAll('tr'));

            const searchInput = document.getElementById(`searchInput-${tableId}`);
            const paginationContainer = document.getElementById(`pagination-${tableId}`);
            const resultsInfoContainer = document.getElementById(`resultsInfo-${tableId}`);
            const selectAllCheckbox = document.getElementById(`selectAll-${tableId}`);

            const deleteForm = document.getElementById(`delete-form-${tableId}`);
            const hiddenInputsContainer = document.getElementById(`hidden-inputs-for-delete-${tableId}`);

            const rowsPerPage = 9;
            let currentPage = 1;

            function updateTable() {
                const searchTerm = searchInput.value.toLowerCase();

                const filteredRows = allRows.filter(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchTerm);
                    row.style.display = 'none';
                    return isVisible;
                });

                const totalRows = filteredRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);

                if (currentPage > totalPages) currentPage = totalPages > 0 ? totalPages : 1;

                const startRow = (currentPage - 1) * rowsPerPage;
                const endRow = startRow + rowsPerPage;

                filteredRows.slice(startRow, endRow).forEach(row => row.style.display = '');

                setupPagination(totalPages, filteredRows);
                updateResultsInfo(startRow, endRow, totalRows);
                updateSelectAllCheckbox(filteredRows.slice(startRow, endRow));
            }

            function setupPagination(totalPages, filteredRows) {
                paginationContainer.innerHTML = '';
                if (totalPages <= 1) return;

                const createButton = (text, page, isDisabled = false) => {
                    const button = document.createElement('button');
                    button.className = 'pagination-btn';
                    button.innerHTML = text;
                    button.disabled = isDisabled;
                    button.addEventListener('click', () => {
                        currentPage = page;
                        updateTable();
                    });
                    return button;
                };

                paginationContainer.appendChild(createButton('‹', currentPage - 1, currentPage === 1));

                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = createButton(i, i);
                    if (i === currentPage) pageButton.classList.add('active');
                    paginationContainer.appendChild(pageButton);
                }

                paginationContainer.appendChild(createButton('›', currentPage + 1, currentPage === totalPages));
            }

            function updateResultsInfo(start, end, total) {
                const startNum = total === 0 ? 0 : start + 1;
                const endNum = Math.min(end, total);
                resultsInfoContainer.textContent = `Affichage de ${startNum} à ${endNum} sur ${total} entrées`;
            }

            function updateSelectAllCheckbox(visibleRows) {
                const allVisibleChecked = visibleRows.length > 0 && visibleRows.every(row => row.querySelector('input[type="checkbox"]').checked);
                selectAllCheckbox.checked = allVisibleChecked;
            }

            searchInput.addEventListener('input', () => {
                currentPage = 1;
                updateTable();
            });

            selectAllCheckbox.addEventListener('change', (e) => {
                const isChecked = e.target.checked;
                const filteredRows = allRows.filter(row => row.style.display !== 'none');
                const startRow = (currentPage - 1) * rowsPerPage;
                const endRow = startRow + rowsPerPage;

                filteredRows.slice(startRow, endRow).forEach(row => {
                    row.querySelector('input[type="checkbox"]').checked = isChecked;
                });
            });

            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    const checkedBoxes = Array.from(tableBody.querySelectorAll('input[type="checkbox"]:checked'));
                    const idsToDelete = checkedBoxes.map(cb => cb.value);

                    if (idsToDelete.length === 0) {
                        e.preventDefault();
                        if(typeof showPopup === 'function') {
                            showPopup("Veuillez sélectionner au moins un utilisateur à supprimer.", "error");
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
                });
            }

            updateTable();
        }

        function initializeUserFormInteractions() {
            const nomInput = document.getElementById('nom-utilisateur');
            const dateInput = document.getElementById('date-naissance');
            const loginInput = document.getElementById('login');
            const typeSelect = document.getElementById('id-type-utilisateur');
            const groupSelect = document.getElementById('id-groupe-utilisateur');
            const form = document.querySelector('form[action="/traitement-utilisateur"]');

            function generateLoginPreview() {
                const nom = nomInput.value.replace(/[^a-zA-Z]/g, '').toUpperCase().substring(0, 4);
                const dateValue = dateInput.value;

                if (nom.length > 0 && dateValue) {
                    const dateParts = dateValue.split('-');
                    const datePart = dateParts[2] + dateParts[1] + dateParts[0].substring(2);
                    loginInput.value = nom + datePart + '...';
                } else {
                    loginInput.value = 'login généré';
                }
            }

            function filterUserGroups() {
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                const categoryId = selectedOption.dataset.categoryId;

                // Réinitialiser le select
                groupSelect.value = "";

                let hasVisibleOptions = false;

                Array.from(groupSelect.options).forEach(option => {
                    if (option.value === "") {
                        option.style.display = 'block';
                        return;
                    }

                    if (option.dataset.categoryMap === categoryId) {
                        option.style.display = 'block';
                        hasVisibleOptions = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Sélectionner automatiquement la première option visible si il n'y en a qu'une
                if (hasVisibleOptions) {
                    const visibleOptions = Array.from(groupSelect.options).filter(
                        option => option.value !== "" && option.style.display !== 'none'
                    );

                    if (visibleOptions.length === 1) {
                        groupSelect.value = visibleOptions[0].value;
                    }
                }
            }

            // Validation avant soumission
            function validateForm(e) {
                const requiredFields = [
                    { element: nomInput, name: 'Nom' },
                    { element: document.getElementById('prenom-utilisateur'), name: 'Prénom' },
                    { element: document.getElementById('email-utilisateur'), name: 'Email' },
                    { element: dateInput, name: 'Date de naissance' },
                    { element: typeSelect, name: 'Type d\'utilisateur' },
                    { element: groupSelect, name: 'Groupe utilisateur' }
                ];

                let isValid = true;
                let errorMessage = '';

                requiredFields.forEach(field => {
                    if (!field.element.value || field.element.value.trim() === '') {
                        isValid = false;
                        errorMessage = `Le champ "${field.name}" est obligatoire.`;
                        field.element.focus();
                        return false;
                    }
                });

                // Validation spécifique de l'email
                const emailInput = document.getElementById('email-utilisateur');
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailInput.value && !emailPattern.test(emailInput.value)) {
                    isValid = false;
                    errorMessage = 'L\'adresse email n\'est pas valide.';
                    emailInput.focus();
                }

                // Vérifier que le groupe sélectionné est visible
                if (groupSelect.value) {
                    const selectedGroupOption = groupSelect.options[groupSelect.selectedIndex];
                    if (selectedGroupOption.style.display === 'none') {
                        isValid = false;
                        errorMessage = 'Le groupe d\'utilisateur sélectionné n\'est pas compatible avec le type d\'utilisateur.';
                        groupSelect.focus();
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    if (typeof showPopup === 'function') {
                        showPopup(errorMessage, "error");
                    } else {
                        alert(errorMessage);
                    }
                    return false;
                }

                return true;
            }

            // Event listeners
            nomInput.addEventListener('input', generateLoginPreview);
            dateInput.addEventListener('change', generateLoginPreview);
            typeSelect.addEventListener('change', filterUserGroups);

            if (form) {
                form.addEventListener('submit', validateForm);
            }

            // Initialisation
            filterUserGroups();
        }

        initializeInteractiveTable('userTable');
        initializeUserFormInteractions();
    });
</script>