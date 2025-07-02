// =========================================================================
//  SCRIPT POUR LA GESTION DE L'ÉVALUATION DES ÉTUDIANTS
// =========================================================================
// Assurez-vous que DataTableManager.js est chargé avant ce script.

(function () {
    const TABLE_ID = 'evaluationTable';
    let evaluationTableManager = null;

    const formEvaluation = document.getElementById('form-evaluation');
    const operationInput = document.getElementById('evaluation-operation');
    const formTitle = document.getElementById('evaluation-form-title');
    const submitButton = document.getElementById('btn-submit-evaluation');
    const cancelButton = document.getElementById('btn-cancel-evaluation');

    // Inputs pour les clés originales (en mode modification)
    const idEnseignantOriginalInput = document.getElementById('id_enseignant_original');
    const idEtudiantOriginalInput = document.getElementById('id_etudiant_original');
    const idEcueOriginalInput = document.getElementById('id_ecue_original');
    const anneeOriginalInput = document.getElementById('annee_academique_id_original');
    const sessionOriginalInput = document.getElementById('session_id_original');

    if (!formEvaluation) {
        console.warn("Formulaire d'évaluation non trouvé pour initialisation.");
        // Ne pas bloquer le reste si la table existe
    }

    function resetForm() {
        if (!formEvaluation) return;
        formEvaluation.reset();
        if(operationInput) operationInput.value = 'ajouter';
        if(formTitle) formTitle.textContent = 'Nouvelle Évaluation';
        if(submitButton) submitButton.textContent = 'Ajouter';
        if(cancelButton) cancelButton.style.display = 'none';

        if(idEnseignantOriginalInput) idEnseignantOriginalInput.value = '';
        if(idEtudiantOriginalInput) idEtudiantOriginalInput.value = '';
        if(idEcueOriginalInput) idEcueOriginalInput.value = '';
        if(anneeOriginalInput) anneeOriginalInput.value = '';
        if(sessionOriginalInput) sessionOriginalInput.value = '';

        document.getElementById('enseignant_id').disabled = false;
        document.getElementById('etudiant_id').disabled = false;
        document.getElementById('ecue_id').disabled = false;
        document.getElementById('annee_academique_id').disabled = false;
        document.getElementById('session_id').disabled = false;
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', resetForm);
    }

    function populateFormForEdit(row) {
        if (!formEvaluation || !row || !row.dataset.compositeKey) return;
        const compositeKey = JSON.parse(row.dataset.compositeKey);

        if(operationInput) operationInput.value = 'modifier';
        if(formTitle) formTitle.textContent = 'Modifier l\'Évaluation';
        if(submitButton) submitButton.textContent = 'Modifier';
        if(cancelButton) cancelButton.style.display = 'inline-block';

        document.getElementById('enseignant_id').value = compositeKey.enseignant_id;
        document.getElementById('etudiant_id').value = compositeKey.etudiant_id;
        document.getElementById('ecue_id').value = compositeKey.ecue_id;
        document.getElementById('annee_academique_id').value = compositeKey.annee_academique_id;
        document.getElementById('session_id').value = compositeKey.session_id;

        // La date doit être formatée en YYYY-MM-DD pour l'input type="date"
        const dateCell = row.querySelector('td[data-label="Date Éval."]');
        if(dateCell && dateCell.textContent) {
            const dateParts = dateCell.textContent.split('/'); // Format JJ/MM/AAAA
            if(dateParts.length === 3) {
                document.getElementById('date_evaluation').value = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
            }
        }

        const noteCell = row.querySelector('td[data-label="Note"]');
        if(noteCell && noteCell.textContent) {
            document.getElementById('note').value = noteCell.textContent.replace(',', '.').replace(' ', '');
        }

        if(idEnseignantOriginalInput) idEnseignantOriginalInput.value = compositeKey.enseignant_id;
        if(idEtudiantOriginalInput) idEtudiantOriginalInput.value = compositeKey.etudiant_id;
        if(idEcueOriginalInput) idEcueOriginalInput.value = compositeKey.ecue_id;
        if(anneeOriginalInput) anneeOriginalInput.value = compositeKey.annee_academique_id;
        if(sessionOriginalInput) sessionOriginalInput.value = compositeKey.session_id;

        document.getElementById('enseignant_id').disabled = true;
        document.getElementById('etudiant_id').disabled = true;
        document.getElementById('ecue_id').disabled = true;
        document.getElementById('annee_academique_id').disabled = true;
        document.getElementById('session_id').disabled = true;

        window.scrollTo({ top: formEvaluation.offsetTop - 20, behavior: 'smooth' });
    }

    function initializeEvaluationModule() {
        if (formEvaluation) resetForm(); // S'assurer que le formulaire est propre au chargement

        if (typeof DataTableManager === 'undefined') {
            console.error('DataTableManager is not loaded. Skipping table initialization for evaluations.');
            return;
        }

        const tableElement = document.getElementById(TABLE_ID);
        if (!tableElement || !tableElement.querySelector('tbody')) {
            console.warn(`Table or tbody for '${TABLE_ID}' not found. DataTableManager not initialized.`);
            return;
        }

        evaluationTableManager = new DataTableManager(TABLE_ID, {
            rowsPerPage: 10,
            rowCheckboxClass: 'select-evaluation-item',
            editButtonClass: 'btn-edit-evaluation',
            deleteButtonClass: 'btn-delete-single-evaluation',
            onEditRow: (rowElement, event) => {
                populateFormForEdit(rowElement);
            },
            // La valeur de la checkbox est le JSON stringifié de la clé composite
            getRowId: function(tr) {
                const checkbox = tr.querySelector(`.${this.rowCheckboxClass}`);
                return checkbox ? checkbox.value : tr.dataset.compositeKey;
            },
            getSearchableText: function(tr) {
                // Concaténer le texte des cellules pertinentes pour la recherche
                let text = "";
                // Enseignant (cell 1), Étudiant (cell 2), ECUE (cell 3), Année (cell 4), Session (cell 5)
                for(let i = 1; i <= 5; i++) {
                    if(tr.cells[i]) text += tr.cells[i].textContent + " ";
                }
                return text.toLowerCase();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initializeEvaluationModule);

    if (typeof window.addAjaxSuccessListener === 'function') {
        window.addAjaxSuccessListener(function(form, data, htmlResponse) {
            const formTargetSelector = form.dataset.target;
            const tableTbodySelector = `#${TABLE_ID} > tbody`;

            if (formTargetSelector === tableTbodySelector && evaluationTableManager && htmlResponse) {
                evaluationTableManager.updateTableContent(); // Le HTML a déjà été inséré par ajax.js
                if (form.id === 'form-evaluation') resetForm(); // Réinitialiser le formulaire d'ajout/modif
            }
        });
    } else {
        console.warn("addAjaxSuccessListener non défini. Les mises à jour AJAX du tableau pourraient ne pas rafraîchir DataTableManager.");
    }

    function escapeHTML(str) { // Utile si on reconstruit le HTML en JS, moins ici car le serveur renvoie du HTML.
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"']/g, match => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[match]));
    }

})();
