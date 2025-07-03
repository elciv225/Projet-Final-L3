<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/authentification.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="icon" href="data:,">
</head>
<body id="content-area">
<div class="auth-container">
    <div class="main-content-wrapper">
        <main class="form-content">
            <!-- Conteneur pour les animations GSAP -->
            <div class="form-container-anim">
                <div class="form-title-group">
                    <h2>Réinitialisation du mot de passe</h2>
                    <p>Veuillez définir votre nouveau mot de passe</p>
                </div>

                <form action="/reinitialiser-mot-de-passe" method="post" class="ajax-form">
                    <input type="hidden" name="token" value="<?= $token ?? '' ?>">

                    <div class="form-group">
                        <input type="password" name="nouveau_mot_de_passe" class="form-input" placeholder=" "
                               id="nouveau_mot_de_passe" required>
                        <label class="form-label" for="nouveau_mot_de_passe">Nouveau mot de passe</label>
                    </div>

                    <div class="form-group">
                        <input type="password" name="confirmation_mot_de_passe" class="form-input" placeholder=" "
                               id="confirmation_mot_de_passe" required>
                        <label class="form-label" for="confirmation_mot_de_passe">Confirmez le mot de passe</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Réinitialiser</button>
                </form>
                <div class="forgot-password-link-container">
                    <a href="/authentification">Retour à la connexion</a>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
    <script src="/assets/js/authentification.js" defer></script>
    <script src="/assets/js/ajax.js" defer></script>
</div>
</body>
</html>
