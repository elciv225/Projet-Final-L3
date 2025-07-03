(function () {
    /**
     * Gère les interactions spécifiques au formulaire étudiant.
     */
    let formInstance;

    function populateEtudiantForm(form, row) {
        const userData = row.querySelector('.nom-prenoms');
        const emailElement = row.querySelector('.email-etudiant');

        form.querySelector('#nom-etudiant').value = userData.dataset.nom;
        form.querySelector('#prenom-etudiant').value = userData.dataset.prenoms;
        form.querySelector('#email-etudiant').value = emailElement.textContent.trim();
        form.querySelector('#date-naissance').value = userData.dataset.dateNaissance;

        // Champs spécifiques aux étudiants
        if (row.querySelector('.niveau-etude')) {
            form.querySelector('#id-niveau-etude').value = row.querySelector('.niveau-etude')?.dataset.niveauId || '';
        }
        if (row.querySelector('.annee-academique')) {
            form.querySelector('#id-annee-academique').value = row.querySelector('.annee-academique')?.dataset.anneeId || '';
        }

        // Récupérer le montant d'inscription s'il existe
        if (row.querySelector('.montant-inscription')) {
            form.querySelector('#montant-inscription').value = row.querySelector('.montant-inscription').textContent.trim().replace(/[^\d]/g, '') || '';
        }
    }

    function initializeEtudiantModule() {
        // Initialiser le gestionnaire de formulaire
        formInstance = window.formHandler.create({
            formSelector: 'form[action="/traitement-etudiant"]',
            idFieldSelector: '#id-etudiant-form',
            generateLogin: true,
            nomSelector: '#nom-etudiant',
            prenomSelector: '#prenom-etudiant',
            addTitle: 'Ajouter un nouvel étudiant',
            editTitle: 'Modifier les informations de l\'étudiant',
            onPopulate: populateEtudiantForm
        });

        // Initialiser le gestionnaire de tableau
        window.tableHandler.init('etudiantTable', {
            entityName: 'étudiant',
            onEdit: row => formInstance?.populateForEdit(row)
        });
    }

    document.addEventListener('DOMContentLoaded', initializeEtudiantModule);
    window.ajaxRebinders = window.ajaxRebinders || [];
    window.ajaxRebinders.push(initializeEtudiantModule);

})();
