// Assurez-vous que DataTableManager.js est chargé avant ce script.
// Exemple: <script src="/assets/js/utils/DataTableManager.js" defer></script>
//          <script src="/assets/js/view-etudiants.js" defer></script>

(function () {
    const TABLE_ID = 'etudiantTable';
    let etudiantTableManager = null;

    /**
     * Gère les interactions spécifiques au formulaire étudiant.
     */
    const formHandler = {
        form: null,
        operationInput: null,
        idEtudiantInput: null,
        formTitle: null,
        submitButton: null,
        cancelButton: null,
        editOnlyFieldsContainer: null,
        idEtudiantDisplay: null,
        numCarteDisplay: null,

        init: function () {
            this.form = document.querySelector('form[action="/traitement-etudiant"]');
            if (!this.form) {
                console.warn("Formulaire étudiant non trouvé pour initialisation.");
                return;
            }

            this.operationInput = this.form.querySelector('#form-operation');
            this.idEtudiantInput = this.form.querySelector('#id-etudiant-form');
            this.formTitle = this.form.querySelector('#form-title');
            this.submitButton = this.form.querySelector('#btn-submit-form');
            this.cancelButton = this.form.querySelector('#btn-cancel-edit');
            this.editOnlyFieldsContainer = this.form.querySelector('#edit-only-fields');
            this.idEtudiantDisplay = this.form.querySelector('#id-etudiant-display');
            this.numCarteDisplay = this.form.querySelector('#numero-carte-display');


            if (this.cancelButton) {
                this.cancelButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.resetForm();
                });
            }
            this.resetForm(); // État initial
        },

        resetForm: function () {
            if (!this.form) return;
            this.form.reset();
            if(this.idEtudiantInput) this.idEtudiantInput.value = '';
            if(this.operationInput) this.operationInput.value = 'inscrire';
            if(this.formTitle) this.formTitle.textContent = 'Inscrire un nouvel étudiant';
            if(this.submitButton) this.submitButton.textContent = 'Inscrire';
            if(this.cancelButton) this.cancelButton.style.display = 'none';
            if(this.editOnlyFieldsContainer) this.editOnlyFieldsContainer.style.display = 'none';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        populateForEdit: function(row) {
            if (!this.form || !row) return;

            // Utilisation des data-attributes pour plus de robustesse
            const id = row.dataset.id;
            const nom = row.dataset.nom;
            const prenoms = row.dataset.prenoms;
            const email = row.dataset.email;
            const dateNaissance = row.dataset.dateNaissance;
            const niveauId = row.dataset.niveauId;
            const anneeId = row.dataset.anneeId;
            const montant = row.dataset.montant;
            const numCarte = row.dataset.numeroCarte;

            this.form.querySelector('#nom-etudiant').value = nom || '';
            this.form.querySelector('#prenom-etudiant').value = prenoms || '';
            this.form.querySelector('#email-etudiant').value = email || '';
            this.form.querySelector('#date-naissance').value = dateNaissance || '';
            this.form.querySelector('#id-niveau-etude').value = niveauId || '';
            this.form.querySelector('#id-annee-academique').value = anneeId || '';
            this.form.querySelector('#montant-inscription').value = montant || '';

            if(this.idEtudiantInput) this.idEtudiantInput.value = id || '';
            if(this.operationInput) this.operationInput.value = 'modifier';

            if (this.editOnlyFieldsContainer) {
                if(this.idEtudiantDisplay) this.idEtudiantDisplay.value = id || '';
                if(this.numCarteDisplay) this.numCarteDisplay.value = numCarte || 'N/A';
                this.editOnlyFieldsContainer.style.display = 'grid';
            }

            if(this.formTitle) this.formTitle.textContent = 'Modifier les informations de l’étudiant';
            if(this.submitButton) this.submitButton.textContent = 'Modifier';
            if(this.cancelButton) this.cancelButton.style.display = 'inline-block';

            window.scrollTo({ top: this.form.offsetTop - 20, behavior: 'smooth' });
        }
    };


    function initializeEtudiantModule() {
        formHandler.init();

        if (typeof DataTableManager === 'undefined') {
            console.error('DataTableManager is not loaded. Skipping table initialization for etudiants.');
            return;
        }

        const tableElement = document.getElementById(TABLE_ID);
        if (!tableElement || !tableElement.querySelector('tbody')) {
            console.warn(`Table or tbody for '${TABLE_ID}' not found. DataTableManager not initialized.`);
            return;
        }


        etudiantTableManager = new DataTableManager(TABLE_ID, {
            rowsPerPage: 9, // Spécifique à cette table
            rowCheckboxClass: 'select-etudiant-item', // Classe CSS des checkboxes de ligne
            editButtonClass: 'btn-edit',      // Classe CSS des boutons d'édition de ligne
            deleteButtonClass: 'btn-delete-single', // Classe CSS des boutons de suppression de ligne
            onEditRow: (rowElement, event) => { // Callback pour l'édition
                formHandler.populateForEdit(rowElement);
            },
            // La fonction pour obtenir l'ID de la ligne (utilisée par _handleDeleteSingle et _updateDeleteFormState)
            getRowId: function(tr) { // `this` ici sera l'objet `options` de DataTableManager
                return tr.dataset.id; // Utiliser le data-attribute
            },
             // Personnaliser comment le texte est extrait pour la recherche si besoin
            getSearchableText: function(tr) {
                // Concaténer le texte de cellules spécifiques ou tous les data-attributes pertinents
                return (tr.dataset.id + " " + tr.dataset.nom + " " + tr.dataset.prenoms + " " + tr.dataset.email + " " + tr.dataset.numeroCarte).toLowerCase();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initializeEtudiantModule);

    // Gestion de la mise à jour du tableau après une opération AJAX
    if (typeof window.addAjaxSuccessListener === 'function') {
        window.addAjaxSuccessListener(function(form, data, htmlResponse) {
            // Vérifier si la cible du formulaire AJAX est le tbody de notre table
            const formTargetSelector = form.dataset.target;
            const tableTbodySelector = `#${TABLE_ID} > tbody`;

            if (formTargetSelector === tableTbodySelector && etudiantTableManager && htmlResponse) {
                 // ajax.js a déjà mis à jour le innerHTML du tbody
                etudiantTableManager.updateTableContent(); // Demander au manager de rafraîchir ses données et de re-rendre
                formHandler.resetForm(); // Réinitialiser le formulaire après succès
            } else if (form.id === 'form-evaluation' && data.statut === "succes" && etudiantTableManager) {
                // Si c'est une réponse JSON pure après une action sur le formulaire principal
                // et que la réponse contient la nouvelle liste (ex: data.etudiants)
                // Il faudrait reconstruire le HTML du tbody ici ou modifier le serveur pour renvoyer le HTML partiel.
                // Pour l'instant, on suppose que le serveur renvoie le HTML partiel pour data-target.
            }
        });
    } else {
        console.warn("addAjaxSuccessListener non défini. Les mises à jour AJAX du tableau pourraient ne pas rafraîchir DataTableManager.");
    }

})();
