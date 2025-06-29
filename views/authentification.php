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
</head>
<body id="content-area">

    <div class="main-content-wrapper">
        <main class="form-content">
            <!-- Conteneur pour les animations GSAP -->
            <div class="form-container-anim">
                <div class="form-title-group">
                    <h2>Veuillez vous connecter</h2>
                    <p>Info</p>
                </div>

                <form action="/authentification-administration" method="post" class="ajax-form">
                    <div class="form-group">
                        <input type="text" name="login" class="form-input" placeholder=" " id="ip" required>
                        <label class="form-label" for="ip">Login</label>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-input" placeholder=" " id="password"
                               required>
                        <label class="form-label" for="password">Mot de passe</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Se Connecter</button>
                </form>
                <a href="#">Mot de passe oublié ?</a>
            </div>
        </main>
    </div>


<div class="modal-overlay" id="modal-overlay"></div>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="/assets/js/authentification.js" defer></script>
<script src="/assets/js/ajax.js" defer></script>

</body>
</html>
