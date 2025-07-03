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
            // Animation d'entrée du conteneur principal
            gsap.fromTo(authContainer, 
                {autoAlpha: 0, scale: 0.96},
                {autoAlpha: 1, scale: 1, duration: 0.8, ease: "power2.out"});

            // Rendre visible le conteneur de formulaire
            gsap.set(formAnimContainer, {autoAlpha: 1});
        } else {
            // Si les éléments n'existent pas, au moins rendre visible le formulaire
            if (formAnimContainer) {
                gsap.set(formAnimContainer, {autoAlpha: 1});
            }
        }


        // Timeline pour l'animation des éléments du formulaire
        const tl = gsap.timeline({delay: 0.2});

        // Animation des éléments du formulaire
        tl.fromTo(".form-title-group h2",
            {y: 20, opacity: 0},
            {
                duration: 0.6,
                y: 0,
                opacity: 1,
                ease: "power3.out"
            })
            .fromTo(".form-title-group p",
            {y: 15, opacity: 0},
            {
                duration: 0.5,
                y: 0,
                opacity: 1,
                ease: "power3.out"
            }, "-=0.3")
            .fromTo(".form-group",
                {y: 15, opacity: 0},
                {
                    duration: 0.5,
                    y: 0,
                    opacity: 1,
                    stagger: 0.1,
                    ease: "power3.out"
                }, "-=0.2")
            .fromTo(".btn.btn-primary",
                {y: 10, opacity: 0},
                {
                    duration: 0.5,
                    y: 0,
                    opacity: 1,
                    ease: "power3.out"
                }, "-=0.1")
            .fromTo(".forgot-password-link-container, .action-links",
                {opacity: 0},
                {
                    duration: 0.4,
                    opacity: 1,
                    ease: "power2.out"
                }, "-=0.1");

        // Animation des éléments du header si présents
        if (document.querySelector('.form-header')) {
            tl.fromTo('.form-header', 
                {y: -10, opacity: 0},
                {y: 0, opacity: 1, duration: 0.4, ease: "power2.out"}, 
                "-=0.7");
        }
    }

    /**
     * Configure l'animation de transition entre les formulaires d'authentification et de mot de passe oublié
     */
    function setupFormTransitions() {
        // Récupération des éléments du DOM
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const backToLoginBtn = document.getElementById('backToLoginBtn');
        const loginForm = document.getElementById('loginForm');
        const forgotPasswordForm = document.getElementById('forgotPasswordForm');

        // Si les éléments n'existent pas, ne pas configurer les animations
        if (!forgotPasswordLink || !backToLoginBtn || !loginForm || !forgotPasswordForm) {
            return;
        }

        // Animation pour passer au formulaire de mot de passe oublié
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();

            // Réinitialiser les positions initiales pour éviter les problèmes
            gsap.set(loginForm, {clearProps: 'all'});
            gsap.set(forgotPasswordForm, {clearProps: 'all'});
            gsap.set(forgotPasswordForm, {x: '100%'});

            // Animation avec GSAP
            const tl = gsap.timeline();

            // Animation plus fluide avec meilleure synchronisation
            tl.to(loginForm, {
                duration: 0.4,
                x: '-100%',
                ease: 'power2.inOut'
            })
            .to(forgotPasswordForm, {
                duration: 0.4,
                x: '0%',  // Position finale centrée
                ease: 'power2.inOut'
            }, '-=0.3'); // Légèrement décalé pour un effet plus naturel
        });

        // Animation pour revenir au formulaire de connexion
        backToLoginBtn.addEventListener('click', function() {
            // Animation avec GSAP
            const tl = gsap.timeline();

            // Animation améliorée pour le retour
            tl.to(forgotPasswordForm, {
                duration: 0.4,
                x: '100%',  // Retour à droite
                ease: 'power2.inOut'
            })
            .to(loginForm, {
                duration: 0.4,
                x: '0%',  // Retour au centre
                ease: 'power2.inOut'
            }, '-=0.3'); // Légèrement décalé pour un effet plus naturel
        });
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
    const formTransitionsFuncName = 'setupFormTransitions';

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
/**
 * Script pour gérer l'interface d'authentification par étapes
 */
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des animations et transitions entre les étapes
    setupSidebarInteractions();

    // Récupérer les éléments clés de l'interface
    const openStepsTrigger = document.getElementById('open-steps-trigger');
    const closeStepsTrigger = document.getElementById('close-steps-trigger');
    const sidebar = document.querySelector('.sidebar');
    const modalOverlay = document.getElementById('modal-overlay');
    const mainContentWrapper = document.querySelector('.main-content-wrapper');
    const formContainer = document.querySelector('.form-container-anim');

    // Mise à jour du compteur d'étapes selon l'étape actuelle
    updateStepCounter();

    /**
     * Configure les interactions avec la sidebar sur mobile
     */
    function setupSidebarInteractions() {
        const openTrigger = document.getElementById('open-steps-trigger');
        const closeTrigger = document.getElementById('close-steps-trigger');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('modal-overlay');
        const contentWrapper = document.querySelector('.main-content-wrapper');

        // Vérifier que les éléments existent
        if (!openTrigger || !sidebar) return;

        // Sur mobile uniquement
        if (window.innerWidth <= 1023 && overlay && closeTrigger && contentWrapper) {
            // Timeline pour l'animation d'ouverture/fermeture
            const openTimeline = gsap.timeline({paused: true, reversed: true});

            openTimeline.to(overlay, {autoAlpha: 1, duration: 0.3, ease: 'power2.inOut'})
                .to(sidebar, {y: '0%', autoAlpha: 1, duration: 0.4, ease: 'power3.out'}, '-=0.2')
                .to(contentWrapper, {filter: 'blur(5px)', duration: 0.3, ease: 'power2.inOut'}, '<');

            // Fonction pour ouvrir la sidebar
            const openSidebar = () => openTimeline.play();

            // Fonction pour fermer la sidebar
            const closeSidebar = () => openTimeline.reverse();

            // Configuration des événements
            openTrigger.addEventListener('click', openSidebar);
            closeTrigger.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        }

        // Configuration du lien dans le footer de la sidebar
        const sidebarLoginLink = document.querySelector('.sidebar-footer a');
        if (sidebarLoginLink) {
            sidebarLoginLink.addEventListener('click', function(e) {
                e.preventDefault();
                // Rediriger vers la page d'authentification standard
                window.location.href = '/authentification';
            });
        }
    }

    /**
     * Met à jour le compteur d'étapes en fonction de l'étape actuelle
     */
    function updateStepCounter() {
        // Trouver l'étape active
        const activeStep = document.querySelector('.etape-item.in-progress');
        const completedSteps = document.querySelectorAll('.etape-item.complete');
        const stepCounter = document.getElementById('open-steps-trigger');
/**
 * Script pour gérer l'interface d'authentification par étapes avec animations fluides
 */
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des animations et transitions entre les étapes
    setupSidebarInteractions();
    animateFormEntrance();
    setupFormSubmit();

    /**
     * Configure les interactions avec la sidebar (ouverture/fermeture sur mobile)
     */
    function setupSidebarInteractions() {
        const openTrigger = document.getElementById('open-steps-trigger');
        const closeTrigger = document.getElementById('close-steps-trigger');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('modal-overlay');
        const contentWrapper = document.querySelector('.main-content-wrapper');

        // Vérifier que les éléments existent
        if (!openTrigger || !sidebar) return;

        // Sur mobile uniquement
        if (window.innerWidth <= 1023 && overlay && closeTrigger && contentWrapper) {
            // Timeline pour l'animation d'ouverture/fermeture
            const openTimeline = gsap.timeline({paused: true, reversed: true});

            openTimeline.to(overlay, {autoAlpha: 1, duration: 0.3, ease: 'power2.inOut'})
                .to(sidebar, {x: '0%', autoAlpha: 1, duration: 0.4, ease: 'power3.out'}, '-=0.2')
                .to(contentWrapper, {filter: 'blur(5px)', duration: 0.3, ease: 'power2.inOut'}, '<');

            // Fonction pour ouvrir la sidebar
            const openSidebar = () => openTimeline.play();

            // Fonction pour fermer la sidebar
            const closeSidebar = () => openTimeline.reverse();

            // Configuration des événements
            openTrigger.addEventListener('click', openSidebar);
            closeTrigger.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        }

        // Configuration du lien dans le footer de la sidebar
        const sidebarLoginLink = document.querySelector('.sidebar-footer a');
        if (sidebarLoginLink) {
            sidebarLoginLink.addEventListener('click', function(e) {
                e.preventDefault();
                // Rediriger vers la page d'authentification standard
                window.location.href = '/authentification';
            });
        }
    }

    /**
     * Animation d'entrée du formulaire
     */
    function animateFormEntrance() {
        const formContainer = document.querySelector('.form-container-anim');
        const formFields = document.querySelectorAll('.form-group');
        const formTitle = document.querySelector('.form-title-group');
        const formButton = document.querySelector('.btn.btn-primary');

        if (!formContainer || !gsap) return;

        // Timeline pour l'animation d'entrée
        const entranceTl = gsap.timeline();

        entranceTl
            .fromTo(formContainer, {
                opacity: 0,
                y: 20,
                visibility: 'visible'
            }, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                ease: 'power3.out'
            })
            .fromTo(formTitle, {
                opacity: 0,
                y: 10
            }, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                ease: 'power2.out'
            }, '-=0.3')
            .fromTo(formFields, {
                opacity: 0,
                y: 15,
                stagger: 0.1
            }, {
                opacity: 1,
                y: 0,
                stagger: 0.1,
                duration: 0.4,
                ease: 'power2.out'
            }, '-=0.3')
            .fromTo(formButton, {
                opacity: 0,
                y: 10,
                scale: 0.95
            }, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.5,
                ease: 'back.out(1.7)'
            }, '-=0.2');
    }

    /**
     * Configuration de la soumission du formulaire avec animations
     */
    function setupFormSubmit() {
        const form = document.getElementById('etape-form');
        if (!form) return;

        // Écouter l'événement submit du formulaire
        form.addEventListener('submit', function(e) {
            // L'événement sera géré par ajax.js, nous ajoutons juste l'animation
            const formContainer = document.querySelector('.form-container-anim');

            if (formContainer && gsap) {
                gsap.to(formContainer, {
                    opacity: 0.7,
                    y: 10,
                    duration: 0.3,
                    ease: 'power2.inOut'
                });
            }
        });

        // Écouter l'événement personnalisé ajaxComplete
        document.addEventListener('ajaxComplete', function(e) {
            if (e.detail && e.detail.target && e.detail.target.matches('#etape-form')) {
                // Animer l'apparition des nouveaux champs
                animateNewFields();
            }
        });
    }

    /**
     * Anime l'apparition des nouveaux champs après une réponse AJAX
     */
    function animateNewFields() {
        // Trouver tous les champs qui viennent d'apparaître
        const newlyVisibleFields = document.querySelectorAll('.form-group[style=""]');
        const formContainer = document.querySelector('.form-container-anim');

        if (formContainer && gsap) {
            // D'abord, réinitialiser l'opacité du conteneur
            gsap.to(formContainer, {
                opacity: 1,
                y: 0,
                duration: 0.4,
                ease: 'power2.out'
            });

            // Ensuite, animer les nouveaux champs
            if (newlyVisibleFields.length > 0) {
                gsap.fromTo(newlyVisibleFields, {
                    opacity: 0,
                    y: 15,
                    height: 0,
                    marginBottom: 0,
                    paddingTop: 0,
                    paddingBottom: 0
                }, {
                    opacity: 1,
                    y: 0,
                    height: 'auto',
                    marginBottom: '30px',
                    paddingTop: '0',
                    paddingBottom: '0',
                    duration: 0.5,
                    stagger: 0.1,
                    ease: 'power3.out'
                });
            }
        }
    }

    /**
     * Fonction pour mettre à jour visuellement l'état des étapes
     */
    function updateStepProgress(currentStep) {
        const etapeItems = document.querySelectorAll('.etape-item');

        etapeItems.forEach((item, index) => {
            if (index < currentStep - 1) {
                item.classList.add('complete');
                item.classList.remove('in-progress');
            } else if (index === currentStep - 1) {
                item.classList.add('in-progress');
                item.classList.remove('complete');
            } else {
                item.classList.remove('complete', 'in-progress');
            }
        });
    }
});

