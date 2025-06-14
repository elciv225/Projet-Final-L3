<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditeur de Rapport de Stage</title>
    <style>
        /* CSS de base fourni */
        @import url("https://use.typekit.net/gys0gor.css");
        @import url("https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;700&display=swap");

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
            --border-light: #E0E6E8;
            --input-border: #1A5E63;
            --input-focus: rgb(26 94 99 / 20%);
            --shadow-md: 0 4px 6px rgb(0 0 0 / 10%);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background-primary: #1B1B1B;
                --background-secondary: #202020;
                --background-input: #2D2D2D;
                --text-primary: #EAEAEA;
                --text-secondary: #CFCFCF;
                --border-light: #2C3E50;
                --shadow-md: 0 4px 6px rgb(0 0 0 / 30%);
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
            display: flex;
            min-height: 100vh;
            max-width: 100vw;
            background: var(--background-secondary);
            color: var(--text-primary);
            transition: background-color 0.5s ease;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .form-panel, .preview-panel {
            padding: 2rem;
            height: 100vh;
            overflow-y: auto;
            flex-grow: 1;
        }

        .form-panel {
            width: 45%;
            background-color: var(--background-primary);
            border-right: 1px solid var(--border-light);
        }

        .preview-panel {
            width: 55%;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-variation-settings: "wght" 600;
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            background-color: var(--background-input);
            color: var(--text-primary);
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--input-border);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        details {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        summary {
            font-family: 'Lexend Deca', sans-serif;
            font-weight: 600;
            padding: 1rem;
            background-color: var(--background-secondary);
            cursor: pointer;
            outline: none;
            transition: background-color 0.2s;
        }

        details[open] summary {
            background-color: var(--primary-color);
            color: white;
        }

        .details-content {
            padding: 1.5rem;
        }

        /* Styles pour la zone d'aperçu */
        .preview-content {
            background-color: white;
            color: #333;
            box-shadow: var(--shadow-md);
            max-width: 800px;
            margin: 0 auto;
        }

        .preview-page {
            padding: 4rem 3rem;
            min-height: 1123px; /* Simule une page A4 */
            border-bottom: 1px dashed #ccc;
        }

        /* --- Style Page de Garde --- */
        #preview-cover-page {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }

        .cover-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            text-align: left;
            width: 100%;
        }

        #preview-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .cover-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #preview-report-title {
            font-family: 'Lexend Deca', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            color: #333;
            margin: 1rem 0;
        }

        .cover-footer {
            text-align: center;
            font-size: 0.9rem;
        }

        .cover-footer p {
            margin-bottom: 0.5rem;
        }

        /* --- Style Sections Rapport --- */
        .report-section h2 {
            font-family: 'Lexend Deca', sans-serif;
            font-size: 1.8rem;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .report-section-content {
            font-size: 1rem;
            line-height: 1.7;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        @media (max-width: 1200px) {
            .container { flex-direction: column; height: auto; }
            .form-panel, .preview-panel { width: 100%; height: auto; min-height: 100vh; }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Panneau du Formulaire -->
    <div class="form-panel">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-family: 'Lexend Deca', sans-serif; font-weight: 600;">Éditeur de Rapport</h1>
        </header>

        <form id="report-form">
            <details open>
                <summary>🧾 Page de garde</summary>
                <div class="details-content">
                    <div class="form-group"><label for="student-name">Nom de l’étudiant</label><input type="text" id="student-name" data-preview="preview-student-name" placeholder="ex: Jean Dupont"></div>
                    <div class="form-group"><label for="report-title">Titre du rapport</label><input type="text" id="report-title" data-preview="preview-report-title" placeholder="ex: Rapport de stage de fin d’études"></div>
                    <div class="form-group"><label for="company-name">Nom de l’entreprise</label><input type="text" id="company-name" data-preview="preview-company-name" placeholder="ex: Société AgriFourni"></div>
                    <div class="form-group"><label for="school-logo">Logo / Nom Établissement</label><input type="text" id="school-logo" data-preview="preview-logo" placeholder="ex: Université Exemplaire"></div>
                    <div class="form-group"><label for="school-tutor">Tuteur (établissement)</label><input type="text" id="school-tutor" data-preview="preview-school-tutor" placeholder="ex: M. Martin"></div>
                    <div class="form-group"><label for="company-tutor">Tuteur (entreprise)</label><input type="text" id="company-tutor" data-preview="preview-company-tutor" placeholder="ex: Mme. Garcia"></div>
                    <div class="form-group"><label for="submission-date">Date de dépôt</label><input type="text" id="submission-date" data-preview="preview-submission-date" placeholder="ex: Le 15 Juin 2025"></div>
                    <div class="form-group"><label for="formation-year">Formation / Année</label><input type="text" id="formation-year" data-preview="preview-formation-year" placeholder="ex: M2 Informatique"></div>
                </div>
            </details>

            <details><summary>📃 Remerciements</summary><div class="details-content"><textarea id="thanks-content" data-preview="preview-thanks"></textarea></div></details>
            <details><summary>📑 Résumé / Abstract</summary><div class="details-content"><label>Résumé (Français)</label><textarea id="summary-fr-content" data-preview="preview-summary-fr"></textarea><br><br><label>Abstract (Anglais)</label><textarea id="summary-en-content" data-preview="preview-summary-en"></textarea></div></details>
            <details><summary>🧭 Introduction</summary><div class="details-content"><textarea id="intro-content" data-preview="preview-intro"></textarea></div></details>
            <details><summary>🏢 Présentation de l’entreprise</summary><div class="details-content"><textarea id="company-pres-content" data-preview="preview-company-pres"></textarea></div></details>
            <details><summary>🎯 Missions du stage</summary><div class="details-content"><textarea id="missions-content" data-preview="preview-missions"></textarea></div></details>
            <details><summary>🔧 Travail réalisé</summary><div class="details-content"><textarea id="work-content" data-preview="preview-work"></textarea></div></details>
            <details><summary>🔍 Bilan personnel</summary><div class="details-content"><textarea id="bilan-content" data-preview="preview-bilan"></textarea></div></details>
            <details><summary>🧭 Conclusion</summary><div class="details-content"><textarea id="conclusion-content" data-preview="preview-conclusion"></textarea></div></details>
            <details><summary>📚 Bibliographie</summary><div class="details-content"><textarea id="biblio-content" data-preview="preview-biblio"></textarea></div></details>
        </form>
    </div>

    <!-- Panneau de l'Aperçu -->
    <div class="preview-panel">
        <div class="preview-content">
            <!-- Page de Garde -->
            <div class="preview-page" id="preview-cover-page">
                <header class="cover-header">
                    <div id="preview-logo">Université Exemplaire</div>
                    <div id="preview-submission-date">Le 15 Juin 2025</div>
                </header>
                <main class="cover-main">
                    <h1 id="preview-report-title">Rapport de stage de fin d’études</h1>
                    <p id="preview-formation-year" style="margin-top:1rem; font-size: 1.2rem;">M2 Informatique</p>
                </main>
                <footer class="cover-footer">
                    <p><strong><span id="preview-company-name">Société AgriFourni</span></strong></p>
                    <p>Rapport préparé par : <strong id="preview-student-name">Jean Dupont</strong></p>
                    <p>Tuteurs : <span id="preview-school-tutor">M. Martin</span> (école) & <span id="preview-company-tutor">Mme. Garcia</span> (entreprise)</p>
                </footer>
            </div>
            <!-- Autres sections -->
            <div class="preview-page report-section" id="thanks-section"><h2 >Remerciements</h2><div class="report-section-content" id="preview-thanks"></div></div>
            <div class="preview-page report-section" id="summary-section"><h2>Résumé / Abstract</h2><div class="report-section-content" id="preview-summary-fr"></div><br><div class="report-section-content" id="preview-summary-en"></div></div>
            <div class="preview-page report-section" id="intro-section"><h2>Introduction</h2><div class="report-section-content" id="preview-intro"></div></div>
            <div class="preview-page report-section" id="company-pres-section"><h2>Présentation de l’entreprise</h2><div class="report-section-content" id="preview-company-pres"></div></div>
            <div class="preview-page report-section" id="missions-section"><h2>Missions du stage</h2><div class="report-section-content" id="preview-missions"></div></div>
            <div class="preview-page report-section" id="work-section"><h2>Travail réalisé</h2><div class="report-section-content" id="preview-work"></div></div>
            <div class="preview-page report-section" id="bilan-section"><h2>Bilan personnel</h2><div class="report-section-content" id="preview-bilan"></div></div>
            <div class="preview-page report-section" id="conclusion-section"><h2>Conclusion</h2><div class="report-section-content" id="preview-conclusion"></div></div>
            <div class="preview-page report-section" id="biblio-section"><h2>Bibliographie</h2><div class="report-section-content" id="preview-biblio"></div></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cible tous les inputs et textareas qui ont un attribut 'data-preview'
        const allInputs = document.querySelectorAll('[data-preview]');

        allInputs.forEach(input => {
            const previewElementId = input.getAttribute('data-preview');
            const previewElement = document.getElementById(previewElementId);
            const sectionContainer = previewElement.closest('.preview-page');

            // Fonction pour mettre à jour la visibilité de la section
            const updateSectionVisibility = () => {
                if (!sectionContainer) return;
                // Pour la page de garde, on ne la cache jamais
                if(sectionContainer.id === 'preview-cover-page') return;

                const content = previewElement.innerHTML.trim();
                sectionContainer.style.display = content ? 'block' : 'none';
            };

            // Fonction de mise à jour du contenu
            const updateContent = () => {
                if (previewElement) {
                    const value = input.value;
                    // Gère les placeholders pour la page de garde
                    if (input.placeholder && sectionContainer && sectionContainer.id === 'preview-cover-page') {
                        previewElement.textContent = value.trim() ? value : input.placeholder.replace('ex: ', '');
                    } else {
                        // Remplace les sauts de ligne par <br> pour les textareas
                        previewElement.innerHTML = value.replace(/\n/g, '<br>');
                    }
                }
                updateSectionVisibility();
            };

            // Écouteur d'événement
            input.addEventListener('input', updateContent);

            // Initialisation au chargement
            updateContent();
        });
    });
</script>

</body>
</html>
