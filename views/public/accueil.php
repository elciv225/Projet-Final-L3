<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail de Soutenance Master 2 - U University</title>
    <link rel="icon" href="data:,">
    <style>
        @import url("https://use.typekit.net/gys0gor.css");

        :root {
            color-scheme: light dark;
            --primary-color: #1A5E63;
            --secondary-color: #FFC857;
            --background-primary: #F9FAFA;
            --background-secondary: #F7F9FA;
            --background-input: #ECF0F1;
            --text-primary: #050E10;
            --text-secondary: #0A1B20;
            --text-disabled: #BDC3C7;
            --button-primary: #1A5E63;
            --button-primary-hover: #15484B;
            --button-secondary: #FFC857;
            --button-secondary-hover: #FCCF6C;
            --button-disabled: #E0E6E8;
            --success: rgb(102 187 106 / 55%);
            --warning: rgb(255 193 7 / 55%);
            --error: rgb(239 83 80 / 55%);
            --info: rgb(100 181 246 / 55%);
            --border-light: #87999A;
            --border-medium: #6B7B7C;
            --border-dark: #162122;
            --gradient-hover: linear-gradient(to bottom, rgb(240 240 240 / 80%), rgb(220 220 220 / 90%));
            --overlay: rgb(44 62 80 / 10%);
            --shadow: rgb(0 0 0 / 5%) 0px 1px 2px 0px;
            --shadow-sm: 0 1px 3px rgb(0 0 0 / 10%);
            --shadow-md: 0 4px 6px rgb(0 0 0 / 10%);
            --shadow-lg: 0 10px 15px rgb(0 0 0 / 10%);
            --input-border: #1A5E63;
            --input-focus: rgb(26 94 99 / 20%);
            --link-color: #2A8F96;
            --link-hover: #1A5E63;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --primary-color: #1A5E63;
                --secondary-color: #FFC857;
                --background-primary: #1B1B1B;
                --background-secondary: #202020;
                --background-input: #2D2D2D;
                --text-primary: #EAEAEA;
                --text-secondary: #CFCFCF;
                --text-disabled: #7F8C8D;
                --button-primary: #1A5E63;
                --button-primary-hover: #15484B;
                --button-secondary: #FFC857;
                --button-secondary-hover: #F3BA44;
                --button-disabled: #4F5B5C;
                --success: rgb(39 174 96 / 40%);
                --warning: rgb(243 156 18 / 40%);
                --error: rgb(231 76 60 / 40%);
                --info: rgb(52 152 219 / 40%);
                --border-light: #2C3E50;
                --border-medium: #34495E;
                --border-dark: #1A252F;
                --gradient-hover: linear-gradient(to bottom, rgb(30 30 30 / 80%), rgb(15 15 15 / 90%));
                --overlay: rgb(0 0 0 / 50%);
                --shadow: rgb(0 0 0 / 10%) 0px 1px 2px 0px;
                --shadow-sm: 0 1px 3px rgb(0 0 0 / 30%);
                --shadow-md: 0 4px 6px rgb(0 0 0 / 30%);
                --shadow-lg: 0 10px 15px rgb(0 0 0 / 30%);
                --input-border: #1A5E63;
                --input-focus: rgb(26 94 99 / 20%);
                --link-color: #1A5E63;
                --link-hover: #15484B;
            }
        }


        /* STYLES GLOBAUX */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: mulish-variable, sans-serif;
            font-variation-settings: "wght" 400;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            color: var(--text-primary);
            background-color: var(--background-primary);
            line-height: 1.7;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px;
        }

        img, video {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 12px;
        }

        .serif-title {
            font-family: 'Merriweather', serif;
            font-weight: 900;
        }

        /* Gardons Merriweather pour les titres, car elle est chargée dans l'import précédent */
        .section-intro {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 60px;
        }

        .section-intro .label {
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .section-intro .title {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            line-height: 1.2;
        }

        /* HEADER */
        .main-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.4s ease;
        }

        .main-nav.scrolled {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
            padding: 15px 0;
        }

        @media (prefers-color-scheme: dark) {
            .main-nav.scrolled {
                background: rgba(27, 27, 27, 0.8);
                border-bottom: 1px solid var(--border-light);
            }
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
            text-decoration: none;
        }

        .nav-brand .logo-icon {
            background-color: var(--primary-color);
            color: white;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 1.2rem;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-actions a:not(.btn) {
            font-size: 1rem;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .nav-actions a:not(.btn):hover {
            color: var(--text-primary);
        }

        .btn {
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--button-primary);
            color: var(--background-primary);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            background-color: var(--button-primary-hover);
            box-shadow: var(--shadow-lg);
        }

        .menu-toggle {
            display: none;
        }

        /* HERO SECTION */
        .hero-section {
            padding-top: 150px;
            padding-bottom: 120px;
            background-color: var(--background-primary);
            overflow: hidden;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 50px;
        }

        .hero-content .label {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: clamp(3rem, 6vw, 5rem);
            line-height: 1.1;
            margin-bottom: 25px;
            perspective: 800px;
            color: var(--text-primary);
        }

        .hero-title .char {
            display: inline-block;
        }

        /* Nécessaire pour SplitText */
        .hero-subtitle {
            font-size: 1.2rem;
            max-width: 500px;
            color: var(--text-secondary);
            margin-bottom: 40px;
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-visual-bg-shape {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: var(--secondary-color);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            z-index: 0;
            opacity: 0.3;
        }

        #hero-video {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
        }

        /* PROCEDURE SECTION (SCROLLYTELLING) */
        section {
            padding: 120px 0;
        }

        .procedure-section {
            background-color: var(--background-secondary);
            padding-bottom: 120px;
        }

        .procedure-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: flex-start;
        }

        .procedure-visual {
            position: sticky;
            top: 120px;
            height: 60vh;
            will-change: transform;
        }

        .procedure-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .procedure-steps {
            padding-top: 20px;
        }

        .step {
            padding: 20px 0;
            margin-bottom: 8vh;
            opacity: 0.35;
            transition: opacity 0.4s ease;
            border-bottom: 1px solid var(--border-light);
        }

        .step.is-active {
            opacity: 1;
        }

        .step-number {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .step h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .step p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .step:last-child {
            border-bottom: none;
        }

        /* STATS SECTION */
        .stats-section {
            background-color: var(--background-primary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            text-align: center;
        }

        .stat-item {
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-10px);
        }

        .stat-item .stat-number {
            font-size: clamp(4rem, 8vw, 6rem);
            font-weight: 700;
            line-height: 1;
            color: var(--primary-color);
        }

        .stat-item .stat-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-top: 10px;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .container {
                padding: 0 30px;
            }

            .hero-grid, .procedure-wrapper {
                grid-template-columns: 1fr;
            }

            .hero-content {
                order: 2;
                margin-top: 40px;
                text-align: center;
            }

            .hero-subtitle {
                margin-left: auto;
                margin-right: auto;
            }

            .procedure-visual {
                position: relative;
                top: 0;
                margin-bottom: 40px;
                height: 300px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 60px;
            }
        }

        @media (max-width: 768px) {
            .nav-actions a:not(.btn) {
                display: none;
            }

            .hero-title {
                font-size: 3rem;
            }

            section {
                padding: 80px 0;
            }

            .menu-toggle {
                display: block;
                background: none;
                border: none;
                cursor: pointer;
                z-index: 1001;
            }

            .menu-toggle svg {
                width: 30px;
                height: 30px;
                color: var(--text-primary);
            }

            .nav-actions {
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                background-color: var(--background-primary);
                flex-direction: column;
                justify-content: center;
                gap: 40px;
                width: 80%;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
                transform: translateX(100%);
                transition: transform 0.4s ease;
            }

            .nav-actions.is-active {
                transform: translateX(0);
            }

            .nav-actions a:not(.btn) {
                display: block;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div id="smooth-wrapper">
    <div id="smooth-content">
        <nav class="main-nav">
            <div class="container nav-container">
                <a href="#" class="nav-brand">
                    <span class="logo-icon">U</span>
                    <span>University</span>
                </a>
                <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <div class="nav-actions" id="nav-actions">
                    <a href="#procedure">Procédure</a>
                    <a href="#stats">Statistiques</a>
                    <a href="#" class="btn btn-primary">Soumettre mon dossier</a>
                </div>
            </div>
        </nav>

        <main>
            <section class="hero-section">
                <div class="container">
                    <div class="hero-grid">
                        <div class="hero-content">
                            <p class="label">Portail de Soutenance</p>
                            <h1 class="hero-title serif-title" id="hero-title">Projet ✖️✖️✖️️</h1>
                            <p class="hero-subtitle">La plateforme officielle pour le dépôt de votre mémoire et de vos
                                documents de soutenance.</p>
                            <a href="#" class="btn btn-primary">Soumettre mon dossier</a>
                        </div>

                        <div class="hero-visual">
                            <video playsinline autoplay muted loop poster="" id="hero-video" data-speed="1.1">
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </section>

            <section id="procedure" class="procedure-section">
                <div class="container">
                    <div class="procedure-wrapper">
                        <div class="procedure-visual">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop"
                                 alt="Étudiants concentrés sur un ordinateur portable">
                        </div>
                        <div class="procedure-steps">
                            <div class="section-intro" style="text-align: left; margin-bottom: 30px;">
                                <p class="label">Procédure</p>
                                <h2 class="serif-title title">Un Processus Simplifié</h2>
                            </div>
                            <div class="step">
                                <div class="step-number">01</div>
                                <h3>Préparation</h3>
                                <p>Vérifiez votre éligibilité et préparez tous les documents requis au format PDF. C'est
                                    l'étape la plus importante.</p>
                            </div>
                            <div class="step">
                                <div class="step-number">02</div>
                                <h3>Soumission</h3>
                                <p>Remplissez le formulaire de soumission en ligne avec précision et téléversez vos
                                    documents.</p>
                            </div>
                            <div class="step">
                                <div class="step-number">03</div>
                                <h3>Validation</h3>
                                <p>Recevez un email de confirmation une fois votre dossier validé par
                                    l'administration.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="stats" class="stats-section">
                <div class="container">
                    <div class="section-intro"><p class="label">En chiffres</p>
                        <h2 class="serif-title title">Notre Engagement, Votre Réussite</h2></div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number" data-target="95">0</div>
                            <div class="stat-label">Taux de diplomation</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="70">0</div>
                            <div class="stat-label">Placement en entreprise</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="98">0</div>
                            <div class="stat-label">Satisfaction étudiant</div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollSmoother.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/SplitText.min.js"></script>

<script src="/assets/js/accueil.js"></script>
</body>
</html>
