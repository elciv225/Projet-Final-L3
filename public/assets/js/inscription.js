(function () {
    // S'assure que le tableau global existe
    window.ajaxRebinders = window.ajaxRebinders || [];

    /**
     * Fonction principale qui initialise toute la logique de la page d'inscription.
     * Elle est conçue pour être appelée au chargement initial et après chaque rechargement AJAX.
     */
    function setupInscriptionPage() {
        console.log("Rebinding inscription events...");

        // 1. Animations d'entrée
        animatePageIn();

        // 2. Logique de soumission du formulaire
        setupFormSubmitListener();

        // 3. Écouteur pour la réponse AJAX (une seule fois)
        // On retire l'ancien écouteur pour éviter les doublons
        document.removeEventListener('ajaxComplete', handleAjaxResponse);
        document.addEventListener('ajaxComplete', handleAjaxResponse);
    }

    /**
     * Gère l'animation d'entrée de la page.
     */
    function animatePageIn() {
        const formContainer = document.querySelector('.form-container-anim');
        if (!formContainer || !gsap) return;

        gsap.fromTo(formContainer,
            { autoAlpha: 0, y: 20 },
            { autoAlpha: 1, y: 0, duration: 0.6, ease: "power3.out" }
        );

        const tl = gsap.timeline({ delay: 0.1 });
        tl.fromTo(".form-title-group h2, .form-title-group p", { y: 15, opacity: 0 }, { duration: 0.5, y: 0, opacity: 1, stagger: 0.1, ease: "power3.out" })
            .fromTo(".form-group", { y: 15, opacity: 0 }, { duration: 0.5, y: 0, opacity: 1, stagger: 0.1, ease: "power3.out" }, "-=0.3")
            .fromTo(".btn.btn-primary", { y: 10, opacity: 0 }, { duration: 0.5, y: 0, opacity: 1, ease: "power3.out" }, "-=0.2")
            .fromTo(".form-etape-counter", { scale: 0.9, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.3, ease: "back.out(2)" }, "-=0.4");

        // Animation du tooltip au premier chargement pour attirer l'attention
        const tooltip = document.querySelector('.form-etape-counter .tooltip');
        if (tooltip) {
            gsap.timeline({ delay: 1.5 })
                .to(tooltip, { opacity: 1, visibility: 'visible', duration: 0.3 })
                .to(tooltip, { opacity: 0, visibility: 'hidden', duration: 0.3, delay: 2.5 });
        }
    }

    // Fonction de modal supprimée car la sidebar n'existe plus

    /**
     * Attache l'écouteur de soumission au formulaire.
     */
    function setupFormSubmitListener() {
        const form = document.querySelector('form.ajax-form');
        if (!form) return;

        form.onsubmit = function(e) {
            // La logique de soumission est déjà dans ajax.js
            // Nous pouvons ajouter une animation de chargement globale si nécessaire
        };
    }

    /**
     * Gère la réponse AJAX et met à jour l'interface.
     * @param {CustomEvent} e L'événement ajaxComplete.
     */
    function handleAjaxResponse(e) {
        const responseData = e.detail?.response?.json;
        const form = e.detail?.form;

        if (!responseData || !form) return;

        // On ne s'intéresse qu'aux réponses des formulaires d'inscription
        if (!form.closest('.inscription-container')) return;

        // Animation de succès si nécessaire
        if (responseData.statut === 'succes') {
            // On pourrait ajouter une animation de succès ici si nécessaire
        }
    }

    // Fonction de chargement d'étape supprimée car nous n'utilisons plus la sidebar

    // Fonction de transition d'étape supprimée car nous n'utilisons plus la sidebar

    // Fonctions de gestion des étapes supprimées car nous n'utilisons plus la sidebar

    // --- Intégration avec le système AJAX ---
    // On s'assure que notre fonction d'initialisation n'est pas ajoutée plusieurs fois.
    const rebinderName = 'setupInscriptionPage';
    if (!window.ajaxRebinders.some(fn => fn.name === rebinderName)) {
        Object.defineProperty(setupInscriptionPage, 'name', { value: rebinderName });
        window.ajaxRebinders.push(setupInscriptionPage);
    }

    // Exécution initiale au chargement de la page
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupInscriptionPage);
    } else {
        setupInscriptionPage();
    }
})();
