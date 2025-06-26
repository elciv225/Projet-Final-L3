<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditeur de Rapport de Stage Dynamique</title>
    <!-- Fonts importés -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;700&family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS de base fourni et amélioré */
        :root {
            color-scheme: light dark;
            --primary-color: #1A5E63;
            --secondary-color: #FFC857;
            --background-primary: #F9FAFA;
            --background-secondary: #F0F2F5;
            --background-input: #FFFFFF;
            --text-primary: #050E10;
            --text-secondary: #4A5568;
            --text-disabled: #BDC3C7;
            --border-light: #E0E6E8;
            --border-medium: #CBD5E0;
            --input-border: #1A5E63;
            --input-focus: rgb(26 94 99 / 20%);
            --shadow-sm: 0 1px 2px rgb(0 0 0 / 5%);
            --shadow-md: 0 4px 6px rgb(0 0 0 / 10%);
            --danger-color: #E53E3E;
            --danger-hover: #C53030;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background-primary: #1A202C;
                --background-secondary: #12161F;
                --background-input: #2D3748;
                --text-primary: #EAEAEA;
                --text-secondary: #A0AEC0;
                --border-light: #2D3748;
                --border-medium: #4A5568;
                --shadow-md: 0 4px 6px rgb(0 0 0 / 30%);
            }
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Mulish', sans-serif;
            font-weight: 400;
        }

        body {
            display: flex;
            min-height: 100vh;
            max-width: 100vw;
            background: var(--background-secondary);
            color: var(--text-primary);
            transition: background-color 0.5s ease;
            font-smooth: antialiased;
            -webkit-font-smoothing: antialiased;
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
            min-width: 450px;
            background-color: var(--background-primary);
            border-right: 1px solid var(--border-light);
        }

        .preview-panel {
            width: 55%;
        }

        header { margin-bottom: 2rem; }
        header h1 { font-family: 'Lexend Deca', sans-serif; font-weight: 600; font-size: 1.75rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); }
        .form-group input, .form-group textarea {
            width: 100%; padding: 0.75rem; border: 1px solid var(--border-medium); border-radius: 8px;
            background-color: var(--background-input); color: var(--text-primary); font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus {
            outline: none; border-color: var(--input-border); box-shadow: 0 0 0 3px var(--input-focus);
        }

        details {
            border: 1px solid var(--border-light); border-radius: 8px; margin-bottom: 1rem;
            overflow: hidden; background: var(--background-input);
        }
        summary {
            font-family: 'Lexend Deca', sans-serif; font-weight: 600; padding: 1rem;
            background-color: var(--background-primary); cursor: pointer; outline: none;
            transition: background-color 0.2s; display: flex; align-items: center; justify-content: space-between;
        }
        summary::after { content: '▼'; font-size: 0.8em; transition: transform 0.2s; }
        details[open] summary::after { transform: rotate(180deg); }
        details[open] summary { background-color: var(--primary-color); color: white; }
        .details-content { padding: 1.5rem; border-top: 1px solid var(--border-light); }

        /* Table des matières */
        #toc-container { list-style: none; padding-left: 0; max-height: 250px; overflow-y: auto; }
        #toc-container li { padding: 0.4rem 0.5rem; margin-bottom: 0.25rem; border-radius: 6px; cursor: pointer; transition: background-color 0.2s; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        #toc-container li:hover { background-color: var(--background-secondary); }
        #toc-container .toc-h2 { padding-left: 1.5rem; }
        #toc-container .toc-h3 { padding-left: 3rem; font-size: 0.85rem; }

        /* Contrôles de blocs */
        .block-controls { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; }
        .block-controls button {
            padding: 0.75rem; border-radius: 8px; border: 1px solid var(--primary-color);
            background-color: transparent; color: var(--primary-color); font-weight: 600;
            cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .block-controls button:hover { background-color: var(--primary-color); color: white; transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .block-controls button svg { width: 16px; height: 16px; }

        /* Blocs éditables */
        #editor-blocks-container { min-height: 50px; }
        .editor-block {
            position: relative; display: flex; align-items: flex-start; gap: 0.5rem;
            background: var(--background-input); border: 1px solid var(--border-light); border-radius: 8px;
            padding: 0.75rem; margin-bottom: 1rem; box-shadow: var(--shadow-sm); transition: box-shadow 0.2s;
        }
        .editor-block:hover { box-shadow: 0 0 0 2px var(--input-focus); }
        .editor-block.dragging { opacity: 0.5; background: var(--input-focus); }
        .drag-handle { padding: 0.5rem 0.25rem; cursor: grab; color: var(--text-disabled); }
        .drag-handle:active { cursor: grabbing; }
        .block-content { flex-grow: 1; }
        .block-content label { font-size: 0.8rem; font-weight: 700; color: var(--primary-color); margin-bottom: 0.5rem; display: block; }
        .block-content input, .block-content textarea {
            width: 100%; background: transparent; border: none; padding: 0.25rem 0; font-size: 1rem;
            color: var(--text-primary); border-bottom: 2px solid transparent;
        }
        .block-content input:focus, .block-content textarea:focus { outline: none; border-bottom-color: var(--input-border); }
        .block-content textarea { min-height: 80px; resize: vertical; }

        .delete-btn {
            position: absolute; top: 0.5rem; right: 0.5rem; width: 28px; height: 28px;
            border: none; background: transparent; color: var(--text-secondary);
            cursor: pointer; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .delete-btn svg { width: 16px; height: 16px; }
        .delete-btn:hover { background-color: var(--danger-color); color: white; transform: scale(1.1); }
        .drop-indicator { height: 2px; background-color: var(--primary-color); margin: 0.5rem 0; border-radius: 1px; }

        /* Aperçu */
        .preview-content { background-color: white; color: #333; box-shadow: var(--shadow-md); max-width: 800px; margin: 0 auto; border-radius: 4px; }
        .preview-page { padding: 4rem 3rem; min-height: 1123px; }
        .preview-body-content { border-top: 1px dashed #ccc; }
        #preview-cover-page { display: flex; flex-direction: column; justify-content: space-between; text-align: center; }
        .cover-header { display: flex; justify-content: space-between; align-items: flex-start; text-align: left; width: 100%; }
        #preview-logo { font-size: 1.5rem; font-weight: bold; color: var(--primary-color); }
        .cover-main { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
        #preview-report-title { font-family: 'Lexend Deca', sans-serif; font-size: 3rem; font-weight: 700; color: #333; margin: 1rem 0; line-height: 1.2; }
        .cover-footer { text-align: center; font-size: 0.9rem; }
        .cover-footer p { margin-bottom: 0.5rem; }
        #preview-body h1, #preview-body h2, #preview-body h3 { font-family: 'Lexend Deca', sans-serif; color: var(--primary-color); margin-bottom: 1.5rem; margin-top: 2.5rem; }
        #preview-body h1 { font-size: 1.8rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; }
        #preview-body h2 { font-size: 1.5rem; }
        #preview-body h3 { font-size: 1.2rem; font-weight: 600; color: #444; }
        #preview-body p { font-size: 1rem; line-height: 1.7; margin-bottom: 1rem; white-space: pre-wrap; word-wrap: break-word; }
        #preview-body > *:first-child { margin-top: 0; }

        @media (max-width: 1200px) {
            .container { flex-direction: column; height: auto; }
            .form-panel, .preview-panel { width: 100%; height: auto; min-height: 100vh; min-width: unset; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-panel">
        <header><h1>Éditeur de Rapport</h1></header>

        <details open><summary>🧾 Page de garde</summary>
            <div class="details-content">
                <div class="form-group"><label for="student-name">Nom de l’étudiant</label><input type="text" id="student-name" data-preview="preview-student-name" placeholder="ex: Jean Dupont"></div>
                <div class="form-group"><label for="report-title">Titre du rapport</label><input type="text" id="report-title" data-preview="preview-report-title" placeholder="ex: Rapport de stage"></div>
                <div class="form-group"><label for="company-name">Nom de l’entreprise</label><input type="text" id="company-name" data-preview="preview-company-name" placeholder="ex: Société AgriFourni"></div>
                <div class="form-group"><label for="school-logo">Logo / Nom Établissement</label><input type="text" id="school-logo" data-preview="preview-logo" placeholder="ex: Université Exemplaire"></div>
                <div class="form-group"><label for="school-tutor">Tuteur (établissement)</label><input type="text" id="school-tutor" data-preview="preview-school-tutor" placeholder="ex: M. Martin"></div>
                <div class="form-group"><label for="company-tutor">Tuteur (entreprise)</label><input type="text" id="company-tutor" data-preview="preview-company-tutor" placeholder="ex: Mme. Garcia"></div>
                <div class="form-group"><label for="submission-date">Date de dépôt</label><input type="text" id="submission-date" data-preview="preview-submission-date" placeholder="ex: Le 15 Juin 2025"></div>
                <div class="form-group"><label for="formation-year">Formation / Année</label><input type="text" id="formation-year" data-preview="preview-formation-year" placeholder="ex: M2 Informatique"></div>
            </div>
        </details>

        <details open><summary>🗺️ Table des matières</summary>
            <div class="details-content">
                <ul id="toc-container">
                    <p style="color: var(--text-secondary); font-style: italic;">Ajoutez des titres pour voir la table des matières se construire ici.</p>
                </ul>
            </div>
        </details>

        <details open><summary>✍️ Contenu du Rapport</summary>
            <div class="details-content">
                <div class="block-controls">
                    <button data-type="h1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16M4 20h16M14 4v8m-4-8v8"/></svg>Grand Titre</button>
                    <button data-type="h2"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16m-8 16V4m4 0v8"/></svg>Titre Moyen</button>
                    <button data-type="h3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16m-8 16V4m4 0v4"/></svg>Petit Titre</button>
                    <button data-type="p"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>Paragraphe</button>
                </div>
                <div id="editor-blocks-container"></div>
            </div>
        </details>
    </div>

    <!-- Panneau de l'Aperçu -->
    <div class="preview-panel">
        <div class="preview-content">
            <div class="preview-page" id="preview-cover-page">
                <header class="cover-header">
                    <div id="preview-logo">Université Exemplaire</div>
                    <div id="preview-submission-date">Le 15 Juin 2025</div>
                </header>
                <main class="cover-main"><h1 id="preview-report-title">Rapport de stage</h1><p id="preview-formation-year" style="margin-top:1rem; font-size: 1.2rem;">M2 Informatique</p></main>
                <footer class="cover-footer">
                    <p><strong><span id="preview-company-name">Société AgriFourni</span></strong></p>
                    <p>Rapport préparé par : <strong id="preview-student-name">Jean Dupont</strong></p>
                    <p>Tuteurs : <span id="preview-school-tutor">M. Martin</span> & <span id="preview-company-tutor">Mme. Garcia</span></p>
                </footer>
            </div>
            <div class="preview-page preview-body-content" id="preview-body"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- GESTION DE LA PAGE DE GARDE ---
        document.querySelectorAll('[data-preview]').forEach(input => {
            const previewElement = document.getElementById(input.dataset.preview);
            const update = () => {
                if (previewElement) previewElement.textContent = input.value.trim() || input.placeholder?.replace('ex: ', '') || '';
            };
            input.addEventListener('input', update);
            update();
        });

        // --- SYSTÈME DE BLOCS ÉDITABLES ---
        const blockControls = document.querySelector('.block-controls');
        const editorContainer = document.getElementById('editor-blocks-container');
        const previewContainer = document.getElementById('preview-body');
        const tocContainer = document.getElementById('toc-container');
        let draggedBlock = null;

        /**
         * Crée un nouveau bloc de contenu dans l'éditeur.
         * @param {string} type - Le type de bloc ('h1', 'h2', 'h3', 'p').
         * @param {string} content - Le contenu initial.
         */
        function createBlock(type, content = '') {
            const blockId = `block-${Date.now()}`;

            const editorBlock = document.createElement('div');
            editorBlock.className = 'editor-block';
            editorBlock.id = blockId;
            editorBlock.dataset.type = type;

            const blockLabel = { h1: 'Grand Titre', h2: 'Titre Moyen', h3: 'Petit Titre', p: 'Paragraphe' }[type];

            let inputElement;
            if (type === 'p') {
                inputElement = document.createElement('textarea');
                inputElement.placeholder = 'Rédigez votre paragraphe ici...';
            } else {
                inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.placeholder = `Saisissez votre ${blockLabel.toLowerCase()}...`;
            }
            inputElement.value = content;

            editorBlock.innerHTML = `
            <span class="drag-handle" title="Glisser pour déplacer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
            </span>
            <div class="block-content">
                <label>${blockLabel}</label>
            </div>
            <button class="delete-btn" title="Supprimer le bloc">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
            </button>
        `;

            editorBlock.querySelector('.block-content').appendChild(inputElement);
            editorContainer.appendChild(editorBlock);

            const previewElement = createPreviewElement(blockId, type, content, inputElement.placeholder);

            inputElement.addEventListener('input', () => {
                previewElement.textContent = inputElement.value || inputElement.placeholder;
                previewElement.style.opacity = inputElement.value ? '1' : '0.5';
                if (type.startsWith('h')) updateTableOfContents();
            });

            editorBlock.querySelector('.delete-btn').addEventListener('click', () => {
                editorBlock.remove();
                previewElement.remove();
                updateTableOfContents();
            });

            editorBlock.addEventListener('dragstart', e => {
                if (e.target.classList.contains('drag-handle')) {
                    draggedBlock = editorBlock;
                    setTimeout(() => editorBlock.classList.add('dragging'), 0);
                    e.dataTransfer.effectAllowed = 'move';
                } else {
                    e.preventDefault();
                }
            });
            editorBlock.addEventListener('dragend', () => {
                if(draggedBlock) {
                    draggedBlock.classList.remove('dragging');
                    draggedBlock = null;
                    document.querySelector('.drop-indicator')?.remove();
                    updateTableOfContents();
                }
            });

            updateTableOfContents();
        }

        /** Crée l'élément de prévisualisation correspondant */
        function createPreviewElement(id, type, content, placeholder) {
            const el = document.createElement(type);
            el.id = `preview-${id}`;
            el.textContent = content || placeholder;
            if (!content) el.style.opacity = '0.5';
            previewContainer.appendChild(el);
            return el;
        }

        /** Met à jour la table des matières */
        function updateTableOfContents() {
            tocContainer.innerHTML = '';
            const headings = editorContainer.querySelectorAll('[data-type^="h"]');

            if (headings.length === 0) {
                tocContainer.innerHTML = `<p style="color: var(--text-secondary); font-style: italic;">Ajoutez des titres pour voir la table des matières se construire ici.</p>`;
                return;
            }

            headings.forEach(headingBlock => {
                const type = headingBlock.dataset.type;
                const text = headingBlock.querySelector('input').value || 'Titre vide';
                const li = document.createElement('li');
                li.textContent = text;
                li.className = `toc-${type}`;
                li.title = text;
                li.addEventListener('click', () => {
                    headingBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.getElementById(`preview-${headingBlock.id}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
                tocContainer.appendChild(li);
            });
        }

        blockControls.addEventListener('click', e => {
            const button = e.target.closest('button');
            if (button && button.dataset.type) createBlock(button.dataset.type);
        });

        // --- LOGIQUE DU GLISSER-DÉPOSER ---
        editorContainer.addEventListener('dragover', e => {
            e.preventDefault();
            if (!draggedBlock) return;

            document.querySelector('.drop-indicator')?.remove();
            const afterElement = getDragAfterElement(editorContainer, e.clientY);
            const indicator = document.createElement('div');
            indicator.className = 'drop-indicator';

            if (afterElement == null) {
                editorContainer.appendChild(indicator);
                editorContainer.appendChild(draggedBlock);
            } else {
                editorContainer.insertBefore(indicator, afterElement);
                editorContainer.insertBefore(draggedBlock, afterElement);
            }
            syncPreviewOrder();
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.editor-block:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        function syncPreviewOrder() {
            [...editorContainer.querySelectorAll('.editor-block')].forEach(block => {
                const previewElement = document.getElementById(`preview-${block.id}`);
                if (previewElement) previewContainer.appendChild(previewElement);
            });
        }
    });
</script>

</body>
</html>
