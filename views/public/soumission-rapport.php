<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditeur de Rapport de Stage Complet</title>
    <!-- JS Libraries for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" xintegrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoVBL5gI9kLmbG0C+wFjrAaBFfdIcw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;700&family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
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

        * { padding: 0; margin: 0; box-sizing: border-box; font-family: 'Mulish', sans-serif; }

        body {
            background: var(--background-secondary);
            color: var(--text-primary);
            font-smooth: antialiased;
            -webkit-font-smoothing: antialiased;
        }

        .main-app-container { display: flex; flex-direction: column; height: 100vh; max-width: 100vw; overflow: hidden; }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 2rem;
            background-color: var(--background-primary);
            border-bottom: 1px solid var(--border-light);
            flex-shrink: 0;
        }
        .app-header h1 { font-family: 'Lexend Deca', sans-serif; font-weight: 600; font-size: 1.5rem; }
        .app-header .actions button {
            margin-left: 1rem; padding: 0.6rem 1.2rem; border-radius: 8px;
            border: 1px solid var(--primary-color); background-color: var(--primary-color);
            color: white; font-weight: 600; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .app-header .actions button:hover { opacity: 0.85; box-shadow: var(--shadow-md); }
        .app-header .actions button.secondary { background-color: transparent; color: var(--primary-color); }
        .app-header .actions button svg { width: 18px; height: 18px; }
        #pdf-loader { display: none; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .main-content { display: flex; flex-grow: 1; overflow: hidden; }

        .form-panel, .preview-panel { padding: 2rem; height: 100%; overflow-y: auto; }
        .form-panel { width: 45%; min-width: 450px; background-color: var(--background-primary); border-right: 1px solid var(--border-light); }
        .preview-panel { width: 55%; }

        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); }
        .form-group input, .form-group textarea {
            width: 100%; padding: 0.75rem; border: 1px solid var(--border-medium); border-radius: 8px;
            background-color: var(--background-input); color: var(--text-primary); font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--input-border); box-shadow: 0 0 0 3px var(--input-focus); }

        details { border: 1px solid var(--border-light); border-radius: 8px; margin-bottom: 1rem; overflow: hidden; background: var(--background-input); }
        summary {
            font-family: 'Lexend Deca', sans-serif; font-weight: 600; padding: 1rem; background-color: var(--background-primary);
            cursor: pointer; outline: none; transition: background-color 0.2s; display: flex; align-items: center; justify-content: space-between;
        }
        summary::after { content: '▼'; font-size: 0.8em; transition: transform 0.2s; }
        details[open] summary { background-color: var(--primary-color); color: white; }
        details[open] summary::after { transform: rotate(180deg); }
        .details-content { padding: 1.5rem; border-top: 1px solid var(--border-light); }

        #toc-container { list-style: none; padding-left: 0; }
        #toc-container li { padding: 0.4rem 0.5rem; margin-bottom: 0.25rem; border-radius: 6px; cursor: pointer; transition: background-color 0.2s; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        #toc-container li:hover { background-color: var(--background-secondary); }
        #toc-container .toc-h2 { padding-left: 1.5rem; }
        #toc-container .toc-h3 { padding-left: 3rem; font-size: 0.85rem; }

        .block-controls { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; }
        .block-controls button {
            padding: 0.75rem; border-radius: 8px; border: 1px solid var(--primary-color); background-color: transparent;
            color: var(--primary-color); font-weight: 600; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .block-controls button:hover { background-color: var(--primary-color); color: white; transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .block-controls button svg { width: 16px; height: 16px; }

        /* -- NEW HIERARCHICAL STRUCTURE -- */
        .section-container {
            position: relative;
        }
        .section-content {
            position: relative;
            padding-left: 30px;
            margin-left: 15px;
            border-left: 2px solid var(--border-light);
        }
        .section-container:last-of-type > .section-content {
            border-left-color: transparent; /* No trailing line on last section */
        }
        .section-content .editor-block {
            position: relative;
            margin-left: 20px; /* Indent sub-blocks */
            margin-top: 1rem;
        }
        /* Connecting lines for sub-blocks */
        .section-content .editor-block::before {
            content: '';
            position: absolute;
            top: 18px; /* Align with middle of the block */
            left: -20px;
            width: 20px;
            height: 2px;
            background-color: var(--border-light);
        }

        .editor-block {
            position: relative; display: flex; align-items: flex-start; gap: 0.5rem;
            background: var(--background-input); border: 1px solid var(--border-medium); border-radius: 8px;
            padding: 0.75rem; transition: box-shadow 0.2s; margin-bottom: 1rem;
        }
        .editor-block:hover { box-shadow: 0 0 0 2px var(--input-focus); }
        .editor-block.dragging { opacity: 0.5; }

        .drag-handle { padding-top: 2px; cursor: grab; color: var(--text-disabled); }
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
            position: absolute; top: 0.5rem; right: 0.5rem; width: 28px; height: 28px; border: none;
            background: transparent; color: var(--text-secondary); cursor: pointer; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;
        }
        .delete-btn svg { width: 16px; height: 16px; }
        .delete-btn:hover { background-color: var(--danger-color); color: white; transform: scale(1.1); }
        .drop-indicator { height: 3px; background-color: var(--primary-color); border-radius: 1px; margin: 4px 0; }

        /* Preview Panel */
        .preview-content { background-color: white; color: #333; box-shadow: var(--shadow-md); max-width: 800px; margin: 0 auto; border-radius: 4px; }
        .preview-page { padding: 4rem 3rem; min-height: 1123px; position: relative; } /* A4 aspect ratio at 96dpi is roughly 794x1123px */
        #preview-cover-page { display: flex; flex-direction: column; justify-content: space-between; text-align: center; }
        .cover-header { display: flex; justify-content: space-between; align-items: flex-start; text-align: left; width: 100%; }
        #preview-logo-container { font-size: 1.5rem; font-weight: bold; color: var(--primary-color); }
        #preview-logo-container img { max-width: 180px; max-height: 60px; object-fit: contain; }
        .cover-main { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
        #preview-report-title { font-family: 'Lexend Deca', sans-serif; font-size: 3rem; font-weight: 700; color: #333; margin: 1rem 0; line-height: 1.2; }
        .cover-footer { text-align: center; font-size: 0.9rem; }
        .cover-footer p { margin-bottom: 0.5rem; }
        #preview-body { border-top: 1px dashed #ccc; }
        #preview-body h1, #preview-body h2, #preview-body h3 { font-family: 'Lexend Deca', sans-serif; color: var(--primary-color); margin-bottom: 1.5rem; margin-top: 2.5rem; }
        #preview-body h1 { font-size: 1.8rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; }
        #preview-body h2 { font-size: 1.5rem; }
        #preview-body h3 { font-size: 1.2rem; font-weight: 600; color: #444; }
        #preview-body p { font-size: 1rem; line-height: 1.7; margin-bottom: 1rem; white-space: pre-wrap; word-wrap: break-word; }
        #preview-body > *:first-child { margin-top: 0; }

        @media (max-width: 1200px) {
            .app-header { flex-direction: column; gap: 1rem; padding: 1rem; }
            .main-content { flex-direction: column; height: auto; }
            .form-panel, .preview-panel { width: 100%; height: auto; min-height: 100vh; min-width: unset; }
        }
    </style>
</head>
<body>

<div class="main-app-container">
    <header class="app-header">
        <h1>Éditeur de Rapport</h1>
        <div class="actions">
            <button id="download-html-btn" class="secondary"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>Télécharger HTML</button>
            <button id="download-pdf-btn"><div id="pdf-loader"></div><svg id="pdf-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg><span id="pdf-text">Télécharger PDF</span></button>
        </div>
    </header>
    <main class="main-content">
        <div class="form-panel">
            <details open><summary>🧾 Page de garde</summary>
                <div class="details-content">
                    <div class="form-group"><label for="student-name">Nom de l’étudiant</label><input type="text" id="student-name" data-preview="preview-student-name" placeholder="ex: Jean Dupont"></div>
                    <div class="form-group"><label for="report-title">Titre du rapport</label><input type="text" id="report-title" data-preview="preview-report-title" placeholder="ex: Rapport de stage"></div>
                    <div class="form-group"><label for="company-name">Nom de l’entreprise</label><input type="text" id="company-name" data-preview="preview-company-name" placeholder="ex: Société AgriFourni"></div>
                    <div class="form-group"><label for="school-tutor">Tuteur (établissement)</label><input type="text" id="school-tutor" data-preview="preview-school-tutor" placeholder="ex: M. Martin"></div>
                    <div class="form-group"><label for="company-tutor">Tuteur (entreprise)</label><input type="text" id="company-tutor" data-preview="preview-company-tutor" placeholder="ex: Mme. Garcia"></div>
                    <div class="form-group"><label for="submission-date">Date de dépôt</label><input type="text" id="submission-date" data-preview="preview-submission-date" placeholder="ex: Le 15 Juin 2025"></div>
                    <div class="form-group"><label for="formation-year">Formation / Année</label><input type="text" id="formation-year" data-preview="preview-formation-year" placeholder="ex: M2 Informatique"></div>
                </div>
            </details>
            <details open><summary>🗺️ Table des matières</summary>
                <div class="details-content"><ul id="toc-container"><p style="color: var(--text-secondary); font-style: italic;">Ajoutez des titres pour construire la table des matières.</p></ul></div>
            </details>
            <details open><summary>✍️ Contenu du Rapport</summary>
                <div class="details-content">
                    <div class="block-controls">
                        <button data-type="h1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16M4 20h16M14 4v8m-4-8v8"/></svg>Section</button>
                        <button data-type="h2"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16m-8 16V4m4 0v8"/></svg>Titre</svg></button>
                        <button data-type="h3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h16m-8 16V4m4 0v4"/></svg>Sous-titre</button>
                        <button data-type="p"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>Paragraphe</button>
                    </div>
                    <div id="editor-blocks-container"></div>
                </div>
            </details>
        </div>
        <div class="preview-panel">
            <div class="preview-content">
                <div class="preview-page" id="preview-cover-page">
                    <header class="cover-header">
                        <div id="preview-logo-container">
                            <img src="https://logo.clearbit.com/ens-lyon.fr" alt="Logo École">
                        </div>
                        <div id="preview-submission-date">Le 15 Juin 2025</div>
                    </header>
                    <main class="cover-main"><h1 id="preview-report-title">Rapport de stage</h1><p id="preview-formation-year" style="margin-top:1rem; font-size: 1.2rem;">M2 Informatique</p></main>
                    <footer class="cover-footer">
                        <p><strong><span id="preview-company-name">Société AgriFourni</span></strong></p>
                        <p>Rapport préparé par : <strong id="preview-student-name">Jean Dupont</strong></p>
                        <p>Tuteurs : <span id="preview-school-tutor">M. Martin</span> & <span id="preview-company-tutor">Mme. Garcia</span></p>
                    </footer>
                </div>
                <div class="preview-page" id="preview-body"></div>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- INITIALIZATION ---
        const { jsPDF } = window.jspdf;
        const blockControls = document.querySelector('.block-controls');
        const editorContainer = document.getElementById('editor-blocks-container');
        const previewContainer = document.getElementById('preview-body');
        const tocContainer = document.getElementById('toc-container');
        let draggedBlock = null;

        // --- COVER PAGE MANAGEMENT ---
        document.querySelectorAll('[data-preview]').forEach(input => {
            const previewElement = document.getElementById(input.dataset.preview);
            const update = () => {
                if (previewElement) previewElement.textContent = input.value.trim() || input.placeholder?.replace('ex: ', '') || '';
            };
            input.addEventListener('input', update);
            update();
        });

        // --- DYNAMIC BLOCK SYSTEM ---

        function createBlock(type, content = '', targetContainer) {
            if (type === 'h1') {
                createSection(content);
            } else {
                const container = targetContainer || document.querySelector('.section-content:last-of-type') || editorContainer;
                if (container.id === 'editor-blocks-container') {
                    // If there's no section, create one first for this block
                    createSection('');
                    const newContainer = document.querySelector('.section-content:last-of-type');
                    const editorBlock = buildEditorBlock(type, content);
                    newContainer.appendChild(editorBlock);
                } else {
                    const editorBlock = buildEditorBlock(type, content);
                    container.appendChild(editorBlock);
                }

                syncPreview();
                updateTableOfContents();
            }
        }

        function createSection(content = '') {
            const sectionId = `section-${Date.now()}`;
            const sectionContainer = document.createElement('div');
            sectionContainer.className = 'section-container';
            sectionContainer.id = sectionId;

            const h1Block = buildEditorBlock('h1', content);
            const contentDiv = document.createElement('div');
            contentDiv.className = 'section-content';

            sectionContainer.appendChild(h1Block);
            sectionContainer.appendChild(contentDiv);
            editorContainer.appendChild(sectionContainer);
            syncPreview();
            updateTableOfContents();
        }

        function buildEditorBlock(type, content = '') {
            const blockId = `block-${Date.now()}`;
            const editorBlock = document.createElement('div');
            editorBlock.className = 'editor-block';
            editorBlock.id = blockId;
            editorBlock.dataset.type = type;

            const blockLabel = { h1: 'Section', h2: 'Titre', h3: 'Sous-titre', p: 'Paragraphe' }[type];

            let inputElement;
            if (type === 'p') {
                inputElement = document.createElement('textarea');
                inputElement.placeholder = 'Rédigez ici...';
            } else {
                inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.placeholder = `Saisissez le ${blockLabel.toLowerCase()}...`;
            }
            inputElement.value = content;

            editorBlock.innerHTML = `
            <span class="drag-handle" draggable="true" title="Glisser pour déplacer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM6 5a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-1 3a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm1 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3-6a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm-1 3a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm1 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm1 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
            </span>
            <div class="block-content"><label>${blockLabel}</label></div>
            <button class="delete-btn" title="Supprimer">&times;</button>
        `;
            editorBlock.querySelector('.block-content').appendChild(inputElement);

            inputElement.addEventListener('input', () => { syncPreview(); updateTableOfContents(); });
            editorBlock.querySelector('.delete-btn').addEventListener('click', () => {
                const container = editorBlock.closest('.section-container');
                if(editorBlock.dataset.type === 'h1' || !container) {
                    (container || editorBlock).remove();
                } else {
                    editorBlock.remove();
                }
                syncPreview();
                updateTableOfContents();
            });
            editorBlock.addEventListener('dragstart', handleDragStart);
            editorBlock.addEventListener('dragend', handleDragEnd);

            return editorBlock;
        }

        function syncPreview() {
            previewContainer.innerHTML = '';
            const blocks = editorContainer.querySelectorAll('.editor-block');
            blocks.forEach(block => {
                const type = block.dataset.type;
                const content = block.querySelector('input, textarea').value;
                if(content || block.querySelector('input, textarea').placeholder){
                    const previewEl = document.createElement(type);
                    previewEl.textContent = content;
                    previewContainer.appendChild(previewEl);
                }
            });
        }

        function updateTableOfContents() {
            tocContainer.innerHTML = '';
            const headings = editorContainer.querySelectorAll('[data-type^="h"]');
            if (headings.length === 0) {
                tocContainer.innerHTML = `<p style="color: var(--text-secondary); font-style: italic;">Ajoutez des sections et titres...</p>`;
                return;
            }
            headings.forEach(block => {
                const type = block.dataset.type;
                const text = block.querySelector('input').value || 'Titre vide';
                const li = document.createElement('li');
                li.textContent = text;
                li.className = `toc-${type}`;
                li.title = text;
                li.onclick = () => block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                tocContainer.appendChild(li);
            });
        }

        blockControls.addEventListener('click', e => {
            const button = e.target.closest('button');
            if (button && button.dataset.type) createBlock(button.dataset.type);
        });

        // --- DRAG & DROP LOGIC ---
        function handleDragStart(e) {
            if (e.target.classList.contains('drag-handle')){
                draggedBlock = e.target.closest('.editor-block, .section-container');
                if (draggedBlock) {
                    e.dataTransfer.effectAllowed = 'move';
                    setTimeout(() => draggedBlock.classList.add('dragging'), 0);
                }
            } else {
                e.preventDefault();
            }
        }
        function handleDragEnd() {
            if (draggedBlock) {
                draggedBlock.classList.remove('dragging');
                draggedBlock = null;
                document.querySelectorAll('.drop-indicator').forEach(el => el.remove());
                syncPreview();
                updateTableOfContents();
            }
        }
        editorContainer.addEventListener('dragover', e => {
            e.preventDefault();
            if (!draggedBlock) return;

            document.querySelectorAll('.drop-indicator').forEach(el => el.remove());
            const indicator = document.createElement('div');
            indicator.className = 'drop-indicator';

            const afterElement = getDragAfterElement(e.target.closest('.section-content, #editor-blocks-container'), e.clientY);
            const dropZone = e.target.closest('.section-content, #editor-blocks-container');

            if (dropZone) {
                if (afterElement) {
                    dropZone.insertBefore(indicator, afterElement);
                } else {
                    dropZone.appendChild(indicator);
                }
            }
        });
        editorContainer.addEventListener('drop', e => {
            e.preventDefault();
            if (!draggedBlock) return;

            const dropIndicator = document.querySelector('.drop-indicator');
            if (dropIndicator) {
                dropIndicator.parentNode.insertBefore(draggedBlock, dropIndicator);
                dropIndicator.remove();
            }
        });

        function getDragAfterElement(container, y) {
            if (!container) return null;
            const draggableElements = [...container.querySelectorAll('.editor-block:not(.dragging), .section-container:not(.dragging)')];
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

        // --- EXPORT FUNCTIONS ---
        document.getElementById('download-html-btn').addEventListener('click', () => {
            const title = document.getElementById('report-title').value || 'rapport';
            const css = document.querySelector('style').innerHTML;
            const content = document.querySelector('.preview-content').outerHTML;

            const html = `
            <!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>${title}</title>
            <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400;700&family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">
            <style>${css.replace(/@media \(max-width: 1200px\)/g, '@media (max-width: 9999px)')}</style>
            <style>.preview-panel{width:100%!important;padding:0!important;} .preview-content{max-width:800px; margin: 0 auto;} .preview-page{border-bottom:1px solid #ccc; box-shadow:none; margin:0 auto; border-radius:0;}</style>
            </head><body><div class="preview-panel">${content}</div></body></html>`;

            const blob = new Blob([html], { type: 'text/html' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `${title.replace(/\s+/g, '_').toLowerCase()}.html`;
            a.click();
        });

        document.getElementById('download-pdf-btn').addEventListener('click', async () => {
            const btn = document.getElementById('download-pdf-btn');
            btn.disabled = true;
            document.getElementById('pdf-icon').style.display = 'none';
            document.getElementById('pdf-text').textContent = 'Génération...';
            document.getElementById('pdf-loader').style.display = 'block';

            const pdf = new jsPDF('p', 'mm', 'a4');
            const pages = document.querySelectorAll('.preview-page');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = pdf.internal.pageSize.getHeight();

            for(let i = 0; i < pages.length; i++) {
                const page = pages[i];
                if(page.innerText.trim() === '') continue;

                const canvas = await html2canvas(page, { scale: 3, useCORS: true, logging: false });
                const imgData = canvas.toDataURL('image/jpeg', 0.9);
                const imgProps = pdf.getImageProperties(imgData);
                const pageHeight = (imgProps.height * pdfWidth) / imgProps.width;

                let heightLeft = pageHeight;
                let position = 0;

                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, position, pdfWidth, pageHeight);
                heightLeft -= pdfHeight;

                while (heightLeft > 0) {
                    position = heightLeft - pageHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'JPEG', 0, position, pdfWidth, pageHeight);
                    heightLeft -= pdfHeight;
                }
            }

            const title = document.getElementById('report-title').value || 'rapport';
            pdf.save(`${title.replace(/\s+/g, '_').toLowerCase()}.pdf`);

            btn.disabled = false;
            document.getElementById('pdf-icon').style.display = 'block';
            document.getElementById('pdf-text').textContent = 'Télécharger PDF';
            document.getElementById('pdf-loader').style.display = 'none';
        });
    });
</script>

</body>
</html>
