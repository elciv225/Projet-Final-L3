function bindCategorieFormAutoSubmit() {
    const select = document.querySelector('form.ajax-form select.select-submit');
    if (select) {
        select.addEventListener('change', function () {
            // Déclencher la soumission AJAX
            const form = this.closest('form');
            if (form) {
                form.dispatchEvent(new Event('submit', {bubbles: true, cancelable: true}));
            }
        });
    }
}

window.ajaxRebinders = window.ajaxRebinders || [];
window.ajaxRebinders.push(bindCategorieFormAutoSubmit);