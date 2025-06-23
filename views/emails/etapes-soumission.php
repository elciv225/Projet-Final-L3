<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de votre rapport</title>
    <style>
        @import url("https://use.typekit.net/gys0gor.css");

        /* --- VOS VARIABLES ROOT - INCHANGÉES --- */
        :root {
            color-scheme: light dark;
            --primary-color: #1A5E63;
            --secondary-color: #FFC857;
            --background-primary: #FFFFFF; /* Un blanc plus pur pour un look plus net */
            --background-secondary: #F7F9FA;
            --text-primary: #050E10;
            --text-secondary: #5a6a6c;
            --text-disabled: #BDC3C7;
            --button-primary: #1A5E63;
            --button-primary-hover: #15484B;
            --success-color: #27ae60;
            --border-light: #eaeded;
            --border-medium: #d5dbdb;
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.07);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background-primary: #18191a;
                --background-secondary: #101010;
                --text-primary: #EAEAEA;
                --text-secondary: #b0bdc0;
                --border-light: #2c3a3b;
                --border-medium: #3a4b4c;
                --success-color: #2ecc71;
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
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .email-container {
            max-width: 620px;
            margin: 20px auto;
            background: var(--background-primary);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
        }

        .email-header {
            padding: 2.5rem;
            text-align: center;
            border-bottom: 1px solid var(--border-light);
        }

        .email-header h1 {
            font-size: 1.8rem;
            font-variation-settings: "wght" 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .email-header p {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .email-body {
            padding: 2.5rem;
        }

        /* --- SYSTÈME DE SUIVI DES ÉTAPES --- */
        .timeline {
            list-style: none;
            padding: 0;
            margin-bottom: 2.5rem;
        }
        .timeline-step {
            display: flex;
            position: relative;
            align-items: flex-start;
        }
        .timeline-step:not(:last-child) .timeline-marker::after {
            content: '';
            position: absolute;
            top: 36px;
            left: 15px;
            width: 2px;
            height: calc(100% - 20px);
            background-color: var(--border-light);
        }
        .timeline-marker {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--background-secondary);
            border: 2px solid var(--border-medium);
            display: flex;
            align-items: center;
            justify-content: center;
            font-variation-settings: "wght" 600;
            color: var(--text-disabled);
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        .timeline-content {
            padding-left: 1.5rem;
            padding-bottom: 2.5rem;
            margin-top: 4px;
        }
        .timeline-title {
            font-size: 1.1rem;
            font-variation-settings: "wght" 700;
            color: var(--text-disabled);
            transition: color 0.3s ease;
        }
        .timeline-description {
            font-size: 0.95rem;
            color: var(--text-disabled);
            transition: color 0.3s ease;
        }

        /* --- Logique des états --- */

        /* État "Terminé" */
        .step-done .timeline-marker {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        .step-done .timeline-title,
        .step-done .timeline-description {
            color: var(--text-secondary);
            opacity: 0.7;
        }
        .step-done:not(:last-child) .timeline-marker::after {
            background-color: var(--success-color);
        }

        /* État "En cours" */
        .step-current .timeline-marker {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: scale(1.1);
        }
        .step-current .timeline-title {
            color: var(--primary-color);
            font-variation-settings: "wght" 700;
        }
        .step-current .timeline-description {
            color: var(--text-secondary);
        }

        .main-info {
            background-color: var(--background-secondary);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .main-info h3 {
            font-size: 1.2rem;
            color: var(--text-primary);
            font-variation-settings: "wght" 600;
            margin-bottom: 0.5rem;
        }
        .main-info p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .cta-button {
            display: inline-block;
            background-color: var(--button-primary);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
            font-variation-settings: "wght" 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .cta-button:hover {
            background-color: var(--button-primary-hover);
            transform: translateY(-2px);
        }

        .email-footer {
            border-top: 1px solid var(--border-light);
            padding: 2.5rem;
            text-align: center;
        }

        .footer-text {
            color: var(--text-disabled);
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body { padding: 10px; }
            .email-container { margin: 10px auto; border-radius: 12px; }
            .email-header, .email-body, .email-footer { padding: 1.5rem; }
            .timeline-content { padding-bottom: 2rem; }
        }
    </style>
</head>
<body>

<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <h1>Suivi de votre Rapport de Stage</h1>
        <p>Bonjour [Nom de l'étudiant], voici l'état d'avancement de votre rapport.</p>
    </div>

    <!-- Body -->
    <div class="email-body">

        <ul class="timeline">
            <!-- Étape 1 -->
            <li class="timeline-step step-done">
                <div class="timeline-marker">1</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Rapport Reçu</h2>
                    <p class="timeline-description">Votre document est en attente de validation par le secrétariat.</p>
                </div>
            </li>
            <!-- Étape 2 -->
            <li class="timeline-step step-current">
                <div class="timeline-marker">2</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Traitement par la Commission</h2>
                    <p class="timeline-description">Votre rapport est en cours d'attribution.</p>
                </div>
            </li>
            <!-- Étape 3 -->
            <li class="timeline-step">
                <div class="timeline-marker">3</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Attribution Finalisée</h2>
                    <p class="timeline-description">Votre encadreur et directeur de mémoire ont été assignés.</p>
                </div>
            </li>
        </ul>

        <!-- Contenu principal qui change selon l'étape -->
        <div class="main-info">
            <h3>Traitement en cours</h3>
            <p>La commission pédagogique a reçu votre rapport et procède actuellement à son étude pour l'attribution d'un encadreur. Aucune action n'est requise de votre part pour le moment.</p>
        </div>

    </div>

    <!-- Footer -->
    <div class="email-footer">
        <p class="footer-text">
            Si vous avez des questions, n'hésitez pas à contacter le secrétariat.
            <br>
            © <?= date('Y') ?> Projet XXX - Tous droits réservés.
        </p>
    </div>
</div>
</body>
</html>
