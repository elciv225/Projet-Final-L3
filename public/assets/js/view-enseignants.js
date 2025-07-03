(function () {
    /**
     * Gère les interactions spécifiques au formulaire enseignant.
     */
    let formInstance;

    function populateEnseignantForm(form, row) {
        const userData = row.querySelector('.user-data');

        form.querySelector('#nom-enseignant').value = userData.dataset.nom;
        form.querySelector('#prenom-enseignant').value = userData.dataset.prenoms;
        form.querySelector('#email-enseignant').value = userData.dataset.email;
        form.querySelector('#date-naissance').value = userData.dataset.naissance;

        // Champs spécifiques aux enseignants
        form.querySelector('#id-grade').value = row.querySelector('.grade-data')?.dataset.gradeId || '';
        form.querySelector('#id-specialite').value = row.querySelector('.specialite-data')?.dataset.specialiteId || '';
        form.querySelector('#id-fonction').value = row.querySelector('.fonction-data')?.dataset.fonctionId || '';
    }

    /**
     * Initialise tous les modules de la page.
     */
    function initializeEnseignantModule() {
        // Initialiser le gestionnaire de formulaire
        formInstance = window.formHandler.create({
            formSelector: 'form[action="/traitement-enseignant"]',
            generateLogin: true,
            nomSelector: '#nom-enseignant',
            prenomSelector: '#prenom-enseignant',
            addTitle: 'Ajouter un nouvel enseignant',
            editTitle: 'Modifier les informations de l\'enseignant',
            onPopulate: populateEnseignantForm
        });

        // Initialiser le gestionnaire de tableau
        window.tableHandler.init('enseignantTable', {
            entityName: 'enseignant',
            onEdit: row => formInstance?.populateForEdit(row)
        });
    }

    document.addEventListener('DOMContentLoaded', initializeEnseignantModule);
    window.ajaxRebinders = window.ajaxRebinders || [];
    window.ajaxRebinders.push(initializeEnseignantModule);

})();
