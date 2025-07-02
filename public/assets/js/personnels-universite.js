// =========================================================================
//  SCRIPT POUR LA GESTION DES PERSONNELS DE L'UNIVERSITÉ
// =========================================================================
// Assurez-vous que DataTableManager.js est chargé avant ce script.

(function () {
    const TABLE_ID = 'personnelTable';
    let personnelTableManager = null;

    /**
     * Gère les interactions spécifiques au formulaire des personnels.
     */
    const formHandler = {
        form: null,
        operationInput: null,
        idPersonnelInput: null, // Champ caché pour l'ID en mode modification
        formTitle: null,
        submitButton: null,
        cancelButton: null,
        editOnlyFieldsContainer: null,
        idPersonnelDisplay: null, // Champ pour afficher l'ID (login) en mode édition
        typePersonnelSelect: null,
        champsEnseignant: null,
        champsAdmin: null,

        init: function () {
            this.form = document.querySelector('form[action="/traitement-personnel"]');
            if (!this.form) {
                console.warn("Formulaire personnel non trouvé pour initialisation.");
                return;
            }

            this.operationInput = this.form.querySelector('#form-operation-personnel');
            this.idPersonnelInput = this.form.querySelector('#id-personnel-form'); // Input caché existant
            this.formTitle = this.form.querySelector('#form-title-personnel');
            this.submitButton = this.form.querySelector('#btn-submit-form-personnel');
            this.cancelButton = this.form.querySelector('#btn-cancel-edit-personnel');
            this.editOnlyFieldsContainer = this.form.querySelector('#edit-only-fields-personnel');
            this.idPersonnelDisplay = this.form.querySelector('#id-personnel-display');
            this.typePersonnelSelect = this.form.querySelector('#type-personnel');
            this.champsEnseignant = this.form.querySelector('#champs-enseignant');
            this.champsAdmin = this.form.querySelector('#champs-administratif');

            if (this.cancelButton) {
                this.cancelButton.addEventListener('click', this.resetForm.bind(this));
            }
            if (this.typePersonnelSelect) {
                this.typePersonnelSelect.addEventListener('change', this.toggleChampsSpecifiques.bind(this));
            }
            this.resetForm();
        },

        resetForm: function () {
            if (!this.form) return;
            this.form.reset();
            if(this.idPersonnelInput) this.idPersonnelInput.value = '';
            if(this.operationInput) this.operationInput.value = 'ajouter';
            if(this.formTitle) this.formTitle.textContent = 'Ajouter un nouveau personnel';
            if(this.submitButton) this.submitButton.textContent = 'Ajouter';
            if(this.cancelButton) this.cancelButton.style.display = 'none';
            if(this.editOnlyFieldsContainer) this.editOnlyFieldsContainer.style.display = 'none';
            if(this.typePersonnelSelect) this.toggleChampsSpecifiques();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        populateForEdit: function (row) {
            if (!this.form || !row) return;

            const id = row.dataset.id;
            this.form.querySelector('#nom-personnel').value = row.dataset.nom || '';
            this.form.querySelector('#prenom-personnel').value = row.dataset.prenoms || '';
            this.form.querySelector('#email-personnel').value = row.dataset.email || '';
            this.form.querySelector('#date-naissance-personnel').value = row.dataset.dateNaissance || '';

            const typePersonnel = row.dataset.typePersonnel || '';
            if (this.typePersonnelSelect) this.typePersonnelSelect.value = typePersonnel;
            this.toggleChampsSpecifiques();

            if (typePersonnel === 'enseignant' && this.champsEnseignant) {
                this.form.querySelector('#grade-enseignant').value = row.dataset.gradeId || '';
                this.form.querySelector('#fonction-enseignant').value = row.dataset.fonctionId || '';
                this.form.querySelector('#specialite-enseignant').value = row.dataset.specialiteId || '';
                this.form.querySelector('#date-grade-enseignant').value = row.dataset.dateGrade || '';
                this.form.querySelector('#date-fonction-enseignant').value = row.dataset.dateFonction || '';
                this.form.querySelector('#date-specialite-enseignant').value = row.dataset.dateSpecialite || '';
            } else if (typePersonnel === 'administratif' && this.champsAdmin) {
                this.form.querySelector('#fonction-administratif').value = row.dataset.fonctionId || '';
                this.form.querySelector('#date-fonction-administratif').value = row.dataset.dateFonction || '';
            }

            if(this.idPersonnelInput) this.idPersonnelInput.value = id || '';
            if(this.operationInput) this.operationInput.value = 'modifier';
            if(this.idPersonnelDisplay) this.idPersonnelDisplay.value = id || '';
            if(this.editOnlyFieldsContainer) this.editOnlyFieldsContainer.style.display = 'grid';
            if(this.formTitle) this.formTitle.textContent = 'Modifier les informations du personnel';
            if(this.submitButton) this.submitButton.textContent = 'Modifier';
            if(this.cancelButton) this.cancelButton.style.display = 'inline-block';

            window.scrollTo({ top: this.form.offsetTop - 20, behavior: 'smooth' });
        },

        toggleChampsSpecifiques: function () {
            if (!this.typePersonnelSelect || !this.champsEnseignant || !this.champsAdmin) return;
            const selectedType = this.typePersonnelSelect.value;
            this.champsEnseignant.style.display = (selectedType === 'enseignant') ? 'grid' : 'none';
            this.champsAdmin.style.display = (selectedType === 'administratif') ? 'grid' : 'none';
        }
    };

    function initializePersonnelModule() {
        formHandler.init();

        if (typeof DataTableManager === 'undefined') {
            console.error('DataTableManager is not loaded. Skipping table initialization for personnels.');
            return;
        }

        const tableElement = document.getElementById(TABLE_ID);
        if (!tableElement || !tableElement.querySelector('tbody')) {
            console.warn(`Table or tbody for '${TABLE_ID}' not found. DataTableManager not initialized.`);
            return;
        }

        personnelTableManager = new DataTableManager(TABLE_ID, {
            rowsPerPage: 10,
            rowCheckboxClass: 'select-personnel',
            editButtonClass: 'btn-edit-personnel',
            deleteButtonClass: 'btn-delete-single-personnel',
            onEditRow: (rowElement, event) => {
                formHandler.populateForEdit(rowElement);
            },
            getRowId: function(tr) { return tr.dataset.id; },
            getSearchableText: function(tr) {
                return (tr.dataset.id + " " + tr.dataset.nom + " " + tr.dataset.prenoms + " " + tr.dataset.email).toLowerCase();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initializePersonnelModule);

    if (typeof window.addAjaxSuccessListener === 'function') {
        window.addAjaxSuccessListener(function(form, data, htmlResponse) {
            const formTargetSelector = form.dataset.target;
            const tableTbodySelector = `#${TABLE_ID} > tbody`;

            if (formTargetSelector === tableTbodySelector && personnelTableManager && htmlResponse) {
                personnelTableManager.updateTableContent();
                formHandler.resetForm();
            }
        });
    } else {
        console.warn("addAjaxSuccessListener non défini. Les mises à jour AJAX du tableau pourraient ne pas rafraîchir DataTableManager.");
    }

})();
