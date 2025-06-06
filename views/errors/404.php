<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Non Trouvée</title>
    <style>
        @import url("https://use.typekit.net/gys0gor.css");

        :root {
            /* Configuration du thème */
            color-scheme: light dark;

            /* Couleurs de base */
            --primary-color: #1A5E63;
            --secondary-color: #FFC857;

            /* Couleurs des composants - Mode clair */
            --background-primary: #F9FAFA;
            --background-secondary: #F7F9FA;
            --background-input: #ECF0F1;

            /* Text */
            --text-primary: #050E10;
            --text-secondary: #0A1B20;
            --text-disabled: #BDC3C7;

            /* Boutons */
            --button-primary: #1A5E63;
            --button-primary-hover: #15484B;
            --button-secondary: #FFC857;
            --button-secondary-hover: #FCCF6C;
            --button-disabled: #E0E6E8;

            /* États */
            --success: rgb(102 187 106 / 55%);
            --warning: rgb(255 193 7 / 55%);
            --error: rgb(239 83 80 / 55%);
            --info: rgb(100 181 246 / 55%);

            /* Bordures */
            --border-light: #87999A;
            --border-medium: #6B7B7C;
            --border-dark: #162122;

            /* Degradés */
            --gradient-hover: linear-gradient(to bottom,
            rgb(240 240 240 /   80%), rgb(220 220 220 / 90%)
            );

            /* Overlays et shadows */
            --overlay: rgb(44 62 80 / 10%);
            --shadow: rgb(0 0 0 / 5%) 0px 1px 2px 0px;
            --shadow-sm: 0 1px 3px rgb(0 0 0 / 10%);
            --shadow-md: 0 4px 6px rgb(0 0 0 / 10%);
            --shadow-lg: 0 10px 15px rgb(0 0 0 / 10%);

            /* Inputs */
            --input-border: #1A5E63;
            --input-focus: rgb(26 94 99 / 20%);

            /* Liens */
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
                --button-disabled: #4F5B5C;
                --success: rgb(39 174 96 / 40%);
                --warning: rgb(243 156 18 / 40%);
                --error: rgb(231 76 60 / 40%);
                --info: rgb(52 152 219 / 40%);
                --border-light: #2C3E50;
                --border-medium: #34495E;
                --border-dark: #1A252F;
                --gradient-hover: linear-gradient(to bottom,
                rgb(30 30 30 / 80%), rgb(15 15 15 / 90%)
                );
                --overlay: rgb(0 0 0 / 50%);
                --shadow: rgb(0 0 0 / 10%) 0px 1px 2px 0px;
                --shadow-sm: 0 1px 3px rgb(0 0 0 / 30%);
                --shadow-md: 0 4px 6px rgb(0 0 0 / 30%);
                --shadow-lg: 0 10px 15px rgb(0 0 0 / 30%);
                --input-border: #1A5E63;
                --input-focus: rgb(26 94 99 / 20%);
                --link-color: #1A5E63;
                --link-hover: #15484B;
            }
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: mulish-variable, sans-serif;
            font-variation-settings: "wght" 400;
        }

        body {
            min-height: 100vh;
            background: var(--background-secondary);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container-404 {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }

        .error-code {
            font-size: 8rem;
            font-variation-settings: "wght" 800;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1;
        }

        .error-code .zero {
            color: var(--secondary-color);
        }

        .error-title {
            font-size: 2.5rem;
            font-variation-settings: "wght" 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }

        .error-description {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-actions {
            margin-top: 2rem;
        }

        .button {
            display: inline-block;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 8px;
            font-variation-settings: "wght" 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .button-primary {
            background-color: var(--button-primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .button-primary:hover {
            background-color: var(--button-primary-hover);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-404 {
                padding: 1.5rem;
            }

            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 2rem;
            }

            .error-description {
                font-size: 1.1rem;
                margin-bottom: 2rem;
            }

            .button {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 4.5rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-description {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<main class="container-404">
    <h1 class="error-code">
        4<span class="zero">0</span>4
    </h1>
    <h2 class="error-title">Oups ! Page non trouvée</h2>
    <p class="error-description">
        La page que vous cherchez semble s'être égarée dans le cyberespace.
        Vérifiez l'URL ou retournez à l'accueil.
    </p>
    <div class="error-actions">
        <a href="/" class="button button-primary">
            Retourner à l'accueil
        </a>
    </div>
</main>

</body>
</html>