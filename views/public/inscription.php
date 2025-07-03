<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Authentification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Assurez-vous que les chemins sont corrects -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="stylesheet" href="/assets/css/inscription.css">
    <link rel="icon" href="data:,">
</head>
<body id="content-area">

<div class="inscription-container">

    <div class="main-content-wrapper">
        <main class="form-content">
            <?php
            // Logique pour déterminer l'étape actuelle et les textes correspondants
            $etape = $etape ?? 'verification'; // Assurez-vous que $etape est définie
            $txtButton = 'Continuer';
            $formTitle = '';
            $formDescription = '';

            switch ($etape) {
                case 'envoi_email':
                    $formTitle = "Envoi de l'email de vérification";
                    $formDescription = "Veuillez saisir votre adresse email pour recevoir un code de vérification.";
                    break;
                case 'verification_code':
                    $formTitle = "Vérifiez votre boite mail";
                    $formDescription = "Un code a été envoyé à votre adresse. Veuillez le saisir ci-dessous.";
                    break;
                case 'enregistrement':
                    $formTitle = "Créez votre mot de passe";
                    $formDescription = "Choisissez un mot de passe sécurisé pour finaliser la création de votre compte.";
                    $txtButton = "Terminer l'inscription";
                    break;
                case 'verification':
                default:
                    $formTitle = "Vérification du statut étudiant";
                    $formDescription = "Entrez votre identifiant permanent pour commencer le processus.";
                    break;
            }
            ?>

            <!-- Conteneur pour les animations GSAP -->
            <div class="form-container-anim">
                <div class="form-header">
                    <a href="/inscription" class="back-link">&larr; Recommencer</a>
                    <span class="form-etape-counter">
                        <?php
                        $etapes = [
                            'verification' => "Vérification",
                            'envoi_email' => "Email",
                            'verification_code' => "Code",
                            'enregistrement' => "Mot de passe"
                        ];
                        $current_etape_key = $etape ?? 'verification';
                        $etape_keys = array_keys($etapes);
                        $current_index = array_search($current_etape_key, $etape_keys);
                        echo "Étape " . ($current_index + 1) . "/" . count($etapes);

                        // Construction du contenu du tooltip
                        $tooltip_content = "<strong>" . htmlspecialchars($etapes[$current_etape_key]) . "</strong><br>";
                        $tooltip_content .= "Étapes du processus:<br>";
                        foreach ($etapes as $key => $name) {
                            $marker = ($key === $current_etape_key) ? "→ " : "";
                            $tooltip_content .= $marker . htmlspecialchars($name) . "<br>";
                        }
                        ?>
                        <span class="tooltip"><?= $tooltip_content ?></span>
                    </span>
                </div>

                <div class="form-title-group">
                    <h2><?php echo htmlspecialchars($formTitle); ?></h2>
                    <p><?php echo htmlspecialchars($formDescription); ?></p>
                </div>

                <form action="/inscription" method="post" class="ajax-form">
                    <?php if ($etape === 'verification'): ?>
                        <div class="form-group">
                            <input type="text" name="ip" class="form-input" placeholder=" " id="ip" required>
                            <label class="form-label" for="ip">Identifiant Permanent</label>
                        </div>
                        <input type="hidden" name="etape" value="verification">

                    <?php elseif ($etape === 'envoi_email'): ?>
                        <div class="form-group">
                            <input type="text" name="ip" class="form-input" placeholder=" " id="ip" value="<?= htmlspecialchars($ip ?? '') ?>" readonly>
                            <label class="form-label" for="ip">Identifiant Permanent</label>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-input" placeholder=" " id="email" required>
                            <label class="form-label" for="email">Votre email</label>
                        </div>
                        <input type="hidden" name="etape" value="envoi_email">

                    <?php elseif ($etape === 'verification_code'): ?>
                        <div class="form-group">
                            <input type="text" name="ip" class="form-input" placeholder=" " id="ip" value="<?= htmlspecialchars($ip ?? '') ?>" readonly>
                            <label class="form-label" for="ip">Identifiant Permanent</label>
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" class="form-input" placeholder=" " id="email" value="<?= htmlspecialchars($email ?? '') ?>" readonly>
                            <label class="form-label" for="email">Votre email</label>
                        </div>
                        <div class="form-group">
                            <input type="text" name="code" class="form-input" placeholder=" " id="code" required>
                            <label class="form-label" for="code">Code de vérification</label>
                        </div>
                        <input type="hidden" name="etape" value="verification_code">

                    <?php elseif ($etape === 'enregistrement'): ?>
                        <div class="form-group">
                            <input type="password" name="password" class="form-input" placeholder=" " id="password" required>
                            <label class="form-label" for="password">Nouveau mot de passe</label>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirm" class="form-input" placeholder=" " id="password_confirm" required>
                            <label class="form-label" for="password_confirm">Confirmez le mot de passe</label>
                        </div>
                        <input type="hidden" name="etape" value="enregistrement">
                        <input type="hidden" name="ip" value="<?= htmlspecialchars($ip ?? '') ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary"><?= htmlspecialchars($txtButton) ?></button>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script src="/assets/js/inscription.js" defer></script>
<script src="/assets/js/ajax.js" defer></script>

</body>
</html>