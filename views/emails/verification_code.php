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
            --border-light: #87999A;
            --border-medium: #6B7B7C;
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
                --background-primary: #1B1B1B;
                --background-secondary: #202020;
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

        /* --- AJOUT POUR L'INTERACTIVITÉ --- */
        @keyframes pulse-glow {
            0% {
                border-color: var(--primary-color);
                box-shadow: 0 0 3px rgba(26, 94, 99, 0.1);
            }
            50% {
                border-color: var(--secondary-color);
                box-shadow: 0 0 15px rgba(255, 200, 87, 0.4);
            }
            100% {
                border-color: var(--primary-color);
                box-shadow: 0 0 3px rgba(26, 94, 99, 0.1);
            }
        }

        /* --- FIN DE L'AJOUT --- */

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: mulish-variable, sans-serif;
            font-variation-settings: "wght" 400;
        }

        body {
            background: var(--background-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--background-primary);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .email-header {
            background: var(--primary-color);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .email-header h1 {
            font-size: 2.5rem;
            font-variation-settings: "wght" 700;
            margin-bottom: 0.5rem;
        }

        .email-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-variation-settings: "wght" 400;
        }

        .email-body {
            padding: 3rem 2rem;
            text-align: center;
        }

        .greeting {
            font-size: 1.5rem;
            font-variation-settings: "wght" 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }

        .message {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .verification-section {
            margin: 3rem 0;
            padding: 3rem 2rem;
            background: var(--background-secondary);
            border-radius: 12px;
            border: 2px solid var(--primary-color);
            /* --- MODIFICATION POUR L'INTERACTIVITÉ --- */
            animation: pulse-glow 3.5s infinite ease-in-out;
        }

        .verification-label {
            color: var(--text-secondary);
            font-variation-settings: "wght" 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .verification-code {
            font-size: 4rem;
            font-variation-settings: "wght" 800;
            color: var(--primary-color);
            letter-spacing: 0.5rem;
            margin: 1rem 0;
            line-height: 1;
        }

        .verification-code .digit {
            color: var(--secondary-color);
        }

        .expiration-info {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-top: 1.5rem;
        }

        .expiration-time {
            color: var(--primary-color);
            font-variation-settings: "wght" 600;
        }

        .security-notice {
            background: var(--warning);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid var(--secondary-color);
        }

        .security-notice h3 {
            color: var(--text-primary);
            font-variation-settings: "wght" 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .security-notice p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .email-footer {
            background: var(--background-input);
            padding: 2rem;
            text-align: center;
        }

        .footer-text {
            color: var(--text-disabled);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .footer-links {
            margin-top: 1rem;
        }

        .footer-link {
            color: var(--link-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-variation-settings: "wght" 500;
            margin: 0 1rem;
            /* --- MODIFICATION POUR L'INTERACTIVITÉ --- */
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--link-hover);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px 0;
            }

            .email-container {
                margin: 0 10px;
                border-radius: 8px;
            }

            .email-header {
                padding: 2rem 1.5rem;
            }

            .email-header h1 {
                font-size: 2rem;
            }

            .email-body,
            .email-footer {
                padding: 2rem 1.5rem;
            }

            .verification-code {
                font-size: 3rem;
                letter-spacing: 0.3rem;
            }

            .greeting {
                font-size: 1.3rem;
            }

            .message {
                font-size: 1rem;
                margin-bottom: 2rem;
            }

            .verification-section {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .email-header h1 {
                font-size: 1.8rem;
            }

            .verification-code {
                font-size: 2.5rem;
                letter-spacing: 0.2rem;
            }

            .greeting {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <h1>Code de vérification</h1>
        <p>Sécurisez votre compte</p>
    </div>

    <!-- Body -->
    <div class="email-body">
        <div class="greeting">
            Bonjour !
        </div>

        <div class="message">
            Voici votre code de vérification pour finaliser la création de votre compte.
            Ce code est valide pendant 10 minutes.
        </div>

        <!-- Code de vérification -->
        <div class="verification-section">
            <div class="verification-label">
                Votre code de vérification
            </div>
            <div class="verification-code">
                <?php $verificationCode = "123456"; // Exemple de code ?>
                <?= htmlspecialchars(substr($verificationCode, 0, 3)) ?><span
                        class="digit"><?= htmlspecialchars(substr($verificationCode, 3, 3)) ?></span>
            </div>
            <div class="expiration-info">
                <?php $expirationTime = date("H:i", strtotime('+10 minutes')); // Exemple d'heure ?>
                Expire à <span class="expiration-time"><?= htmlspecialchars($expirationTime) ?></span>
            </div>
        </div>

        <!-- Security notice -->
        <div class="security-notice">
            <h3>🔒 Sécurité</h3>
            <p>
                Ne partagez jamais ce code. Si vous n'êtes pas à l'origine de cette demande,
                ignorez cet email.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        <div class="footer-text">
            Cet email a été envoyé automatiquement
        </div>
        <div class="footer-text">
            © <?= date('Y') ?> Projet XXX - Tous droits réservés
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
