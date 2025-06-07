<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail de Soutenance Master 2 - U University</title>
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/accueil.css">
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
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrambleTextPlugin.min.js"></script>

<script src="/assets/js/accueil.js"></script>
</body>
</html>