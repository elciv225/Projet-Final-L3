<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        @import url("https://use.typekit.net/gys0gor.css");

        /* --- Variables de couleurs --- */
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
            --success: rgb(102 187 106 / 55%);
            --warning: rgb(255 193 7 / 55%);
            --error: rgb(239 83 80 / 55%);
            --info: rgb(100 181 246 / 55%);
            --border-light: #e1e1e1;
            --border-medium: #c7c7c7;
            --shadow: rgb(0 0 0 / 5%) 0px 1px 2px 0px;
            --shadow-sm: 0 1px 3px rgb(0 0 0 / 10%);
            --shadow-md: 0 4px 6px rgb(0 0 0 / 10%);
            --shadow-lg: 0 10px 15px rgb(0 0 0 / 10%);
            --input-border: #1A5E63;
            --link-color: #2A8F96;
            --link-hover: #1A5E63;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --primary-color: #1A5E63;
                --secondary-color: #FFC857;
                --background-primary: #121212;
                --background-secondary: #1E1E1E;
                --background-input: #2D2D2D;
                --text-primary: #EAEAEA;
                --text-secondary: #CFCFCF;
                --text-disabled: #7F8C8D;
                --button-primary: #1A5E63;
                --button-primary-hover: #15484B;
                --button-secondary: #FFC857;
                --button-secondary-hover: #F3BA44;
                --success: rgb(39 174 96 / 40%);
                --warning: rgb(243 156 18 / 40%);
                --error: rgb(231 76 60 / 40%);
                --info: rgb(52 152 219 / 40%);
                --border-light: #2C3E50;
                --border-medium: #34495E;
                --shadow: rgb(0 0 0 / 10%) 0px 1px 2px 0px;
                --shadow-sm: 0 1px 3px rgb(0 0 0 / 30%);
                --shadow-md: 0 4px 6px rgb(0 0 0 / 30%);
                --shadow-lg: 0 10px 15px rgb(0 0 0 / 30%);
                --link-color: #1A5E63;
                --link-hover: #15484B;
            }
        }

        /* --- Styles généraux --- */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: mulish-variable, sans-serif;
            font-variation-settings: "wght" 400;
        }

        body {
            background-color: var(--background-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 580px;
            margin: 20px auto;
            background: var(--background-primary);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            border-top: 5px solid var(--primary-color);
        }

        .email-header {
            padding: 2.5rem 2.5rem 1rem;
        }

        .email-header h1 {
            font-size: 2rem;
            font-variation-settings: "wght" 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .email-header p {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .email-body {
            padding: 1.5rem 2.5rem;
        }

        .message {
            font-size: 1.05rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .action-section {
            padding: 2.5rem 1.5rem;
            background-color: var(--background-secondary);
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--border-light);
            margin-bottom: 2rem;
        }

        .action-button {
            display: inline-block;
            background-color: var(--button-primary);
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-variation-settings: "wght" 600;
            margin: 1rem 0;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: var(--shadow-md);
        }

        .action-button:hover {
            background-color: var(--button-primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .expiration-info {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-top: 1.5rem;
        }

        .expiration-time {
            color: var(--text-primary);
            font-variation-settings: "wght" 600;
        }

        .timer-info {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: var(--background-input);
            border-radius: 20px;
            border: 1px solid var(--border-light);
        }

        .timer-info .icon {
            font-size: 1.1rem;
            line-height: 1;
        }

        .timer-info strong {
            font-variation-settings: "wght" 600;
            color: var(--text-primary);
        }

        .security-notice {
            margin-top: 1.5rem;
            padding: 1rem 0;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .security-notice .icon {
            font-size: 1.2rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
            color: var(--secondary-color);
        }

        .security-notice p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .manual-link {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-align: center;
        }

        .manual-link a {
            color: var(--link-color);
            text-decoration: none;
            word-break: break-all;
        }

        .manual-link a:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        .email-footer {
            border-top: 1px solid var(--border-light);
            padding: 1.5rem 2.5rem;
            text-align: center;
        }

        .footer-text {
            color: var(--text-disabled);
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--link-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-variation-settings: "wght" 500;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-container {
                margin: 10px auto;
                border-radius: 12px;
            }
            .email-header {
                padding: 2rem 1.5rem 0.5rem;
            }
            .email-body {
                padding: 1.5rem;
            }
            .email-footer {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <h1>Réinitialisation de mot de passe</h1>
        <p>Récupérez l'accès à votre compte</p>
    </div>

    <!-- Body -->
    <div class="email-body">
        <div class="message">
            Bonjour,<br>
            Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte. Pour définir un nouveau mot de passe, veuillez cliquer sur le bouton ci-dessous.
        </div>

        <!-- Section d'action -->
        <div class="action-section">
            <a href="<?= htmlspecialchars($lienReinitialisation) ?>" class="action-button">Réinitialiser mon mot de passe</a>

            <div class="expiration-info">
                Valide jusqu'à <span class="expiration-time"><?= htmlspecialchars($expirationTime) ?></span>
            </div>

            <div class="timer-info">
                <span class="icon">⏱️</span> Ce lien expire dans <strong>60 minutes</strong>.
            </div>
        </div>

        <!-- Lien manuel -->
        <div class="manual-link">
            Si le bouton ne fonctionne pas, vous pouvez copier et coller le lien suivant dans votre navigateur :<br>
            <a href="<?= htmlspecialchars($lienReinitialisation) ?>"><?= htmlspecialchars($lienReinitialisation) ?></a>
        </div>

        <!-- Security notice -->
        <div class="security-notice">
            <span class="icon">🔒</span>
            <div>
                <p>
                    <strong>Avis de sécurité :</strong> Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email ou contacter notre support immédiatement. Quelqu'un pourrait essayer d'accéder à votre compte.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        <div class="footer-text">
            © <?= date('Y') ?> Administration - Tous droits réservés
        </div>
        <div class="footer-links">
            <a href="#" class="footer-link">Aide</a>
            <a href="#" class="footer-link">Confidentialité</a>
            <a href="#" class="footer-link">Contact</a>
        </div>
    </div>
</div>
</body>
</html>
