// =========================================================================
//  SCRIPT POUR LA GESTION DES PARAMÈTRES GÉNÉRAUX
// =========================================================================
// Assurez-vous que DataTableManager.js est chargé avant ce script.

(function () {
    const selectParametreElement = document.getElementById('paramatre-specifique');
    const zoneDynamique = document.getElementById('zone-dynamique');
    let currentParamTableManager = null;
    let currentParamType = null;

    if (!selectParametreElement || !zoneDynamique) {
        console.error("Éléments clés manquants pour la page des paramètres généraux (selecteur ou zone dynamique).");
        return;
    }

    // La soumission du formulaire du select est gérée par ajax.js (classe select-submit)
    // On a besoin d'une fonction qui sera appelée APRÈS que ajax.js ait mis à jour #zone-dynamique

    function initializeFormAndTableForParametre() {
        const formParametre = zoneDynamique.querySelector('.form-parametre-general');
        const tableParametreElement = zoneDynamique.querySelector('.table-parametre-general');
        currentParamType = tableParametreElement ? tableParametreElement.dataset.parametreType : null;

        if (formParametre) {
            initializeSpecificFormLogic(formParametre, currentParamType);
        }

        if (tableParametreElement && currentParamType) {
            const tableId = `table-param-${currentParamType}`; // Donner un ID unique si pas déjà fait
            tableParametreElement.id = tableId;

            // S'assurer que les IDs des contrôles de table sont uniques et correspondent au manager
            const searchInputId = `searchInput-${tableId}`;
            const paginationContainerId = `pagination-${tableId}`;
            const resultsInfoContainerId = `resultsInfo-${tableId}`;
            const selectAllCheckboxId = `selectAll-${tableId}`;
            const deleteFormId = `delete-form-${tableId}`; // Le formulaire de suppression groupée
            const hiddenInputsContainerId = `hidden-inputs-for-delete-${tableId}`;

            // Attribuer dynamiquement les IDs si nécessaire (ou s'assurer qu'ils sont dans le HTML partiel)
            const searchInput = zoneDynamique.querySelector('.search-input-param'); // Classe générique
            if (searchInput) searchInput.id = searchInputId;

            const paginationDiv = zoneDynamique.querySelector('.pagination-param');
            if (paginationDiv) paginationDiv.id = paginationContainerId;

            const resultsDiv = zoneDynamique.querySelector('.results-info-param');
            if (resultsDiv) resultsDiv.id = resultsInfoContainerId;

            const selectAllCb = zoneDynamique.querySelector('.select-all-param');
            if (selectAllCb) selectAllCb.id = selectAllCheckboxId;

            const delForm = zoneDynamique.querySelector('.form-delete-selected-param');
            if(delForm) {
                delForm.id = deleteFormId;
                let hiddenContainer = delForm.querySelector('div[id^="hidden-inputs-for-delete-"]');
                if(!hiddenContainer) {
                    hiddenContainer = document.createElement('div');
                    delForm.appendChild(hiddenContainer);
                }
                hiddenContainer.id = hiddenInputsContainerId;
            }


            if (typeof DataTableManager === 'undefined') {
                console.error('DataTableManager is not loaded. Cannot initialize table for paramètres.');
                return;
            }

            currentParamTableManager = new DataTableManager(tableId, {
                rowsPerPage: 5, // Moins de lignes pour les paramètres
                rowCheckboxClass: 'select-param-item',
                editButtonClass: 'btn-edit-param',
                deleteButtonClass: 'btn-delete-param',
                searchInputId: searchInputId,
                paginationContainerId: paginationContainerId,
                resultsInfoContainerId: resultsInfoContainerId,
                selectAllCheckboxId: selectAllCheckboxId,
                deleteFormId: deleteFormId,
                hiddenInputsContainerId: hiddenInputsContainerId,
                onEditRow: (rowElement, event) => {
                    populateFormForEditParametre(formParametre, rowElement, currentParamType);
                },
                getRowId: function(tr) { return tr.dataset.id; },
                getSearchableText: function(tr) {
                    return (tr.dataset.id + " " + tr.dataset.libelle).toLowerCase();
                }
            });
        }
    }

    function initializeSpecificFormLogic(form, parametreType) {
        const operationInput = form.querySelector('input[name="operation"]');
        const idInput = form.querySelector('input[name="id"]');
        const libelleInput = form.querySelector('input[name="libelle"]');
        const submitButton = form.querySelector('button[type="submit"]');
        const cancelButton = form.querySelector('.btn-cancel-param');

        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                form.reset();
                if (idInput) idInput.value = '';
                if (operationInput) operationInput.value = 'ajouter';
                if (submitButton) submitButton.textContent = 'Ajouter';
                this.style.display = 'none';
                if (libelleInput) libelleInput.focus();
                 // Réinitialiser les champs spécifiques au type de paramètre
                resetExtraFormFields(form, parametreType);
            });
        }
         // S'assurer que le bouton Annuler est caché initialement
        if (cancelButton && operationInput && operationInput.value === 'ajouter') {
            cancelButton.style.display = 'none';
        }
    }

    function resetExtraFormFields(form, parametreType) {
        if (parametreType === 'menu') {
            form.querySelector('input[name="vue"]').value = '';
            form.querySelector('select[name="categorie_menu_id"]').value = '';
        } else if (parametreType === 'ue') {
            form.querySelector('input[name="credit"]').value = '';
            form.querySelector('select[name="niveau_etude_id"]').value = '';
        } else if (parametreType === 'ecue') {
            form.querySelector('input[name="credit_ecue"]').value = '';
            form.querySelector('select[name="ue_id"]').value = '';
            form.querySelector('input[name="heure_cm"]').value = '';
            form.querySelector('input[name="heure_td"]').value = '';
            form.querySelector('input[name="heure_tp"]').value = '';
        }
    }


    function populateFormForEditParametre(form, row, parametreType) {
        if (!form || !row) return;

        form.querySelector('input[name="id"]').value = row.dataset.id || '';
        form.querySelector('input[name="libelle"]').value = row.dataset.libelle || '';
        // Champs spécifiques
        if (parametreType === 'menu') {
            form.querySelector('input[name="vue"]').value = row.dataset.vue || '';
            form.querySelector('select[name="categorie_menu_id"]').value = row.dataset.categorie_menu_id || '';
        } else if (parametreType === 'ue') {
            form.querySelector('input[name="credit"]').value = row.dataset.credit || '';
            form.querySelector('select[name="niveau_etude_id"]').value = row.dataset.niveau_etude_id || '';
        } else if (parametreType === 'ecue') {
            form.querySelector('input[name="credit_ecue"]').value = row.dataset.credit_ecue || '';
            form.querySelector('select[name="ue_id"]').value = row.dataset.ue_id || '';
            form.querySelector('input[name="heure_cm"]').value = row.dataset.heure_cm || '';
            form.querySelector('input[name="heure_td"]').value = row.dataset.heure_td || '';
            form.querySelector('input[name="heure_tp"]').value = row.dataset.heure_tp || '';
        }

        form.querySelector('input[name="operation"]').value = 'modifier';
        const submitBtn = form.querySelector('button[type="submit"]');
        if(submitBtn) submitBtn.textContent = 'Modifier';
        const cancelBtn = form.querySelector('.btn-cancel-param');
        if(cancelBtn) cancelBtn.style.display = 'inline-block';

        form.querySelector('input[name="libelle"]').focus();
        window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });
    }


    // Enregistrer cette fonction pour qu'elle soit appelée par ajax.js
    // après une requête réussie qui cible #zone-dynamique
    if (typeof window.addAjaxSuccessListener === 'function') {
        window.addAjaxSuccessListener(function(form, data, htmlResponse, targetElement) {
            if (targetElement && targetElement.id === 'zone-dynamique' && htmlResponse) {
                // Le contenu de #zone-dynamique a été mis à jour par ajax.js
                initializeFormAndTableForParametre();
            } else if (form && form.classList.contains('form-parametre-general') && htmlResponse) {
                 // Si le formulaire de paramètre lui-même a été soumis et a mis à jour sa table parente
                 // (ex: data-target="#container-param-type")
                 // Il faut réinitialiser le DataTableManager pour cette table spécifique.
                const containerId = form.dataset.target.substring(1); // Enlever le #
                const updatedContainer = document.getElementById(containerId);
                if(updatedContainer && updatedContainer.contains(document.querySelector('.table-parametre-general'))) {
                    initializeFormAndTableForParametre(); // Réinitialise tout, y compris le form
                }
                 // Et réinitialiser le formulaire d'ajout/modif.
                const mainForm = zoneDynamique.querySelector('.form-parametre-general');
                if (mainForm) {
                    mainForm.reset();
                    mainForm.querySelector('input[name="operation"]').value = 'ajouter';
                    mainForm.querySelector('input[name="id"]').value = '';
                    mainForm.querySelector('button[type="submit"]').textContent = 'Ajouter';
                    const btnCancel = mainForm.querySelector('.btn-cancel-param');
                    if(btnCancel) btnCancel.style.display = 'none';
                    resetExtraFormFields(mainForm, mainForm.querySelector('input[name="parametre_type"]').value);
                }

            }
        });
    } else {
        console.warn("addAjaxSuccessListener n'est pas défini. L'initialisation dynamique des tables de paramètres pourrait ne pas fonctionner.");
        // Fallback pour le chargement initial si aucun AJAX n'est impliqué au début.
        document.addEventListener('DOMContentLoaded', initializeFormAndTableForParametre);
    }

    // Appel initial si du contenu est déjà dans zoneDynamique (peu probable sans sélection initiale)
    // initializeFormAndTableForParametre();

})();
