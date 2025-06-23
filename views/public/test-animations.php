<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test des Loaders AJAX</title>
    <!-- On inclut GSAP qui est une dépendance pour les animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <style>
        .test-container {
            max-width: 1200px;
            margin: auto;
        }

        header {
            text-align: center;
            border-bottom: 1px solid var(--border-light);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        h1 {
            color: var(--primary-color);
        }

        .test-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .controls, .targets {
            background-color: var(--background-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
        }

        h2 {
            margin-top: 0;
            border-bottom: 1px solid var(--border-medium);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .control-group {
            margin-bottom: 1.5rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px 16px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: var(--button-primary);
            color: white;
        }

        .btn:hover {
            background-color: var(--button-primary-hover);
        }

        .target-box {
            border: 2px dashed var(--border-medium);
            border-radius: 8px;
            padding: 1.5rem;
            min-height: 150px;
            margin-bottom: 1.5rem;
            transition: background-color 0.3s ease;
        }

        .target-box:last-child {
            margin-bottom: 0;
        }

        .response-content {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 6px;
        }

        /* On importe ici le CSS du fichier ajax.css */
        @import url('https://raw.githubusercontent.com/d-cardo/projet-sekou/f668f4e04f98126b83f3bf7828003a27e025d2c2/public/assets/css/ajax.css');

    </style>
    <!-- On copie directement le contenu du fichier ajax.css pour que le test soit autonome -->
    <style id="ajax-styles">
        /* Styles pour les popups et notifications */
        .popup {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 12px;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 14px;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border: 1px solid rgb(255 255 255 / 10%);
            min-width: 280px;
            max-width: 400px;
            font-family: mulish-variable, sans-serif;
            opacity: 0;
            transform: translateX(20px) scale(0.95);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .popup.success {
            background: var(--success);
            border-left: 4px solid var(--primary-color);
            color: var(--text-primary);
        }

        .popup.error {
            background: var(--error);
            border-left: 4px solid #dc2626;
            color: var(--text-primary);
        }

        .popup.info {
            background: var(--info);
            border-left: 4px solid var(--primary-color);
            color: var(--text-primary);
        }

        .popup.warning {
            background: var(--warning);
            border-left: 4px solid var(--secondary-color);
            color: var(--text-primary);
        }

        .popup-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        #popup-message {
            flex: 1;
            line-height: 1.4;
            letter-spacing: 0.025em;
            color: var(--text-primary);
            font-weight: 600;
        }

        .close-popup {
            background: rgb(255 255 255 / 15%);
            border: 1px solid rgb(255 255 255 / 20%);
            border-radius: 6px;
            color: var(--text-primary);
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .close-popup:hover {
            background: rgb(255 255 255 / 25%);
            border-color: rgb(255 255 255 / 30%);
            transform: scale(1.1);
        }

        .close-popup:active {
            transform: scale(0.95);
        }

        /* Styles pour la carte d'avertissement */
        .warning-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--overlay);
            backdrop-filter: blur(4px);
            z-index: 1002;
            justify-content: center;
            align-items: center;
            animation: fade-in 0.3s ease;
        }

        .warning-card {
            background: var(--background-primary);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            min-width: 400px;
            max-width: 500px;
            max-height: 90vh;
            overflow: hidden;
            animation: scale-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: mulish-variable, sans-serif;
        }

        .warning-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px 16px;
            background: var(--warning);
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-light);
        }

        .warning-icon {
            font-size: 24px;
            color: var(--secondary-color);
            background: rgb(255 255 255 / 20%);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .warning-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .warning-content {
            padding: 24px;
        }

        .warning-content p {
            margin: 0;
            line-height: 1.6;
            color: var(--text-secondary);
            font-size: 15px;
        }

        .warning-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 24px 24px;
            background: var(--background-secondary);
            border-top: 1px solid var(--border-light);
        }

        .warning-actions button {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            font-family: mulish-variable, sans-serif;
        }

        .warning-actions .btn-secondary {
            background: var(--background-input);
            color: var(--text-secondary);
            border: 1px solid var(--border-medium);
        }

        .warning-actions .btn-secondary:hover {
            background: var(--border-light);
            transform: translateY(-1px);
        }

        .warning-actions .btn-primary {
            background: var(--button-primary);
            color: white;
        }

        .warning-actions .btn-primary:hover {
            background: var(--button-primary-hover);
            transform: translateY(-1px);
        }

        /* Styles pour les loaders */
        .loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--overlay, rgba(20, 20, 20, 0.5));
            backdrop-filter: blur(4px);
            z-index: 1001;
            justify-content: center;
            align-items: center;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 4px solid rgb(255 255 255 / 20%);
            border-top: 4px solid var(--primary-color, #29d);
            border-radius: 50%;
            animation: loader-spin 1s linear infinite;
            box-shadow: 0 4px 20px rgb(26 94 99 / 30%);
        }

        .container-loader-overlay {
            position: absolute;
            inset: 0;
            background-color: var(--overlay, rgba(20, 20, 20, 0.5));
            backdrop-filter: blur(2px);
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: inherit;
            animation: fade-in 0.3s ease;
        }

        .container-loader-spinner {
            width: 32px;
            height: 32px;
            border: 3px solid var(--border-medium, #444);
            border-top-color: var(--primary-color, #29d);
            border-radius: 50%;
            animation: loader-spin 0.8s linear infinite;
        }

        #ajax-progress-bar {
            width: 0;
            height: 100%;
            background-color: var(--primary-color, #29d);
            box-shadow: 0 0 10px var(--primary-color, #29d), 0 0 5px var(--primary-color, #29d);
            transition: width 0.3s ease, opacity 0.3s ease;
        }

        @keyframes loader-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .ajax-form button[type="submit"].loading {
            color: transparent;
            pointer-events: none;
        }

        .ajax-form button[type="submit"].loading::after {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-top: -9px;
            margin-left: -9px;
            border: 2px solid rgb(255 255 255 / 30%);
            border-radius: 50%;
            border-top-color: white;
            animation: loader-spin 0.8s infinite linear;
        }

        /* Animation pour l'entrée du contenu */
        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes content-enter {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-entering {
            animation: content-enter 0.5s ease-out;
        }
    </style>
</head>
<body>

<div class="test-container">
    <header>
        <h1>Page de Test des Loaders AJAX</h1>
        <p>Cliquez sur les boutons pour déclencher les différents types de loaders.</p>
    </header>

    <div class="test-grid">
        <div class="controls">
            <h2>Panneau de Contrôle</h2>

            <!-- Scénario 1: Loader dans le conteneur 1 -->
            <div class="control-group">
                <p>Ce bouton affichera un loader à l'intérieur du <strong>Conteneur 1</strong>.</p>
                <form action="/mock/update1" method="post" class="ajax-form" data-target="#target1">
                    <button type="submit" class="btn btn-primary">Lancer Loader sur Conteneur 1</button>
                </form>
            </div>

            <!-- Scénario 2: Loader dans le conteneur 2 -->
            <div class="control-group">
                <p>Ce bouton affichera un loader à l'intérieur du <strong>Conteneur 2</strong>.</p>
                <form action="/mock/update2" method="post" class="ajax-form" data-target="#target2">
                    <button type="submit" class="btn">Lancer Loader sur Conteneur 2</button>
                </form>
            </div>

            <!-- Scénario 3: Loader dans le conteneur 3 -->
            <div class="control-group">
                <p>Ce bouton affichera un loader à l'intérieur du <strong>Conteneur 3</strong>.</p>
                <form action="/mock/update3" method="post" class="ajax-form" data-target="#target3">
                    <button type="submit" class="btn">Lancer Loader sur Conteneur 3</button>
                </form>
            </div>

            <!-- Scénario 4: Loader global -->
            <div class="control-group">
                <p>Ce bouton (avec `data-target = "global"`) affichera le <strong>loader global</strong> en plein écran.
                </p>
                <form action="/mock/global" method="post" class="ajax-form" data-target="global">
                    <button type="submit" class="btn">Lancer Loader Global</button>
                </form>
            </div>

        </div>

        <div class="targets">
            <h2>Conteneurs Cibles</h2>
            <div id="target1" class="target-box">
                <p>Contenu initial du conteneur 1.</p>
            </div>
            <div id="target2" class="target-box">
                <p>Contenu initial du conteneur 2.</p>
            </div>
            <div id="target3" class="target-box">
                <p>Contenu initial du conteneur 3.</p>
            </div>
        </div>
    </div>
</div>

<!-- Le script ajax.js que nous avons développé -->
<script id="ajax-script-to-test" src="/assets/js/ajax.js"></script>

<!-- Script de simulation pour les tests -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // On surcharge la fonction fetch pour simuler une réponse serveur
        const originalFetch = window.fetch;
        window.fetch = function (url, options) {
            console.log(`Appel fetch intercepté pour : ${url}`);

            // On détermine quelle cible est mise à jour pour le message
            const urlPath = new URL(url, window.location.origin).pathname;
            let targetName = "global";
            if (urlPath.includes('update1')) targetName = "Conteneur 1";
            if (urlPath.includes('update2')) targetName = "Conteneur 2";
            if (urlPath.includes('update3')) targetName = "Conteneur 3";


            // La réponse HTML simulée
            const mockHtmlResponse = `
                    <div class="response-content">
                        <h3>Contenu pour ${targetName} mis à jour !</h3>
                        <p>Chargé à : ${new Date().toLocaleTimeString()}</p>
                        <p>ID Aléatoire : ${Math.floor(Math.random() * 1000)}</p>
                    </div>
                `;

            // On simule une réponse JSON pour le loader global pour varier
            if (targetName === "global") {
                return new Promise(resolve => {
                    setTimeout(() => {
                        console.log("Simulation de la réponse JSON...");
                        const jsonResponse = {
                            statut: 'success',
                            message: 'Opération globale réussie !'
                        };
                        resolve(new Response(JSON.stringify(jsonResponse), {
                            status: 200,
                            headers: {'Content-Type': 'application/json'}
                        }));
                    }, 2000);
                });
            }

            // Pour les autres, on simule une réponse HTML
            return new Promise(resolve => {
                // On simule une latence réseau
                setTimeout(() => {
                    console.log("Simulation de la réponse HTML...");
                    const response = new Response(mockHtmlResponse, {
                        status: 200,
                        headers: {'Content-Type': 'text/html'}
                    });
                    resolve(response);
                }, 1500); // 1.5 secondes de délai
            });
        };

        // Remplacer le src du script de test par le contenu réel du canvas
        const ajaxScriptContent = document.getElementById('ajax_js_final_complete').textContent;
        const scriptTag = document.getElementById('ajax-script-to-test');
        const newScript = document.createElement('script');
        newScript.textContent = ajaxScriptContent;
        scriptTag.parentNode.replaceChild(newScript, scriptTag);
    });
</script>
</body>
</html>
