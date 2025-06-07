<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail de Soutenance Master 2 | U University</title>

    <!-- Balises SEO -->
    <meta name="description" content="La plateforme officielle de l'U University pour le dépôt de votre mémoire et l'organisation de votre soutenance de Master 2. Finalisez votre parcours en toute sérénité.">
    <meta name="author" content="U University">
    <meta property="og:title" content="Portail de Soutenance Master 2 | U University">
    <meta property="og:description" content="Finalisez votre parcours en toute sérénité avec la plateforme officielle de dépôt de mémoire.">
    <!-- Pensez à ajouter les autres balises meta (og:image, twitter:card, etc.) -->

    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/accueil.css">
    <link rel="icon" href="data:,">
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
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
                <div class="hero-background-video">
                    <video playsinline autoplay muted loop poster="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop" id="hero-video">
                        <source src="https://videos.pexels.com/video-files/5949397/5949397-hd_1920_1080_25fps.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="hero-overlay"></div>
                <div class="container hero-content-container">
                    <div class="hero-content" data-speed="0.9">
                        <p class="label">Portail de Soutenance Master 2</p>
                        <h1 class="hero-title" id="hero-title">Tu es sur ?</h1>
                        <p class="hero-subtitle">Finalisez votre Master en toute sérénité sur la plateforme officielle de l'université.</p>
                        <div class="hero-actions">
                            <a href="#soumission" class="btn btn-primary">Démarrer la soumission</a>
                            <a href="#procedure" class="btn btn-secondary">Voir la procédure</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="procedure" class="procedure-section">
                <div class="container">
                    <!-- Titre de section centré -->
                    <div class="section-intro">
                        <p class="label">Procédure</p>
                        <h2 class="title">Un Processus Simplifié</h2>
                    </div>

                    <div class="procedure-wrapper">
                        <div class="procedure-visual">
                            <div class="procedure-visual-inner">
                                <img src="https://images.unsplash.com/photo-1516534775068-ba3e7458af70?q=80&w=2070&auto=format&fit=crop"
                                     alt="Étudiants organisant leurs documents de recherche sur un bureau." class="procedure-image active">
                                <img src="https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?q=80&w=2070&auto=format&fit=crop"
                                     alt="Une personne soumettant un formulaire en ligne sur un ordinateur portable." class="procedure-image">
                                <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?q=80&w=2070&auto=format&fit=crop"
                                     alt="Un livre ouvert symbolisant la validation et la réussite académique." class="procedure-image">
                            </div>
                        </div>
                        <div class="procedure-steps">
                            <div class="step">
                                <div class="step-number">01</div>
                                <h3>Préparation</h3>
                                <p>Préparez vos documents au format PDF et vérifiez les prérequis.</p>
                            </div>
                            <div class="step">
                                <div class="step-number">02</div>
                                <h3>Soumission</h3>
                                <p>Remplissez le formulaire en ligne et téléversez vos fichiers avec soin.</p>
                            </div>
                            <div class="step">
                                <div class="step-number">03</div>
                                <h3>Validation</h3>
                                <p>Recevez la confirmation de validation par l'administration par email.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="stats" class="stats-section">
                <div class="container">
                    <div class="section-intro">
                        <p class="label">En chiffres</p>
                        <h2 class="title">Notre Engagement, Votre Réussite</h2>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number" data-target="95">0%</div>
                            <div class="stat-label">Taux de diplomation</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="70">0%</div>
                            <div class="stat-label">Placement en entreprise</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="98">0%</div>
                            <div class="stat-label">Satisfaction étudiant</div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="main-footer">
            <div class="container">
                <p>&copy; 2025 U University. Tous droits réservés.</p>
            </div>
        </footer>
    </div>
</div>

<!-- SCRIPTS JS -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollSmoother.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/SplitText.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrambleTextPlugin.min.js"></script>
<script src="/assets/js/accueil.js"></script>
</body>
</html>
