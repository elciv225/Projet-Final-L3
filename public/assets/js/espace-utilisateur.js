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

    /**
     * Gère l'ouverture et la fermeture du modal de modification des informations utilisateur
     */
    function initModalUtilisateur() {
        const modal = document.getElementById('modal-modifier-infos');
        const btnOuvrir = document.getElementById('btn-modifier-infos');
        const btnFermer = modal.querySelector('.close');
        const btnAnnuler = document.getElementById('btn-annuler');
        const form = document.getElementById('form-modifier-infos');

        // Ouvrir le modal
        btnOuvrir.addEventListener('click', function() {
            modal.style.display = 'block';
        });

        // Fermer le modal (bouton X)
        btnFermer.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Fermer le modal (bouton Annuler)
        btnAnnuler.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Fermer le modal en cliquant en dehors
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Gérer la soumission du formulaire
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour les informations affichées sans rechargement
                    const userNameElement = document.querySelector('.user-name');
                    const userEmailElement = document.querySelector('.user-email');
                    const headerTitle = document.querySelector('.header-title h1');
                    const userAvatar = document.querySelector('.user-avatar');

                    if (userNameElement) userNameElement.textContent = data.utilisateur.nom_complet;
                    if (userEmailElement) userEmailElement.textContent = data.utilisateur.email;
                    if (headerTitle) headerTitle.textContent = `Bonjour, ${data.utilisateur.prenoms} !`;
                    if (userAvatar) userAvatar.textContent = data.utilisateur.initiales;

                    // Afficher un message de succès
                    alert(data.message);

                    // Fermer le modal
                    modal.style.display = 'none';
                } else {
                    // Afficher un message d'erreur
                    alert(data.message || 'Une erreur est survenue lors de la mise à jour de vos informations.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la communication avec le serveur.');
            });
        });
    }

    /**
     * Gère le menu utilisateur et ses interactions
     */
    function initMenuUtilisateur() {
        const userProfile = document.querySelector('.user-profile');
        const userMenu = document.querySelector('.user-menu');
        const btnModifierProfil = document.getElementById('btn-modifier-profil');
        const btnDeconnexion = document.getElementById('btn-deconnexion');
        const modalModifierInfos = document.getElementById('modal-modifier-infos');
        const modalDeconnexion = document.getElementById('modal-deconnexion');

        // Gestion du menu utilisateur (alternative au hover CSS pour plus de contrôle)
        let menuTimeout;

        userProfile.addEventListener('mouseenter', function() {
            clearTimeout(menuTimeout);
            userMenu.classList.add('active');
        });

        userProfile.addEventListener('mouseleave', function() {
            menuTimeout = setTimeout(function() {
                userMenu.classList.remove('active');
            }, 300); // Délai avant fermeture pour éviter fermeture accidentelle
        });

        userMenu.addEventListener('mouseenter', function() {
            clearTimeout(menuTimeout);
        });

        userMenu.addEventListener('mouseleave', function() {
            menuTimeout = setTimeout(function() {
                userMenu.classList.remove('active');
            }, 300);
        });

        // Gestion du clic sur "Modifier mes informations"
        if (btnModifierProfil) {
            btnModifierProfil.addEventListener('click', function(e) {
                e.preventDefault();
                userMenu.classList.remove('active');
                modalModifierInfos.style.display = 'block';
            });
        }

        // Gestion du clic sur "Se déconnecter"
        if (btnDeconnexion) {
            btnDeconnexion.addEventListener('click', function(e) {
                e.preventDefault();
                userMenu.classList.remove('active');
                modalDeconnexion.style.display = 'block';
            });
        }

        // Fermer le modal de déconnexion (bouton X)
        const btnFermerDeconnexion = modalDeconnexion.querySelector('.close');
        if (btnFermerDeconnexion) {
            btnFermerDeconnexion.addEventListener('click', function() {
                modalDeconnexion.style.display = 'none';
            });
        }

        // Fermer le modal de déconnexion (bouton Annuler)
        const btnAnnulerDeconnexion = document.getElementById('btn-annuler-deconnexion');
        if (btnAnnulerDeconnexion) {
            btnAnnulerDeconnexion.addEventListener('click', function() {
                modalDeconnexion.style.display = 'none';
            });
        }

        // Fermer le modal en cliquant en dehors
        window.addEventListener('click', function(event) {
            if (event.target === modalDeconnexion) {
                modalDeconnexion.style.display = 'none';
            }
        });
    }

    // Lancement des fonctions
    updateStatusProgress();
    initAnimations();
    initModalUtilisateur();
    initMenuUtilisateur();

});
