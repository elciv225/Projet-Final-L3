(function () {
    /**
     * Gère les interactions spécifiques au formulaire du personnel administratif.
     */
    let formInstance;

    function populatePersonnelForm(form, row) {
        const userData = row.querySelector('.user-data');

        form.querySelector('#nom-personnel').value = userData.dataset.nom;
        form.querySelector('#prenom-personnel').value = userData.dataset.prenoms;
        form.querySelector('#email-personnel').value = userData.dataset.email;
        form.querySelector('#date-naissance').value = userData.dataset.naissance;

        // Ajouter ici d'autres champs spécifiques au personnel administratif si nécessaire
    }

    function initializePersonnelAdminModule() {
        // Initialiser le gestionnaire de formulaire
        formInstance = window.formHandler.create({
            formSelector: 'form[action="/traitement-personnel-admin"]',
            nomSelector: '#nom-personnel',
            prenomSelector: '#prenom-personnel',
            addTitle: 'Ajouter un membre du personnel',
            editTitle: 'Modifier les informations',
            onPopulate: populatePersonnelForm
        });

        // Initialiser le gestionnaire de tableau
        window.tableHandler.init('adminTable', {
            entityName: 'membre',
            onEdit: row => formInstance?.populateForEdit(row)
        });
    }

    document.addEventListener('DOMContentLoaded', initializePersonnelAdminModule);
    window.ajaxRebinders = window.ajaxRebinders || [];
    window.ajaxRebinders.push(initializePersonnelAdminModule);

})();
