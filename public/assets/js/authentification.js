(function () {
    /**
     * Gère les animations d'apparition des éléments de la page avec GSAP.
     * Est conçue pour être appelée au chargement initial et après chaque rechargement AJAX.
     */
    function animatePageIn() {
        const authContainer = document.querySelector('.auth-container');
        const formAnimContainer = document.querySelector('.form-container-anim');

        // S'assure que les éléments existent avant d'animer
        if (authContainer && formAnimContainer) {
            // Réinitialise la visibilité pour l'animation
            gsap.set(authContainer, {autoAlpha: 1}); // autoAlpha gère opacity et visibility

            // CORRECTION: Définir l'état initial visible pour les éléments statiques (sidebar)
            gsap.set(".sidebar", {opacity: 1, x: 0});
            gsap.set(".sidebar-header, .etapes-nav, .sidebar-footer", {opacity: 1, y: 0});
        }

        gsap.set(formAnimContainer, {autoAlpha: 1});


        // Pour les éléments du formulaire, définir l'état final après animation
        gsap.set(".form-title-group h2, .form-title-group p", {opacity: 1, y: 0});
        gsap.set(".form-group", {opacity: 1, y: 0});
        gsap.set(".btn.btn-primary", {opacity: 1, y: 0});

        // Timeline pour l'animation des éléments du formulaire qui changent
        const tl = gsap.timeline({delay: 0.3}); // Léger délai pour un effet plus doux

        tl.fromTo(".form-title-group h2, .form-title-group p",
            {y: 20, opacity: 0},
            {
                duration: 0.6,
                y: 0,
                opacity: 1,
                stagger: 0.15,
                ease: "power3.out"
            })
            .fromTo(".form-group",
                {y: 15, opacity: 0},
                {
                    duration: 0.5,
                    y: 0,
                    opacity: 1,
                    stagger: 0.1,
                    ease: "power3.out"
                }, "-=0.3") // Se superpose légèrement à l'animation précédente
            .fromTo(".btn.btn-primary",
                {y: 10, opacity: 0},
                {
                    duration: 0.5,
                    y: 0,
                    opacity: 1,
                    ease: "power3.out"
                }, "-=0.2");
    }

    /**
     * Configure les écouteurs d'événements pour la modale (sidebar en vue mobile) en utilisant GSAP.
     */
    function setupModalEventListeners() {
        const openTrigger = document.getElementById('open-steps-trigger');
        const closeTrigger = document.getElementById('close-steps-trigger');
        const modal = document.querySelector('.sidebar');
        const overlay = document.getElementById('modal-overlay');
        const blurTarget = document.querySelector('.main-content-wrapper');

        if (!openTrigger || !modal || !overlay || !blurTarget) return;

        // Timeline GSAP pour l'ouverture, réutilisable
        const openTimeline = gsap.timeline({paused: true, reversed: true});
        openTimeline.to(overlay, {autoAlpha: 1, duration: 0.3, ease: 'power2.inOut'})
            .to(modal, {y: '0%', autoAlpha: 1, duration: 0.4, ease: 'power3.out'}, '-=0.2')
            .to(blurTarget, {filter: 'blur(5px)', duration: 0.3, ease: 'power2.inOut'}, '<');


        const openModal = () => {
            openTimeline.play();
        };

        const closeModal = () => {
            openTimeline.reverse();
        };

        openTrigger.onclick = openModal;
        closeTrigger.onclick = closeModal;
        overlay.onclick = closeModal;
    }

    // --- Intégration avec le système AJAX existant ---

    // Assure que le tableau global existe
    window.ajaxRebinders = window.ajaxRebinders || [];

    // Noms des fonctions pour vérifier si elles sont déjà dans le tableau
    const pageAnimFuncName = 'animatePageIn';
    const modalFuncName = 'setupModalEventListeners';

    // Ajoute la fonction d'animation si elle n'est pas déjà présente
    if (!window.ajaxRebinders.some(fn => fn.name === pageAnimFuncName)) {
        Object.defineProperty(animatePageIn, 'name', {value: pageAnimFuncName});
        window.ajaxRebinders.push(animatePageIn);
    }

    // Ajoute la fonction de la modale si elle n'est pas déjà présente
    if (!window.ajaxRebinders.some(fn => fn.name === modalFuncName)) {
        Object.defineProperty(setupModalEventListeners, 'name', {value: modalFuncName});
        window.ajaxRebinders.push(setupModalEventListeners);
    }

    // Exécution initiale au chargement de la page
    function initialLoad() {
        animatePageIn();
        setupModalEventListeners();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialLoad);
    } else {
        initialLoad();
    }

})();