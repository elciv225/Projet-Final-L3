<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Authentification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Assurez-vous que les chemins sont corrects -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/authentification.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="icon" href="data:,">
    <style>
        .forms-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            min-height: 380px; /* Hauteur fixe augmentée pour éviter les sauts */
        }

        .login-form-container,
        .forgot-password-container {
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            will-change: transform; /* Optimisation pour les animations */
        }

        .login-form-container {
            transform: translateX(0);
        }

        .forgot-password-container {
            transform: translateX(100%);
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--link-color);
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .back-button:hover {
            text-decoration: underline;
            color: var(--link-hover);
        }
    </style>
</head>
<body id="content-area">

    <div class="auth-container">
    <div class="main-content-wrapper">
        <main class="form-content">
            <!-- Conteneur pour les animations GSAP -->
            <div class="form-container-anim">
                <div class="forms-container">
                    <!-- Formulaire de connexion -->
                    <div class="login-form-container" id="loginForm">
                        <div class="form-title-group">
                            <h2>Veuillez vous connecter</h2>
                            <p>Entrez vos identifiants pour accéder à votre compte</p>
                        </div>

                        <form action="/authentification" method="post" class="ajax-form">
                            <div class="form-group">
                                <input type="text" name="login" class="form-input" placeholder=" " id="ip" required>
                                <label class="form-label" for="ip">Login</label>
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-input" placeholder=" " id="password" required>
                                <label class="form-label" for="password">Mot de passe</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Se Connecter</button>
                        </form>
                        <div class="forgot-password-link-container">
                            <a href="#" id="forgotPasswordLink">Mot de passe oublié ?</a>
                        </div>
                    </div>

                    <!-- Formulaire de récupération de mot de passe -->
                    <div class="forgot-password-container" id="forgotPasswordForm">
                        <div class="form-title-group">
                            <h2>Mot de passe oublié</h2>
                            <p>Nous vous enverrons un lien pour réinitialiser votre mot de passe</p>
                        </div>

                        <div class="back-link-container">
                            <span class="back-button" id="backToLoginBtn">&larr; Retour à la connexion</span>
                        </div>

                        <form action="/mot-de-passe-oublie" method="post" class="ajax-form">
                            <div class="form-group">
                                <input type="email" name="email" class="form-input" placeholder=" " id="email" required>
                                <label class="form-label" for="email">Adresse email</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal-overlay" id="modal-overlay"></div>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="/assets/js/authentification.js" defer></script>
<script src="/assets/js/ajax.js" defer></script>

</body>
</html>
