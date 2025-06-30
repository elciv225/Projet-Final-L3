function bindCategorieFormAutoSubmit() {
    const select = document.querySelector('form.ajax-form select.select-submit');
    const tableContainer = document.querySelector('.table-container.default')
    if (select) {
        select.addEventListener('change', function () {
            const value = this.value;


            // Masquer ou afficher la table selon la sélection
            if (tableContainer) {
                tableContainer.style.display = value === '' ? 'block' : 'none';
            }


            // Déclencher la soumission AJAX
            const form = this.closest('form');
            if (form) {
                form.dispatchEvent(new Event('submit', {bubbles: true, cancelable: true}));
            }
        });

        // Initialiser au chargement : afficher la table uniquement si vide
        if (tableContainer) {
            tableContainer.style.display = select.value === '' ? 'block' : 'none';
        }
    }
}

window.ajaxRebinders = window.ajaxRebinders || [];
window.ajaxRebinders.push(bindCategorieFormAutoSubmit);