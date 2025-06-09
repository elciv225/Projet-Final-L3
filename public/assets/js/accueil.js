document.addEventListener("DOMContentLoaded", () => {
    // --- 0. & 1. & 2. INITIALISATION, SMOOTHER, HEADER ---
    gsap.registerPlugin(ScrollTrigger, SplitText, ScrollSmoother, ScrambleTextPlugin);

    if (window.matchMedia("(pointer: fine)").matches) {
        // ... code du curseur inchangé ...
    }

    const smoother = ScrollSmoother.create({
        wrapper: "#smooth-wrapper",
        content: "#smooth-content",
        smooth: 1.5,
        effects: true,
    });

    ScrollTrigger.create({
        start: 'top -80px',
        onEnter: () => document.querySelector('.main-nav').classList.add('scrolled'),
        onLeaveBack: () => document.querySelector('.main-nav').classList.remove('scrolled'),
    });

    // --- NOUVELLE FONCTION: PRÉCHARGEMENT DES IMAGES ---
    function preloadImages() {
        return new Promise((resolve) => {
            const images = document.querySelectorAll('.timeline-visual img');
            let loadedCount = 0;
            const totalImages = images.length;

            if (totalImages === 0) {
                resolve(); // Pas d'images à charger
                return;
            }

            images.forEach((img) => {
                // Si l'image est déjà chargée
                if (img.complete && img.naturalHeight !== 0) {
                    img.classList.add('loaded');
                    loadedCount++;
                    if (loadedCount === totalImages) {
                        resolve();
                    }
                } else {
                    // Attendre le chargement de l'image
                    img.addEventListener('load', () => {
                        img.classList.add('loaded');
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            resolve();
                        }
                    });

                    // Gérer les erreurs de chargement
                    img.addEventListener('error', () => {
                        console.warn('Erreur de chargement de l\'image:', img.src);
                        img.classList.add('loaded'); // Afficher quand même pour éviter le blocage
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            resolve();
                        }
                    });
                }
            });
        });
    }

    // --- FONCTION D'INITIALISATION DES ANIMATIONS ---
    function initializeAnimations() {
        // --- 3. ANIMATION D'INTRODUCTION HERO ---
        document.fonts.ready.then(() => {
            const heroTitle = document.getElementById('hero-title');

            if (heroTitle) {
                const split = new SplitText(heroTitle, { type: "chars" });
                gsap.set([".hero-subtitle", ".hero-actions"], { autoAlpha: 0, y: 30 });

                const tl = gsap.timeline({ defaults: { ease: 'power4.out' } });

                tl.from(".video-overlay", { autoAlpha: 0, duration: 1.5 }, 0)
                    .from(split.chars, {
                        duration: 0.8,
                        opacity: 0,
                        scale: 0.5,
                        y: 60,
                        rotationX: -180,
                        transformOrigin: "50% 50%",
                        ease: "back.out",
                        stagger: 0.04
                    }, 0.5)
                    .to(".hero-subtitle", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.8")
                    .to(".hero-actions", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.7");
            }
        });

        // --- 4. ANIMATIONS COHÉRENTES AU DÉFILEMENT ---
        function animateOnScroll(elements, vars = {}) {
            gsap.from(elements, {
                autoAlpha: 0,
                y: 50,
                duration: 1,
                ease: 'power3.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: elements,
                    start: 'top 85%',
                    toggleActions: 'play none none reverse'
                },
                ...vars
            });
        }

        animateOnScroll(gsap.utils.toArray([".section-intro .title", ".section-intro .label"]));
        animateOnScroll(gsap.utils.toArray(".stat-item"));
        animateOnScroll(document.querySelector(".main-footer"));

        // --- 5. ANIMATION DYNAMIQUE POUR LA TIMELINE ---
        gsap.utils.toArray('.timeline-item').forEach((item, index) => {
            const content = item.querySelector('.timeline-content');
            const visual = item.querySelector('.timeline-visual');

            const direction = index % 2 === 0 ? -60 : 60;

            gsap.from([content, visual], {
                autoAlpha: 0,
                x: direction,
                stagger: 0.15,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // --- 6. ANIMATION DES COMPTEURS (STATS) ---
        gsap.utils.toArray('.stat-number').forEach(stat => {
            const target = parseInt(stat.dataset.target, 10);
            gsap.from(stat, {
                textContent: 0,
                duration: 2.5,
                ease: "power2.out",
                snap: { textContent: 1 },
                onUpdate: function() {
                    this.targets()[0].innerHTML = Math.ceil(this.ratio * target) + '%';
                },
                scrollTrigger: {
                    trigger: stat,
                    start: 'top 85%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // Rafraîchir ScrollTrigger après toutes les animations
        ScrollTrigger.refresh();
    }

    // --- 7. MENU MOBILE ---
    const menuToggle = document.getElementById('menu-toggle');
    const navActions = document.getElementById('nav-actions');
    if (menuToggle && navActions) {
        menuToggle.addEventListener('click', () => {
            const isActive = menuToggle.classList.toggle('is-active');
            menuToggle.setAttribute('aria-expanded', isActive);
            document.body.classList.toggle('nav-open');
            navActions.classList.toggle('is-active');
        });
    }

    // --- 8. GESTION DE LA NAVIGATION INTERNE ---
    function setupSmoothNavigation() {
        const navLinks = document.querySelectorAll('a[href^="#"]');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');

                if (href === '#') {
                    e.preventDefault();
                    return;
                }

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();

                    if (navActions && navActions.classList.contains('is-active')) {
                        menuToggle.classList.remove('is-active');
                        menuToggle.setAttribute('aria-expanded', false);
                        document.body.classList.remove('nav-open');
                        navActions.classList.remove('is-active');
                    }

                    smoother.scrollTo(target, true, "top top");
                }
            });
        });
    }

    // --- SÉQUENCE D'INITIALISATION CORRIGÉE ---
    // 1. Initialiser la navigation (ne dépend pas des images)
    setupSmoothNavigation();

    // 2. Attendre le chargement des images, puis initialiser les animations
    preloadImages().then(() => {
        console.log('✅ Toutes les images sont chargées, initialisation des animations...');
        initializeAnimations();
    }).catch((error) => {
        console.error('❌ Erreur lors du préchargement des images:', error);
        // Initialiser quand même les animations en cas d'erreur
        initializeAnimations();
    });
});