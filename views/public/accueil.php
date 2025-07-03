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
                    <span class="logo-icon"></span>
                    <span></span>
                </a>
                <!-- Structure du bouton modifiée pour l'animation -->
                <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
                <div class="nav-actions" id="nav-actions">
                    <a href="#procedure">Procédure</a>
                    <a href="#stats">Statistiques</a>
                    <a href="/authentification" class="btn btn-primary">Soumettre mon dossier</a>
                </div>
            </div>
        </nav>

        <main>
            <section class="hero-section">
                <div class="video-overlay"></div>
                <div class="container">
                    <div class="hero-grid">
                        <div class="hero-content">
                            <p class="label">Portail de Soutenance</p>
                            <h1 class="hero-title serif-title" id="hero-title">Portail de Soutenance Master 2</h1>
                            <p class="hero-subtitle">La plateforme officielle pour le dépôt de votre mémoire et de vos documents de soutenance.</p>
                            <a href="/authentification" class="btn btn-primary hero-actions">Soumettre mon dossier</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="procedure" class="procedure-section-timeline">
                <div class="container">
                    <div class="section-intro">
                        <p class="label">Procédure</p>
                        <h2 class="serif-title title">Un Processus Simplifié</h2>
                    </div>
                    <div class="timeline">
                        <div class="timeline-line"></div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="step-number">01</div>
                                <h3>Préparation</h3>
                                <p>Vérifiez votre éligibilité et préparez tous les documents requis au format PDF. C'est l'étape la plus importante.</p>
                            </div>
                            <div class="timeline-visual">
                                <img src="https://images.pexels.com/photos/4144923/pexels-photo-4144923.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Étudiants préparant des documents dans une bibliothèque.">
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="step-number">02</div>
                                <h3>Soumission</h3>
                                <p>Remplissez le formulaire de soumission en ligne avec précision et téléversez vos documents.</p>
                            </div>
                            <div class="timeline-visual">
                                <img src="https://images.pexels.com/photos/4050315/pexels-photo-4050315.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Personne soumettant un dossier en ligne sur un ordinateur.">
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="step-number">03</div>
                                <h3>Validation</h3>
                                <p>Recevez un email de confirmation une fois votre dossier validé par l'administration.</p>
                            </div>
                            <div class="timeline-visual">
                                <img src="https://images.pexels.com/photos/7129713/pexels-photo-7129713.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Célébration d'une réussite ou d'une validation.">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="stats" class="stats-section">
                <div class="container">
                    <div class="section-intro">
                        <p class="label">En chiffres</p>
                        <h2 class="serif-title title">Notre Engagement, Votre Réussite</h2>
                    </div>
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

        <footer class="main-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-brand">
                        <a href="#" class="nav-brand">
                            <span class="logo-icon">U</span>
                            <span>University</span>
                        </a>
                        <p>La réussite de nos étudiants est notre priorité.</p>
                    </div>
                    <div class="footer-links">
                        <h4>Navigation</h4>
                        <ul>
                            <li><a href="#procedure">Procédure</a></li>
                            <li><a href="#stats">Statistiques</a></li>
                            <li><a href="#">Mentions Légales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2024 U University. Tous droits réservés.</p>
                </div>
            </div>
        </footer>

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
