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
<body id="content-area"> <!-- L'ID de body est utilisé par main.css -->

<div class="auth-container">
    <aside class="sidebar">
        <!-- BOUTON DE FERMETURE AJOUTÉ POUR LA VUE MOBILE -->
        <button type="button" class="close-btn" id="close-steps-trigger">&times;</button>

        <div class="sidebar-header">
            <h1 class="logo">Projet XXX</h1>
        </div>
        <nav class="etapes-nav">
            <ol>
                <li class="etape-item complete">
                    <div class="etape-marker">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="etape-details">
                        <span class="etape-number">etape 1</span>
                        <span class="etape-title">Vérification du statut de l'étudiant.</span>
                    </div>
                </li>
                <li class="etape-item in-progress">
                    <div class="etape-marker"></div>
                    <div class="etape-details">
                        <span class="etape-number">etape 2</span>
                        <span class="etape-title">Envoie de l'email.</span>
                    </div>
                </li>
                <li class="etape-item">
                    <div class="etape-marker"></div>
                    <div class="etape-details">
                        <span class="etape-number">etape 3</span>
                        <span class="etape-title">Vérification du code envoyé par email.</span>
                    </div>
                </li>
                <li class="etape-item">
                    <div class="etape-marker"></div>
                    <div class="etape-details">
                        <span class="etape-number">etape 4</span>
                        <span class="etape-title">Création du mot de passe.</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="sidebar-footer">
            <p>Avez-vous déjà un compte? <a href="#">Se Connecter</a></p>
        </div>
    </aside>

    <!-- ENCAPSULEUR POUR LE CONTENU PRINCIPAL -->
    <div class="main-content-wrapper">
        <main class="form-content">
            <div class="form-header">
                <a href="#" class="back-link">&larr; Retour</a>
                <!-- LE COMPTEUR EST MAINTENANT UN BOUTON POUR LA COMPATIBILITÉ MOBILE -->
                <button type="button" class="form-etape-counter" id="open-steps-trigger">Etapes 1/4</button>
            </div>

            <form action="/authentification" method="post" class="ajax-form">
                <?php
                $etape = $etape ?? 'verification';
                $txtButton = $txtButton ?? 'Continuer';
                ?>

                <?php if ($etape === 'verification'): ?>
                    <div class="form-group">
                        <input type="text" name="ip" class="form-input" placeholder=" " id="ip" required>
                        <label class="form-label" for="ip">Identifiant Permanent</label>
                    </div>
                    <input type="hidden" name="etape" value="verification">

                <?php elseif ($etape === 'envoi_email'): ?>
                    <div class="form-group">
                        <input type="text" name="ip" class="form-input" placeholder=" " id="ip" value="<?= htmlspecialchars($ip ?? '') ?>" disabled>
                        <label class="form-label" for="ip">Identifiant Permanent</label>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-input" placeholder=" " id="email" required>
                        <label class="form-label" for="email">Votre email</label>
                    </div>
                    <input type="hidden" name="etape" value="envoi_email">

                <?php elseif ($etape === 'verification_code'): ?>
                    <div class="form-group">
                        <input type="text" name="ip" class="form-input" placeholder=" " id="ip" value="<?= htmlspecialchars($ip ?? '') ?>" disabled>
                        <label class="form-label" for="ip">Identifiant Permanent</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-input" placeholder=" " id="email" value="<?= htmlspecialchars($email ?? '') ?>" disabled>
                        <label class="form-label" for="email">Votre email</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="code" class="form-input" placeholder=" " id="code" required>
                        <label class="form-label" for="code">Code de vérification</label>
                    </div>
                    <input type="hidden" name="etape" value="verification_code">

                <?php elseif ($etape === 'enregistrement'): ?>
                    <div class="form-group">
                        <input type="text" name="ip" class="form-input" placeholder=" " id="ip" value="<?= htmlspecialchars($ip ?? '') ?>" disabled>
                        <label class="form-label" for="ip">Identifiant Permanent</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-input" placeholder=" " id="email" value="<?= htmlspecialchars($email ?? '') ?>" disabled>
                        <label class="form-label" for="email">Votre email</label>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-input" placeholder=" " id="password" required>
                        <label class="form-label" for="password">Nouveau mot de passe</label>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirm" class="form-input" placeholder=" " id="password_confirm" required>
                        <label class="form-label" for="password_confirm">Confirmez le mot de passe</label>
                    </div>
                    <input type="hidden" name="etape" value="enregistrement">
                <?php endif; ?>

                <button type="submit" class="btn btn-primary"><?= htmlspecialchars($txtButton) ?></button>
            </form>
        </main>
    </div>
</div>

<!-- Fond pour la modale -->
<div class="modal-overlay" id="modal-overlay"></div>

<script>
    /**
     * CORRECTION : La logique de la modale est maintenant dans une fonction
     * pour pouvoir être appelée plusieurs fois.
     */
    function setupModalEventListeners() {
        const openTrigger = document.getElementById('open-steps-trigger');
        const closeTrigger = document.getElementById('close-steps-trigger');
        const modal = document.querySelector('.sidebar');
        const overlay = document.getElementById('modal-overlay');
        const blurTarget = document.querySelector('.main-content-wrapper');

        const openModal = () => {
            if (modal && overlay && blurTarget) {
                overlay.classList.add('is-open');
                modal.classList.add('is-open');
                blurTarget.classList.add('is-blurred');
            }
        };

        const closeModal = () => {
            if (modal && overlay && blurTarget) {
                overlay.classList.remove('is-open');
                modal.classList.remove('is-open');
                blurTarget.classList.remove('is-blurred');
            }
        };

        // On s'assure que les éléments existent avant d'attacher les écouteurs
        if (openTrigger) {
            // Utiliser .onclick est une façon simple de réassigner l'événement
            // sans se soucier de supprimer les anciens écouteurs.
            openTrigger.onclick = openModal;
        }
        if (closeTrigger) {
            closeTrigger.onclick = closeModal;
        }
        if (overlay) {
            overlay.onclick = closeModal;
        }
    }

    // Appel initial lors du premier chargement de la page
    document.addEventListener('DOMContentLoaded', setupModalEventListeners);

    /**
     * IMPORTANT :
     * Vous devez appeler `setupModalEventListeners()` à nouveau
     * depuis votre fichier `ajax.js`, juste après avoir mis à jour le DOM
     * avec le HTML reçu du serveur.
     *
     * Exemple de ce que vous devriez avoir dans votre `ajax.js`:
     *
     * fetch(url, options)
     * .then(response => response.text())
     * .then(html => {
     * // Ligne où vous mettez à jour le contenu
     * document.querySelector('.main-content-wrapper').innerHTML = html;
     *
     * // Appel CRUCIAL pour que la modale refonctionne
     * setupModalEventListeners();
     * });
     */
</script>

<!-- Vos scripts externes -->
<script src="/assets/js/authentification.js" defer></script>
<script src="/assets/js/ajax.js" defer></script>
<!-- Note: si le code ci-dessus est dans authentification.js, pas besoin de le dupliquer -->
<!-- <script src="/assets/js/authentification.js" defer></script> -->

</body>
</html>
