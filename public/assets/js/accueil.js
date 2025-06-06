document.addEventListener("DOMContentLoaded", () => {

    gsap.registerPlugin(ScrollTrigger, SplitText, ScrollSmoother);

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

    // --- 4. ANIMATION SCROLLYTELLING POUR LA PROCÉDURE ---
    ScrollTrigger.matchMedia({
        "(min-width: 993px)": function () {
            // Version desktop - pin visual
            ScrollTrigger.create({
                trigger: ".procedure-wrapper",
                pin: ".procedure-visual",
                start: "top 120px",
                end: "bottom bottom-=120px"
            });

            gsap.utils.toArray('.step').forEach((step, i) => {
                gsap.timeline({
                    scrollTrigger: {
                        trigger: step,
                        start: "top center",
                        end: "bottom center",
                        toggleClass: {targets: step, className: "is-active"},
                        scrub: 0.5
                    }
                });
            });
        },
        "(max-width: 992px)": function () {
            // Version mobile - animation simple et efficace
            gsap.utils.toArray('.step').forEach((step, index) => {
                // Animation d'entrée
                gsap.fromTo(step,
                    {
                        opacity: 0.3,
                        y: 30
                    },
                    {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: step,
                            start: "top 80%",
                            end: "top 20%",
                            toggleActions: "play none none reverse",
                            onEnter: () => {
                                // Retirer la classe active de tous les steps
                                gsap.utils.toArray('.step').forEach(s => s.classList.remove('is-active'));
                                // Ajouter la classe active au step courant
                                step.classList.add('is-active');
                            },
                            onLeave: () => {
                                step.classList.remove('is-active');
                            },
                            onEnterBack: () => {
                                // Retirer la classe active de tous les steps
                                gsap.utils.toArray('.step').forEach(s => s.classList.remove('is-active'));
                                // Ajouter la classe active au step courant
                                step.classList.add('is-active');
                            },
                            onLeaveBack: () => {
                                step.classList.remove('is-active');
                            }
                        }
                    }
                );
            });

            // S'assurer que le premier step est actif au chargement sur mobile
            const firstStep = document.querySelector('.step');
            if (firstStep) {
                firstStep.classList.add('is-active');
            }
        }
    });

    // --- 5. ANIMATION DES COMPTEURS (STATS) ---
    gsap.utils.toArray('.stat-number').forEach(stat => {
        const target = parseInt(stat.dataset.target);
        gsap.from(stat, {
            textContent: 0,
            duration: 2,
            ease: 'power1.inOut',
            snap: {textContent: 1},
            scrollTrigger: {
                trigger: stat,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            onUpdate: function () {
                this.targets()[0].innerHTML = Math.ceil(this.targets()[0].textContent) + '%';
            }
        });
    });

    // --- 6. MENU MOBILE ---
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

    // --- 3. ANIMATION D'INTRODUCTION AVEC SPLITTEXT ---
    document.fonts.ready.then(() => {
        const heroTitle = document.getElementById('hero-title');
        if (heroTitle) {
            let split = new SplitText(heroTitle, {type: "chars"});
            // Cacher initialement les éléments qui vont être animés
            gsap.set([".hero-subtitle", ".hero-content .btn"], {opacity: 0, y: 30});
            gsap.set(".hero-content .btn", {scale: 0.8});

            const tl = gsap.timeline({defaults: {ease: 'power4.out'}});

            tl.from(".main-nav", {y: -30, opacity: 0, duration: 1})
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
                .to(".hero-subtitle", {
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                }, "-=0.8")
                .to(".hero-content .btn", {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.2,
                    ease: "back.out(1.7)",
                    delay: -0.8
                })
                .from("#hero-video", {clipPath: 'inset(50% 50% 50% 50%)', duration: 1.5}, "<");
        } else {
            console.warn("L'élément #hero-title n'a pas été trouvé pour SplitText.");
        }
    }).catch(err => {
        console.error("Erreur lors du chargement des polices :", err);
    });
});