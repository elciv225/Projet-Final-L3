(function() {
    // 1. On définit la fonction qui initialise les écouteurs pour la modale
    function setupModalEventListeners() {
        const openTrigger = document.getElementById('open-steps-trigger');
        const closeTrigger = document.getElementById('close-steps-trigger');
        const modal = document.querySelector('.sidebar');
        const overlay = document.getElementById('modal-overlay');
        const blurTarget = document.querySelector('.main-content-wrapper');

        // Si le bouton pour ouvrir n'existe pas, on arrête tout.
        if (!openTrigger) return;

        const openModal = () => {
            if (modal && overlay && blurTarget) {
                overlay.classList.add('is-open');
                modal.classList.add('is-open');
                blurTarget.classList.add('is-blurred');
            }
        };

        const closeModal = () => {
            if (modal && overlay && blurTarget) {
                overlay.classList.remove('is-open');
                modal.classList.remove('is-open');
                blurTarget.classList.remove('is-blurred');
            }
        };

        // On utilise .onclick pour s'assurer que l'événement est toujours le dernier défini,
        // évitant les doublons que addEventListener pourrait créer.
        openTrigger.onclick = openModal;
        if (closeTrigger) closeTrigger.onclick = closeModal;
        if (overlay) overlay.onclick = closeModal;
    }

    // 2. On s'assure que notre tableau global existe (au cas où ce script se chargerait avant ajax.js)
    window.ajaxRebinders = window.ajaxRebinders || [];

    // 3. On ajoute notre fonction d'initialisation au tableau pour qu'elle soit appelée par ajax.js.
    // On vérifie d'abord si elle n'est pas déjà présente pour éviter les doublons.
    if (!window.ajaxRebinders.some(fn => fn.name === 'setupModalEventListeners')) {
        window.ajaxRebinders.push(setupModalEventListeners);
    }

    // 4. On exécute la fonction une fois au chargement initial, juste au cas où
    // l'événement DOMContentLoaded de ajax.js se serait déjà déclenché.
    // L'utilisation d'une IIFE (Immediately Invoked Function Expression) encapsule la logique.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupModalEventListeners);
    } else {
        setupModalEventListeners();
    }
})();