document.addEventListener("DOMContentLoaded", () => {

    // --- 0. INITIALISATION DES PLUGINS ET DU CURSEUR ---
    gsap.registerPlugin(ScrollTrigger, SplitText, ScrollSmoother, ScrambleTextPlugin);

    // Initialise le curseur seulement sur les appareils non-tactiles
    if (window.matchMedia("(pointer: fine)").matches) {
        const cursor = document.createElement('div');
        cursor.className = 'custom-cursor';
        document.body.appendChild(cursor);

        window.addEventListener('mousemove', e => {
            gsap.to(cursor, { duration: 0.3, x: e.clientX, y: e.clientY, ease: 'power2.out' });
        });

        const hoverElements = document.querySelectorAll('a, button');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', () => gsap.to(cursor, { scale: 2.5, duration: 0.3 }));
            el.addEventListener('mouseleave', () => gsap.to(cursor, { scale: 1, duration: 0.3 }));
        });
    }

    // --- 1. SCROLL SMOOTHER ---
    const smoother = ScrollSmoother.create({
        wrapper: "#smooth-wrapper",
        content: "#smooth-content",
        smooth: 1.5,
        effects: true,
    });

    // --- 2. GESTION DU HEADER ---
    ScrollTrigger.create({
        start: 'top -80px',
        onEnter: () => document.querySelector('.main-nav').classList.add('scrolled'),
        onLeaveBack: () => document.querySelector('.main-nav').classList.remove('scrolled'),
    });

    // --- 3. ANIMATION D'INTRODUCTION HERO (AVEC SPLITTEXT 3D) ---
    document.fonts.ready.then(() => {
        const heroTitle = document.getElementById('hero-title');
        const heroVideo = document.getElementById('hero-video');

        if (heroTitle) {
            const split = new SplitText(heroTitle, { type: "chars" });
            gsap.set([".hero-subtitle", ".hero-actions"], { autoAlpha: 0, y: 30 });

            const tl = gsap.timeline({ defaults: { ease: 'power4.out' } });

            tl.from(heroVideo, { scale: 1.1, duration: 4, ease: 'power2.inOut' }, 0)
                .from(".hero-overlay", { autoAlpha: 0, duration: 1.5 }, 0)
                .from(split.chars, {
                    duration: 0.8,
                    opacity: 0,
                    scale: 0.5,
                    y: 60,
                    rotationX: -180, // L'effet 3D que vous aviez apprécié
                    transformOrigin: "50% 50%",
                    ease: "back.out",
                    stagger: 0.04
                }, 0.5)
                .to(".hero-subtitle", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.8")
                .to(".hero-actions", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.7");
        }
    });

    // --- 4. ANIMATIONS COHÉRENTES AU DÉFILEMENT ---
    // Fonction unique pour une animation d'apparition propre
    function animateOnScroll(elements, staggerVal = 0) {
        gsap.from(elements, {
            autoAlpha: 0, y: 50, duration: 1, ease: 'power3.out',
            stagger: staggerVal,
            scrollTrigger: { trigger: elements, start: 'top 85%', toggleActions: 'play none none reverse' }
        });
    }

    // Animation des titres de section avec ScrambleText
    gsap.utils.toArray('.section-intro .title').forEach(title => {
        gsap.from(title, {
            duration: 1.5,
            scrambleText: { text: "████████ ████████", chars: "lowerCase", revealDelay: 0.5, speed: 0.3 },
            scrollTrigger: { trigger: title, start: 'top 90%', toggleActions: 'play none none none' }
        });
    });

    // Animation des autres éléments
    animateOnScroll(gsap.utils.toArray([".section-intro .label", ".main-footer"]));
    animateOnScroll(gsap.utils.toArray(".step"), 0.1);
    animateOnScroll(gsap.utils.toArray(".stat-item"), 0.1);

    // --- 5. ANIMATION PROCÉDURE (LOGIQUE STABLE ET CORRIGÉE) ---
    const steps = gsap.utils.toArray('.step');
    const images = gsap.utils.toArray('.procedure-image');

    steps.forEach((step, i) => {
        step.setAttribute('data-step-number', `0${i + 1}`);
    });

    ScrollTrigger.matchMedia({
        "(min-width: 993px)": function() {
            ScrollTrigger.create({ trigger: ".procedure-wrapper", pin: ".procedure-visual", start: "top 120px", end: "bottom bottom" });
        }
    });

    // Boucle unique pour gérer le "scrollytelling" de manière synchronisée
    steps.forEach((step, i) => {
        ScrollTrigger.create({
            trigger: step,
            start: "top center",
            end: "bottom center",
            toggleActions: "play reverse play reverse", // Activation/désactivation nette
            toggleClass: { targets: step, className: "is-active" },
            onEnter: () => {
                images.forEach(img => img.classList.remove('active'));
                if (images[i]) images[i].classList.add('active');
            },
            onEnterBack: () => {
                images.forEach(img => img.classList.remove('active'));
                if (images[i]) images[i].classList.add('active');
            }
        });
    });

    // --- 6. ANIMATION DES COMPTEURS (STATS) ---
    gsap.utils.toArray('.stat-number').forEach(stat => {
        const target = stat.dataset.target + "%";
        gsap.from(stat, {
            textContent: "0%", duration: 2.5, ease: "power2.out",
            scrambleText: { text: target, chars: "0123456789%", speed: 0.5 },
            scrollTrigger: { trigger: stat, start: 'top 85%', toggleActions: 'play none none none' }
        });
    });

    // --- 7. MENU MOBILE ---
    const menuToggle = document.getElementById('menu-toggle');
    const navActions = document.getElementById('nav-actions');
    if (menuToggle && navActions) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            navActions.classList.toggle('is-active');
        });
        document.addEventListener('click', (e) => {
            if (navActions.classList.contains('is-active') && !navActions.contains(e.target)) {
                navActions.classList.remove('is-active');
            }
        });
    }
});
