// =========================================================================
//  SCRIPT POUR LA GESTION DES UTILISATEURS (Vue Utilisateurs)
// =========================================================================
// Assurez-vous que DataTableManager.js est chargé avant ce script.

(function () {
    const TABLE_ID = 'userTable'; // ID de la table des utilisateurs
    let utilisateurTableManager = null;

    /**
     * Gère les interactions spécifiques au formulaire des utilisateurs.
     */
    const formHandler = {
        form: null,
        operationInput: null,
        idUtilisateurInput: null, // Input caché pour l'ID en mode modification
        formTitle: null,
        submitButton: null,
        cancelButton: null, // Sera ajouté dynamiquement ou via HTML
        cancelButtonContainer: null,
        loginInput: null,
        nomInput: null,
        dateNaissanceInput: null,

        init: function () {
            this.form = document.querySelector('form[action="/traitement-utilisateur"]');
            if (!this.form) {
                console.warn("Formulaire utilisateur non trouvé pour initialisation.");
                return;
            }

            this.operationInput = this.form.querySelector('input[name="operation"]');
            // L'input id-utilisateur est ajouté dynamiquement par le JS pour le mode 'modifier'
            // S'il est déjà dans le HTML (ex: <input type="hidden" name="id-utilisateur">), on peut le récupérer ici.
            this.idUtilisateurInput = this.form.querySelector('input[name="id-utilisateur"]');
            if (!this.idUtilisateurInput) {
                this.idUtilisateurInput = document.createElement('input');
                this.idUtilisateurInput.type = 'hidden';
                this.idUtilisateurInput.name = 'id-utilisateur';
                this.form.appendChild(this.idUtilisateurInput);
            }

            this.formTitle = this.form.querySelector('.section-title'); // Ajuster si le titre a un ID/classe spécifique
            this.submitButton = this.form.querySelector('button[type="submit"]');
            this.cancelButtonContainer = this.form.querySelector('#cancel-button-container'); // Le conteneur du bouton
            this.cancelButton = this.form.querySelector('#btn-cancel-edit-user');


            this.loginInput = this.form.querySelector('#login');
            this.nomInput = this.form.querySelector('#nom-utilisateur');
            this.dateNaissanceInput = this.form.querySelector('#date-naissance');

            if (this.cancelButton) {
                this.cancelButton.addEventListener('click', this.resetForm.bind(this));
            }
            if (this.nomInput) this.nomInput.addEventListener('input', this.generateLoginPreview.bind(this));
            if (this.dateNaissanceInput) this.dateNaissanceInput.addEventListener('change', this.generateLoginPreview.bind(this));

            const typeUserSelect = this.form.querySelector('#id-type-utilisateur');
            if (typeUserSelect) {
                typeUserSelect.addEventListener('change', this.filterUserGroups.bind(this));
            }
            this.filterUserGroups(); // Appel initial
            this.resetForm();
        },

        resetForm: function () {
            if (!this.form) return;
            this.form.reset();
            if(this.idUtilisateurInput) this.idUtilisateurInput.value = '';
            if(this.operationInput) this.operationInput.value = 'ajouter';
            // Le titre est déjà "Informations Générales de l'Utilisateur" et ne change pas ici.
            // Il pourrait être changé par populateForEdit.
            if(this.submitButton) this.submitButton.textContent = 'Créer';

            if(this.cancelButtonContainer) this.cancelButtonContainer.style.display = 'none';
            if(this.cancelButton && this.cancelButton.parentNode !== this.cancelButtonContainer) {
                 // Si le bouton annuler n'est pas dans son conteneur, le cacher aussi.
                this.cancelButton.style.display = 'none';
            }


            if(this.loginInput) {
                this.loginInput.value = 'login généré';
                this.loginInput.readOnly = true;
            }
            this.filterUserGroups();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        populateForEdit: function (row) {
            if (!this.form || !row) return;

            this.form.querySelector('#nom-utilisateur').value = row.dataset.nom || '';
            this.form.querySelector('#prenom-utilisateur').value = row.dataset.prenoms || '';
            this.form.querySelector('#email-utilisateur').value = row.dataset.email || '';
            this.form.querySelector('#date-naissance').value = row.dataset.dateNaissance || '';
            this.form.querySelector('#id-type-utilisateur').value = row.dataset.typeUtilisateurId || '';
            this.filterUserGroups();
            this.form.querySelector('#id-groupe-utilisateur').value = row.dataset.groupeUtilisateurId || '';
            if(this.loginInput) {
                this.loginInput.value = row.dataset.id || '';
                this.loginInput.readOnly = true;
            }

            if(this.idUtilisateurInput) this.idUtilisateurInput.value = row.dataset.id || '';
            if(this.operationInput) this.operationInput.value = 'modifier';
            // Le titre du formulaire pourrait être modifié ici si on avait un ID spécifique pour le titre du formulaire.
            // this.formTitle.textContent = 'Modifier les informations de l’utilisateur';
            if(this.submitButton) this.submitButton.textContent = 'Modifier';

            if(this.cancelButtonContainer) this.cancelButtonContainer.style.display = 'block';
             if(this.cancelButton && this.cancelButton.parentNode !== this.cancelButtonContainer) {
                this.cancelButton.style.display = 'inline-block'; // Assurer la visibilité
            }


            window.scrollTo({ top: this.form.offsetTop - 20, behavior: 'smooth' });
        },
        generateLoginPreview: function () {
            if (!this.nomInput || !this.dateNaissanceInput || !this.loginInput || (this.operationInput && this.operationInput.value !== 'ajouter')) return;

            const nomVal = this.nomInput.value.replace(/[^a-zA-Z]/g, '').toUpperCase().substring(0, 4);
            const dateVal = this.dateNaissanceInput.value;

            if (nomVal.length > 0 && dateVal) {
                try {
                    const dateParts = dateVal.split('-');
                    if (dateParts.length === 3) {
                        const datePart = dateParts[2] + dateParts[1] + dateParts[0].substring(2);
                        this.loginInput.value = nomVal + datePart + '... (auto)';
                    } else {
                        this.loginInput.value = 'login généré';
                    }
                } catch (e) { this.loginInput.value = 'login généré'; }
            } else {
                this.loginInput.value = 'login généré';
            }
        },
        filterUserGroups: function() {
            if (!this.form) return;
            const typeSelect = this.form.querySelector('#id-type-utilisateur');
            const groupSelect = this.form.querySelector('#id-groupe-utilisateur');
            if (!typeSelect || !groupSelect) return;

            const selectedTypeOption = typeSelect.options[typeSelect.selectedIndex];
            const categoryId = selectedTypeOption ? selectedTypeOption.dataset.categoryId : null;

            let firstVisibleOptionValue = null;

            Array.from(groupSelect.options).forEach(option => {
                if (option.value === "") {
                    option.style.display = 'block'; return;
                }
                if (categoryId && option.dataset.categoryMap === categoryId) {
                    option.style.display = 'block';
                    if (firstVisibleOptionValue === null) firstVisibleOptionValue = option.value;
                } else {
                    option.style.display = 'none';
                }
            });

            const currentGroupValue = groupSelect.value;
            if (groupSelect.options[groupSelect.selectedIndex] && groupSelect.options[groupSelect.selectedIndex].style.display === 'none') {
                 groupSelect.value = firstVisibleOptionValue || "";
            } else if (!currentGroupValue && firstVisibleOptionValue) {
                groupSelect.value = firstVisibleOptionValue;
            } else if (!categoryId) { // Aucun type sélectionné, vider la sélection de groupe
                groupSelect.value = "";
            }
        }
    };

    function initializeUtilisateurModule() {
        formHandler.init();

        if (typeof DataTableManager === 'undefined') {
            console.error('DataTableManager is not loaded. Skipping table initialization for utilisateurs.');
            return;
        }

        const tableElement = document.getElementById(TABLE_ID);
        if (!tableElement || !tableElement.querySelector('tbody')) {
            console.warn(`Table or tbody for '${TABLE_ID}' not found. DataTableManager not initialized.`);
            return;
        }

        utilisateurTableManager = new DataTableManager(TABLE_ID, {
            rowsPerPage: 10,
            rowCheckboxClass: 'select-user',
            editButtonClass: 'btn-edit-user',
            deleteButtonClass: 'btn-delete-single-user',
            onEditRow: (rowElement, event) => {
                formHandler.populateForEdit(rowElement);
            },
            getRowId: function(tr) { return tr.dataset.id; },
            getSearchableText: function(tr) {
                // Recherche sur nom, prénom, email, login (ID)
                 return (tr.dataset.nom + " " + tr.dataset.prenoms + " " + tr.dataset.email + " " + tr.dataset.id).toLowerCase();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initializeUtilisateurModule);

    if (typeof window.addAjaxSuccessListener === 'function') {
        window.addAjaxSuccessListener(function(form, data, htmlResponse) {
            const formTargetSelector = form.dataset.target;
            const tableTbodySelector = `#${TABLE_ID} > tbody`;

            if (formTargetSelector === tableTbodySelector && utilisateurTableManager && htmlResponse) {
                utilisateurTableManager.updateTableContent();
                formHandler.resetForm();
            }
        });
    } else {
        console.warn("addAjaxSuccessListener non défini. Les mises à jour AJAX du tableau pourraient ne pas rafraîchir DataTableManager.");
    }

})();
