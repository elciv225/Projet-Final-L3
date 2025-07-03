document.addEventListener('DOMContentLoaded', function () {

    /**
     * Met à jour la barre de progression du statut du rapport.
     * La fonction calcule le pourcentage de progression en fonction de l'étape "active"
     * et ajuste la largeur (ou la hauteur en vue mobile) de la ligne de statut.
     */
    function updateStatusProgress() {
        const statusLine = document.getElementById('status-line');
        if (!statusLine) return; // Quitter si l'élément n'existe pas

        const steps = document.querySelectorAll('.status-step');
        const activeStep = document.querySelector('.status-step.active');

        // S'il n'y a aucune étape, on ne fait rien
        if (steps.length === 0) return;

        let progressPercentage = 0;

        if (activeStep) {
            const activeIndex = Array.from(steps).indexOf(activeStep);
            // Calcul simple du pourcentage basé sur l'index de l'étape active
            if (steps.length > 1) {
                progressPercentage = (activeIndex / (steps.length - 1)) * 100;
            }
        } else {
            // Si aucune étape n'est active, on vérifie si toutes sont complétées
            const completedSteps = document.querySelectorAll('.status-step.completed');
            if (completedSteps.length === steps.length) {
                progressPercentage = 100;
            }
        }

        // Appliquer le style. En CSS, la vue mobile change la 'width' en 'height'
        // On met donc à jour les deux propriétés pour que ça fonctionne dans les deux cas.
        statusLine.style.width = `${progressPercentage}%`;
        statusLine.style.height = `${progressPercentage}%`;
    }

    /**
     * Initialise les animations GSAP pour une apparition fluide des éléments.
     */
    function initAnimations() {
        if (typeof gsap !== 'undefined') {
            // Animation pour l'en-tête
            gsap.from('.dashboard-header', {
                y: -30,
                opacity: 0,
                duration: 0.6,
                ease: 'power3.out'
            });

            // Animation pour les widgets avec un effet de décalage
            gsap.from('.widget', {
                y: 40,
                opacity: 0,
                duration: 0.7,
                stagger: 0.1,
                delay: 0.2,
                ease: 'power3.out'
            });

            // Animation pour la barre de navigation
            if (window.innerWidth > 768) { // Uniquement sur desktop
                gsap.from('.nav-link', {
                    x: -30,
                    opacity: 0,
                    duration: 0.5,
                    stagger: 0.1,
                    delay: 0.5
                });
            }
        }
    }

    // Lancement des fonctions
    updateStatusProgress();
    initAnimations();

});