/**
 * Extension pour gérer les réponses AJAX et animations
 */
document.addEventListener('DOMContentLoaded', function() {
    // Intercepter les réponses AJAX pour mettre à jour l'interface
    const originalSendRequest = window.sendAjaxRequest;
    if (originalSendRequest) {
        window.sendAjaxRequest = async function(url, options) {
            try {
                const response = await originalSendRequest(url, options);

                // Si nous sommes sur la page d'authentification par étapes
                if (document.querySelector('.auth-container') && document.getElementById('etape-form')) {
                    handleAuthResponse(response, options);
                }

                return response;
            } catch (error) {
                throw error;
            }
        };
    }

    /**
     * Traite la réponse AJAX spécifiquement pour l'authentification par étapes
     */
    function handleAuthResponse(response, options) {
        if (response && response.data) {
            // Vérifier si nous avons une redirection vers l'espace utilisateur
            if (response.data.redirect && response.data.redirect.includes('/espace-utilisateur')) {
                // Animation de succès avant la redirection
                const formContainer = document.querySelector('.form-container-anim');
                if (formContainer && window.gsap) {
                    gsap.to(formContainer, {
                        opacity: 0,
                        y: -20,
                        duration: 0.6,
                        ease: 'power3.in',
                        onComplete: () => {
                            window.location.href = response.data.redirect;
                        }
                    });

                    // Empêcher la redirection automatique pour permettre l'animation
                    return true;
                }
            }

            // Mettre à jour l'interface selon l'étape actuelle
            if (response.data.etape) {
                updateFormForStep(response.data.etape);
            }
        }
    }

    /**
     * Met à jour l'interface du formulaire en fonction de l'étape
     */
    function updateFormForStep(etape) {
        const ipGroup = document.getElementById('ip-group');
        const emailGroup = document.getElementById('email-group');
        const codeGroup = document.getElementById('code-group');
        const passwordGroup = document.getElementById('password-group');
        const passwordConfirmGroup = document.getElementById('password-confirm-group');

        // Mettre à jour l'indicateur d'étape
        const stepCounter = document.getElementById('open-steps-trigger');
        if (stepCounter) {
            let stepNumber = 1;
            switch(etape) {
                case 'envoi_email': stepNumber = 2; break;
                case 'verification_code': stepNumber = 3; break;
                case 'enregistrement': stepNumber = 4; break;
            }
            stepCounter.textContent = `Étape ${stepNumber}/4`;
        }

        // Afficher/masquer les champs selon l'étape
        if (etape === 'envoi_email') {
            if (ipGroup) ipGroup.querySelector('input').readOnly = true;
            if (emailGroup) emailGroup.style.display = '';
            if (codeGroup) codeGroup.style.display = 'none';
            if (passwordGroup) passwordGroup.style.display = 'none';
            if (passwordConfirmGroup) passwordConfirmGroup.style.display = 'none';
        } else if (etape === 'verification_code') {
            if (ipGroup) ipGroup.querySelector('input').readOnly = true;
            if (emailGroup) {
                emailGroup.style.display = '';
                emailGroup.querySelector('input').readOnly = true;
            }
            if (codeGroup) codeGroup.style.display = '';
            if (passwordGroup) passwordGroup.style.display = 'none';
            if (passwordConfirmGroup) passwordConfirmGroup.style.display = 'none';
        } else if (etape === 'enregistrement') {
            if (ipGroup) ipGroup.querySelector('input').readOnly = true;
            if (emailGroup) {
                emailGroup.style.display = '';
                emailGroup.querySelector('input').readOnly = true;
            }
            if (codeGroup) codeGroup.style.display = 'none';
            if (passwordGroup) passwordGroup.style.display = '';
            if (passwordConfirmGroup) passwordConfirmGroup.style.display = '';
        }
    }
});
        if (activeStep && stepCounter) {
            const currentStep = completedSteps.length + 1;
            const totalSteps = document.querySelectorAll('.etape-item').length;
            stepCounter.textContent = `Étape ${currentStep}/${totalSteps}`;
        }
    }

    /**
     * Animation lors de la soumission du formulaire
     */
    document.querySelector('.ajax-form')?.addEventListener('submit', function() {
        // Animation de transition pendant le chargement
        gsap.to(formContainer, {
            opacity: 0.7,
            y: 10,
            duration: 0.3,
            ease: 'power2.inOut'
        });
    });

    /**
     * Écouter les événements AJAX et réinitialiser l'animation
     */
    document.addEventListener('ajaxComplete', function() {
        // Réinitialiser l'animation après le chargement
        gsap.to(formContainer, {
            opacity: 1,
            y: 0,
            duration: 0.4,
            ease: 'back.out(1.7)'
        });

        // Mettre à jour le compteur d'étapes
        updateStepCounter();
    });
});
    // Ajoute la fonction des transitions de formulaire si elle n'est pas déjà présente
    if (!window.ajaxRebinders.some(fn => fn.name === formTransitionsFuncName)) {
        Object.defineProperty(setupFormTransitions, 'name', {value: formTransitionsFuncName});
        window.ajaxRebinders.push(setupFormTransitions);
    }

    // Exécution initiale au chargement de la page
    function initialLoad() {
        animatePageIn();
        setupModalEventListeners();
        setupFormTransitions();

        // Configurer le lien dans le footer de la sidebar
        const footerLoginLink = document.getElementById('footerLoginLink');
        if (footerLoginLink) {
            footerLoginLink.addEventListener('click', function(e) {
                e.preventDefault();
                // Si en mobile, ferme d'abord la sidebar
                if (window.innerWidth <= 1023) {
                    const closeTrigger = document.getElementById('close-steps-trigger');
                    if (closeTrigger) {
                        closeTrigger.click();
                    }
                }

                // Revenir au formulaire de connexion si on est sur un autre formulaire
                const loginForm = document.getElementById('loginForm');
                const forgotPasswordForm = document.getElementById('forgotPasswordForm');

                if (loginForm && forgotPasswordForm) {
                    const tl = gsap.timeline();

                    // Vérifier où on se trouve actuellement
                    if (getComputedStyle(forgotPasswordForm).transform !== 'matrix(1, 0, 0, 1, 0, 0)') {
                        // On n'est pas sur le formulaire de mot de passe oublié, donc pas besoin d'animation
                        return;
                    }

                    // On est sur le formulaire de mot de passe oublié, retourner à la connexion
                    tl.to(forgotPasswordForm, {
                        duration: 0.4,
                        x: '100%',
                        ease: 'power2.inOut'
                    })
                    .to(loginForm, {
                        duration: 0.4,
                        x: '0%',
                        ease: 'power2.inOut'
                    }, '-=0.3');
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialLoad);
    } else {
        initialLoad();
    }

})();