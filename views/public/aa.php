<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Authentification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/authentification.css">
    <link rel="icon" href="data:,">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
    <!-- Le script ajax.js gère les soumissions de formulaire et les popups -->
    <script src="/assets/js/ajax.js" defer></script>
    <!-- Le script authentification.js gère la navigation entre vues (connexion/inscription) -->
    <script src="/assets/js/authentification.js" defer></script>
</head>
<body>
    <div id="popup-container"></div>
    <div class="auth-wrapper">
        <aside class="steps-panel" id="steps-panel">
            <div class="steps-header">
                <div class="steps-logo"></div>
                <div class="steps-title">RapportFacile</div>
            </div>
            <ol class="steps-list">

                    <li class="step-item ">
                        <div class="step-icon-wrapper">
                            <div class="step-line"></div>
                            <div class="step-icon">

                            </div>
                        </div>
                        <div class="step-details">
                            <p class="step-title"></p>
                            <p class="step-subtitle"></p>
                        </div>
                    </li>

            </ol>
            <div class="steps-footer" id="auth-footer">
                 <p>Vous avez déjà un compte? <a href="#connexion">Se connecter</a></p>
            </div>
        </aside>

        <main class="form-container">
            <div id="form-container-inner">

                <!-- Vue Inscription (par étapes) -->
                <div id="vue-inscription" class="form-view">
                     <form action="/authentification" method="post" class="ajax-form">
                        <div class="form-content">
                             <div class="form-header">
                                <h1>Etape</h1>
                            </div>
                            <p>Description</p>
                            <div class="form-fields">
                                <?php if ($etape === 'verification'): ?>
                                    <div class="form-group">
                                        <label for="ip">Identifiant Permanent</label>
                                        <input type="text" id="ip" name="ip" placeholder="Votre identifiant permanent" required>
                                    </div>
                                <?php elseif ($etape === 'envoi_email'): ?>
                                    <div class="form-group">
                                        <label for="ip_display">Identifiant Permanent</label>
                                        <input type="text" id="ip_display" value="<?= htmlspecialchars($ip) ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Adresse Email</label>
                                        <input type="email" id="email" name="email" placeholder="Votre email" required>
                                    </div>
                                <?php elseif ($etape === 'verification_code'): ?>
                                     <div class="form-group">
                                        <label for="code">Code de vérification</label>
                                        <input type="text" id="code" name="code" placeholder="Code à 6 chiffres" required>
                                    </div>
                                <?php elseif ($etape === 'enregistrement'): ?>
                                     <div class="form-group">
                                        <label for="password">Nouveau mot de passe</label>
                                        <input type="password" id="password" name="password" required>
                                    </div>
                                     <div class="form-group">
                                        <label for="password_confirm">Confirmez le mot de passe</label>
                                        <input type="password" id="password_confirm" name="password_confirm" required>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="etape" value="<?= $etape ?>">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="next-button"><?= $txtButton ?></button>
                        </div>
                    </form>
                </div>

                <!-- Vue Connexion -->
                <div id="vue-connexion" class="form-view" style="display:none;">
                    <form action="/connexion" method="post" class="ajax-form">
                         <div class="form-content">
                            <div class="form-header"><h1>Content de vous revoir !</h1></div>
                            <p>Connectez-vous pour accéder à votre espace.</p>
                            <div class="form-fields">
                                <div class="form-group">
                                    <label for="ip_login">Identifiant Permanent</label>
                                    <input type="text" id="ip_login" name="ip" placeholder="Votre identifiant permanent" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_login">Mot de passe</label>
                                    <input type="password" id="password_login" name="password" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                             <button type="submit" class="next-button">Se connecter</button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
