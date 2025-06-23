<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification</title>
    <style>
        @import url("https://use.typekit.net/gys0gor.css");

        /* --- VOS VARIABLES ROOT - INCHANGÉES --- */
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

        /* --- STYLES GÉNÉRAUX --- */
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

        .verification-section {
            padding: 2.5rem 1.5rem;
            background-color: var(--background-secondary);
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--border-light);
        }

        .verification-label {
            color: var(--text-secondary);
            font-variation-settings: "wght" 500;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            display: block;
        }

        .verification-code {
            font-size: 3.5rem;
            font-variation-settings: "wght" 800;
            color: var(--primary-color);
            letter-spacing: 0.75rem;
            margin: 1rem 0;
            line-height: 1;
            padding-left: 0.75rem; /* Compenser le letter-spacing */
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

        /* --- NOUVEAUX STYLES POUR LE MINUTEUR --- */
        .timer-info {
            display: inline-flex; /* Pour que le conteneur s'adapte au contenu */
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: var(--background-input);
            border-radius: 20px; /* Bords arrondis pour un look moderne */
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
        /* --- FIN DES NOUVEAUX STYLES --- */

        .security-notice {
            margin-top: 2.5rem;
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
            .verification-code {
                font-size: 2.5rem;
                letter-spacing: 0.5rem;
                padding-left: 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <h1>Votre code de sécurité</h1>
        <p>Utilisez ce code pour finaliser votre action.</p>
    </div>

    <!-- Body -->
    <div class="email-body">
        <div class="message">
            Bonjour, <br>
            Pour garantir la sécurité de votre compte, veuillez utiliser le code ci-dessous.
        </div>

        <!-- Code de vérification -->
        <div class="verification-section">
            <span class="verification-label">
                Code de Vérification
            </span>
            <div class="verification-code">
                <?= htmlspecialchars($verificationCode ?? "123-456") ?>
            </div>
            <div class="expiration-info">
                Valide jusqu'à <span class="expiration-time"><?= htmlspecialchars($expirationTime ?? "14:58") ?></span>
            </div>
            <!-- MINUTEUR AJOUTÉ ICI -->
            <div class="timer-info">
                <span class="icon">⏱️</span> Ce code expire dans <strong>10 minutes</strong>.
            </div>
        </div>

        <!-- Security notice -->
        <div class="security-notice">
            <span class="icon">🔒</span>
            <div>
                <p>
                    <strong>Ne partagez jamais ce code.</strong> Notre équipe ne vous le demandera jamais.
                    Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        <div class="footer-text">
            © <?= date('Y') ?> Projet XXX - Tous droits réservés
        </div>
        <div class="footer-links">
            <a href="#" class="footer-link">Aide</a>
            <a href="#" class="footer-link">Confidentialité</a>
            <a href="#" class="footer-link">Se désinscrire</a>
        </div>
    </div>
</div>
</body>
</html>
